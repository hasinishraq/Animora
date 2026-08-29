<?php
session_start();
include '../config/db.php';

// Fetch user's name and profile picture
$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];

$stmt = $conn->prepare("SELECT Name, ProfilePhoto FROM users WHERE UserID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = $user["Name"];
    $profile_photo = $user["ProfilePhoto"];
} else {
    $user_name = "Guest";
    $profile_photo = "https://randomuser.me/api/portraits/men/32.jpg";
}

// Get filter values from URL
$selectedCategory = $_GET['category'] ?? 'all';
$selectedPriceRange = $_GET['price-range'] ?? 'all';
$selectedPetType = $_GET['pet-type'] ?? null;

// Function to get products with filters
function getFilteredProducts($conn, $categoryId = null, $priceRange = 'all', $petType = null)
{
    $query = "SELECT p.ProductID, p.Name, p.Price, p.ImageURL, p.Description, 
                     p.CategoryID, pc.Name as CategoryName
              FROM products p
              JOIN productcategories pc ON p.CategoryID = pc.CategoryID
              WHERE 1=1";

    $params = [];
    $types = '';

    // Category filter
    if ($categoryId && $categoryId !== 'all') {
        $query .= " AND p.CategoryID = ?";
        $params[] = $categoryId;
        $types .= 'i';
    }

    // Price range filter
    if ($priceRange !== 'all') {
        switch ($priceRange) {
            case 'under20':
                $query .= " AND p.Price < 20";
                break;
            case '20-50':
                $query .= " AND p.Price BETWEEN 20 AND 50";
                break;
            case 'over50':
                $query .= " AND p.Price > 50";
                break;
        }
    }

    // Pet type filter (only for pet food - category 1)
    if ($petType && ($categoryId === '1' || $categoryId === 1)) {
        $query .= " AND p.Name LIKE ?";
        $params[] = '%' . $petType . '%';
        $types .= 's';
    }

    // Default sorting when no filters
    if ($categoryId === 'all' && $priceRange === 'all' && !$petType) {
        $query .= " ORDER BY RAND()";
    }

    // Prepare and execute query
    $stmt = $conn->prepare($query);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $row['rating'] = rand(3, 5); // Add random rating
        $products[] = $row;
    }

    return $products;
}

// Get filtered products
$products = getFilteredProducts(
    $conn,
    $selectedCategory !== 'all' ? $selectedCategory : null,
    $selectedPriceRange,
    $selectedPetType
);

// Function to get all categories
function getProductCategories($conn)
{
    $query = "SELECT CategoryID, Name FROM productcategories ORDER BY Name";
    $result = $conn->query($query);

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    return $categories;
}

// Get all categories
$categories = getProductCategories($conn);

