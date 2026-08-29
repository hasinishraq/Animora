<?php
// DB connection — adjust to your credentials
$host = "localhost";
$db = "animora";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product_id safely from URL
$product_id = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;
if ($product_id <= 0) {
    echo "Invalid product ID.";
    exit;
}

// Fetch product data
$stmt = $conn->prepare("SELECT * FROM products WHERE ProductID = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();




// Close connection (optional here)
$stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawsome Adoptions - Product Detail</title>
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

        /* Product Image Gallery Styles */
        .product-image-gallery {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            /* rounded-3xl */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .product-main-image {
            width: 100%;
            height: 500px;
            /* Fixed height for consistency */
            object-fit: cover;
            object-position: center;
            border-radius: 1.5rem;
            transition: transform 0.3s ease-in-out;
        }

        .product-main-image.zoom {
            transform: scale(1.1);
            cursor: zoom-out;
        }

        .thumbnail-container {
            display: flex;
            gap: 0.75rem;
            /* gap-3 */
            margin-top: 1rem;
            /* mt-4 */
            justify-content: center;
        }

        .thumbnail-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.75rem;
            /* rounded-xl */
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        .thumbnail-image.active {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px var(--light-orange-accent);
            transform: scale(1.05);
        }

        .thumbnail-image:hover:not(.active) {
            border-color: var(--pinkish-orange);
            transform: scale(1.02);
        }

        /* Star rating display */
        .star-rating {
            display: flex;
            align-items: center;
            color: gold;
            margin-bottom: 0.75rem;
        }

        .star-rating svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.1rem;
        }

        /* Quantity selector */
        .quantity-selector {
            @apply flex items-center bg-white rounded-full border-2 border-[color:var(--pinkish-orange)] p-1 shadow-inner;
        }

        .quantity-button {
            @apply bg-[color:var(--light-cream)] text-[color:var(--primary-orange)] font-bold w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[color:var(--light-orange-accent)] hover:text-white;
        }

        .quantity-input {
            @apply w-16 text-center text-xl font-bold text-[color:var(--text-dark)] bg-transparent focus:outline-none;
        }

        /* Add to Cart Button (reused from marketplace) */
        .add-to-cart-btn {
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

        .add-to-cart-btn:hover {
            transform: scale(1.08);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            border-color: white;
        }

        .add-to-cart-btn.clicked {
            animation: buttonClickPop 0.3s ease-out;
        }

        @keyframes buttonClickPop {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(0.95);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Added to cart message */
        .add-to-cart-message {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--primary-orange);
            color: white;
            padding: 1rem 2rem;
            border-radius: 9999px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out, transform 0.5s ease-out;
            z-index: 1001;
            font-family: 'Fredoka', sans-serif;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .add-to-cart-message.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-10px);
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
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="%23F5A623"%3E%3Cpath d="M573.4 246.3c-1.3-4.3-3.6-8.2-6.9-11.4l-44.5-44.5c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-103.5 25.9c-4.4 1.1-8.7 3-12.6 5.8-3.9 2.8-7.3 6.3-10.1 10.3l-59.5 83.3c-2.8 3.9-6.3 7.3-10.3 10.1-3.9 2.8-8.3 4.7-12.6 5.8l-117 29.3c-4.1 1-8.3 1.2-12.4.3-4.1-1-8-3.3-11.2-6.5l-20.5-20.5c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-95.2 23.8c-4.4 1.1-8.7 3-12.6 5.8-3.9 2.8-7.3 6.3-10.1 10.3l-50.5 70.7c-2.8 3.9-6.3 7.3-10.3 10.1-3.9 2.8-8.3 4.7-12.6 5.8l-87.5 21.9c-4.1 1-8.3 1.2-12.4.3-4.1-1-8-3.3-11.2-6.5L6.6 448.4c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-5.7 1.4L.3 441.7c-1.3-4.3-3.6-8.2-6.9-11.4l-44.5-44.5c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-103.5 25.9c-4.4 1.1-8.7 3-12.6 5.8-3.9 2.8-7.3 6.3-10.1 10.3l-59.5 83.3c-2.8 3.9-6.3 7.3-10.3 10.1-3.9 2.8-8.3 4.7-12.6 5.8l-117 29.3c-4.1 1-8.3 1.2-12.4.3-4.1-1-8-3.3-11.2-6.5l-20.5-20.5c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-95.2 23.8c-4.4 1.1-8.7 3-12.6 5.8-3.9 2.8-7.3 6.3-10.1 10.3l-50.5 70.7c-2.8 3.9-6.3 7.3-10.3 10.1-3.9 2.8-8.3 4.7-12.6 5.8l-87.5 21.9c-4.1 1-8.3 1.2-12.4.3-4.1-1-8-3.3-11.2-6.5L6.6 448.4c-3.1-3.1-6.9-5.4-10.9-6.6-4.1-1.2-8.3-1.3-12.4-.3l-5.7 1.4z"%3E%3C/path%3E%3C/svg%3E');
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
                <a href="user-home.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Dashboard</a>
                <a href="marketplacehome.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Home</a>

                <a href="marketplace-all-product.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">All
                    Products</a>
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

    <!-- Product Detail Section -->
    <section class="bg-[color:var(--light-cream)] py-16 relative overflow-hidden">
        <!-- Floating themed icons in background -->
        <div class="floating-themed-icon bone" style="left: 10%; top: 15%; animation-delay: 0s;"></div>
        <div class="floating-themed-icon fish" style="right: 15%; top: 30%; animation-delay: 4s;"></div>
        <div class="floating-themed-icon feather" style="left: 5%; top: 60%; animation-delay: 8s;"></div>
        <div class="floating-themed-icon bone" style="right: 20%; top: 75%; animation-delay: 12s;"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-10">

            <!-- Product Image Gallery (Left Side) -->
            <div class="lg:w-1/2 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="product-image-gallery">
                    <img id="main-product-image" src="<?= htmlspecialchars($product['ImageURL']) ?>"
                        alt="<?= htmlspecialchars($product['Name']) ?>" class="product-main-image cursor-zoom-in">
                </div>
                <div id="thumbnail-gallery" class="thumbnail-container">
                    <!-- You can decide how to fetch multiple images if you have, for now showing same image thumbnails -->
                    <img src="<?= htmlspecialchars($product['ImageURL']) ?>" alt="Thumbnail 1"
                        class="thumbnail-image active" data-full-src="<?= htmlspecialchars($product['ImageURL']) ?>">
                    <img src="<?= htmlspecialchars($product['ImageURL']) ?>" alt="Thumbnail 2" class="thumbnail-image"
                        data-full-src="<?= htmlspecialchars($product['ImageURL']) ?>">
                    <img src="<?= htmlspecialchars($product['ImageURL']) ?>" alt="Thumbnail 3" class="thumbnail-image"
                        data-full-src="<?= htmlspecialchars($product['ImageURL']) ?>">
                    <img src="<?= htmlspecialchars($product['ImageURL']) ?>" alt="Thumbnail 4" class="thumbnail-image"
                        data-full-src="<?= htmlspecialchars($product['ImageURL']) ?>">
                </div>
            </div>

            <!-- Product Details (Right Side) -->
            <div class="lg:w-1/2 bg-white p-8 rounded-3xl shadow-xl border-4 border-[color:var(--pinkish-orange)] animate-fade-in-up"
                style="animation-delay: 0.4s;">
                <h1 class="text-4xl font-bold text-[color:var(--text-dark)] mb-3">
                    <?= htmlspecialchars($product['Name']) ?>
                </h1>

                <p class="text-xl text-[color:var(--text-medium)] mb-2">Brand: <span class="font-semibold">
                        <?= htmlspecialchars($product['Brand'] ?? 'N/A') // adjust if you have a Brand column ?>
                    </span></p>

                <div class="flex items-center mb-4">
                    <div class="star-rating">
                        <!-- You can optionally replace this with dynamic rating if you have -->
                        <!-- Keeping the stars static as your design -->
                        <!-- ... your SVG stars ... -->
                    </div>
                    <span
                        class="text-lg text-[color:var(--text-medium)]">(<?= htmlspecialchars($product['Rating'] ?? '4.5') ?>/5)
                        based on <?= htmlspecialchars($product['ReviewCount'] ?? '120') ?> reviews</span>
                </div>

                <p class="text-4xl font-extrabold text-[color:var(--primary-orange)] mb-6">
                    $<?= number_format($product['Price'], 2) ?>
                </p>

                <p class="text-[color:var(--text-dark)] mb-6 leading-relaxed">
                    <?= nl2br(htmlspecialchars($product['Description'])) ?>
                </p>

                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-[color:var(--text-dark)] mb-3">Key Features:</h3>
                    <ul class="list-disc list-inside text-[color:var(--text-medium)] space-y-2">
                        <!-- Replace this with real features if you have a features column or table -->
                        <li>Grain-free for sensitive digestion</li>
                        <li>High-quality protein source</li>
                        <li>Enriched with Omega-3 & Omega-6 for skin and coat health</li>
                        <li>No artificial colors, flavors, or preservatives</li>
                        <li>Suitable for all adult dog breeds</li>
                    </ul>
                </div>

                <div class="flex items-center gap-6 mb-8">
                    <span class="text-xl font-semibold text-[color:var(--text-dark)]">Quantity:</span>
                    <div class="quantity-selector">
                        <button id="decrement-quantity" class="quantity-button">-</button>
                        <input type="number" id="product-quantity" value="1" min="1" class="quantity-input">
                        <button id="increment-quantity" class="quantity-button">+</button>
                    </div>
                </div>

                <button id="add-to-cart-btn" class="add-to-cart-btn w-full">
                    Add to Cart
                </button>
            </div>
        </div>


        <!-- Detailed Description Section -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-16 bg-white p-8 rounded-3xl shadow-xl border-4 border-[color:var(--pinkish-orange)] animate-fade-in-up"
            style="animation-delay: 0.6s;">
            <h2 class="text-3xl font-bold text-[color:var(--text-dark)] mb-6">Product Details</h2>
            <div class="text-[color:var(--text-medium)] space-y-4 leading-relaxed">
                <?= nl2br(htmlspecialchars($product['Description'])) ?>
            </div>

        </div>

        <!-- Related Products Section (Example - you can populate with dynamic data) -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-16">
            <h2 class="text-3xl font-bold text-[color:var(--text-dark)] text-center mb-10 animate-fade-in-up"
                style="animation-delay: 0.8s;">You Might Also Like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <!-- Example Related Product Card (reused product-card style) -->
                <div class="product-card animate-fade-in-up" style="animation-delay: 0.9s;">
                    <img src="https://placehold.co/400x300/FCC370/FFFFFF?text=Dog+Toy" alt="Related Product 1"
                        class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">Durable Chew Toy</h3>
                        <div class="star-rating">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.974 2.887a1 1 0 00-.364 1.118l1.519 4.674c.3.921-.755 1.688-1.539 1.118l-3.974-2.887a1 1 0 00-1.176 0l-3.974 2.887c-.784.57-1.838-.197-1.539-1.118l1.519-4.674a1 1 0 00-.364-1.118L2.921 10.1c-.783-.57-.381-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z">
                                </path>
                            </svg>
                        </div>
                        <p class="product-price text-2xl">$14.99</p>
                        <button class="add-to-cart-btn">Add to Cart</button>
                    </div>
                </div>
                <div class="product-card animate-fade-in-up" style="animation-delay: 1.0s;">
                    <img src="https://placehold.co/400x300/FFE9BD/F5A623?text=Cat+Scratch" alt="Related Product 2"
                        class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">Luxury Cat Scratcher</h3>
                        <div class="star-rating">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.974 2.887a1 1 0 00-.364 1.118l1.519 4.674c.3.921-.755 1.688-1.539 1.118l-3.974-2.887a1 1 0 00-1.176 0l-3.974 2.887c-.784.57-1.838-.197-1.539-1.118l1.519-4.674a1 1 0 00-.364-1.118L2.921 10.1c-.783-.57-.381-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z">
                                </path>
                            </svg>
                        </div>
                        <p class="product-price text-2xl">$25.00</p>
                        <button class="add-to-cart-btn">Add to Cart</button>
                    </div>
                </div>
                <div class="product-card animate-fade-in-up" style="animation-delay: 1.1s;">
                    <img src="https://placehold.co/400x300/E0BBE4/F5A623?text=Fish+Food" alt="Related Product 3"
                        class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">Tropical Fish Flakes</h3>
                        <div class="star-rating">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 1.944A1 1 0 0111.049 1a.999.999 0 01.902.69l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.92 8.725c-.783-.57-.381-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="product-price text-2xl">$9.50</p>
                        <button class="add-to-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (from previous design) -->
    <footer class="bg-[color:var(--primary-orange)] text-white py-12 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="animate-fade-in-up" style="animation-delay: 1.2s;">
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--light-cream)]">Pawsome Adoptions</h3>
                <p class="text-[color:var(--light-cream)] text-sm">Where every tail finds its wag! We're dedicated to
                    uniting furry hearts with forever homes.</p>
            </div>
            <div class="animate-fade-in-up" style="animation-delay: 1.3s;">
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
            <div class="animate-fade-in-up" style="animation-delay: 1.4s;">
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
            <div class="animate-fade-in-up" style="animation-delay: 1.5s;">
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

    <!-- Added to Cart Message Container -->
    <div id="add-to-cart-message" class="add-to-cart-message">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 12.356 2.62 13.542 2.62 14.5c0 .828.672 1.5 1.5 1.5h10.243a.5.5 0 000-1H4.122c-.172 0-.25-.132-.217-.183l.222-.221.75-.75L6.96 7.464l.877 3.51a.5.5 0 00.99.248l.94-3.763 3.657-3.657A1 1 0 0016 4V3a1 1 0 00-1-1H4.5a1 1 0 00-.97 1.24L3 4.5V3a1 1 0 00-1-1zm14 0a1 1 0 00-1 1v1h-1a1 1 0 100 2h1v1a1 1 0 102 0V5h1a1 1 0 100-2h-1V1a1 1 0 00-1-1z" />
        </svg>
        Item Added! 🎉
    </div>

    <!-- Scroll to Top Button -->
    <button onclick="topFunction()" id="scrollToTopBtn" title="Go to top">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loadingSpinner = document.getElementById('loading-spinner');
            setTimeout(() => {
                loadingSpinner.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Re-enable scroll
            }, 1000);

            document.getElementById('current-year').textContent = new Date().getFullYear();

            let cartCount = 0;
            const cartCounter = document.getElementById('cart-counter');
            const addToCartMessage = document.getElementById('add-to-cart-message');

            const mainProductImage = document.getElementById('main-product-image');
            const thumbnailGallery = document.getElementById('thumbnail-gallery');
            const quantityInput = document.getElementById('product-quantity');
            const decrementButton = document.getElementById('decrement-quantity');
            const incrementButton = document.getElementById('increment-quantity');
            const addToCartBtn = document.getElementById('add-to-cart-btn');

            // Image gallery functionality
            thumbnailGallery.addEventListener('click', (e) => {
                if (e.target.classList.contains('thumbnail-image')) {
                    // Update main image source
                    mainProductImage.src = e.target.dataset.fullSrc;

                    // Remove active class from all thumbnails
                    document.querySelectorAll('.thumbnail-image').forEach(img => {
                        img.classList.remove('active');
                    });

                    // Add active class to clicked thumbnail
                    e.target.classList.add('active');
                }
            });

            // Image zoom functionality
            mainProductImage.addEventListener('click', () => {
                mainProductImage.classList.toggle('zoom');
                mainProductImage.style.cursor = mainProductImage.classList.contains('zoom') ? 'zoom-out' : 'zoom-in';
            });


            // Quantity selector functionality
            decrementButton.addEventListener('click', () => {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });

            incrementButton.addEventListener('click', () => {
                let currentValue = parseInt(quantityInput.value);
                quantityInput.value = currentValue + 1;
            });

            quantityInput.addEventListener('change', () => {
                let currentValue = parseInt(quantityInput.value);
                if (isNaN(currentValue) || currentValue < 1) {
                    quantityInput.value = 1; // Default to 1 if invalid
                }
            });

            // Add to Cart functionality
            addToCartBtn.addEventListener('click', () => {
                const quantityToAdd = parseInt(quantityInput.value);
                if (quantityToAdd > 0) {
                    console.log(`${quantityToAdd} items added to cart.`);
                    cartCount += quantityToAdd;
                    cartCounter.textContent = cartCount;

                    addToCartBtn.classList.add('clicked');
                    addToCartBtn.addEventListener('animationend', () => {
                        addToCartBtn.classList.remove('clicked');
                    }, { once: true });

                    addToCartMessage.classList.add('show');
                    setTimeout(() => {
                        addToCartMessage.classList.remove('show');
                    }, 1500);
                }
            });

            // Intersection Observer for fade-in animations on scroll
            const faders = document.querySelectorAll('.animate-fade-in-up');
            const appearOptions = {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            };

            const appearOnScroll = new IntersectionObserver(function (entries, observer) {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) {
                        return;
                    } else {
                        const delay = entry.target.dataset.animationDelay || '0s';
                        entry.target.style.animationDelay = delay;
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, appearOptions);

            faders.forEach(fader => {
                fader.style.opacity = '0';
                fader.style.transform = 'translateY(20px)';
                appearOnScroll.observe(fader);
            });

            // Header shrink on scroll (reusing logic from marketplace page)
            const header = document.querySelector('header');
            const logo = header.querySelector('img');
            const profileButton = header.querySelector('[aria-label="User Profile"]');
            const cartButton = header.querySelector('[aria-label="Shopping Cart"]');

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
                    cartButton.classList.add('p-2');
                    cartButton.classList.remove('p-3');
                    cartButton.querySelector('svg').classList.add('w-5', 'h-5');
                    cartButton.querySelector('svg').classList.remove('w-6', 'h-6');
                } else {
                    header.classList.remove('header-shrink');
                    header.classList.add('py-4', 'shadow-md');
                    logo.classList.remove('h-12');
                    logo.classList.add('h-16');
                    profileButton.classList.remove('p-2');
                    profileButton.classList.add('p-3');
                    profileButton.querySelector('svg').classList.remove('w-5', 'h-5');
                    profileButton.querySelector('svg').classList.add('w-6', 'h-6');
                    cartButton.classList.remove('p-2');
                    cartButton.classList.add('p-3');
                    cartButton.querySelector('svg').classList.remove('w-5', 'h-5');
                    cartButton.querySelector('svg').classList.add('w-6', 'h-6');
                }
            });

            // Scroll to top button logic
            const scrollToTopBtn = document.getElementById("scrollToTopBtn");

            window.onscroll = function () { scrollFunction() };

            function scrollFunction() {
                if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                    scrollToTopBtn.style.display = "block";
                } else {
                    scrollToTopBtn.style.display = "none";
                }
            }

            window.topFunction = function () {
                document.body.scrollTop = 0; // For Safari
                document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
            }
        });
    </script>
</body>

</html>