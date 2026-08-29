<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../config/db.php'; // Adjust path if needed

// Only allow logged-in users with role "User"
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'User') {
    header("Location: /animora/auth/login.php");
    exit();
}

$userName = "User";
$profilePhoto = "https://i.pravatar.cc/100"; // Default profile photo

$userID = $_SESSION["user_id"];

// Fetch user's name and profile photo from the database
$stmt = $conn->prepare("SELECT Name, ProfilePhoto FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($name, $photo);

if ($stmt->fetch()) {
    $userName = htmlspecialchars($name);
    if (!empty($photo)) {
        $profilePhoto = htmlspecialchars($photo);
    }
}
$stmt->close();

// Fetch service categories
$categories = [];
$query = "SELECT CategoryID, CategoryName, Description FROM servicecategories";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - Pawsome Adoptions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* Define Color Palette Variables */
        :root {
            --color-mustard: #D4931F;
            /* Main Brand Color - Gold/Mustard */
            --color-reddish-orange: #E17C56;
            /* Accent Color - Reddish Orange */
            --color-light-greenish-grey: #C2D4C8;
            /* Light Background */
            --color-muted-green: #AEC8B5;
            /* Medium Background/Accents */
            --color-peach: #F7B9A6;
            /* Soft Highlight/Hero Gradient Start */
            --color-dark-text: #4A4A4A;
            /* General Body Text */
            --color-heading-dark: #1C2A39;
            /* Very Dark Blue for Main Headings */

            /* New Service Colors */
            --color-service-blue: #5A7E9F;
            /* Training, Playtime */
            --color-service-purple: #8C56A1;
            /* Grooming */
            --color-service-green: #1F9B73;
            /* Feeding, Walking */
        }

        /* Base Body Styles */
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--color-light-greenish-grey);
            color: var(--color-dark-text);
            overflow-x: hidden;
            /* Prevents horizontal scroll */
            line-height: 1.6;
        }

        /* Heading Font Styles */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
            color: var(--color-mustard);
        }

        /* Global Fade-in Animation */
        .animate-fade-in {
            animation: fadeIn 1s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--color-mustard);
            color: white;
            padding: 1rem 2.5rem;
            /* Equivalent to px-10 py-4 */
            border-radius: 9999px;
            font-weight: 700;
            font-size: 1.125rem;
            /* text-lg */
            transition: all 0.3s ease-in-out;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            border-color: white;
            background-color: #B87B19;
            /* Slightly darker mustard */
        }

        .btn-secondary {
            background-color: white;
            color: var(--color-mustard);
            padding: 1rem 2.5rem;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 1.125rem;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid var(--color-mustard);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-secondary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            background-color: var(--color-light-greenish-grey);
            border-color: #B87B19;
        }

        /* Header Specific Styles */
        header {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
            transition: all 0.3s ease-in-out;
            position: relative;
            z-index: 50;
            /* Ensure header is above content */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            /* Add shadow to header */
        }

        .header-shrink {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Loading Spinner */
        .loading-spinner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.85);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }

        .loading-spinner.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loading-spinner::after {
            content: '';
            width: 60px;
            height: 60px;
            border: 8px solid rgba(212, 147, 31, 0.2);
            border-top-color: var(--color-mustard);
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
            background-color: var(--color-mustard);
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 50%;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        #scrollToTopBtn:hover {
            background-color: #B87B19;
            transform: scale(1.1);
        }

        /* Hero Section for Services Page */
        .services-hero-section {
            background: linear-gradient(135deg, var(--color-muted-green), var(--color-light-greenish-grey));
            color: var(--color-dark-text);
            padding: 120px 2rem 80px 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: fadeInScale 1.2s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        .services-hero-section h1 {
            color: var(--color-heading-dark);
            font-size: 4.8rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.1);
        }

        .services-hero-section p {
            font-size: 1.8rem;
            opacity: 0.9;
            max-width: 900px;
            margin: 0 auto 3rem auto;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.05);
        }

        /* Paw Prints (general use across sections) */
        .paw-print {
            position: absolute;
            width: 50px;
            height: 50px;
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23D4931F"%3E%3Cpath d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"%3E%3C/path%3E%3C/svg%3E');
            background-size: contain;
            background-repeat: no-repeat;
            border-radius: 50%;
            animation: floatPaw 12s infinite linear;
            opacity: 0.25;
            z-index: 0;
            pointer-events: none;
        }

        /* Define colored paw print SVG data URLs */
        .paw-mustard {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23D4931F"%3E%3Cpath d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"%3E%3C/path%3E%3C/svg%3E');
        }

        .paw-reddish-orange {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23E17C56"%3E%3Cpath d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"%3E%3C/path%3E%3C/svg%3E');
        }

        .paw-muted-green {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23AEC8B5"%3E%3Cpath d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"%3E%3C/path%3E%3C/svg%3E');
        }

        .paw-peach {
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23F7B9A6"%3E%3Cpath d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"%3E%3C/path%3E%3C/svg%3E');
        }


        @keyframes floatPaw {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.25;
            }

            50% {
                transform: translateY(-25px) rotate(180deg);
                opacity: 0.35;
                /* Increased max opacity */
            }

            100% {
                transform: translateY(0) rotate(360deg);
                opacity: 0.25;
            }
        }


        /* Wave Divider */
        .wave-divider {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            overflow: hidden;
            z-index: 1;
        }

        .wave-divider svg {
            display: block;
            width: 100%;
            height: 100%;
            transform: scaleY(1.5);
            transform-origin: bottom;
        }

        .wave-divider path {
            fill: var(--color-light-greenish-grey);
        }

        /* General Section Styling */
        .main-section {
            padding-top: 5rem;
            padding-bottom: 5rem;
            position: relative;
            z-index: 10;
        }

        .section-heading {
            color: var(--color-heading-dark);
            font-size: 3.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 4rem;
        }

        /* Service Cards - Adjusted for specific service types */
        .service-card {
            background-color: white;
            padding: 2.5rem;
            border-radius: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 3px solid transparent;
            transition: all 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 400px;
        }

        .service-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.2);
        }

        /* Specific border colors for service cards */
        .service-card.adoption {
            border-color: var(--color-muted-green);
        }

        .service-card.marketplace {
            border-color: var(--color-reddish-orange);
        }

        .service-card.vet {
            border-color: var(--color-mustard);
        }

        .service-card.training {
            border-color: var(--color-service-blue);
        }

        .service-card.grooming {
            border-color: var(--color-service-purple);
        }

        .service-card.walking {
            border-color: var(--color-service-green);
        }

        .service-card.feeding {
            border-color: var(--color-service-green);
        }

        .service-card.playtime {
            border-color: var(--color-service-blue);
        }


        .service-card svg {
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
        }

        /* Specific fill colors for service card SVGs */
        .service-card.adoption svg {
            fill: var(--color-muted-green);
        }

        .service-card.marketplace svg {
            fill: var(--color-reddish-orange);
        }

        .service-card.vet svg {
            fill: var(--color-mustard);
        }

        .service-card.training svg {
            fill: var(--color-service-blue);
        }

        .service-card.grooming svg {
            fill: var(--color-service-purple);
        }

        .service-card.walking svg {
            fill: var(--color-service-green);
        }

        .service-card.feeding svg {
            fill: var(--color-service-green);
        }

        .service-card.playtime svg {
            fill: var(--color-service-blue);
        }


        .service-card h3 {
            font-size: 2.25rem;
            margin-bottom: 1rem;
            color: var(--color-mustard);
        }

        .service-card p {
            font-size: 1.125rem;
            color: var(--color-dark-text);
            flex-grow: 1;
            margin-bottom: 1.5rem;
        }

        /* Detail Section Styles */
        .detail-section {
            background-color: white;
            border-radius: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 3rem;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            align-items: center;
        }

        .detail-section.left-aligned {
            flex-direction: column;
            /* Default for mobile */
            text-align: center;
        }

        .detail-section.right-aligned {
            flex-direction: column;
            /* Default for mobile */
            text-align: center;
        }

        @media (min-width: 768px) {
            .detail-section.left-aligned {
                flex-direction: row;
                text-align: left;
            }

            .detail-section.right-aligned {
                flex-direction: row-reverse;
                text-align: left;
            }
        }

        .detail-section img {
            border-radius: 1.5rem;
            max-width: 100%;
            height: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .detail-section .content {
            flex: 1;
        }

        .detail-section h3 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--color-heading-dark);
            text-align: inherit;
            /* Inherit text alignment */
        }

        .detail-section p {
            font-size: 1.125rem;
            color: var(--color-dark-text);
            margin-bottom: 1.5rem;
            text-align: inherit;
            /* Inherit text alignment */
        }

        .detail-section ul {
            list-style: none;
            padding: 0;
            margin-bottom: 1.5rem;
        }

        .detail-section ul li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.75rem;
            color: var(--color-dark-text);
            font-size: 1rem;
        }

        .detail-section ul li svg {
            width: 20px;
            height: 20px;
            fill: var(--color-mustard);
            margin-right: 0.75rem;
            flex-shrink: 0;
            margin-top: 0.2rem;
            /* Adjust for icon vertical alignment */
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--color-mustard), var(--color-reddish-orange));
            color: white;
            padding: 6rem 2rem;
            border-radius: 3rem;
            margin: 0 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .cta-section h2 {
            color: white;
            font-size: 3.5rem;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .cta-section p {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 800px;
            margin: 0 auto 2.5rem auto;
        }

        /* Footer */
        footer {
            background-color: var(--color-mustard);
            color: white;
            padding-top: 6rem;
            padding-bottom: 3rem;
        }

        footer h3 {
            color: white;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }

        footer ul li a {
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        footer ul li a:hover {
            color: white;
            transform: translateX(5px);
        }

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

        /* New Card Animations */
        /* Float Animation */
        .animate-float-card {
            animation: floatCard 4s ease-in-out infinite alternate;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes floatCard {
            0% {
                transform: translateY(0px);
                opacity: 0.8;
            }

            100% {
                transform: translateY(-15px);
                opacity: 1;
            }
        }

        /* Slide In Right Animation */
        .animate-slide-in-right {
            animation: slideInRight 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            opacity: 0;
            transform: translateX(50px);
        }

        @keyframes slideInRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Slide In Left Animation */
        .animate-slide-in-left {
            animation: slideInLeft 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            opacity: 0;
            transform: translateX(-50px);
        }

        @keyframes slideInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Scale In Animation */
        .animate-scale-in {
            animation: scaleIn 0.7s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            opacity: 0;
            transform: scale(0.9);
        }

        @keyframes scaleIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="text-[color:var(--color-dark-text)]">

    <div id="loading-spinner" class="loading-spinner"></div>

    <header class="w-full fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4 py-4">
            <nav class="flex justify-center md:justify-start gap-8 text-lg font-semibold">
                <a href="user-home.php"
                    class="text-[color:var(--color-dark-text)] hover:text-[color:var(--color-mustard)] transition duration-200">Dashboard</a>
                <a href="services.php"
                    class="text-[color:var(--color-dark-text)] hover:text-[color:var(--color-mustard)] transition duration-200">Services</a>
                <a href="my_bookings.php"
                    class="text-[color:var(--color-dark-text)] hover:text-[color:var(--color-mustard)] transition duration-200">My
                    Bookings</a>
            </nav>

            <div class="flex justify-center flex-shrink-0">
                <img src="assets/images/logo2.png" alt="Pawsome Adoptions Logo"
                    class="h-16 w-auto transition-all duration-300 ease-in-out">
            </div>

            <div class="flex items-center gap-3 text-lg font-semibold text-[color:var(--color-dark-text)]">
                <a href="profile.php"
                    class="flex items-center gap-2 hover:text-[color:var(--color-mustard)] transition duration-200">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                    <div class="flex items-center gap-3">
                        <img src="<?= $profilePhoto ?>" alt="Profile Photo"
                            class="w-10 h-10 rounded-full border-2 border-[#E17C56] shadow" />
                        <span class="text-sm font-semibold"><?= $userName ?></span>
                    </div>


                </a>
            </div>
        </div>
    </header>

    <div class="h-24"></div>

    <main>
        <section class="services-hero-section">
            <div class="paw-print paw-mustard"
                style="left: 10%; top: 15%; animation-delay: 0s; width: 55px; height: 55px;"></div>
            <div class="paw-print paw-reddish-orange"
                style="left: 80%; top: 40%; animation-delay: 2s; width: 40px; height: 40px;"></div>
            <div class="paw-print paw-muted-green"
                style="left: 25%; top: 60%; animation-delay: 4s; width: 60px; height: 60px;"></div>
            <div class="paw-print paw-peach"
                style="left: 60%; top: 85%; animation-delay: 1s; width: 45px; height: 45px;"></div>
            <div class="paw-print paw-mustard"
                style="left: 5%; top: 90%; animation-delay: 3s; width: 35px; height: 35px;"></div>
            <div class="paw-print paw-reddish-orange"
                style="left: 90%; top: 10%; animation-delay: 5s; width: 65px; height: 65px;"></div>

            <div class="container mx-auto px-4 relative z-10">
                <h1 class="font-extrabold">Book Pet Services</h1>
                <p class="font-normal">Easily browse and schedule feeding, walking, grooming, training, and veterinary
                    care for your beloved pet. Find the perfect service and book it directly here.</p>
            </div>

            <div class="wave-divider">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160" preserveAspectRatio="none">
                    <path d="M0,96C288,160,1152,0,1440,32L1440,160L0,160Z"></path>
                </svg>
            </div>
        </section>

        ---


        <section id="all-services-overview" class="main-section container mx-auto">
            <h2 class="section-heading animate-fade-in" style="animation-delay: 0.2s;">Choose a Service to Book</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $delay = 1.2;
                foreach ($categories as $cat):
                    $categoryName = htmlspecialchars($cat['CategoryName']);
                    $description = htmlspecialchars($cat['Description']);
                    // Button text uses the first word of the category name to keep it short
                    $buttonText = "Book " . explode(' ', $categoryName)[0];
                    ?>
                    <div class="service-card animate-float-card" style="animation-delay: <?= $delay ?>s;">
                        <!-- Default SVG icon (you can customize or replace per category) -->
                        <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 16h10v2H7zm0-4h10v2H7zm-3-4h16v2H4zm13-4c0-.55-.45-1-1-1H8c-.55 0-1 .45-1 1v.5H4v2h16v-2h-3V4z" />
                        </svg>
                        <h3><?= $categoryName ?></h3>
                        <p><?= $description ?></p>
                        <form action="service-book.php" method="POST">
                            <input type="hidden" name="category_id" value="<?= (int) $cat['CategoryID'] ?>">
                            <button type="submit" class="btn-primary px-6 py-3 text-lg"><?= $buttonText ?></button>
                        </form>
                    </div>
                    <?php
                    $delay += 0.2;
                endforeach;
                ?>
            </div>
        </section>



    </main>



    <footer class="mt-20">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 pb-8">
            <div>
                <h3 class="text-xl font-bold mb-4">Pawsome Adoptions</h3>
                <p class="text-sm text-white/80">
                    Your trusted partner for pet care and services.
                </p>
                <div class="flex items-center gap-2 mt-4 text-white/80 text-sm">
                    <svg class="w-4 h-4 footer-paw-rotate" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z">
                        </path>
                    </svg>
                    <span>Every paw deserves the best care!</span>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="dashboard.php" class="hover:text-white block transition-transform">Dashboard</a></li>
                    <li><a href="services.php" class="hover:text-white block transition-transform">Our Services</a>
                    </li>
                    <li><a href="my_bookings.php" class="hover:text-white block transition-transform">My Bookings</a>
                    </li>
                    <li><a href="profile.php" class="hover:text-white block transition-transform">My Profile</a>
                    </li>
                    <li><a href="index.php#about-us" class="hover:text-white block transition-transform">About Us</a>
                    </li>
                    <li><a href="index.php#testimonials"
                            class="hover:text-white block transition-transform">Testimonials</a></li>
                    <li><a href="#" class="hover:text-white block transition-transform">Blog</a></li>
                    <li><a href="#" class="hover:text-white block transition-transform">FAQ</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-4">Contact Us</h3>
                <p class="text-sm text-white/80 mb-2">Email: info@pawsomeadoptions.com</p>
                <p class="text-sm text-white/80 mb-4">Phone: +1 (555) 123-4567</p>

                <h3 class="text-xl font-bold mb-4">Follow Us</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-white hover:scale-110 transition-transform duration-200"
                        aria-label="Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19 0H5C2.243 0 0 2.243 0 5v14c0 2.757 2.243 5 5 5h14c2.757 0 5-2.243 5-5V5c0-2.757-2.243-5-5-5zm-3 7h-2.5c-.276 0-.5.224-.5.5v2.5h3l-.388 3H13v7h-3v-7H7V10h3V7.5C10 5.545 11.545 4 13.5 4h2C15.776 4 16 4.224 16 4.5V7z">
                            </path>
                        </svg>
                    </a>
                    <a href="#" class="text-white hover:scale-110 transition-transform duration-200"
                        aria-label="Twitter">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.791-1.574 2.164-2.721-.951.564-2.005.974-3.127 1.195-.89-.944-2.172-1.535-3.593-1.535-2.71 0-4.91 2.2-4.91 4.91 0 .386.044.76.128 1.124C7.794 10.354 4.1 8.354 1.671 5.485c-.417.712-.656 1.545-.656 2.434 0 1.701.868 3.209 2.188 4.096-.807-.025-1.56-.246-2.227-.616v.061c0 2.385 1.697 4.374 3.946 4.827-.412.112-.843.172-1.287.172-.314 0-.61-.03-.898-.086.631 1.957 2.446 3.38 4.6 3.42-1.688 1.321-3.812 2.109-6.115 2.109-.398 0-.79-.023-1.177-.069 2.176 1.397 4.768 2.212 7.548 2.212 9.057 0 14.008-7.503 14.008-14.008 0-.214-.005-.429-.014-.643.962-.695 1.797-1.564 2.464-2.553z">
                            </path>
                        </svg>
                    </a>
                    <a href="#" class="text-white hover:scale-110 transition-transform duration-200"
                        aria-label="Instagram">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.92.333 4.145.642C3.365.952 2.65 1.38 2.073 1.957A5.992 5.992 0 00.642 4.145C.333 4.92.132 5.775.072 7.053.015 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.278.261 2.133.57 2.908.31.78.738 1.493 1.315 2.071a5.992 5.992 0 002.071 1.315c.775.309 1.63.51 2.908.57 1.278.057 1.694.072 4.947.072s3.667-.015 4.947-.072c1.278-.06 2.133-.261 2.908-.57.78-.31 1.493-.738 2.071-1.315a5.992 5.992 0 001.315-2.071c.309-.775.51-1.63.57-2.908.057-1.278.072-1.694.072-4.947s-.015-3.667-.072-4.947c-.06-1.278-.261-2.133-.57-2.908A5.992 5.992 0 0022.043.642C21.268.333 20.413.132 19.135.072 17.857.015 17.443 0 12 0zm0 2.163c3.204 0 3.584.012 4.85.072 1.171.055 1.805.249 2.227.412.56.216.92.483 1.258.821a4.004 4.004 0 01.821 1.258c.163.422.356 1.056.412 2.227.06 1.266.072 1.646.072 4.85s-.012 3.584-.072 4.85c-.055 1.171-.249 1.805-.412 2.227a4.004 4.004 0 01-1.258.821c-.422.163-1.056.356-2.227.412-1.266.06-1.646.072-4.85.072s-3.584-.012-4.85-.072c-1.171-.055-1.805-.249-2.227-.412a4.004 4.004 0 01-.821-1.258c-.163-.422-.356-1.056-.412-2.227-.06-1.266-.072-1.646-.072-4.85s.012-3.584.072-4.85c.055-1.171.249-1.805.412-2.227a4.004 4.004 0 011.258-.821c.422-.163 1.056-.356 2.227-.412 1.266-.06 1.646-.072 4.85-.072zm0 3.635A6.165 6.165 0 1018.165 12 6.171 6.171 0 0012 5.798zm0 10.158A3.993 3.993 0 1115.993 12 3.998 3.998 0 0112 15.956zm5.325-10.916a.916.916 0 10.916.916.916.916 0 00-.916-.916z">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center text-white/70 text-sm mt-8 border-t border-white/20 pt-6">
            &copy; 2025 Pawsome Adoptions. All rights reserved.
        </div>
    </footer>


    <button id="scrollToTopBtn" title="Go to top">↑</button>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hide loading spinner
            const spinner = document.getElementById('loading-spinner');
            spinner.classList.add('hidden');

            // Header Shrink on Scroll
            const header = document.querySelector('header');
            const logo = header.querySelector('img');

            function shrinkHeader() {
                if (window.scrollY > 50) {
                    header.classList.add('header-shrink');
                    logo.style.height = '4rem';
                } else {
                    header.classList.remove('header-shrink');
                    logo.style.height = '5rem';
                }
            }

            window.addEventListener('scroll', shrinkHeader);
            shrinkHeader(); // Call on load to set initial state


            // Scroll to Top Button functionality
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    scrollToTopBtn.style.display = 'block';
                } else {
                    scrollToTopBtn.style.display = 'none';
                }
            });

            scrollToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Smooth Scrolling for Navigation Links (modified for this page's anchor links)
            document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    // For internal links on the services page
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Intersection Observer for animations
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0) translateX(0) scale(1)';
                        // For float cards, restart animation on view
                        if (entry.target.classList.contains('animate-float-card')) {
                            entry.target.style.animation = 'none';
                            void entry.target.offsetWidth; // Trigger reflow
                            entry.target.style.animation = 'floatCard 4s ease-in-out infinite alternate';
                        }
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-fade-in, .animate-float-card, .animate-slide-in-right, .animate-slide-in-left, .animate-scale-in').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>

</html>