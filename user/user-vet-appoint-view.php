<?php
session_start();  // Start the session
include '../config/db.php';  // Include the database connection file (adjust path as necessary)

// Check if the user is logged in and if the role is "User"
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'User') {
    // Redirect to the login page if the user is not logged in or role is not "User"
    header("Location: /animora/auth/login.php");  // Adjust the login page URL if necessary
    exit();  // Stop further script execution after redirection
}



// At the top of your file (after session/db connection)
$user_id = $_SESSION["user_id"];

// Fetch user's appointments from database
$appointments = [];
$stmt = $conn->prepare("
    SELECT 
        a.AppointmentID,
        a.PetName,
        a.Reason,
        a.Status,
        u.Name AS DoctorName,
        t.SlotDate,
        t.StartTime,
        t.EndTime
    FROM appointments a
    JOIN users u ON a.DoctorID = u.UserID
    JOIN timeslots t ON a.SlotID = t.SlotID
    WHERE a.UserID = ?
    ORDER BY t.SlotDate, t.StartTime
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($appointment = $result->fetch_assoc()) {
    $appointments[] = $appointment;
}
$stmt->close();
?>








<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawsome Adoptions - Your Appointments</title>
    <!-- Tailwind CSS (for base utilities) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* Color Palette Variables */
        :root {
            --cream: #FBF3F0;
            --mustard: #D4931F;
            --teal: #A0C0A9;
            --mint: #C2D4C8;
            --sand: #EEC3A4;
            --dark-text: #4A4A4A;
            /* Blue shades for hero title */
            --blue-title: #345678;
            --blue-title-light: #5B86A9;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #eaa793;
            /* NEW: Main body background */
            color: var(--dark-text);
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
            color: var(--mustard);
            /* Default heading color for most sections */
        }

        /* Custom Buttons */
        .btn-primary {
            /* Tailwind classes for primary button */
            @apply bg-[color:var(--mustard)] text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-transparent hover:border-white;
        }

        .btn-secondary {
            /* Tailwind classes for secondary button */
            @apply bg-white text-[color:var(--mustard)] px-8 py-4 rounded-full font-bold text-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-[color:var(--mustard)];
        }

        /* Global Fade-in-Up Animation */
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card Hover Effect */
        .card-pop-shadow:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        /* Header Specific Animation */
        .header-shrink {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Hero Section H1 Specific Animation */
        .animate-pulse-text-h1-hero {
            animation: pulseTextH1Hero 2s infinite alternate;
        }

        @keyframes pulseTextH1Hero {
            0% {
                color: var(--blue-title);
                /* Shade of blue for the title */
                transform: scale(1);
            }

            50% {
                color: var(--blue-title-light);
                /* A lighter shade for pulse effect */
                transform: scale(1.02);
            }

            100% {
                color: var(--blue-title);
                transform: scale(1);
            }
        }

        /* Paw Print Animations (reused) */
        .paw-print {
            position: absolute;
            width: 50px;
            height: 50px;
            /* Inline SVG data for consistent mustard color */
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23D4931F"%3E%3Cpath d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"%3E%3C/path%3E%3C/svg%3E');
            background-size: contain;
            background-repeat: no-repeat;
            border-radius: 50%;
            animation: floatPaw 12s infinite linear;
            opacity: 0.6;
            z-index: 0;
            pointer-events: none;
        }

        .paw-print:nth-child(1) {
            left: 10%;
            top: 15%;
            animation-delay: 0s;
            opacity: 0.7;
            width: 55px;
            height: 55px;
        }

        .paw-print:nth-child(2) {
            left: 80%;
            top: 40%;
            animation-delay: 2s;
            width: 40px;
            height: 40px;
        }

        .paw-print:nth-child(3) {
            left: 25%;
            top: 60%;
            animation-delay: 4s;
            opacity: 0.8;
            width: 60px;
            height: 60px;
        }

        .paw-print:nth-child(4) {
            left: 60%;
            top: 85%;
            animation-delay: 1s;
            width: 45px;
            height: 45px;
        }

        .paw-print:nth-child(5) {
            left: 5%;
            top: 90%;
            animation-delay: 3s;
            opacity: 0.6;
            width: 35px;
            height: 35px;
        }

        .paw-print:nth-child(6) {
            left: 90%;
            top: 10%;
            animation-delay: 5s;
            opacity: 0.9;
            width: 65px;
            height: 65px;
        }

        .paw-print:nth-child(7) {
            left: 35%;
            top: 5%;
            animation-delay: 1.5s;
            opacity: 0.7;
            width: 40px;
            height: 40px;
        }

        .paw-print:nth-child(8) {
            left: 70%;
            top: 70%;
            animation-delay: 3.5s;
            opacity: 0.6;
            width: 50px;
            height: 50px;
        }

        @keyframes floatPaw {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.4;
            }

            50% {
                transform: translateY(-25px) rotate(180deg);
                opacity: 0.7;
            }

            100% {
                transform: translateY(0) rotate(360deg);
                opacity: 0.4;
            }
        }

        /* Wave styles */
        .wave-bottom svg {
            width: 100%;
            height: 200px;
            /* Adjust height as needed */
            display: block;
            /* Remove any inline spacing issues */
        }

        .wave-bottom path {
            fill: #eaa793;
            /* Wave color matches new body background */
        }

        /* Appointment Card Styles */
        .appointment-card {
            background-color: white;
            border: 2px solid var(--mint);
            border-radius: 1.5rem;
            /* Rounded-3xl */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .appointment-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .appointment-card-detail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--dark-text);
            font-size: 0.95rem;
        }

        .appointment-card-detail svg {
            color: var(--mustard);
            width: 1.25rem;
            height: 1.25rem;
        }

        .appointment-status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 9999px;
            /* Rounded-full */
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.5rem;
        }

        .status-confirmed {
            background-color: #d4edda;
            /* Light green */
            color: #155724;
            /* Dark green text */
        }

        .status-pending {
            background-color: #fff3cd;
            /* Light yellow */
            color: #856404;
            /* Dark yellow text */
        }

        .status-completed {
            background-color: #e2e3e5;
            /* Light gray */
            color: #495057;
            /* Dark gray text */
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

        /* Walking Cat Animation */
        .walking-cat {
            position: absolute;
            top: 60px;
            /* Adjust this to sit right on the top edge of the header */
            height: 100px;
            /* Increased size */
            width: auto;
            animation: catWalk 15s linear infinite;
            /* Increased time for slower walk */
            z-index: 1000;
            /* Ensure it's above the header */
            transform: translateX(100vw);
            /* Start off-screen right, no rotation */
            -webkit-filter: drop-shadow(0 4px 4px rgba(0, 0, 0, 0.2));
            /* Add subtle shadow */
            filter: drop-shadow(0 4px 4px rgba(0, 0, 0, 0.2));
            pointer-events: none;
            /* Make it unclickable */
        }

        @keyframes catWalk {
            0% {
                transform: translateX(100vw);
                /* Start off-screen right */
            }

            100% {
                transform: translateX(-100%);
                /* Move fully off-screen left */
            }
        }

        /* Adjust cat position on smaller screens if necessary */
        @media (max-width: 768px) {
            .walking-cat {
                height: 80px;
                /* Smaller cat on mobile, but still larger than previous */
                top: 50px;
            }
        }
    </style>
</head>

<body class="text-[color:var(--dark-text)]">

    <!-- Walking Cat Element -->
    <img src='/assets/images/cat-walking.gif' alt="Walking Black Cat" class="walking-cat">

    <!-- Header / Navbar -->
    <header
        class="w-full bg-white py-4 shadow-md fixed top-0 left-0 right-0 z-50 transform transition-all duration-300 ease-in-out">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Left Menu -->
            <nav class="flex justify-center md:justify-start gap-8 text-lg font-semibold">
                <a href="user-home.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Dashboard</a>
                <a href="user-vet-appoint-view.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">View
                    Your Appointments</a>
                <a href="user-vet-appoint-book.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Book
                    An Appointment</a>

            </nav>

            <!-- Center Logo -->
            <div class="flex justify-center flex-shrink-0">
                <!-- Placeholder logo for consistency -->
                <img src="https://placehold.co/280x80/D4931F/FBF3F0?text=Pawsome+Logo" alt="Pawsome Adoptions Logo"
                    class="h-16 w-auto transition-all duration-300 ease-in-out">
            </div>

            <!-- Right Button (Post Pet) -->
            <div class="flex justify-center md:justify-end">
                <button class="btn-primary transition-all duration-300 ease-in-out hover:rotate-3">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m0 0h-3m-1.293-7.293A9.994 9.994 0 0012 2c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10A9.994 9.994 0 0021 10.707">
                            </path>
                        </svg>
                        Post Pet
                    </span>
                </button>
            </div>
        </div>
    </header>

    <!-- Spacer for fixed header -->
    <div class="h-24"></div>

    <!-- Hero Section for Appointment View Page -->
    <section
        class="relative w-full h-[550px] flex items-center justify-center overflow-hidden rounded-t-3xl shadow-xl text-center"
        style="background-color: var(--cream);"> <!-- Hero background is now Cream -->
        <!-- Background Paw Prints -->
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>

        <!-- Content -->
        <div class="relative z-10 p-6 max-w-7xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight drop-shadow-md">
                <span class="block animate-pulse-text-h1-hero">Your Upcoming Visits</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto drop-shadow-sm text-[color:var(--dark-text)]">
                Manage your pet's appointments with ease.
            </p>
        </div>

        <!-- SVG Wave at the bottom -->
        <div class="absolute bottom-0 left-0 w-full z-10 wave-bottom">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#eaa793" fill-opacity="1" <!-- Wave color matches new body background -->
                    d="M0,96L34.3,117.3C68.6,139,137,181,206,202.7C274.3,224,343,224,411,208C480,192,549,160,617,149.3C685.7,139,754,149,823,176C891.4,203,960,245,1029,245.3C1097.1,245,1166,203,1234,197.3C1302.9,192,1371,224,1406,240L1440,256L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                </path>
            </svg>
        </div>
    </section>

    <!-- Main Content: Appointment List -->
    <section class="w-full py-14 px-6 bg-[#eaa793] relative z-10">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-4xl font-bold mb-8 text-center text-[color:var(--mustard)] animate-fade-in-up"
                style="animation-delay: 0.4s;">Your Scheduled Appointments</h2>

            <div id="appointments-list" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php if (!empty($appointments)): ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <div class="bg-white rounded-3xl shadow-xl border-4 border-[color:var(--mint)] p-6 animate-fade-in-up">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-2xl font-bold text-[color:var(--mustard)]">
                                    <?= htmlspecialchars($appointment['PetName']) ?>
                                </h3>
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-semibold 
                                <?= $appointment['Status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' ?>">
                                    <?= htmlspecialchars($appointment['Status']) ?>
                                </span>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-[color:var(--mustard)]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    <span>Dr. <?= htmlspecialchars($appointment['DoctorName']) ?></span>
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-[color:var(--mustard)]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>
                                        <?= date('F j, Y', strtotime($appointment['SlotDate'])) ?> •
                                        <?= date('g:i A', strtotime($appointment['StartTime'])) ?> -
                                        <?= date('g:i A', strtotime($appointment['EndTime'])) ?>
                                    </span>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 text-[color:var(--mustard)]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                        </path>
                                    </svg>
                                    <span><?= htmlspecialchars($appointment['Reason']) ?></span>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button onclick="confirmCancel(<?= $appointment['AppointmentID'] ?>)"
                                    class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                    Cancel
                                </button>
                                <button onclick="window.location.href='reschedule.php?id=<?= $appointment['AppointmentID'] ?>'"
                                    class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                                    Reschedule
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center p-10 bg-white rounded-3xl shadow-xl border-4 border-[color:var(--mint)] mt-10 animate-fade-in-up"
                        style="animation-delay: 0.6s;">
                        <p class="text-xl mb-6">Looks like you don't have any appointments yet!</p>
                        <button class="btn-primary" onclick="window.location.href='book-appointment.php'">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Book Your First Appointment!
                            </span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[color:var(--mustard)] text-white py-12 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">
            <div>
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--cream)]">Pawsome Adoptions</h3>
                <p class="text-[color:var(--cream)] text-sm">Where every tail finds its wag! We're dedicated to uniting
                    furry hearts with forever homes.</p>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--cream)]">Quick Sniffs</h3>
                <ul class="space-y-3 text-[color:var(--cream)]">
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
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--cream)]">Support Paws</h3>
                <ul class="space-y-3 text-[color:var(--cream)]">
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
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--cream)]">Join Our Fur-mily!</h3>
                <div class="flex space-x-5">
                    <a href="#"
                        class="text-[color:var(--cream)] hover:text-white transition duration-300 transform hover:scale-125">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.776-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.247 0-1.646.773-1.646 1.572V12h2.77l-.443 2.89h-2.327v6.987C18.343 21.128 22 16.991 22 12z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="text-[color:var(--cream)] hover:text-white transition duration-300 transform hover:scale-125">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12 2C9.176 2 8.787 2.01 7.424 2.062c-1.365.053-2.06.27-2.618.497-.56.228-1.015.546-1.468.999-.453.453-.77 1.008-.999 1.568-.228.558-.444 1.253-.497 2.618-.052 1.363-.062 1.753-.062 4.5s.01 3.137.062 4.5c.053 1.365.27 2.06.497 2.618.228.56.546 1.015.999 1.468.453.453 1.008.77 1.568.999.558.228 1.253.444 2.618.497 1.363.052 1.753.062 4.5.062s3.137-.01 4.5-.062c1.365-.053 2.06-.27 2.618-.497.56-.228 1.015-.546 1.468-.999.453-.453.77-1.008.999-1.568.228-.558.444-1.253.497-2.618.052-1.363.062-1.753.062-4.5s-.01-3.137-.062-4.5c-.053-1.365-.27-2.06-.497-2.618-.228-.56-.546-1.015-.999-1.468-.453-.453-1.008-.77-1.568-.999-.558-.228-1.253-.444-2.618-.497C15.137 2.01 14.747 2 12 2zm0 2.164c2.784 0 3.109.011 4.2.053 1.088.043 1.638.257 2.02.417.382.16.634.357.877.6.242.242.438.495.6.877.16.382.374.932.417 2.02.042 1.091.053 1.416.053 4.2s-.011 3.109-.053 4.2c-.043 1.088-.257 1.638-.417 2.02-.16.382-.357.634-.6.877-.242.242-.495.438-.877.6-.382.16-.932.374-2.02.417-1.091.042-1.416.053-4.2.053s-3.109-.011-4.2-.053c-1.088-.043-1.638-.257-2.02-.417-.382-.16-.634-.357-.877-.6-.242-.242-.495-.438-.6-.877-.16-.382-.374-.932-.417-2.02-.042-1.091-.053-1.416-.053-4.2s.011-3.109.053-4.2c.043-1.088.257-1.638.417-2.02.16-.382.357-.634.6-.877.242-.242-.495.438-.877.6.382-.16.932-.374 2.02-.417C8.891 4.175 9.216 4.164 12 4.164zm0 3.659A4.177 4.177 0 1012 16.002a4.177 4.177 0 000-8.354zm0 2.164a2.013 2.013 0 110 4.026 2.013 2.013 0 010-4.026z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="text-[color:var(--cream)] hover:text-white transition duration-300 transform hover:scale-125">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Set current year in footer
            document.getElementById('current-year').textContent = new Date().getFullYear();

            // Cancel appointment function
            function confirmCancel(appointmentId) {
                if (confirm('Are you sure you want to cancel this appointment?')) {
                    fetch('cancel-appointment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'id=' + appointmentId
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Appointment cancelled successfully');
                                window.location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while cancelling the appointment');
                        });
                }
            }

            // Animation handling
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

            // Header shrink effect on scroll
            const header = document.querySelector('header');
            if (header) {
                const logo = header.querySelector('img');
                const primaryButton = header.querySelector('.btn-primary');

                window.addEventListener('scroll', () => {
                    if (window.scrollY > 50) {
                        header.classList.add('header-shrink');
                        header.classList.remove('py-4', 'shadow-md');
                        if (logo) {
                            logo.classList.add('h-12');
                            logo.classList.remove('h-16');
                        }
                        if (primaryButton) {
                            primaryButton.classList.add('px-6', 'py-2', 'text-base');
                            primaryButton.classList.remove('px-8', 'py-4', 'text-lg');
                        }
                    } else {
                        header.classList.remove('header-shrink');
                        header.classList.add('py-4', 'shadow-md');
                        if (logo) {
                            logo.classList.remove('h-12');
                            logo.classList.add('h-16');
                        }
                        if (primaryButton) {
                            primaryButton.classList.remove('px-6', 'py-2', 'text-base');
                            primaryButton.classList.add('px-8', 'py-4', 'text-lg');
                        }
                    }
                });
            }

            // Time slot selection handling (if on booking page)
            const doctorSelect = document.getElementById('select-doctor');
            const dateInput = document.getElementById('appointment-date');
            const timeSlotContainer = document.getElementById('time-slot-container');

            if (doctorSelect && dateInput && timeSlotContainer) {
                function fetchAvailableSlots() {
                    const doctorId = doctorSelect.value;
                    const appointmentDate = dateInput.value;

                    if (doctorId && appointmentDate) {
                        const formData = new FormData();
                        formData.append('doctor_id', doctorId);
                        formData.append('appointment_date', appointmentDate);

                        fetch('get_available_slots.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                timeSlotContainer.innerHTML = '';
                                if (data.length > 0) {
                                    data.forEach(slot => {
                                        const button = document.createElement('button');
                                        button.type = 'button';
                                        button.classList.add('time-slot-button');
                                        button.setAttribute('data-slot-id', slot.SlotID);
                                        button.textContent = `${slot.StartTime} - ${slot.EndTime}`;

                                        button.addEventListener('click', function () {
                                            document.querySelectorAll('.time-slot-button').forEach(btn => {
                                                btn.classList.remove('selected');
                                            });
                                            this.classList.add('selected');
                                            document.getElementById('selected-time-slot').value = this.getAttribute('data-slot-id');
                                        });

                                        timeSlotContainer.appendChild(button);
                                    });
                                } else {
                                    timeSlotContainer.innerHTML = '<p class="text-red-500">No available time slots for this date.</p>';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                timeSlotContainer.innerHTML = '<p class="text-red-500">Error loading time slots.</p>';
                            });
                    }
                }

                doctorSelect.addEventListener('change', fetchAvailableSlots);
                dateInput.addEventListener('change', fetchAvailableSlots);
            }
        });
    </script>
</body>

</html>