// Close the statement
$stmt->close();
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawsome Adoptions - Marketplace</title>
    <!-- Tailwind CSS (for base utilities) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* Color Palette Variables (Strictly kept as provided by user's last input) */
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

            /* Text colors derived from user's gray classes, mapped to similar hues in original palette */
            --text-dark: #4A4A4A;
            /* From original dark-text */
            --text-medium: #5A5A5A;
            /* Slightly lighter for readability */
            --text-light: #718096;
            /* Tailwind gray-600, good for descriptive text */

            /* Blues from previous iteration, adapted to fit the new professional tone */
            --blue-title: #4A6D90;
            --blue-title-light: #7DA4C5;

            /* Additional specific colors from user's provided HTML */
            --soft-red: #D2691E;
            /* Example, if needed. User had some browns/reds implicitly */
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--wave-front);
            /* Main body background, now unified */
            color: var(--text-dark);
            overflow-x: hidden;
            /* Prevent horizontal scroll from animations */
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
            /* Default heading color for most sections */
        }

        /* Custom Buttons */
        .btn-primary {
            @apply bg-[color:var(--primary-orange)] text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-transparent hover:border-white;
        }

        .btn-secondary {
            @apply bg-white text-[color:var(--primary-orange)] px-8 py-4 rounded-full font-bold text-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-[color:var(--primary-orange)];
        }

        /* Global Fade-in-Up Animation */
        .animate-fade-in-up {
            animation: fadeInUp 1s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            /* Slower, more elegant easing */
            opacity: 0;
            transform: translateY(50px);
            /* Increased initial offset for more dramatic entrance */
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Specific Animation */
        .header-shrink {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            /* Softer, more professional shadow */
        }

        /* Hero Section H1 Specific Animation */
        .animate-pulse-text-h1-hero {
            animation: pulseTextH1Hero 3s infinite alternate ease-in-out;
            /* Slower, smoother pulse */
        }

        @keyframes pulseTextH1Hero {
            0% {
                color: var(--blue-title);
                transform: scale(1);
            }

            50% {
                color: var(--blue-title-light);
                transform: scale(1.005);
                /* Very subtle scale for professionalism */
            }

            100% {
                color: var(--blue-title);
                transform: scale(1);
            }
        }

        /* Paw Print Animations (More subtle) */
        .paw-print {
            position: absolute;
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23F5A623"%3E%3Cpath d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"%3E%3C/path%3E%3C/svg%3E');
            background-size: contain;
            background-repeat: no-repeat;
            border-radius: 50%;
            animation: floatPaw 20s infinite linear;
            opacity: 0.2;
            /* Much lower opacity */
            z-index: 0;
            pointer-events: none;
        }

        /* More dynamic and varied paw print properties */
        .paw-print:nth-child(1) {
            left: 8%;
            top: 10%;
            animation-delay: 0s;
            opacity: 0.25;
            width: 80px;
            height: 80px;
        }

        .paw-print:nth-child(2) {
            left: 75%;
            top: 30%;
            animation-delay: 3s;
            width: 60px;
            height: 60px;
        }

        .paw-print:nth-child(3) {
            left: 20%;
            top: 55%;
            animation-delay: 6s;
            opacity: 0.3;
            width: 90px;
            height: 90px;
        }

        .paw-print:nth-child(4) {
            left: 65%;
            top: 80%;
            animation-delay: 1.5s;
            width: 70px;
            height: 70px;
        }

        .paw-print:nth-child(5) {
            left: 3%;
            top: 90%;
            animation-delay: 4.5s;
            opacity: 0.25;
            width: 50px;
            height: 50px;
        }

        .paw-print:nth-child(6) {
            left: 90%;
            top: 5%;
            animation-delay: 7.5s;
            opacity: 0.35;
            width: 100px;
            height: 100px;
        }

        .paw-print:nth-child(7) {
            left: 45%;
            top: 20%;
            animation-delay: 2s;
            opacity: 0.3;
            width: 65px;
            height: 65px;
        }

        .paw-print:nth-child(8) {
            left: 15%;
            top: 70%;
            animation-delay: 5s;
            opacity: 0.25;
            width: 75px;
            height: 75px;
        }

        /* Additional paw prints for "all over" effect - Increased the number for more coverage */
        .paw-print.global-1 {
            left: 15%;
            top: 200px;
            width: 40px;
            height: 40px;
            opacity: 0.15;
            animation-delay: 2s;
        }

        .paw-print.global-2 {
            right: 10%;
            top: 600px;
            width: 50px;
            height: 50px;
            opacity: 0.18;
            animation-delay: 7s;
        }

        .paw-print.global-3 {
            left: 5%;
            bottom: 100px;
            width: 30px;
            height: 30px;
            opacity: 0.1;
            animation-delay: 12s;
        }

        .paw-print.global-4 {
            right: 25%;
            bottom: 400px;
            width: 45px;
            height: 45px;
            opacity: 0.2;
            animation-delay: 5s;
        }

        .paw-print.global-5 {
            left: 50%;
            top: 800px;
            width: 55px;
            height: 55px;
            opacity: 0.17;
            animation-delay: 9s;
        }

        .paw-print.global-6 {
            right: 30%;
            top: 1200px;
            width: 60px;
            height: 60px;
            opacity: 0.22;
            animation-delay: 15s;
        }

        .paw-print.global-7 {
            left: 20%;
            top: 1500px;
            width: 48px;
            height: 48px;
            opacity: 0.16;
            animation-delay: 18s;
        }

        .paw-print.global-8 {
            right: 5%;
            top: 1800px;
            width: 70px;
            height: 70px;
            opacity: 0.25;
            animation-delay: 10s;
        }

        .paw-print.global-9 {
            left: 40%;
            bottom: 50px;
            width: 35px;
            height: 35px;
            opacity: 0.1;
            animation-delay: 20s;
        }

        .paw-print.global-10 {
            right: 45%;
            top: 100px;
            width: 42px;
            height: 42px;
            opacity: 0.19;
            animation-delay: 3s;
        }

        .paw-print.global-11 {
            left: 70%;
            top: 1000px;
            width: 50px;
            height: 50px;
            opacity: 0.2;
            animation-delay: 6s;
        }

        .paw-print.global-12 {
            left: 30%;
            top: 300px;
            width: 65px;
            height: 65px;
            opacity: 0.23;
            animation-delay: 11s;
        }


        @keyframes floatPaw {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.2;
            }

            50% {
                transform: translateY(-10px) rotate(180deg);
                opacity: 0.4;
            }

            100% {
                transform: translateY(0) rotate(360deg);
                opacity: 0.2;
            }
        }

        /* Wave styles */
        .wave-bottom svg {
            width: 100%;
            height: 200px;
            display: block;
        }

        .wave-bottom path {
            fill: var(--wave-front);
            /* Matches new body background */
        }

        /* Footer animation for paw */
        .footer-paw-rotate {
            display: inline-block;
            animation: rotatePawFast 1s linear infinite;
        }

        @keyframes rotatePawFast {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Walking Cat Animation - Left to Right */
        .walking-cat-ltr {
            position: absolute;
            top: 70px;
            /* Default top position */
            height: 80px;
            width: auto;
            animation: catWalkLeftToRight 15s linear infinite;
            z-index: 1000;
            transform: translateX(-100%);
            /* Start off-screen left */
            -webkit-filter: drop-shadow(0 4px 4px rgba(0, 0, 0, 0.2));
            filter: drop-shadow(0 4px 4px rgba(0, 0, 0, 0.2));
            pointer-events: none;
        }

        @keyframes catWalkLeftToRight {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }

            10% {
                transform: translateX(0);
                opacity: 1;
            }

            40% {
                transform: translateX(50vw);
                opacity: 1;
            }

            /* Crosses halfway */
            50% {
                transform: translateX(60vw);
                opacity: 0;
            }

            /* Disappears past halfway */
            100% {
                transform: translateX(60vw);
                opacity: 0;
            }

            /* Remains off screen */
        }

        /* Walking Cat Animation - Right to Left */
        .walking-cat-rtl {
            position: absolute;
            top: 100px;
            /* Different top position */
            height: 90px;
            width: auto;
            animation: catWalkRightToLeft 18s linear infinite;
            animation-delay: 5s;
            /* Start later */
            z-index: 1000;
            transform: translateX(100vw);
            /* Start off-screen right */
            -webkit-filter: drop-shadow(0 4px 4px rgba(0, 0, 0, 0.2));
            filter: drop-shadow(0 4px 4px rgba(0, 0, 0, 0.2));
            pointer-events: none;
        }

        @keyframes catWalkRightToLeft {
            0% {
                transform: translateX(100vw);
                opacity: 0;
            }

            10% {
                transform: translateX(calc(100vw - 100%));
                opacity: 1;
            }

            40% {
                transform: translateX(calc(50vw - 100%));
                opacity: 1;
            }

            /* Crosses halfway */
            50% {
                transform: translateX(calc(40vw - 100%));
                opacity: 0;
            }

            /* Disappears past halfway */
            100% {
                transform: translateX(calc(40vw - 100%));
                opacity: 0;
            }

            /* Remains off screen */
        }

        /* Adjust cat positions on smaller screens if necessary */
        @media (max-width: 768px) {

            .walking-cat-ltr,
            .walking-cat-rtl {
                height: 60px;
                top: 40px;
            }
        }


        /* --- Marketplace Specific Styles --- */
        .filter-button {
            @apply px-5 py-2 rounded-lg font-semibold text-base transition-all duration-300 ease-in-out;
        }

        .filter-button.active {
            @apply text-[color:var(--primary-orange)] bg-white border-b-4 border-[color:var(--primary-orange)] shadow-inner;
            transform: scale(1.02);
        }

        .filter-button:not(.active) {
            @apply bg-transparent text-[color:var(--text-dark)] hover:bg-[color:var(--light-cream)] hover:text-[color:var(--primary-orange)];
            /* Subtle hover for filter buttons */
        }

        .filter-group-title {
            @apply text-xl font-bold text-[color:var(--text-dark)] mb-4 pb-2 border-b border-[color:var(--pinkish-orange)];
        }

        .product-card {
            @apply bg-white rounded-3xl overflow-hidden transition-all duration-300 ease-in-out flex flex-col items-center p-5;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05), 0 3px 8px rgba(0, 0, 0, 0.03);
            animation: floatCard 4.5s ease-in-out infinite;
            position: relative;
        }

        @keyframes floatCard {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-3px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 8px 20px rgba(0, 0, 0, 0.08);
            z-index: 10;
        }

        /* Category item circle float */
        .category-circle-float {
            animation: floatCategoryCircle 3s ease-in-out infinite;
        }

        @keyframes floatCategoryCircle {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }

            100% {
                transform: translateY(0);
            }
        }


        .product-image {
            @apply w-full h-48 object-cover object-center rounded-2xl mb-4;
        }

        .product-info {
            @apply text-center flex-grow flex flex-col justify-between items-center w-full;
        }

        .product-name {
            @apply text-2xl font-bold text-[color:var(--text-dark)] mb-2;
            word-break: break-word;
        }

        .product-price {
            @apply text-4xl font-extrabold text-[color:var(--primary-orange)] mb-4;
        }

        /* Enhanced Add to Cart Button */
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
            /* For the pseudo-element border */
            overflow: hidden;
            /* To hide overflow from inner glow */
        }

        .add-to-cart-btn:hover {
            transform: scale(1.08);
            /* Slightly more prominent scale */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            border-color: white;
            /* White border on hover */
        }

        /* Star rating display */
        .star-rating {
            display: flex;
            align-items: center;
            color: gold;
            margin-bottom: 1rem;
        }

        .star-rating svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.1rem;
        }

        /* Cart counter styling */
        .cart-counter {
            @apply bg-red-500 text-white rounded-full px-2 py-1 text-xs font-bold absolute -top-1 -right-1;
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

        /* Pagination Styles */
        .pagination-container {
            @apply flex justify-center items-center gap-3 mt-10 p-4 bg-white rounded-full shadow-md border-2 border-[color:var(--pinkish-orange)];
        }

        .pagination-button {
            @apply bg-[color:var(--light-cream)] text-[color:var(--text-dark)] font-bold py-2 px-5 rounded-full transition-all duration-300 ease-in-out;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            /* Subtle inner shadow */
        }

        .pagination-button:hover {
            @apply bg-[color:var(--light-orange-accent)] text-white transform scale-105;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .pagination-button.active {
            @apply bg-[color:var(--primary-orange)] text-white shadow-lg transform scale-110;
            /* More prominent active state */
            border: 2px solid white;
            /* Border for active */
        }

        .pagination-button:disabled {
            @apply opacity-50 cursor-not-allowed bg-gray-200 text-gray-500 shadow-none transform-none;
        }


        /* Floating Themed Icons (Extremely subtle) */
        .floating-themed-icon {
            position: absolute;
            opacity: 0.08;
            /* Even lower opacity for professionalism */
            animation: floatThemedIcon 20s infinite ease-in-out;
            /* Slower animation */
            pointer-events: none;
            z-index: 0;
        }

        /* SVG data for themed icons */
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

        /* Button click animation for add to cart */
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
    </style>
</head>

<body class="text-[color:var(--text-dark)]">

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="loading-spinner"></div>




    <!-- Header / Navbar (Adapted from user's provided structure) -->

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
                <a href="#shop-by-category"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105"
                    onclick="smoothScroll('#shop-by-category')">
                    Shop By Category
                </a>
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

    <!-- Hero Section -->
    <section class="bg-[color:var(--light-cream)] py-16 px-6 relative overflow-hidden rounded-b-3xl shadow-xl">
        <!-- Background Paw Prints in hero -->
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <!-- Added more paw prints for "all over" effect -->


        <div class="max-w-7xl mx-auto flex flex-col-reverse lg:flex-row items-center justify-between relative z-10">

            <!-- Left Text Content -->
            <div class="w-full lg:w-1/2 text-center lg:text-left mt-10 lg:mt-0 animate-fade-in-up"
                style="animation-delay: 0.2s;">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-[color:var(--text-dark)] leading-tight mb-4">
                    <span class="text-[color:var(--primary-orange)] animate-pulse-text-h1-hero">Fresh Food</span><br>
                    Your Pet Can Trust.
                </h2>
                <p class="text-[color:var(--text-medium)] text-lg mt-4 mb-8 px-2 lg:px-0 max-w-xl mx-auto lg:mx-0">
                    Our premium pet food is crafted with natural ingredients, ensuring your furry friend stays happy and
                    healthy with every bite.
                </p>

            </div>

            <!-- Right Image with Background Shape -->
            <div class="w-full lg:w-1/2 relative flex justify-center items-center z-10 p-6">
                <!-- Background Decorative Shapes (colors adapted) -->
                <div class="absolute w-[280px] h-[280px] bg-[color:var(--light-orange-accent)] rounded-full -left-6 top-12 z-0 animate-fade-in-up"
                    style="animation-delay: 0.4s;"></div>
                <div class="absolute w-[420px] h-[420px] bg-[color:var(--medium-orange-accent)] rounded-full -right-6 top-6 z-0 animate-fade-in-up"
                    style="animation-delay: 0.6s;"></div>

                <!-- Image with Matching Background - Now Round and Floating -->
                <div class="bg-[color:var(--light-cream)] w-[400px] h-[400px] z-10 relative rounded-full overflow-hidden shadow-xl animate-fade-in-up category-circle-float"
                    style="animation-delay: 0.8s;">
                    <!-- Replaced local path with Placehold.co -->
                    <img src="/assets/images/girlwpet2.png" alt="Dog Hero"
                        class="w-full h-full object-cover rounded-full" />
                </div>
            </div>
        </div>
    </section>

    <!-- Info Bar with SVG Icons -->
    <div class="bg-[color:var(--medium-orange-accent)] py-6 shadow-lg">
        <div class="container mx-auto flex flex-col md:flex-row justify-around items-center text-white gap-4 md:gap-0">
            <div class="flex items-center space-x-3 animate-fade-in-up" style="animation-delay: 0.9s;">
                <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 4c-4.41 0-8 3.59-8 8 0 1.82.62 3.48 1.65 4.85L12 21l6.35-8.15C19.38 15.48 20 13.82 20 12c0-4.41-3.59-8-8-8zm0 2a6 6 0 016 6 6 6 0 01-6 6 6 6 0 01-6-6 6 6 0 016-6zm-3 5a1 1 0 100 2 1 1 0 000-2zm6 0a1 1 0 100 2 1 1 0 000-2z" />
                </svg>
                <span class="text-sm md:text-base font-medium">Find Local Pets</span>
            </div>
            <div class="flex items-center space-x-3 animate-fade-in-up" style="animation-delay: 1.0s;">
                <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 16c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-2c3.31 0 6-2.69 6-6s-2.69-6-6-6-6 2.69-6 6 2.69 6 6 6z" />
                </svg>
                <span class="text-sm md:text-base font-medium">Shop Pet Supplies</span>
            </div>
            <div class="flex items-center space-x-3 animate-fade-in-up" style="animation-delay: 1.1s;">
                <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 2c-5.52 0-10 4.48-10 10s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-7h2v2h-2zm2-2h-2V7h2v4z" />
                </svg>
                <span class="text-sm md:text-base font-medium">Expert Pet Advice</span>
            </div>
            <div class="flex items-center space-x-3 animate-fade-in-up" style="animation-delay: 1.2s;">
                <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8h5z" />
                </svg>
                <span class="text-sm md:text-base font-medium">Find Pet Services</span>
            </div>
        </div>
    </div>

    <!-- Category Section -->
    <section class="bg-[color:var(--light-cream)] py-16" id="shop-by-category">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-[color:var(--text-dark)] text-center mb-10 animate-fade-in-up"
                style="animation-delay: 1.3s;">Shop by Category</h2>
            <div class="flex flex-wrap justify-center items-center gap-8">
                <!-- Cat Food -->
                <a href="?category=1&pet-type=cat#featured-products"
                    class="flex flex-col items-center group animate-fade-in-up" style="animation-delay: 1.4s;">
                    <img src="https://placehold.co/100x100/F5A623/FFFFFF?text=Cat+Food" alt="Cat Food"
                        class="rounded-full w-24 h-24 object-cover shadow-md transition-transform duration-300 group-hover:scale-110 group-hover:shadow-lg category-circle-float">
                    <p class="mt-4 text-base font-medium text-[color:var(--text-medium)]">Cat Food</p>
                </a>

                <!-- Dog Food -->
                <a href="?category=1&pet-type=dog#featured-products"
                    class="flex flex-col items-center group animate-fade-in-up" style="animation-delay: 1.5s;">
                    <img src="https://placehold.co/100x100/F5A623/FFFFFF?text=Dog+Food" alt="Dog Food"
                        class="rounded-full w-24 h-24 object-cover shadow-md transition-transform duration-300 group-hover:scale-110 group-hover:shadow-lg category-circle-float">
                    <p class="mt-4 text-base font-medium text-[color:var(--text-medium)]">Dog Food</p>
                </a>

                <!-- Small Animal Food -->
                <a href="?category=1&pet-type=small-animal#featured-products"
                    class="flex flex-col items-center group animate-fade-in-up" style="animation-delay: 1.6s;">
                    <img src="https://placehold.co/100x100/F5A623/FFFFFF?text=Small+Anim" alt="Small Animal Food"
                        class="rounded-full w-24 h-24 object-cover shadow-md transition-transform duration-300 group-hover:scale-110 group-hover:shadow-lg category-circle-float">
                    <p class="mt-4 text-base font-medium text-[color:var(--text-medium)]">Small Animal Food</p>
                </a>

                <!-- Bird Food -->
                <a href="?category=1&pet-type=bird#featured-products"
                    class="flex flex-col items-center group animate-fade-in-up" style="animation-delay: 1.7s;">
                    <img src="https://placehold.co/100x100/F5A623/FFFFFF?text=Bird+Food" alt="Bird Food"
                        class="rounded-full w-24 h-24 object-cover shadow-md transition-transform duration-300 group-hover:scale-110 group-hover:shadow-lg category-circle-float">
                    <p class="mt-4 text-base font-medium text-[color:var(--text-medium)]">Bird Food</p>
                </a>

                <!-- Fish Food -->
                <a href="?category=1&pet-type=fish#featured-products"
                    class="flex flex-col items-center group animate-fade-in-up" style="animation-delay: 1.8s;">
                    <img src="https://placehold.co/100x100/F5A623/FFFFFF?text=Fish+Food" alt="Fish Food"
                        class="rounded-full w-24 h-24 object-cover shadow-md transition-transform duration-300 group-hover:scale-110 group-hover:shadow-lg category-circle-float">
                    <p class="mt-4 text-base font-medium text-[color:var(--text-medium)]">Fish Food</p>
                </a>
            </div>
        </div>
    </section>










    <!-- Marketplace Section (Filters & Products) -->
    <section class="bg-[color:var(--light-cream)] py-16">
        <!-- Floating Themed Icons (Extremely subtle in the background) -->
        <div class="floating-themed-icon bone" style="left: 10%; top: 15%; animation-delay: 0s;"></div>
        <div class="floating-themed-icon fish" style="right: 15%; top: 30%; animation-delay: 4s;"></div>
        <div class="floating-themed-icon feather" style="left: 5%; top: 60%; animation-delay: 8s;"></div>
        <div class="floating-themed-icon bone" style="right: 20%; top: 75%; animation-delay: 12s;"></div>
        <div class="floating-themed-icon fish" style="left: 25%; top: 40%; animation-delay: 1.5s;"></div>
        <div class="floating-themed-icon feather" style="right: 5%; bottom: 10%; animation-delay: 7s;"></div>
        <div class="floating-themed-icon bone" style="left: 60%; top: 20%; animation-delay: 9s;"></div>
        <div class="floating-themed-icon fish" style="left: 55%; bottom: 25%; animation-delay: 14s;"></div>


        <div class="container mx-auto px-4 sm:px-6 lg:px-8 lg:flex gap-10 ">

            <aside
                class="bg-white p-6 rounded-3xl shadow-xl border-4 border-[color:var(--pinkish-orange)] lg:w-1/4 lg:sticky top-28 h-fit animate-fade-in-up"
                style="animation-delay: 2.3s;">
                <!-- Add this form wrapper -->
                <form method="GET" action="">
                    <h3 class="text-2xl font-bold text-[color:var(--text-dark)] mb-6">Filter by Category</h3>
                    <div class="space-y-4">
                        <!-- All Categories -->
                        <label class="flex items-center group cursor-pointer">
                            <input type="radio" name="category" value="all"
                                class="form-radio h-5 w-5 text-[color:var(--primary-orange)] focus:ring-[color:var(--primary-orange)] rounded-full border-gray-300"
                                <?= ($selectedCategory === 'all') ? 'checked' : '' ?>>
                            <span
                                class="ml-3 text-[color:var(--text-medium)] text-lg group-hover:text-[color:var(--primary-orange)] transition-colors duration-200">All
                                Categories</span>
                        </label>

                        <!-- Dynamic Categories -->
                        <?php foreach ($categories as $category): ?>
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="category" value="<?= $category['CategoryID'] ?>"
                                    class="form-radio h-5 w-5 text-[color:var(--primary-orange)] focus:ring-[color:var(--primary-orange)] rounded-full border-gray-300"
                                    <?= ($selectedCategory == $category['CategoryID']) ? 'checked' : '' ?>>
                                <span
                                    class="ml-3 text-[color:var(--text-medium)] text-lg group-hover:text-[color:var(--primary-orange)] transition-colors duration-200">
                                    <?= htmlspecialchars($category['Name']) ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <!-- Updated Price Range Filter -->
                    <div class="mt-8">
                        <h3 class="text-xl font-bold text-[color:var(--text-dark)] mb-4">Price Range</h3>
                        <div class="space-y-4">
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="price-range" value="all"
                                    class="form-radio h-5 w-5 text-[color:var(--primary-orange)] focus:ring-[color:var(--primary-orange)] rounded-full border-gray-300"
                                    <?= ($priceRange === 'all') ? 'checked' : '' ?>>
                                <span
                                    class="ml-3 text-[color:var(--text-medium)] text-lg group-hover:text-[color:var(--primary-orange)] transition-colors duration-200">Any
                                    Price</span>
                            </label>
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="price-range" value="under20"
                                    class="form-radio h-5 w-5 text-[color:var(--primary-orange)] focus:ring-[color:var(--primary-orange)] rounded-full border-gray-300"
                                    <?= ($priceRange === 'under20') ? 'checked' : '' ?>>
                                <span
                                    class="ml-3 text-[color:var(--text-medium)] text-lg group-hover:text-[color:var(--primary-orange)] transition-colors duration-200">Under
                                    $20</span>
                            </label>
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="price-range" value="20-50"
                                    class="form-radio h-5 w-5 text-[color:var(--primary-orange)] focus:ring-[color:var(--primary-orange)] rounded-full border-gray-300"
                                    <?= ($priceRange === '20-50') ? 'checked' : '' ?>>
                                <span
                                    class="ml-3 text-[color:var(--text-medium)] text-lg group-hover:text-[color:var(--primary-orange)] transition-colors duration-200">$20
                                    - $50</span>
                            </label>
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="price-range" value="over50"
                                    class="form-radio h-5 w-5 text-[color:var(--primary-orange)] focus:ring-[color:var(--primary-orange)] rounded-full border-gray-300"
                                    <?= ($priceRange === 'over50') ? 'checked' : '' ?>>
                                <span
                                    class="ml-3 text-[color:var(--text-medium)] text-lg group-hover:text-[color:var(--primary-orange)] transition-colors duration-200">Over
                                    $50</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full mt-6">Apply Filters</button>
                    <button type="button" onclick="location.href='<?= $_SERVER['PHP_SELF'] ?>'"
                        class="btn-secondary w-full mt-2">
                        Reset Filters
                    </button>
                </form>
            </aside>

            <div class="lg:w-3/4 pl-0 lg:pl-8 mt-10 lg:mt-0">
                <h2 class="text-3xl font-bold text-[color:var(--text-dark)] mb-8">Our Featured Pet Products</h2>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <img src="<?= htmlspecialchars($product['ImageURL'] ?? 'default.jpg') ?>"
                                alt="<?= htmlspecialchars($product['Name']) ?>" class="w-full h-48 object-cover">

                            <div class="p-4">
                                <h3 class="font-bold text-lg"><?= htmlspecialchars($product['Name']) ?></h3>
                                <p class="text-[color:var(--pinkish-orange)] font-semibold mt-2">
                                    $<?= number_format($product['Price'], 2) ?>
                                </p>
                                <button class="btn-primary mt-4">Add to Cart</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- No Products Message (will show if array is empty) -->
                <?php if (empty($products)): ?>
                    <div
                        class="text-center p-10 bg-white rounded-3xl shadow-xl border-4 border-[color:var(--pinkish-orange)] mt-10">
                        <p class="text-xl mb-6">No products available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Become a Provider Section -->
    <section class="bg-[color:var(--wave-front)] py-12 relative overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center animate-fade-in-up"
            style="animation-delay: 3.1s;">
            <div class="w-full lg:w-1/2 animate-fade-in-up" style="animation-delay: 3.3s;">
                <!-- Replaced local path with Placehold.co -->
                <img src="https://placehold.co/500x400/F7B9A6/F5A623?text=Seller+Pet" alt="Cat Licking Lips"
                    class="max-w-full h-auto object-cover rounded-2xl shadow-xl">
            </div>
            <div class="w-full lg:w-1/2 pl-0 lg:pl-12 mt-10 lg:mt-0 animate-fade-in-up" style="animation-delay: 3.5s;">
                <div class="bg-white bg-opacity-90 rounded-3xl shadow-xl p-8 md:p-10">
                    <h2 class="text-3xl font-bold text-[color:var(--primary-orange)] mb-4 md:mb-6">Become a Valued
                        Provider for Furry Friends</h2>
                    <p class="text-base text-[color:var(--text-medium)] mb-6 md:mb-8">
                        Offer pet products/services to caring owners. Connect directly if you create unique items,
                        provide care, or supply essentials. Expand your reach in our furry-friend marketplace. Register
                        now!
                    </p>
                    <a href="#" class="btn-primary py-3 px-6 text-base">Find out more</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (Adapted from previous refined version with user's colors) -->
    <footer class="bg-[color:var(--primary-orange)] text-white py-12 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="animate-fade-in-up" style="animation-delay: 3.7s;">
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--light-cream)]">Pawsome Adoptions</h3>
                <p class="text-[color:var(--light-cream)] text-sm">Where every tail finds its wag! We're dedicated to
                    uniting furry hearts with forever homes.</p>
            </div>
            <div class="animate-fade-in-up" style="animation-delay: 3.8s;">
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
            <div class="animate-fade-in-up" style="animation-delay: 3.9s;">
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
            <div class="animate-fade-in-up" style="animation-delay: 4.0s;">
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
            // Simulate content loading
            setTimeout(() => {
                loadingSpinner.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Re-enable scroll after loading
            }, 1000); // Hide spinner after 1 second

            document.getElementById('current-year').textContent = new Date().getFullYear();

            let cartCount = 0;
            const cartCounter = document.getElementById('cart-counter');
            const addToCartMessage = document.getElementById('add-to-cart-message');

            let currentFilters = {
                type: 'All',
                price: 'All',
                rating: 'All'
            };

            let currentPage = 1;
            const itemsPerPage = 8; // Number of products per page

            const productGrid = document.getElementById('product-grid');
            const noProductsMessage = document.getElementById('no-products-message');
            const paginationControls = document.getElementById('pagination-controls');

            function filterAndPaginateProducts() {
                let filteredProducts = productsData.filter(product => {
                    // Filter by Type
                    if (currentFilters.type !== 'All' && product.type !== currentFilters.type) {
                        return false;
                    }
                    // Filter by Price
                    if (currentFilters.price === 'under20' && product.price >= 20) {
                        return false;
                    }
                    if (currentFilters.price === '20-50' && (product.price < 20 || product.price > 50)) {
                        return false;
                    }
                    if (currentFilters.price === 'over50' && product.price <= 50) {
                        return false;
                    }
                    // Filter by Rating
                    if (currentFilters.rating === '4plus' && product.rating < 4) {
                        return false;
                    }
                    if (currentFilters.rating === '3plus' && product.rating < 3) {
                        return false;
                    }
                    return true;
                });

                const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const productsToDisplay = filteredProducts.slice(startIndex, endIndex);

                renderProductCards(productsToDisplay);
                renderPaginationControls(totalPages);

                if (filteredProducts.length === 0) {
                    noProductsMessage.classList.remove('hidden');
                    paginationControls.classList.add('hidden');
                } else {
                    noProductsMessage.classList.add('hidden');
                    // Only show pagination if there's more than one page
                    if (totalPages > 1) {
                        paginationControls.classList.remove('hidden');
                    } else {
                        paginationControls.classList.add('hidden');
                    }
                }
            }



            function smoothScroll(target) {
                const element = document.querySelector(target);
                if (element) {
                    element.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                return false; // Prevent default anchor behavior
            }

            function renderProductCards(products) {
                productGrid.innerHTML = ''; // Clear current products
                products.forEach((product, index) => {
                    const productCard = document.createElement('div');
                    productCard.classList.add('product-card', 'animate-fade-in-up');
                    productCard.style.animationDelay = `${0.7 + (index * 0.05)}s`;

                    const starIcons = Array(5).fill(null).map((_, i) => `
                    <svg class="w-5 h-5" fill="${i < Math.floor(product.rating) ? 'currentColor' : 'none'}" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.974 2.887a1 1 0 00-.364 1.118l1.519 4.674c.3.921-.755 1.688-1.539 1.118l-3.974-2.887a1 1 0 00-1.176 0l-3.974 2.887c-.784.57-1.838-.197-1.539-1.118l1.519-4.674a1 1 0 00-.364-1.118L2.921 10.1c-.783-.57-.381-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z"></path>
                    </svg>
                `).join('');

                    productCard.innerHTML = `
                    <img src="${product.ImageURL}" alt="${product.Name}" class="product-image">
                    <div class="product-info">
                        <h3 class="product-name">${product.Name}</h3>
                        <div class="star-rating">
                            ${starIcons}
                            <span>(${product.rating.toFixed(1)})</span>
                        </div>
                        <p class="product-price">$${product.Price.toFixed(2)}</p>
                        <button class="add-to-cart-btn" data-product-id="${product.ProductID}">Add to Cart</button>
                    </div>
                `;
                    productGrid.appendChild(productCard);
                });
            }

            function renderPaginationControls(totalPages) {
                paginationControls.innerHTML = ''; // Clear existing controls

                if (totalPages <= 1) {
                    paginationControls.classList.add('hidden');
                    return;
                }
                paginationControls.classList.remove('hidden');

                const prevButton = document.createElement('button');
                prevButton.classList.add('pagination-button');
                prevButton.textContent = 'Previous';
                prevButton.disabled = currentPage === 1;
                prevButton.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        filterAndPaginateProducts();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
                paginationControls.appendChild(prevButton);

                for (let i = 1; i <= totalPages; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.classList.add('pagination-button');
                    pageButton.textContent = i;
                    if (i === currentPage) {
                        pageButton.classList.add('active');
                    }
                    pageButton.addEventListener('click', () => {
                        currentPage = i;
                        filterAndPaginateProducts();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                    paginationControls.appendChild(pageButton);
                }

                const nextButton = document.createElement('button');
                nextButton.classList.add('pagination-button');
                nextButton.textContent = 'Next';
                nextButton.disabled = currentPage === totalPages;
                nextButton.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        filterAndPaginateProducts();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
                paginationControls.appendChild(nextButton);
            }

            window.resetFilters = () => {
                currentFilters = {
                    type: 'All',
                    price: 'All',
                    rating: 'All'
                };
                document.querySelector('input[name="pet-type"][data-filter-type="All"]').checked = true;
                document.querySelector('input[name="price-range"][data-filter-price="All"]').checked = true;
                document.querySelector('input[name="customer-rating"][data-filter-rating="All"]').checked = true;
                currentPage = 1;
                filterAndPaginateProducts();
            };

            productGrid.addEventListener('click', (e) => {
                if (e.target.classList.contains('add-to-cart-btn')) {
                    const productId = e.target.dataset.productId;
                    const button = e.target;

                    button.classList.add('clicked');
                    button.addEventListener('animationend', () => {
                        button.classList.remove('clicked');
                    }, { once: true });

                    cartCount++;
                    cartCounter.textContent = cartCount;

                    addToCartMessage.classList.add('show');
                    setTimeout(() => {
                        addToCartMessage.classList.remove('show');
                    }, 1500);
                }
            });

            // Initial render of products
            filterAndPaginateProducts();
        });
    </script>
    <script>
        const productsData = <?php echo json_encode($products); ?>;
    </script>

</body>

</html>