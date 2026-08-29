<?php

session_start();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawsome Adoptions - Find Your Fur-ever Friend!</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Fredoka for headings, Nunito for body -->
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
            /* Needed for absolute positioning of walking cat */
            overflow: hidden;
            /* Important for containing the walking cat */
        }

        .header-shrink {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Walking Cat in Header */
        .walking-cat {
            position: absolute;
            bottom: -100px;
            /* Adjust if needed to align with header bottom */
            left: -100px;
            /* Start off-screen left */
            width: 200px;
            height: 200px;
            background-image: url('assets/images/cat-walking.gif');
            background-size: contain;
            background-repeat: no-repeat;
            z-index: 51;
            /* Above header elements, below fixed content like modal */
            animation: walkAcrossHeader 20s linear infinite;
            /* Adjust speed as needed */
            pointer-events: none;
            /* Allows clicks to pass through to elements below */
            transform-origin: center bottom;
            /* For potential future animation enhancements */
        }

        @keyframes walkAcrossHeader {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(calc(100vw + 100px));
            }

            /* Moves across entire viewport plus its own width */
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

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--color-peach), var(--color-reddish-orange));
            color: white;
            padding: 120px 2rem 80px 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            /* Removed border-bottom-left-radius and border-bottom-right-radius */
            /* Removed box-shadow */
            animation: fadeInScale 1.2s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .hero-section h1 {
            color: var(--color-heading-dark);
            font-size: 4.8rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.2);
            animation: pulseHeroText 2.5s infinite alternate;
        }

        @keyframes pulseHeroText {
            0% {
                transform: scale(1);
                text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.2);
            }

            50% {
                transform: scale(1.01);
                text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.3);
            }

            100% {
                transform: scale(1);
                text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.2);
            }
        }

        .hero-section p {
            font-size: 1.8rem;
            opacity: 0.9;
            max-width: 900px;
            margin: 0 auto 3rem auto;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
            color: var(--color-dark-text);
        }

        /* Paw Prints (general use across sections) */
        .paw-print {
            position: absolute;
            width: 50px;
            height: 50px;
            /* Default fill color, can be overridden by inline style */
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23D4931F"%3E%3Cpath d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"%3E%3C/path%3E%3C/svg%3E');
            background-size: contain;
            background-repeat: no-repeat;
            border-radius: 50%;
            animation: floatPaw 12s infinite linear;
            opacity: 0.25;
            /* Slightly increased opacity for color visibility */
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
            /* Increased height for more pronounced wave */
            overflow: hidden;
            z-index: 1;
        }

        .wave-divider svg {
            display: block;
            width: 100%;
            height: 100%;
            transform: scaleY(1.5);
            /* Scale Y to make the wave more dramatic */
            transform-origin: bottom;
        }

        .wave-divider path {
            fill: var(--color-light-greenish-grey);
            /* Changed to next section's background color */
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
            /* text-5xl */
            font-weight: 800;
            text-align: center;
            margin-bottom: 4rem;
        }

        /* Service Cards */
        .service-card {
            background-color: white;
            padding: 2.5rem;
            border-radius: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 3px solid transparent;
            /* Default transparent border */
            transition: all 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 400px;
            /* Ensures consistent height */
        }

        .service-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.2);
        }

        .service-card.adoption {
            border-color: var(--color-muted-green);
        }

        .service-card.marketplace {
            border-color: var(--color-reddish-orange);
        }

        .service-card.vet {
            border-color: var(--color-mustard);
        }

        .service-card svg {
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
        }

        .service-card.adoption svg {
            fill: var(--color-muted-green);
        }

        .service-card.marketplace svg {
            fill: var(--color-reddish-orange);
        }

        .service-card.vet svg {
            fill: var(--color-mustard);
        }

        .service-card h3 {
            font-size: 2.25rem;
            /* text-4xl */
            margin-bottom: 1rem;
            color: var(--color-mustard);
        }

        .service-card p {
            font-size: 1.125rem;
            /* text-lg */
            color: var(--color-dark-text);
            flex-grow: 1;
            margin-bottom: 1.5rem;
        }

        /* About Us Section */
        .about-us-section {
            background-color: var(--color-muted-green);
            color: white;
            padding: 6rem 2rem;
            border-radius: 3rem;
            margin: 0 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .about-us-section h2 {
            color: white;
            /* Heading white on muted green */
            font-size: 3.5rem;
            /* text-5xl */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .about-us-section p {
            font-size: 1.125rem;
            /* text-lg */
            color: rgba(255, 255, 255, 0.9);
        }

        /* Testimonial Cards */
        .testimonial-card {
            background-color: white;
            padding: 2.5rem;
            border-radius: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 2px solid var(--color-light-greenish-grey);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            border-color: var(--color-reddish-orange);
        }

        .testimonial-card p {
            font-size: 1.125rem;
            /* text-lg */
            font-style: italic;
            color: var(--color-dark-text);
            margin-bottom: 1.5rem;
            z-index: 2;
            position: relative;
        }

        .testimonial-card .author {
            font-weight: 700;
            color: var(--color-mustard);
            font-size: 1.125rem;
            z-index: 2;
            position: relative;
        }

        /* Peeking Cat Icons (Updated animation) */
        .peeking-cat-edge {
            position: absolute;
            width: 120px;
            height: 120px;
            /* Black cat SVG (distinct cat shape) */
            background-image: url('assets/images/testing2.gif');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0;
            /* Start hidden */
            z-index: 15;
            pointer-events: none;
            transition: transform 0.8s ease-in-out, opacity 0.8s ease-in-out;
        }

        /* New animations for peeking */
        @keyframes peekInFromLeft {
            0% {
                transform: translateX(-100%) rotate(0deg);
                opacity: 0;
            }

            20% {
                transform: translateX(0%) rotate(5deg);
                opacity: 0.7;
            }

            80% {
                transform: translateX(0%) rotate(5deg);
                opacity: 0.7;
            }

            100% {
                transform: translateX(-100%) rotate(0deg);
                opacity: 0;
            }
        }

        @keyframes peekInFromRight {
            0% {
                transform: translateX(100%) rotate(0deg) scaleX(-1);
                opacity: 0;
            }

            20% {
                transform: translateX(0%) rotate(-5deg) scaleX(-1);
                opacity: 0.7;
            }

            80% {
                transform: translateX(0%) rotate(-5deg) scaleX(-1);
                opacity: 0.7;
            }

            100% {
                transform: translateX(100%) rotate(0deg) scaleX(-1);
                opacity: 0;
            }
        }

        .peeking-cat-edge.left {
            left: 0;
            /* Position at the left edge */
            transform: translateX(-100%);
            /* Initially off-screen left */
            animation: peekInFromLeft 6s infinite ease-in-out;
            /* Adjust timing */
        }

        .peeking-cat-edge.right {
            right: 0;
            /* Position at the right edge */
            transform: translateX(100%) scaleX(-1);
            /* Initially off-screen right, flipped */
            animation: peekInFromRight 6s infinite ease-in-out;
            /* Adjust timing */
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
            /* Adjusted top padding for more space */
            padding-bottom: 3rem;
            /* Removed margin-top to simplify spacing */
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
            /* Start hidden for stagger */
            transform: translateY(20px);
            /* Start slightly down */
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

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="loading-spinner"></div>

    <!-- Header / Navbar -->
    <header class="w-full fixed top-0 left-0 right-0 z-50 shadow-md">
        <!-- Walking Cat Element -->
        <div class="walking-cat"></div>

        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4 py-4">
            <!-- Left Menu -->
            <nav class="flex justify-center md:justify-start gap-8 text-lg font-semibold">
                <a href="#hero-section"
                    class="text-[color:var(--color-dark-text)] hover:text-[color:var(--color-mustard)] transition duration-200">Home</a>
                <a href="#services-overview"
                    class="text-[color:var(--color-dark-text)] hover:text-[color:var(--color-mustard)] transition duration-200">Services</a>
                <a href="#about-us"
                    class="text-[color:var(--color-dark-text)] hover:text-[color:var(--color-mustard)] transition duration-200">About
                    Us</a>
                <a href="#testimonials"
                    class="text-[color:var(--dark-text)] hover:text-[color:var(--color-mustard)] transition duration-200">Testimonials</a>
            </nav>

            <!-- Center Logo -->
            <div class="flex justify-center flex-shrink-0">
                <img src="assets/images/logo2.png" alt="Pawsome Adoptions Logo"
                    class="h-16 w-auto transition-all duration-300 ease-in-out">
            </div>


            <!-- Right Buttons (Login & Sign Up) -->
            <!-- Right Buttons (Login & Sign Up) -->
            <div class="flex justify-center md:justify-end gap-4">
                <a href="auth/login.php">Login

                    <button class="btn-secondary px-6 py-2 text-base">Login</button>
                </a>
                <a href="auth/register.php">
                    <button class="btn-primary px-6 py-2 text-base">Sign Up</button>
                </a>
            </div>

        </div>
    </header>

    <!-- Spacer for fixed header -->
    <div class="h-24"></div>

    <main>
        <!-- Hero Section -->
        <section id="hero-section" class="hero-section">
            <!-- Floating Paw Print Icons -->
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
            <div class="paw-print paw-muted-green"
                style="left: 35%; top: 5%; animation-delay: 1.5s; width: 40px; height: 40px;"></div>
            <div class="paw-print paw-peach"
                style="left: 70%; top: 70%; animation-delay: 3.5s; width: 50px; height: 50px;"></div>
            <div class="paw-print paw-mustard"
                style="left: 15%; top: 30%; animation-delay: 6s; width: 48px; height: 48px;"></div>
            <div class="paw-print paw-reddish-orange"
                style="left: 75%; top: 20%; animation-delay: 7s; width: 52px; height: 52px;"></div>
            <div class="paw-print paw-muted-green"
                style="left: 45%; top: 70%; animation-delay: 8s; width: 58px; height: 58px;"></div>
            <div class="paw-print paw-peach"
                style="left: 20%; top: 80%; animation-delay: 9s; width: 43px; height: 43px;"></div>
            <div class="paw-print paw-mustard"
                style="left: 85%; top: 60%; animation-delay: 10s; width: 62px; height: 62px;"></div>
            <div class="paw-print paw-reddish-orange"
                style="left: 50%; top: 10%; animation-delay: 11s; width: 38px; height: 38px;"></div>
            <div class="paw-print paw-muted-green"
                style="left: 5%; top: 50%; animation-delay: 12s; width: 70px; height: 70px;"></div>


            <!-- Peeking Cat Icons -->
            <div class="peeking-cat-edge left" style="top: 10%;"></div>
            <div class="peeking-cat-edge right" style="top: 50%; animation-delay: 3s;"></div>

            <div class="container mx-auto px-4 relative z-10">
                <h1 class="font-extrabold">Pawsome Adoptions: <br class="md:hidden"> Where Every Paw Finds a Home!</h1>
                <p class="font-normal">Your ultimate hub for connecting with lovable pets, discovering quality products,
                    and accessing expert vet care. Caring for pets made easy!</p>
                <div class="flex flex-col sm:flex-row justify-center gap-6">
                    <button class="btn-primary">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z">
                            </path>
                        </svg>
                        Find Your Furry Friend
                    </button>
                    <button class="btn-secondary">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM11 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z">
                            </path>
                        </svg>
                        Shop Pet Essentials
                    </button>
                </div>
            </div>

            <!-- SVG Wave Divider (Updated) -->
            <div class="wave-divider">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160" preserveAspectRatio="none">
                    <path d="M0,96C288,160,1152,0,1440,32L1440,160L0,160Z"></path>
                </svg>
            </div>
        </section>

        <!-- Services Overview Section -->
        <section id="services-overview" class="main-section container mx-auto">
            <!-- Floating Paw Print Icons for this section -->
            <div class="paw-print paw-muted-green"
                style="left: 10%; top: 5%; animation-delay: 0.5s; width: 50px; height: 50px;"></div>
            <div class="paw-print paw-peach"
                style="left: 40%; top: 15%; animation-delay: 1.5s; width: 35px; height: 35px;"></div>
            <div class="paw-print paw-mustard"
                style="left: 85%; top: 10%; animation-delay: 2.5s; width: 65px; height: 65px;"></div>
            <div class="paw-print paw-reddish-orange"
                style="left: 20%; top: 40%; animation-delay: 3.5s; width: 60px; height: 60px;"></div>
            <div class="paw-print paw-muted-green"
                style="left: 70%; top: 50%; animation-delay: 4.5s; width: 45px; height: 45px;"></div>
            <div class="paw-print paw-peach"
                style="left: 5%; top: 75%; animation-delay: 5.5s; width: 55px; height: 55px;"></div>
            <div class="paw-print paw-mustard"
                style="left: 90%; top: 80%; animation-delay: 6.5s; width: 40px; height: 40px;"></div>
            <div class="paw-print paw-reddish-orange"
                style="left: 30%; top: 90%; animation-delay: 7.5s; width: 70px; height: 70px;"></div>


            <!-- Peeking Cat Icons -->
            <div class="peeking-cat-edge right" style="top: 20%; animation-delay: 2s;"></div>
            <div class="peeking-cat-edge left" style="top: 70%; animation-delay: 5s;"></div>

            <h2 class="section-heading animate-fade-in" style="animation-delay: 0.2s;">Our Pawsome Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Pet Adoption Card -->
                <div class="service-card adoption animate-float-card" style="animation-delay: 0.4s;">
                    <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z">
                        </path>
                    </svg>
                    <h3>Pet Adoption</h3>
                    <p>Open your heart and home to a wonderful companion. Browse profiles of adorable pets patiently
                        waiting for their forever families. Our process makes adoption joyful and seamless.</p>
                    <button class="btn-secondary py-3 px-6 text-lg">Learn More</button>
                </div>

                <!-- Marketplace Card -->
                <div class="service-card marketplace animate-slide-in-right" style="animation-delay: 0.6s;">
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM11 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z">
                        </path>
                    </svg>
                    <h3>Pawsome Marketplace</h3>
                    <p>Equip your pet with the best! Our marketplace offers a curated selection of premium food, fun
                        toys, cozy beds, and essential accessories. Shop with confidence for quality and care.</p>
                    <button class="btn-secondary py-3 px-6 text-lg">Explore Shop</button>
                </div>

                <!-- Vet Appointments Card -->
                <div class="service-card vet animate-scale-in" style="animation-delay: 0.8s;">
                    <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                        </path>
                    </svg>
                    <h3>Vet Appointments</h3>
                    <p>Ensure your pet's well-being with easy online booking for veterinary appointments. From routine
                        check-ups to specialized care, connect with trusted vets effortlessly.</p>
                    <button class="btn-secondary py-3 px-6 text-lg">Book Now</button>
                </div>
            </div>
        </section>

        <!-- About Us Section -->
        <section id="about-us" class="main-section about-us-section container mx-auto text-center">
            <!-- Floating Paw Print Icons for this section -->
            <div class="paw-print paw-mustard"
                style="left: 15%; top: 10%; animation-delay: 1s; width: 40px; height: 40px;"></div>
            <div class="paw-print paw-peach"
                style="right: 5%; top: 30%; animation-delay: 2.2s; width: 55px; height: 55px;"></div>
            <div class="paw-print paw-reddish-orange"
                style="left: 40%; bottom: 5%; animation-delay: 3.4s; width: 60px; height: 60px;"></div>
            <div class="paw-print paw-muted-green"
                style="left: 5%; top: 50%; animation-delay: 4.6s; width: 50px; height: 50px;"></div>
            <div class="paw-print paw-mustard"
                style="right: 25%; bottom: 40%; animation-delay: 5.8s; width: 42px; height: 42px;"></div>


            <!-- Peeking Cat Icons -->
            <div class="peeking-cat-edge left" style="top: 40%; animation-delay: 8s;"></div>
            <div class="peeking-cat-edge right" style="bottom: 10%; animation-delay: 18s;"></div>

            <h2 class="section-heading animate-fade-in" style="animation-delay: 0.2s;">Our Passion: Connecting Hearts &
                Paws</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mt-12">
                <div class="animate-fade-in" style="animation-delay: 0.4s;">
                    <p class="text-lg leading-relaxed mb-6">
                        At Pawsome Adoptions, we're driven by a simple, powerful mission: to make the world a happier
                        place, one paw at a time. We've built this platform as a bridge, connecting loving animals with
                        compassionate families.
                    </p>
                    <p class="text-lg leading-relaxed">
                        Beyond adoptions, we strive to be a comprehensive resource for pet parents, offering a curated
                        marketplace for quality pet essentials and facilitating easy access to vital veterinary care.
                        Our community is growing, and every success story fuels our dedication.
                    </p>
                </div>
                <div class="flex justify-center animate-fade-in" style="animation-delay: 0.6s;">
                    <img src="assets/images/adore2..jpg" alt="Happy family with a pet"
                        class="rounded-3xl shadow-xl border-4 border-white">
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="main-section container mx-auto">
            <!-- Floating Paw Print Icons for this section -->
            <div class="paw-print paw-reddish-orange"
                style="left: 5%; top: 8%; animation-delay: 0.8s; width: 48px; height: 48px;"></div>
            <div class="paw-print paw-mustard"
                style="right: 12%; top: 25%; animation-delay: 2.5s; width: 62px; height: 62px;"></div>
            <div class="paw-print paw-peach"
                style="left: 60%; bottom: 15%; animation-delay: 1.2s; width: 40px; height: 40px;"></div>
            <div class="paw-print paw-muted-green"
                style="right: 30%; top: 5%; animation-delay: 3s; width: 50px; height: 50px;"></div>
            <div class="paw-print paw-reddish-orange"
                style="left: 20%; bottom: 5%; animation-delay: 4.2s; width: 58px; height: 58px;"></div>


            <!-- Peeking Cat Icons -->
            <div class="peeking-cat-edge right" style="top: 60%; animation-delay: 1.5s;"></div>
            <div class="peeking-cat-edge left" style="bottom: 25%; animation-delay: 4s;"></div>

            <h2 class="section-heading animate-fade-in" style="animation-delay: 0.2s;">Happy Tails, Happy Families</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="testimonial-card animate-scale-in" style="animation-delay: 0.4s;">
                    <p>"Pawsome Adoptions made finding my soul-dog, Luna, an absolute dream! The detailed profiles and
                        smooth process were amazing. Forever grateful!"</p>
                    <p class="author">- Jessica W.</p>
                </div>
                <div class="testimonial-card animate-float-card" style="animation-delay: 0.6s;">
                    <p>"The marketplace is my go-to for my cat's food and toys. High quality, great prices, and fast
                        shipping. My furbaby is thriving!"</p>
                    <p class="author">- Michael B.</p>
                </div>
                <div class="testimonial-card animate-slide-in-right" style="animation-delay: 0.8s;">
                    <p>"Scheduling vet visits for my rabbit, Hoppy, has never been this easy. The platform is intuitive,
                        and the reminders are a lifesaver."</p>
                    <p class="author">- Olivia S.</p>
                </div>
            </div>
        </section>

        <!-- Final Call to Action Section -->
        <section class="main-section cta-section container mx-auto">
            <!-- Floating Paw Print Icons for this section -->
            <div class="paw-print paw-mustard"
                style="left: 20%; top: 10%; animation-delay: 1.5s; width: 70px; height: 70px;"></div>
            <div class="paw-print paw-reddish-orange"
                style="right: 10%; bottom: 5%; animation-delay: 3.5s; width: 50px; height: 50px;"></div>
            <div class="paw-print paw-muted-green"
                style="left: 25%; top: 30%; animation-delay: 0.9s; width: 60px; height: 60px;"></div>
            <div class="paw-print paw-peach"
                style="left: 8%; bottom: 15%; animation-delay: 2s; width: 45px; height: 45px;"></div>
            <div class="paw-print paw-mustard"
                style="right: 15%; top: 25%; animation-delay: 0.5s; width: 65px; height: 65px;"></div>
            <div class="paw-print paw-reddish-orange"
                style="left: 40%; bottom: 20%; animation-delay: 3s; width: 55px; height: 55px;"></div>


            <!-- Peeking Cat Icons -->
            <div class="peeking-cat-edge left" style="top: 20%; animation-delay: 6s;"></div>
            <div class="peeking-cat-edge right" style="bottom: 30%; animation-delay: 16s;"></div>

            <h2 class="animate-fade-in" style="animation-delay: 0.2s;">Ready to Join Our Pawsome Community?</h2>
            <p class="animate-fade-in" style="animation-delay: 0.4s;">Sign up today and embark on a rewarding journey of
                pet care, companionship, and endless happy moments. Your furry friend is waiting!</p>
            <div class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="auth/login.php" class="btn-primary px-8 py-4 text-xl inline-flex items-center">
                    <svg class="w-7 h-7 mr-2" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                    Login to Your Account
                </a>

                <a href="/animora/auth/register.php">
                    <button class="btn-secondary px-8 py-4 text-xl">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Create a Free Account
                    </button>
                </a>

            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="text-white py-12 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">
            <div>
                <h3 class="text-2xl font-bold mb-4">Pawsome Adoptions</h3>
                <p class="text-sm opacity-80">Where every tail finds its wag! We're dedicated to uniting furry hearts
                    with forever homes.</p>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4">Quick Sniffs</h3>
                <ul class="space-y-3">
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
            <div>
                <h3 class="text-2xl font-bold mb-4">Support Paws</h3>
                <ul class="space-y-3">
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
            <div>
                <h3 class="text-2xl font-bold mb-4">Join Our Fur-mily!</h3>
                <div class="flex space-x-5">
                    <a href="#" class="opacity-80 hover:opacity-100 transition duration-300 transform hover:scale-125">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.776-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.247 0-1.646.773-1.646 1.572V12h2.77l-.443 2.89h-2.327v6.987C18.343 21.128 22 16.991 22 12z" />
                        </svg>
                    </a>
                    <a href="#" class="opacity-80 hover:opacity-100 transition duration-300 transform hover:scale-125">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 2C9.176 2 8.787 2.01 7.424 2.062c-1.365.053-2.06.27-2.618.497-.56.228-1.015.546-1.468.999-.453.453-.77 1.008-.999 1.568-.228.558-.444 1.253-.497 2.618-.052 1.363-.062 1.753-.062 4.5s.01 3.137.062 4.5c.053 1.365.27 2.06.497 2.618.228.56.546 1.015.999 1.468.453.453 1.008.77 1.568.999.558.228 1.253.444 2.618.497 1.363.052 1.753.062 4.5.062s3.137-.01 4.5-.062c1.365-.053 2.06-.27 2.618-.497.56-.228 1.015-.546 1.468-.999.453-.453.77-1.008.999-1.568.228-.558.444-1.253.497-2.618.052-1.363.062-1.753.062-4.5s-.01-3.137-.062-4.5c-.053-1.365-.27-2.06-.497-2.618-.228-.56-.546-1.015-.999-1.468-.453-.453-1.008-.77-1.568-.999-.558-.228-1.253-.444-2.618-.497C15.137 2.01 14.747 2 12 2zm0 2.164c2.784 0 3.109.011 4.2.053 1.088.043 1.638.257 2.02.417.382.16.634.357.877.6.242.242.438.495.6.877.16.382.374.932.417 2.02.042 1.091.053 1.416.053 4.2s-.011 3.109-.053 4.2c-.043 1.088-.257 1.638-.417 2.02-.16.382-.357.634-.6.877-.242-.242-.495.438-.877.6-.382.16-.932.374-2.02.417-1.091.042-1.416.053-4.2.053s.011-3.109.053-4.2c.043-1.088.257-1.638.417-2.02.16-.382.357-.634.6-.877.242-.242-.495-.438-.6-.877-.16-.382-.374-.932-.417-2.02-.042-1.091-.053-1.416-.053-4.2s.011-3.109.053-4.2c.043-1.088.257-1.638.417-2.02.16-.382.357-.634.6-.877.242-.242.495-.438.877-.6.382-.16.932-.374 2.02-.417C8.891 4.175 9.216 4.164 12 4.164zm0 3.659A4.177 4.177 0 1012 16.002a4.177 4.177 0 000-8.354zm0 2.164a2.013 2.013 0 110 4.026 2.013 2.013 0 010-4.026z" />
                        </svg>
                    </a>
                    <a href="#" class="opacity-80 hover:opacity-100 transition duration-300 transform hover:scale-125">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M8.29 20.082a9.664 9.664 0 001.325.074c.068 0 .135-.002.203-.004a9.684 9.684 0 007.82-3.805c.67-.936 1.055-2.062 1.055-3.266 0-.07-.002-.138-.004-.207a7.662 7.662 0 001.88-2.083c-.694.306-1.44.512-2.228.604a3.876 3.876 0 001.7-2.148c-.767.456-1.62.78-2.527.954a3.864 3.864 0 00-6.58 3.541c-3.242-.162-6.104-1.716-8.026-4.072a3.868 3.868 0 001.196 5.176c-.636-.02-1.23-.194-1.748-.485v.048c0 3.52 2.502 6.444 5.817 7.108a3.91 3.91 0 01-1.74.066c.928 2.89 3.61 4.974 6.786 5.033a7.747 7.747 0 004.834-1.677c1.378.9 2.926 1.43 4.536 1.43.585 0 1.15-.054 1.705-.158-.292.936-.677 1.812-1.144 2.625-.467.813-.996 1.564-1.587 2.253a15.82 15.82 0 01-3.66 3.01c-.886.435-1.79.79-2.708 1.077a23.905 23.905 0 01-5.344.205z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="text-center text-[color:var(--cream)] text-sm mt-12 border-t-2 border-[color:var(--sand)] pt-8">
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
            const loadingSpinner = document.getElementById('loading-spinner');
            setTimeout(() => {
                loadingSpinner.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Re-enable scroll
            }, 1000);

            document.getElementById('current-year').textContent = new Date().getFullYear();

            // Smooth scrolling for navigation links
            document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Header shrink on scroll
            const header = document.querySelector('header');
            const logo = header.querySelector('img');
            const headerButtons = header.querySelectorAll('.btn-secondary, .btn-primary');
            const navLinks = header.querySelectorAll('nav a');


            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('header-shrink', 'shadow-lg');
                    logo.classList.add('h-12');
                    logo.classList.remove('h-16');
                    headerButtons.forEach(button => {
                        button.classList.add('px-4', 'py-1.5', 'text-sm');
                        button.classList.remove('px-6', 'py-2', 'text-base');
                    });
                    navLinks.forEach(link => {
                        link.classList.add('text-base');
                        link.classList.remove('text-lg');
                    });

                } else {
                    header.classList.remove('header-shrink', 'shadow-lg');
                    logo.classList.remove('h-12');
                    logo.classList.add('h-16');
                    headerButtons.forEach(button => {
                        button.classList.remove('px-4', 'py-1.5', 'text-sm');
                        button.classList.add('px-6', 'py-2', 'text-base');
                    });
                    navLinks.forEach(link => {
                        link.classList.remove('text-base');
                        link.classList.add('text-lg');
                    });
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

            // Intersection Observer for fade-in animations on scroll
            const faders = document.querySelectorAll('.animate-fade-in, .animate-float-card, .animate-slide-in-right, .animate-scale-in');
            const appearOptions = {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            };

            const appearOnScroll = new IntersectionObserver(function (entries, observer) {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) {
                        return;
                    } else {
                        // Apply animation and remove initial hidden state
                        entry.target.style.animationDelay = entry.target.dataset.animationDelay || '0s';
                        entry.target.style.opacity = '1'; // Ensure opacity starts at 1 for the animation

                        // Reset transform for new animations if it was set initially
                        if (entry.target.classList.contains('animate-fade-in')) {
                            entry.target.style.transform = 'translateY(0) scale(1)';
                        }
                        if (entry.target.classList.contains('animate-float-card')) {
                            entry.target.style.transform = 'translateY(0px)';
                        }
                        if (entry.target.classList.contains('animate-slide-in-right')) {
                            entry.target.style.transform = 'translateX(0)';
                        }
                        if (entry.target.classList.contains('animate-scale-in')) {
                            entry.target.style.transform = 'scale(1)';
                        }

                        observer.unobserve(entry.target);
                    }
                });
            }, appearOptions);

            faders.forEach(fader => {
                // Initialize hidden state based on animation type
                if (fader.classList.contains('animate-fade-in')) {
                    fader.style.opacity = '0';
                    // No initial transform for simple fade-in
                } else if (fader.classList.contains('animate-float-card')) {
                    fader.style.opacity = '0';
                    fader.style.transform = 'translateY(20px)'; // Start slightly below
                } else if (fader.classList.contains('animate-slide-in-right')) {
                    fader.style.opacity = '0';
                    fader.style.transform = 'translateX(50px)'; // Start off-screen right
                } else if (fader.classList.contains('animate-scale-in')) {
                    fader.style.opacity = '0';
                    fader.style.transform = 'scale(0.9)'; // Start slightly scaled down
                }
                appearOnScroll.observe(fader);
            });

            // Peeking Cat Animation Logic
            const catLeft = document.querySelector('.peeking-cat-edge.left');
            const catRight = document.querySelector('.peeking-cat-edge.right');

            function showCat(catElement) {
                if (!catElement) return; // Ensure element exists

                catElement.classList.add('active');
                catElement.style.opacity = '0.7'; // Make visible
                catElement.style.animationPlayState = 'running'; // Ensure animation runs

                const hideDelay = Math.random() * (4000 - 2000) + 2000; // 2-4 seconds
                setTimeout(() => {
                    if (catElement) { // Check again before attempting to remove classes
                        catElement.classList.remove('active');
                        catElement.style.opacity = '0'; // Hide
                        catElement.style.animationPlayState = 'paused'; // Pause animation when hidden
                    }
                }, hideDelay);
            }

            function randomizeCatPeeks() {
                const randomDelay = Math.random() * (7000 - 3000) + 3000; // Random delay between peeks (3-7 seconds)
                setTimeout(() => {
                    const randomSide = Math.random() < 0.5 ? 'left' : 'right';
                    if (randomSide === 'left' && catLeft) {
                        showCat(catLeft);
                    } else if (randomSide === 'right' && catRight) {
                        showCat(catRight);
                    }
                    randomizeCatPeeks();
                }, randomDelay);
            }

            setTimeout(randomizeCatPeeks, 1500); // Initial delay before first peek
        });
    </script>
</body>

</html>