<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawsome Adoptions - User Profile</title>
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

        /* Profile Page Specific Styles */
        .profile-container {
            @apply bg-white p-8 rounded-3xl shadow-xl border-4 border-[color:var(--pinkish-orange)];
        }

        .profile-card {
            @apply flex flex-col items-center p-6 rounded-2xl bg-[color:var(--light-cream)] shadow-md text-center;
        }

        .profile-picture {
            @apply w-32 h-32 rounded-full object-cover border-4 border-[color:var(--primary-orange)] shadow-lg mb-4;
        }

        .profile-nav-item {
            @apply flex items-center justify-center lg:justify-start gap-3 p-4 rounded-xl text-lg font-semibold cursor-pointer transition-all duration-200 hover:bg-[color:var(--light-orange-accent)] hover:text-white;
        }

        .profile-nav-item.active {
            @apply bg-[color:var(--primary-orange)] text-white shadow-md;
        }

        .section-card {
            @apply bg-white p-6 rounded-3xl shadow-xl border-4 border-[color:var(--purple-accent)];
        }

        .order-item {
            @apply flex items-center justify-between py-3 border-b border-[color:var(--light-cream)];
        }

        .order-item:last-child {
            @apply border-b-0;
        }

        .order-item-image {
            @apply w-16 h-16 object-cover rounded-md mr-4;
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
                <a href="#"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Home</a>
                <a href="#"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Shop
                    By Catergory</a>
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

    <!-- Profile Section -->
    <section class="bg-[color:var(--light-cream)] py-16 relative overflow-hidden">
        <!-- Floating themed icons in background -->
        <div class="floating-themed-icon bone"
            style="left: 5%; top: 10%; animation-delay: 0s; width: 50px; height: 50px;"></div>
        <div class="floating-themed-icon fish"
            style="right: 8%; top: 25%; animation-delay: 3s; width: 60px; height: 60px;"></div>
        <div class="floating-themed-icon feather"
            style="left: 15%; top: 50%; animation-delay: 6s; width: 40px; height: 40px;"></div>
        <div class="floating-themed-icon bone"
            style="right: 2%; top: 70%; animation-delay: 9s; width: 70px; height: 70px;"></div>
        <div class="floating-themed-icon fish"
            style="left: 20%; bottom: 15%; animation-delay: 12s; width: 45px; height: 45px;"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 lg:flex gap-10">

            <!-- Left Sidebar (User Info & Navigation) -->
            <aside class="lg:w-1/4 profile-container h-fit animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="profile-card">
                    <img id="profile-picture-display" src="https://placehold.co/128x128/F5A623/FFFFFF?text=User"
                        alt="User Profile Picture" class="profile-picture">
                    <h2 id="profile-name-display" class="text-2xl font-bold text-[color:var(--text-dark)]">John Doe</h2>
                    <p id="profile-email-display" class="text-[color:var(--text-medium)] text-sm">john.doe@example.com
                    </p>
                    <p class="text-[color:var(--text-light)] text-sm">Member since: Jan 2023</p>
                </div>

                <nav class="mt-8 space-y-3">
                    <a href="#dashboard" class="profile-nav-item active" data-section="dashboard">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="#edit-profile" class="profile-nav-item" data-section="edit-profile">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-7.272 7.272A.5.5 0 018.006 14H6.5a.5.5 0 01-.5-.5v-1.506a.5.5 0 01.146-.354l7.272-7.272zM15 5l1.5-1.5M2 13h2a1 1 0 011 1v2a1 1 0 01-1 1H2a1 1 0 01-1-1v-2a1 1 0 011-1z">
                            </path>
                        </svg>
                        <span>Edit Profile</span>
                    </a>
                    <a href="#my-orders" class="profile-nav-item" data-section="my-orders">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 12.356 2.62 13.542 2.62 14.5c0 .828.672 1.5 1.5 1.5h10.243a.5.5 0 000-1H4.122c-.172 0-.25-.132-.217-.183l.222-.221.75-.75L6.96 7.464l.877 3.51a.5.5 0 00.99.248l.94-3.763 3.657-3.657A1 1 0 0016 4V3a1 1 0 00-1-1H4.5a1 1 0 00-.97 1.24L3 4.5V3a1 1 0 00-1-1z">
                            </path>
                        </svg>
                        <span>My Orders</span>
                    </a>
                    <a href="#saved-items" class="profile-nav-item" data-section="saved-items">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Saved Items</span>
                    </a>
                    <a href="#address-book" class="profile-nav-item" data-section="address-book">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Address Book</span>
                    </a>
                    <a href="#payment-methods" class="profile-nav-item" data-section="payment-methods">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 00.707.293h3.586a1 1 0 001-1v-2.586a1 1 0 00-.293-.707l-4.414-4.414A1 1 0 0110.586 6H6a2 2 0 00-2-2z">
                            </path>
                        </svg>
                        <span>Payment Methods</span>
                    </a>
                    <a href="#settings" class="profile-nav-item" data-section="settings">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M11.49 4.07a.75.75 0 011.02-1.077l3.997 1.703a.75.75 0 01.385 1.137l-1.636 2.148a.75.75 0 01-1.26-.067l.115-.41a.75.75 0 00-.63-.935l-.427-.06a.75.75 0 00-.7-.406L11.49 4.07zm-7.965 2.54a.75.75 0 01.815-1.22l2.121.707a.75.75 0 01.442 1.157L7.14 9.176a.75.75 0 01-1.12.983l-.707-.236a.75.75 0 00-.91-.12L3.525 7.62a.75.75 0 01.006-1.01zM10 18a.75.75 0 01-.75-.75v-2.5a.75.75 0 011.5 0v2.5a.75.75 0 01-.75.75zM5 14a.75.75 0 01-.75-.75v-2.5a.75.75 0 011.5 0v2.5a.75.75 0 01-.75.75zm10 0a.75.75 0 01-.75-.75v-2.5a.75.75 0 011.5 0v2.5a.75.75 0 01-.75.75z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Settings</span>
                    </a>
                    <button class="btn-secondary w-full mt-4 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H5a3 3 0 01-3-3v-5m14-3V4a3 3 0 00-3-3H5a3 3 0 00-3 3v5m14 3h-3">
                            </path>
                        </svg>
                        Log Out
                    </button>
                </nav>
            </aside>

            <!-- Right Content Area (Dynamic Sections) -->
            <div class="lg:w-3/4 mt-10 lg:mt-0">
                <!-- Dashboard Section -->
                <div id="dashboard-section" class="section-card animate-fade-in-up" style="animation-delay: 0.4s;">
                    <h2 class="text-3xl font-bold mb-6 text-[color:var(--text-dark)]">Welcome Back, John!</h2>
                    <p class="text-lg text-[color:var(--text-medium)] mb-6">
                        Here's a quick overview of your Pawsome Adoptions account.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="p-5 bg-[color:var(--light-orange-accent)] rounded-2xl shadow-sm text-center">
                            <p class="text-5xl font-bold text-[color:var(--primary-orange)]">3</p>
                            <p class="text-[color:var(--text-dark)] font-semibold">Orders Placed</p>
                        </div>
                        <div class="p-5 bg-[color:var(--light-orange-accent)] rounded-2xl shadow-sm text-center">
                            <p class="text-5xl font-bold text-[color:var(--primary-orange)]">7</p>
                            <p class="text-[color:var(--text-dark)] font-semibold">Saved Items</p>
                        </div>
                        <div class="p-5 bg-[color:var(--light-orange-accent)] rounded-2xl shadow-sm text-center">
                            <p class="text-5xl font-bold text-[color:var(--primary-orange)]">2</p>
                            <p class="text-[color:var(--text-dark)] font-semibold">Addresses</p>
                        </div>
                    </div>

                    <h3
                        class="text-2xl font-bold text-[color:var(--text-dark)] mb-4 border-b pb-2 border-[color:var(--pinkish-orange)]">
                        Recent Activity</h3>
                    <div class="space-y-4">
                        <div
                            class="bg-[color:var(--light-cream)] p-4 rounded-xl flex items-center justify-between shadow-sm">
                            <span class="text-[color:var(--text-medium)]">Order #PA1001 placed on 2024-05-15</span>
                            <span class="font-semibold text-[color:var(--primary-orange)]">$89.99</span>
                        </div>
                        <div
                            class="bg-[color:var(--light-cream)] p-4 rounded-xl flex items-center justify-between shadow-sm">
                            <span class="text-[color:var(--text-medium)]">Saved "Interactive Cat Toy" to wishlist</span>
                            <span class="text-[color:var(--text-light)] text-sm">2024-05-10</span>
                        </div>
                    </div>

                </div>

                <!-- Edit Profile Section -->
                <div id="edit-profile-section" class="section-card hidden mt-10 animate-fade-in-up">
                    <h2 class="text-3xl font-bold mb-6 text-[color:var(--text-dark)]">Edit Your Profile</h2>
                    <form id="edit-profile-form" class="space-y-6">
                        <div class="flex flex-col items-center mb-6">
                            <img id="edit-profile-picture-preview"
                                src="https://placehold.co/128x128/F5A623/FFFFFF?text=User" alt="Profile Picture"
                                class="profile-picture">
                            <label class="btn-secondary mt-4 cursor-pointer py-2 px-4 text-base">
                                <input type="file" id="profile-picture-upload" accept="image/*" class="hidden">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Upload Photo
                            </label>
                        </div>
                        <div>
                            <label for="edit-name"
                                class="block text-lg font-medium text-[color:var(--text-dark)] mb-2">Full Name</label>
                            <input type="text" id="edit-name"
                                class="w-full p-3 rounded-md border border-[color:var(--pinkish-orange)] focus:outline-none focus:ring-2 focus:ring-[color:var(--primary-orange)] bg-[color:var(--light-cream)] text-[color:var(--text-dark)]">
                        </div>
                        <div>
                            <label for="edit-email"
                                class="block text-lg font-medium text-[color:var(--text-dark)] mb-2">Email
                                Address</label>
                            <input type="email" id="edit-email"
                                class="w-full p-3 rounded-md border border-[color:var(--pinkish-orange)] focus:outline-none focus:ring-2 focus:ring-[color:var(--primary-orange)] bg-[color:var(--light-cream)] text-[color:var(--text-dark)]">
                        </div>
                        <div class="flex gap-4 mt-8">
                            <button type="submit" class="btn-primary flex-grow">Save Changes</button>
                            <button type="button" class="btn-secondary flex-grow"
                                id="cancel-edit-profile">Cancel</button>
                        </div>
                    </form>
                </div>


                <!-- My Orders Section (Hidden by default) -->
                <div id="my-orders-section" class="section-card hidden mt-10 animate-fade-in-up">
                    <h2 class="text-3xl font-bold mb-6 text-[color:var(--text-dark)]">My Orders</h2>
                    <div class="space-y-6">
                        <!-- Order 1 -->
                        <div class="bg-[color:var(--light-cream)] p-5 rounded-2xl shadow-md">
                            <div
                                class="flex justify-between items-center mb-4 border-b pb-3 border-[color:var(--pinkish-orange)]">
                                <h3 class="text-xl font-semibold text-[color:var(--primary-orange)]">Order #PA1001</h3>
                                <span class="text-sm text-[color:var(--text-light)]">Placed: 2024-05-15</span>
                            </div>
                            <div class="space-y-3">
                                <div class="order-item">
                                    <div class="flex items-center">
                                        <img src="https://placehold.co/60x60/F5A623/FFFFFF?text=Prod1" alt="Product 1"
                                            class="order-item-image">
                                        <div>
                                            <p class="font-medium">Premium Hypoallergenic Dog Food</p>
                                            <p class="text-sm text-[color:var(--text-light)]">Qty: 1</p>
                                        </div>
                                    </div>
                                    <span class="font-semibold">$49.99</span>
                                </div>
                                <div class="order-item">
                                    <div class="flex items-center">
                                        <img src="https://placehold.co/60x60/FCC370/FFFFFF?text=Prod2" alt="Product 2"
                                            class="order-item-image">
                                        <div>
                                            <p class="font-medium">Interactive Cat Toy Set</p>
                                            <p class="text-sm text-[color:var(--text-light)]">Qty: 2</p>
                                        </div>
                                        </div<P <span class="font-semibold">$37.00</span>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-between items-center text-lg font-bold mt-4 pt-4 border-t border-[color:var(--pinkish-orange)]">
                                    <span>Total:</span>
                                    <span>$86.99</span>
                                </div>
                                <button class="btn-primary mt-4 py-2 px-4 text-sm">View Details</button>
                            </div>

                            <!-- Order 2 -->
                            <div class="bg-[color:var(--light-cream)] p-5 rounded-2xl shadow-md">
                                <div
                                    class="flex justify-between items-center mb-4 border-b pb-3 border-[color:var(--pinkish-orange)]">
                                    <h3 class="text-xl font-semibold text-[color:var(--primary-orange)]">Order #PA0987
                                    </h3>
                                    <span class="text-sm text-[color:var(--text-light)]">Placed: 2024-04-20</span>
                                </div>
                                <div class="space-y-3">
                                    <div class="order-item">
                                        <div class="flex items-center">
                                            <img src="https://placehold.co/60x60/FFE9BD/FFFFFF?text=Prod3"
                                                alt="Product 3" class="order-item-image">
                                            <div>
                                                <p class="font-medium">Large Parrot Perch</p>
                                                <p class="text-sm text-[color:var(--text-light)]">Qty: 1</p>
                                            </div>
                                        </div>
                                        <span class="font-semibold">$24.99</span>
                                    </div>
                                </div>
                                <div
                                    class="flex justify-between items-center text-lg font-bold mt-4 pt-4 border-t border-[color:var(--pinkish-orange)]">
                                    <span>Total:</span>
                                    <span>$24.99</span>
                                </div>
                                <button class="btn-primary mt-4 py-2 px-4 text-sm">View Details</button>
                            </div>
                        </div>
                    </div>

                    <!-- Saved Items Section (Hidden by default) -->
                    <div id="saved-items-section" class="section-card hidden mt-10 animate-fade-in-up">
                        <h2 class="text-3xl font-bold mb-6 text-[color:var(--text-dark)]">My Saved Items</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Saved Item 1 -->
                            <div class="bg-[color:var(--light-cream)] p-4 rounded-xl shadow-md flex items-center gap-4">
                                <img src="https://placehold.co/80x80/E0BBE4/FFFFFF?text=Item1" alt="Saved Item 1"
                                    class="w-20 h-20 object-cover rounded-md">
                                <div>
                                    <p class="font-semibold text-[color:var(--text-dark)]">Orthopedic Pet Bed</p>
                                    <p class="text-[color:var(--text-medium)]">$75.00</p>
                                    <button class="text-[color:var(--primary-orange)] text-sm mt-1 hover:underline">Add
                                        to Cart</button>
                                    <button class="text-red-500 text-sm mt-1 ml-4 hover:underline">Remove</button>
                                </div>
                            </div>
                            <!-- Saved Item 2 -->
                            <div class="bg-[color:var(--light-cream)] p-4 rounded-xl shadow-md flex items-center gap-4">
                                <img src="https://placehold.co/80x80/F5A623/FFFFFF?text=Item2" alt="Saved Item 2"
                                    class="w-20 h-20 object-cover rounded-md">
                                <div>
                                    <p class="font-semibold text-[color:var(--text-dark)]">Gourmet Dog Treats (Bulk)</p>
                                    <p class="text-[color:var(--text-medium)]">$32.00</p>
                                    <button class="text-[color:var(--primary-orange)] text-sm mt-1 hover:underline">Add
                                        to Cart</button>
                                    <button class="text-red-500 text-sm mt-1 ml-4 hover:underline">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Book Section (Hidden by default) -->
                    <div id="address-book-section" class="section-card hidden mt-10 animate-fade-in-up">
                        <h2 class="text-3xl font-bold mb-6 text-[color:var(--text-dark)]">Address Book</h2>
                        <div class="space-y-6">
                            <!-- Address 1 -->
                            <div class="bg-[color:var(--light-cream)] p-5 rounded-2xl shadow-md">
                                <p class="font-semibold text-[color:var(--text-dark)]">Primary Address</p>
                                <p class="text-[color:var(--text-medium)]">123 Pawsome Lane</p>
                                <p class="text-[color:var(--text-medium)]">Petville, CA 90210</p>
                                <p class="text-[color:var(--text-medium)]">USA</p>
                                <div class="flex gap-4 mt-3">
                                    <button
                                        class="text-[color:var(--primary-orange)] text-sm hover:underline">Edit</button>
                                    <button class="text-red-500 text-sm hover:underline">Delete</button>
                                </div>
                            </div>
                            <!-- Address 2 -->
                            <div class="bg-[color:var(--light-cream)] p-5 rounded-2xl shadow-md">
                                <p class="font-semibold text-[color:var(--text-dark)]">Work Address</p>
                                <p class="text-[color:var(--text-medium)]">456 Wagging Tail Blvd</p>
                                <p class="text-[color:var(--text-medium)]">Canine City, NY 10001</p>
                                <p class="text-[color:var(--text-medium)]">USA</p>
                                <div class="flex gap-4 mt-3">
                                    <button
                                        class="text-[color:var(--primary-orange)] text-sm hover:underline">Edit</button>
                                    <button class="text-red-500 text-sm hover:underline">Delete</button>
                                </div>
                            </div>
                            <button class="btn-secondary py-2 px-4 text-base flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m0 0h-3m-1.293-7.293A9.994 9.994 0 0012 2c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10A9.994 9.994 0 0021 10.707">
                                    </path>
                                </svg>
                                Add New Address
                            </button>
                        </div>
                    </div>

                    <!-- Payment Methods Section (Hidden by default) -->
                    <div id="payment-methods-section" class="section-card hidden mt-10 animate-fade-in-up">
                        <h2 class="text-3xl font-bold mb-6 text-[color:var(--text-dark)]">Payment Methods</h2>
                        <div class="space-y-6">
                            <div
                                class="bg-[color:var(--light-cream)] p-5 rounded-2xl shadow-md flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-[color:var(--text-dark)]">Visa **** 1234</p>
                                    <p class="text-[color:var(--text-medium)] text-sm">Expires 12/26</p>
                                </div>
                                <div class="flex gap-4">
                                    <button
                                        class="text-[color:var(--primary-orange)] text-sm hover:underline">Edit</button>
                                    <button class="text-red-500 text-sm hover:underline">Remove</button>
                                </div>
                            </div>
                            <div
                                class="bg-[color:var(--light-cream)] p-5 rounded-2xl shadow-md flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-[color:var(--text-dark)]">Mastercard **** 5678</p>
                                    <p class="text-[color:var(--text-medium)] text-sm">Expires 08/25</p>
                                </div>
                                <div class="flex gap-4">
                                    <button
                                        class="text-[color:var(--primary-orange)] text-sm hover:underline">Edit</button>
                                    <button class="text-red-500 text-sm hover:underline">Remove</button>
                                </div>
                            </div>
                            <button class="btn-secondary py-2 px-4 text-base flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m0 0h-3m-1.293-7.293A9.994 9.994 0 0012 2c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10A9.994 9.994 0 0021 10.707">
                                    </path>
                                </svg>
                                Add New Payment
                            </button>
                        </div>
                    </div>

                    <!-- Settings Section (Hidden by default) -->
                    <div id="settings-section" class="section-card hidden mt-10 animate-fade-in-up">
                        <h2 class="text-3xl font-bold mb-6 text-[color:var(--text-dark)]">Account Settings</h2>
                        <form class="space-y-6">
                            <div>
                                <label for="name"
                                    class="block text-lg font-medium text-[color:var(--text-dark)] mb-2">Full
                                    Name</label>
                                <input type="text" id="name" value="John Doe"
                                    class="w-full p-3 rounded-md border border-[color:var(--pinkish-orange)] focus:outline-none focus:ring-2 focus:ring-[color:var(--primary-orange)] bg-[color:var(--light-cream)] text-[color:var(--text-dark)]">
                            </div>
                            <div>
                                <label for="email"
                                    class="block text-lg font-medium text-[color:var(--text-dark)] mb-2">Email
                                    Address</label>
                                <input type="email" id="email" value="john.doe@example.com"
                                    class="w-full p-3 rounded-md border border-[color:var(--pinkish-orange)] focus:outline-none focus:ring-2 focus:ring-[color:var(--primary-orange)] bg-[color:var(--light-cream)] text-[color:var(--text-dark)]">
                            </div>
                            <div>
                                <label for="password"
                                    class="block text-lg font-medium text-[color:var(--text-dark)] mb-2">Password</label>
                                <input type="password" id="password" value="********"
                                    class="w-full p-3 rounded-md border border-[color:var(--pinkish-orange)] focus:outline-none focus:ring-2 focus:ring-[color:var(--primary-orange)] bg-[color:var(--light-cream)] text-[color:var(--text-dark)]">
                                <button class="text-[color:var(--primary-orange)] text-sm mt-2 hover:underline">Change
                                    Password</button>
                            </div>
                            <button type="submit" class="btn-primary">Save Changes</button>
                        </form>
                    </div>
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
            const loadingSpinner = document.getElementById('loading-spinner');
            setTimeout(() => {
                loadingSpinner.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Re-enable scroll
            }, 1000);

            document.getElementById('current-year').textContent = new Date().getFullYear();

            // Mock user data
            let userData = {
                name: "John Doe",
                email: "john.doe@example.com",
                profilePicture: "https://placehold.co/128x128/F5A623/FFFFFF?text=User"
            };

            // Update profile display with mock data
            const updateProfileDisplay = () => {
                document.getElementById('profile-name-display').textContent = userData.name;
                document.getElementById('profile-email-display').textContent = userData.email;
                document.getElementById('profile-picture-display').src = userData.profilePicture;
                document.getElementById('edit-profile-picture-preview').src = userData.profilePicture;
                document.getElementById('edit-name').value = userData.name;
                document.getElementById('edit-email').value = userData.email;
            };

            // Section switching logic
            const navItems = document.querySelectorAll('.profile-nav-item');
            const sections = document.querySelectorAll('.section-card');

            navItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetSectionId = e.currentTarget.dataset.section + '-section';

                    // Remove active class from all nav items
                    navItems.forEach(nav => nav.classList.remove('active'));
                    // Add active class to clicked nav item
                    e.currentTarget.classList.add('active');

                    // Hide all sections
                    sections.forEach(section => section.classList.add('hidden'));

                    // Show the target section
                    const targetSection = document.getElementById(targetSectionId);
                    if (targetSection) {
                        targetSection.classList.remove('hidden');
                        // Trigger fade-in-up animation for the newly displayed section
                        targetSection.style.opacity = '0';
                        targetSection.style.transform = 'translateY(50px)';
                        setTimeout(() => {
                            targetSection.style.opacity = '1';
                            targetSection.style.transform = 'translateY(0)';
                        }, 50); // Small delay to ensure animation re-triggers
                    }
                });
            });

            // Edit Profile Form Handling
            const editProfileForm = document.getElementById('edit-profile-form');
            const profilePictureUpload = document.getElementById('profile-picture-upload');
            const editProfilePicturePreview = document.getElementById('edit-profile-picture-preview');
            const cancelEditProfileButton = document.getElementById('cancel-edit-profile');

            profilePictureUpload.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        editProfilePicturePreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            editProfileForm.addEventListener('submit', (e) => {
                e.preventDefault();
                // In a real application, you would send this data to a server
                userData.name = document.getElementById('edit-name').value;
                userData.email = document.getElementById('edit-email').value;
                // For profile picture, in a real app, you'd upload the file and get a new URL
                // For this mock, if a new file was selected, the preview is already updated.
                // We'll just assume the URL is updated on the server if it changed.

                updateProfileDisplay(); // Update display with new data
                alert('Profile updated successfully!');
                // Switch back to dashboard or stay on edit profile
                document.querySelector('.profile-nav-item[data-section="dashboard"]').click();
            });

            cancelEditProfileButton.addEventListener('click', () => {
                // Reset form fields to current user data
                document.getElementById('edit-name').value = userData.name;
                document.getElementById('edit-email').value = userData.email;
                editProfilePicturePreview.src = userData.profilePicture;
                // Switch back to dashboard
                document.querySelector('.profile-nav-item[data-section="dashboard"]').click();
            });


            // Intersection Observer for fade-in animations on scroll (for footer, etc.)
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
                // Apply initial hidden state only if it's not the default active section
                if (!fader.classList.contains('hidden')) {
                    fader.style.opacity = '0';
                    fader.style.transform = 'translateY(20px)';
                }
                appearOnScroll.observe(fader);
            });

            // Header shrink on scroll (reusing logic from marketplace page)
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

            // Initial update of profile display
            updateProfileDisplay();
        });
    </script>
</body>

</html>