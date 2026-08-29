<?php
session_start();  // Start the session
include '../config/db.php';  // Include the database connection file (adjust path as necessary)

// Check if the user is logged in and if the role is "User"
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'User') {
    // Redirect to the login page if the user is not logged in or role is not "User"
    header("Location: /animora/auth/login.php");  // Adjust the login page URL if necessary
    exit();  // Stop further script execution after redirection
}

// Pagination setup
$productsPerPage = 12;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;

$offset = ($page - 1) * $productsPerPage;

// Total count for pagination
$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM products");
$totalRow = $totalQuery->fetch_assoc();
$totalProducts = $totalRow['total'];
$totalPages = ceil($totalProducts / $productsPerPage);

// Fetch only the products for this page
$sql = "SELECT * FROM products LIMIT $productsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawsome Adoptions - All Products</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* Color Palette Variables (from previous design) */
        :root {
            --primary-orange: #F5A623;
            --light-cream: #FFFAF5;
            --light-orange-accent: #FFE9BD;
            --medium-orange-accent: #FCC370;
            --purple-accent: #E0BBE4;
            --pinkish-orange: #f8bba2;
            --wave-lightest: #ffe6dc;
            --wave-middle: #f5a18c;
            --wave-front: #f7b9a6;
            --dark-hover-orange: #D98E0B;
            --light-hover-cream: #fff3e0;

            /* Text colors */
            --text-dark: #4A4A4A;
            --text-medium: #5A5A5A;
            --text-light: #718096;

            /* Blues for headings/accents */
            --blue-title: #4A6D90;
            --blue-title-light: #7DA4C5;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--wave-front);
            color: var(--text-dark);
            overflow-x: hidden;
            /* Prevent horizontal scroll */
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
            color: var(--primary-orange);
        }

        /* Global Fade-in-Up Animation */
        .animate-fade-in-up {
            animation: fadeInUp 1s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            opacity: 0;
            transform: translateY(50px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Specific (adjust to match your actual header if integrating) */
        .header-shrink {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Main Buttons (reused from marketplace and product detail) */
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-orange), var(--dark-hover-orange));
            color: white;
            padding: 12px 32px;
            border-radius: 9999px;
            /* Full rounded */
            font-weight: bold;
            font-size: 1.125rem;
            /* text-lg */
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border: 2px solid transparent;
            /* Initial transparent border */
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            border-color: white;
        }

        .btn-secondary {
            @apply bg-white text-[color:var(--primary-orange)] px-8 py-4 rounded-full font-bold text-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-[color:var(--primary-orange)];
        }


        /* Cart counter styling */
        .cart-counter {
            @apply bg-red-500 text-white rounded-full px-2 py-1 text-xs font-bold absolute -top-1 -right-1;
        }

        /* Loading Spinner */
        .loading-spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s ease-out;
            opacity: 1;
        }

        .loading-spinner.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loading-spinner::after {
            content: '';
            width: 50px;
            height: 50px;
            border: 8px solid var(--light-cream);
            border-top-color: var(--primary-orange);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Scroll to Top Button */
        #scrollToTopBtn {
            display: none;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 99;
            border: none;
            outline: none;
            background-color: var(--primary-orange);
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 50%;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        #scrollToTopBtn:hover {
            background-color: var(--dark-hover-orange);
            transform: scale(1.1);
        }

        /* Floating themed icons (from marketplace) */
        .floating-themed-icon {
            position: absolute;
            opacity: 0.08;
            animation: floatThemedIcon 20s infinite ease-in-out;
            pointer-events: none;
            z-index: 0;
        }

        .floating-themed-icon.bone {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%23F5A623"%3E%3Cpath d="M448 96c17.7 0 32-14.3 32-32s-14.3-32-32-32H384V32c0-17.7-14.3-32-32-32s-32 14.3-32 32v32H192V32c0-17.7-14.3-32-32-32S128 14.3 128 32v32H64c-17.7 0-32 14.3-32 32s14.3 32 32 32h64V200H64c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64H64c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v32c0 17.7 14.3 32 32 32s32-14.3 32-32v-32H352v32c0 17.7 14.3 32 32 32s32-14.3 32-32v-32h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H384V264h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H384V168h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H384V96H448zM168 168h176v64H168V168zm0 96h176v64H168V264z"%3E%3C/path%3E%3C/svg%3E');
            width: 40px;
            height: 40px;
        }

        .floating-themed-icon.fish {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="%23F5A623"%3E%3Cpath d="M573.4 246.3c-1.3-4.3-3.6-8.2-6.9-11.4l-44.5-44.5c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-103.5 25.9c-4.4 1.1-8.7 3-12.6 5.8-3.9 2.8-7.3 6.3-10.1 10.3l-59.5 83.3c-2.8 3.9-6.3 7.3-10.3 10.1-3.9 2.8-8.3 4.7-12.6 5.8l-117 29.3c-4.1 1-8.3 1.2-12.4-.3-4.1-1-8-3.3-11.2-6.5l-20.5-20.5c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-95.2 23.8c-4.4 1.1-8.7 3-12.6 5.8-3.9 2.8-7.3 6.3-10.1 10.3l-50.5 70.7c-2.8 3.9-6.3 7.3-10.3 10.1-3.9 2.8-8.3 4.7-12.6 5.8l-87.5 21.9c-4.1 1-8.3 1.2-12.4-.3-4.1-1-8-3.3-11.2-6.5L6.6 448.4c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-5.7 1.4L.3 441.7c-1.3-4.3-3.6-8.2-6.9-11.4l-44.5-44.5c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-103.5 25.9c-4.4 1.1-8.7 3-12.6 5.8-3.9 2.8-7.3 6.3-10.1 10.3l-59.5 83.3c-2.8 3.9-6.3 7.3-10.3 10.1-3.9 2.8-8.3 4.7-12.6 5.8l-117 29.3c-4.1 1-8.3 1.2-12.4-.3-4.1-1-8-3.3-11.2-6.5l-20.5-20.5c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-95.2 23.8c-4.4 1.1-8.7 3-12.6 5.8-3.9 2.8-7.3 6.3-10.1 10.3l-50.5 70.7c-2.8 3.9-6.3 7.3-10.3 10.1-3.9 2.8-8.3 4.7-12.6 5.8l-87.5 21.9c-4.1 1-8.3 1.2-12.4-.3-4.1-1-8-3.3-11.2-6.5L6.6 448.4c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-5.7 1.4z"%3E%3C/path%3E%3C/svg%3E');
            width: 50px;
            height: 50px;
        }

        .floating-themed-icon.feather {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%23F5A623"%3E%3Cpath d="M480 32C402.7 32 336 98.7 336 176c0 40 16.3 76.5 44 102.7-30.8 19.3-53.7 48.6-67.6 82.3-13.9 33.7-14.8 69.2-5.7 103 10.8 40.5 40.9 74.3 79.5 90.7 38.6 16.4 81.6 18.4 121.7 8.3 40.2-10.2 73.8-38.3 90.5-77.9 16.6-39.6 18.2-84.3 4.4-123.6C502.9 203.2 512 176 512 176c0-82.7-67.3-152-150-152H480zM362 256c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"%3E%3C/path%3E%3C/svg%3E');
            width: 30px;
            height: 30px;
        }

        @keyframes floatThemedIcon {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.08;
            }

            25% {
                transform: translateY(-8px) rotate(3deg);
                opacity: 0.12;
            }

            50% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.15;
            }

            75% {
                transform: translateY(8px) rotate(-3deg);
                opacity: 0.12;
            }

            100% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.08;
            }
        }

        /* Floating Paw Icon for Pagination */
        .floating-paw-icon {
            position: absolute;
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="%23FCC370"%3E%3Cpath d="M256 0C149.3 0 64 85.3 64 192c0 106.7 85.3 192 192 192s192-85.3 192-192C448 85.3 362.7 0 256 0zM192 256a64 64 0 100-128 64 64 0 000 128zm128-128a64 64 0 100-128 64 64 0 000 128zm-64 256a64 64 0 100-128 64 64 0 000 128zm-128-128a64 64 0 100-128 64 64 0 000 128zm128 128a64 64 0 100-128 64 64 0 000 128z"/%3E%3C/svg%3E');
            /* Simplified paw print */
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.15;
            /* Slightly more visible than background icons */
            animation: floatPawIcon 15s infinite ease-in-out;
            /* Faster, more playful float */
            pointer-events: none;
            z-index: 0;
        }

        @keyframes floatPawIcon {
            0% {
                transform: translateY(0) rotate(0deg);
            }

            25% {
                transform: translateY(-5px) rotate(5deg);
            }

            50% {
                transform: translateY(0) rotate(0deg);
            }

            75% {
                transform: translateY(5px) rotate(-5deg);
            }

            100% {
                transform: translateY(0) rotate(0deg);
            }
        }

        /* Marketplace All Products Specific Styles */
        .product-card {
            @apply bg-white rounded-3xl shadow-xl overflow-hidden transform transition-all duration-300 hover:scale-105 border-4 border-[color:var(--pinkish-orange)];
            opacity: 0;
            /* Initial state for entrance animation */
            transform: translateY(20px);
            /* Initial state for entrance animation */
        }

        .product-card.loaded {
            animation: productFadeInUp 0.6s ease-out forwards;
            /* Custom animation for product cards */
        }

        @keyframes productFadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        .product-image {
            @apply w-full h-48 object-cover object-center;
        }

        .product-info {
            @apply p-5 text-center;
        }

        .product-title {
            @apply text-xl font-bold mb-2 text-[color:var(--blue-title)];
        }

        .product-price {
            @apply text-2xl font-extrabold text-[color:var(--primary-orange)] mb-4;
        }

        .add-to-cart-btn {
            background: linear-gradient(45deg, var(--primary-orange), var(--dark-hover-orange));
            color: white;
            padding: 10px 24px;
            border-radius: 9999px;
            /* Full rounded */
            font-weight: bold;
            font-size: 1rem;
            /* text-base */
            transition: all 0.3s ease-in-out;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            border: 2px solid transparent;
        }

        .add-to-cart-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            border-color: white;
        }

        /* Pagination Styles */
        .pagination-container {
            @apply flex justify-center items-center gap-4 mt-12 mb-8 animate-fade-in-up;
            position: relative;
            /* For positioning floating icons */
            padding: 20px 0;
            /* Added padding */
        }

        .pagination-button {
            @apply px-5 py-3 rounded-full font-bold text-lg transition duration-300 ease-in-out;
            /* Increased padding and font size */
            color: var(--primary-orange);
            background-color: var(--light-cream);
            border: 2px solid var(--primary-orange);
            min-width: 50px;
            /* Ensure buttons have a minimum width */
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .pagination-button:hover:not(:disabled) {
            background-color: var(--primary-orange);
            color: white;
            transform: translateY(-3px);
            /* More pronounced lift */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            /* Stronger shadow */
        }

        .pagination-button.active {
            background-color: var(--primary-orange);
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
            border-color: white;
            transform: scale(1.05);
            /* Slightly larger when active */
        }

        .pagination-button:disabled {
            opacity: 0.6;
            /* Slightly less opaque than before */
            cursor: not-allowed;
            background-color: var(--light-cream);
            /* Ensure disabled buttons remain light */
            color: var(--text-light);
            /* Lighter text for disabled */
            border-color: var(--text-light);
            /* Lighter border for disabled */
            transform: none;
            /* No transform for disabled */
            box-shadow: none;
            /* No shadow for disabled */
        }
    </style>
</head>

<body class="text-[color:var(--text-dark)]">

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="loading-spinner"></div>

    <!-- Header / Navbar -->
    <header
        class="w-full bg-white py-4 shadow-md fixed top-0 left-0 right-0 z-50 transform transition-all duration-300 ease-in-out">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Left Menu -->
            <nav class="flex justify-center md:justify-start gap-8 text-lg font-semibold">
                <a href="marketplacehome.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Home</a>
                <a href="user-home.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Dashboard</a>
                <a href="post-adoption.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Contact</a>
            </nav>

            <!-- Center Logo -->
            <div class="flex justify-center flex-shrink-0">
                <img src="/assets/images/logo2.png" alt="Pawsome Adoptions Logo"
                    class="h-16 w-auto transition-all duration-300 ease-in-out">
            </div>

            <!-- Right Buttons (Profile & Cart) -->
            <div class="w-full md:w-auto flex justify-center md:justify-end gap-4">
                <!-- Profile Button -->
                <button
                    class="relative bg-white text-[color:var(--primary-orange)] p-3 rounded-full shadow-md transition duration-300 ease-in-out transform hover:scale-110"
                    aria-label="User Profile">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <!-- Cart Button -->
                <button
                    class="relative bg-[color:var(--primary-orange)] text-white p-3 rounded-full shadow-md transition duration-300 ease-in-out transform hover:scale-110"
                    aria-label="Shopping Cart">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 12.356 2.62 13.542 2.62 14.5c0 .828.672 1.5 1.5 1.5h10.243a.5.5 0 000-1H4.122c-.172 0-.25-.132-.217-.183l.222-.221.75-.75L6.96 7.464l.877 3.51a.5.5 0 00.99.248l.94-3.763 3.657-3.657A1 1 0 0016 4V3a1 1 0 00-1-1H4.5a1 1 0 00-.97 1.24L3 4.5V3a1 1 0 00-1-1zm14 0a1 1 0 00-1 1v1h-1a1 1 0 100 2h1v1a1 1 0 102 0V5h1a1 1 0 100-2h-1V1a1 1 0 00-1-1z" />
                    </svg>
                    <span id="cart-counter" class="cart-counter">0</span>
                </button>
            </div>

        </div>
    </header>

    <!-- Spacer for fixed header -->
    <div class="h-24"></div>

    <!-- Marketplace All Products Section -->
    <section class="bg-[color:var(--light-cream)] py-16 relative overflow-hidden">
        <!-- Floating themed icons in background -->
        <div class="floating-themed-icon bone" style="left: 10%; top: 15%; animation-delay: 0s;"></div>
        <div class="floating-themed-icon fish" style="right: 15%; top: 30%; animation-delay: 4s;"></div>
        <div class="floating-themed-icon feather" style="left: 5%; top: 60%; animation-delay: 8s;"></div>
        <div class="floating-themed-icon bone" style="right: 20%; top: 75%; animation-delay: 12s;"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-center mb-10 animate-fade-in-up" style="animation-delay: 0.2s;">Our
                Pawsome Products</h1>
            <p class="text-lg text-center text-[color:var(--text-medium)] mb-12 animate-fade-in-up"
                style="animation-delay: 0.3s;">
                Discover a wide range of products for your beloved pets!
            </p>

            <h1 class="text-3xl font-bold mb-6">All Products</h1>

            <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php
                if ($result === false) {
                    echo "<p>❌ Query failed: " . $conn->error . "</p>";
                } elseif ($result->num_rows === 0) {
                    echo "<p>📭 No products found in the database.</p>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">';

                        echo '<img src="' . htmlspecialchars($row['ImageURL']) . '" alt="Product Image" class="w-full h-48 object-cover">';

                        echo '<div class="p-4 flex-grow flex flex-col justify-between">';

                        echo '<div>';
                        echo '<h2 class="text-lg font-semibold mb-1">' . htmlspecialchars($row['Name']) . '</h2>';
                        echo '<p class="text-sm text-gray-600 mb-2">' . htmlspecialchars($row['Description']) . '</p>';
                        echo '<div class="font-bold text-indigo-600 mb-2">৳' . number_format($row['Price'], 2) . '</div>';
                        echo '<div class="text-sm text-gray-500 mb-4">Stock: ' . $row['StockQuantity'] . '</div>';
                        echo '</div>';

                        echo '<div class="flex gap-2">';
                        // View Details button with bg #eaa793 and blue text
                        echo '<a href="marketplace-product-details.php?product_id=' . $row['ProductID'] . '" class="flex-grow text-center py-2 rounded transition" style="background-color:#eaa793; color:#2563eb;" onmouseover="this.style.backgroundColor=\'#d78b7b\'" onmouseout="this.style.backgroundColor=\'#eaa793\'">View Details</a>';
                        // Add to Cart button with bg #F5A623 and blue text
                        echo '<a href="marketplace-cart.php?action=add&product_id=' . $product['ProductID'] . '">
      <button class="add-to-cart-btn">Add to Cart</button>
      </a>';




                        echo '</div>';

                        echo '</div>'; // p-4 container end
                
                        echo '</div>'; // card end
                    }
                }
                ?>
            </div>



            <!-- Pagination Controls -->
            <div class="flex justify-center mt-10 flex-wrap gap-2">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="px-4 py-2 rounded font-semibold"
                        style="background-color: #eaa793; color: #1D4ED8;">
                        « Prev
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>"
                        class="px-4 py-2 rounded font-semibold <?= ($i === $page ? 'ring-2 ring-offset-2 ring-blue-600' : '') ?>"
                        style="background-color: #eaa793; color: #1D4ED8;">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 rounded font-semibold"
                        style="background-color: #eaa793; color: #1D4ED8;">
                        Next »
                    </a>
                <?php endif; ?>
            </div>


        </div>
    </section>

    <!-- Footer (from previous design) -->
    <footer class="bg-[color:var(--primary-orange)] text-white py-12 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="animate-fade-in-up" style="animation-delay: 0.8s;">
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--light-cream)]">Pawsome Adoptions</h3>
                <p class="text-[color:var(--light-cream)] text-sm">Where every tail finds its wag! We're dedicated to
                    uniting furry hearts with forever homes.</p>
            </div>
            <div class="animate-fade-in-up" style="animation-delay: 0.9s;">
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--light-cream)]">Quick Sniffs</h3>
                <ul class="space-y-3 text-[color:var(--light-cream)]">
                    <li><a href="#" class="hover:text-white transition duration-300 transform hover:translate-x-1">Adopt
                            a Pet 🐾</a></li>
                    <li><a href="#" class="hover:text-white transition duration-300 transform hover:translate-x-1">Post
                            for Adoption 💌</a></li>
                    <li><a href="#"
                            class="hover:text-white transition duration-300 transform hover:translate-x-1">Success
                            Stories ✨</a></li>
                    <li><a href="#" class="hover:text-white transition duration-300 transform hover:translate-x-1">FAQ's
                            ❓</a></li>
                </ul>
            </div>
            <div class="animate-fade-in-up" style="animation-delay: 1.0s;">
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--light-cream)]">Support Paws</h3>
                <ul class="space-y-3 text-[color:var(--light-cream)]">
                    <li><a href="#"
                            class="hover:text-white transition duration-300 transform hover:translate-x-1">Contact Our
                            Pack 📞</a></li>
                    <li><a href="#"
                            class="hover:text-white transition duration-300 transform hover:translate-x-1">Privacy Nook
                            🔒</a></li>
                    <li><a href="#" class="hover:text-white transition duration-300 transform hover:translate-x-1">Terms
                            of Play 📖</a></li>
                </ul>
            </div>
            <div class="animate-fade-in-up" style="animation-delay: 1.1s;">
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--light-cream)]">Join Our Fur-mily!</h3>
                <div class="flex space-x-5">
                    <a href="#"
                        class="text-[color:var(--light-cream)] hover:text-white transition duration-300 transform hover:scale-125">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.776-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.247 0-1.646.773-1.646 1.572V12h2.77l-.443 2.89h-2.327v6.987C18.343 21.128 22 16.991 22 12z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="text-[color:var(--light-cream)] hover:text-white transition duration-300 transform hover:scale-125">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 2C9.176 2 8.787 2.01 7.424 2.062c-1.365.053-2.06.27-2.618.497-.56.228-1.015.546-1.468.999-.453.453-.77 1.008-.999 1.568-.228.558-.444 1.253-.497 2.618-.052 1.363-.062 1.753-.062 4.5.062s3.137-.01 4.5-.062c1.365-.053 2.06-.27 2.618-.497.56-.228 1.015-.546 1.468-.999.453-.453 1.008.77 1.568.999.558.228 1.253.444 2.618.497 1.363.052 1.753.062 4.5.062s3.137-.01 4.5-.062c1.365-.053 2.06-.27 2.618-.497.56-.228 1.015-.546 1.468-.999.453-.453 1.008.77 1.568.999.558.228 1.253.444 2.618.497C15.137 2.01 14.747 2 12 2zm0 2.164c2.784 0 3.109.011 4.2.053 1.088.043 1.638.257 2.02.417.382.16.634.357.877.6.242.242.438.495.6.877.16.382.374.932.417 2.02.042 1.091.053 1.416.053 4.2s-.011 3.109-.053 4.2c-.043 1.088-.257 1.638-.417 2.02-.16.382-.357.634-.6.877-.242.242-.495.438-.877.6-.382.16-.932.374-2.02.417-1.091.042-1.416.053-4.2.053s.011-3.109.053-4.2c.043-1.088.257-1.638.417-2.02.16-.382.357.634.6-.877.242-.242-.495.438-.877.6.382-.16.932-.374 2.02-.417C8.891 4.175 9.216 4.164 12 4.164zm0 3.659A4.177 4.177 0 1012 16.002a4.177 4.177 0 000-8.354zm0 2.164a2.013 2.013 0 110 4.026 2.013 2.013 0 010-4.026z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="text-[color:var(--light-cream)] hover:text-white transition duration-300 transform hover:scale-125">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M8.29 20.082a9.664 9.664 0 001.325.074c.068 0 .135-.002.203-.004a9.684 9.684 0 007.82-3.805c.67-.936 1.055-2.062 1.055-3.266 0-.07-.002-.138-.004-.207a7.662 7.662 0 001.88-2.083c-.694.306-1.44.512-2.228.604a3.876 3.876 0 001.7-2.148c-.767.456-1.62.78-2.527.954a3.864 3.864 0 00-6.58 3.541c-3.242-.162-6.104-1.716-8.026-4.072a3.868 3.868 0 001.196 5.176c-.636-.02-1.23-.194-1.748-.485v.048c0 3.52 2.502 6.444 5.817 7.108a3.91 3.91 0 01-1.74.066c.928 2.89 3.61 4.974 6.786 5.033a7.747 7.747 0 004.834-1.677c1.378.9 2.926 1.43 4.536 1.43.585 0 1.15-.054 1.705-.158-.292.936-.677 1.812-1.144 2.625-.467.813-.996 1.564-1.587 2.253a15.82 15.82 0 01-3.66 3.01c-.886.435-1.79.79-2.708 1.077a23.905 23.905 0 01-5.344.205z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div
            class="text-center text-[color:var(--light-cream)] text-sm mt-12 border-t-2 border-[color:var(--light-orange-accent)] pt-8">
            &copy; <span id="current-year"></span> Pawsome Adoptions. All rights reserved. Made with love! <span
                class="footer-paw-rotate">💖</span>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button onclick="topFunction()" id="scrollToTopBtn" title="Go to top">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Spinner logic
            const loadingSpinner = document.getElementById('loading-spinner');
            setTimeout(() => {
                loadingSpinner?.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 1000);

            // Set current year
            document.getElementById('current-year').textContent = new Date().getFullYear();

            // ====================
            // Product grid: REMOVED JS data injection
            // ====================
            // PHP will handle product rendering now, no productGrid.innerHTML here.

            // ========== Intersection Observer (header/footer animations) ==========
            const faders = document.querySelectorAll('.animate-fade-in-up');
            const appearOptions = {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            };

            const appearOnScroll = new IntersectionObserver(function (entries, observer) {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;

                    const delay = entry.target.dataset.animationDelay || '0s';
                    entry.target.style.animationDelay = delay;
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                });
            }, appearOptions);

            faders.forEach(fader => {
                fader.style.opacity = '0';
                fader.style.transform = 'translateY(20px)';
                appearOnScroll.observe(fader);
            });

            // ========== Header shrink on scroll ==========
            const header = document.querySelector('header');
            const logo = header.querySelector('img');
            const profileButton = header.querySelector('[aria-label="User Profile"]');
            const cartHeaderButton = header.querySelector('[aria-label="Shopping Cart"]');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('header-shrink');
                    header.classList.remove('py-4', 'shadow-md');
                    logo.classList.add('h-12');
                    logo.classList.remove('h-16');
                    profileButton.classList.add('p-2');
                    profileButton.classList.remove('p-3');
                    profileButton.querySelector('svg').classList.add('w-5', 'h-5');
                    profileButton.querySelector('svg').classList.remove('w-6', 'h-6');
                    cartHeaderButton.classList.add('p-2');
                    cartHeaderButton.classList.remove('p-3');
                    cartHeaderButton.querySelector('svg').classList.add('w-5', 'h-5');
                    cartHeaderButton.querySelector('svg').classList.remove('w-6', 'h-6');
                } else {
                    header.classList.remove('header-shrink');
                    header.classList.add('py-4', 'shadow-md');
                    logo.classList.remove('h-12');
                    logo.classList.add('h-16');
                    profileButton.classList.remove('p-2');
                    profileButton.classList.add('p-3');
                    profileButton.querySelector('svg').classList.remove('w-5', 'h-5');
                    profileButton.querySelector('svg').classList.add('w-6', 'h-6');
                    cartHeaderButton.classList.remove('p-2');
                    cartHeaderButton.classList.add('p-3');
                    cartHeaderButton.querySelector('svg').classList.remove('w-5', 'h-5');
                    cartHeaderButton.querySelector('svg').classList.add('w-6', 'h-6');
                }
            });

            // ========== Scroll to top button ==========
            const scrollToTopBtn = document.getElementById("scrollToTopBtn");

            window.onscroll = function () {
                if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                    scrollToTopBtn.style.display = "block";
                } else {
                    scrollToTopBtn.style.display = "none";
                }
            };

            window.topFunction = function () {
                document.body.scrollTop = 0; // For Safari
                document.documentElement.scrollTop = 0; // For other browsers
            };
        });
    </script>

</body>

</html>