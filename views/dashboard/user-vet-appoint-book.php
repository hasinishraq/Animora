<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawsome Adoptions - Book Appointment</title>
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
            --furry-friends-text: #1C2A39;
            /* Retained from original for consistency */
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--cream);
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
            /* Default heading color for other sections */
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
                color: #345678;
                /* Shade of blue for the title */
                transform: scale(1);
            }

            50% {
                color: #5B86A9;
                /* A lighter shade for pulse effect */
                transform: scale(1.02);
            }

            100% {
                color: #345678;
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
        .wave-top svg,
        .wave-bottom svg {
            width: 100%;
            height: 200px;
            /* Adjust height as needed */
            display: block;
            /* Remove any inline spacing issues */
        }

        .wave-top path,
        .wave-bottom path {
            fill: var(--cream);
        }

        /* Form specific styles */
        .form-input-group label {
            color: var(--mustard);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-input-group input,
        .form-input-group select,
        .form-input-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--mint);
            border-radius: 0.75rem;
            font-size: 1rem;
            color: var(--dark-text);
            background-color: #fff;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            appearance: none;
            /* Remove default select arrow */
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .form-input-group select {
            /* Custom arrow for select dropdown */
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%20viewBox%3D%220%200%20292.4%20292.4%22%3E%3Cpath%20fill%3D%22%23D4931F%22%20d%3D%22M287%2C197.3l-13.9%2C13.9L146.2%2C81.8L19.3%2C211.2L5.4%2C197.3L146.2%2C5.4L287%2C197.3z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 0.75rem auto;
            padding-right: 2.5rem;
            /* Make space for the arrow */
        }


        .form-input-group input:focus,
        .form-input-group select:focus,
        .form-input-group textarea:focus {
            outline: none;
            border-color: var(--mustard);
            box-shadow: 0 0 0 3px rgba(212, 147, 31, 0.3);
            /* Mustard shadow */
        }

        .form-input-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Custom radio button styling for Time Slots */
        .time-slot-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: center;
        }

        .time-slot-group .input-container {
            position: relative;
        }

        .time-slot-group .input-container input {
            position: absolute;
            height: 100%;
            width: 100%;
            margin: 0;
            cursor: pointer;
            z-index: 2;
            opacity: 0;
        }

        .time-slot-group .input-container .radio-tile {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 100px;
            /* Ensures minimum width */
            padding: 0.75rem 1.25rem;
            border-radius: 15px;
            background-color: var(--mint);
            border: 2px solid var(--mint);
            transition: all 0.3s ease-in-out;
            cursor: pointer;
            font-weight: 600;
            color: var(--dark-text);
            text-align: center;
        }

        .time-slot-group .input-container input:checked+.radio-tile {
            background-color: var(--mustard);
            color: var(--cream);
            border-color: var(--sand);
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(212, 147, 31, 0.4);
        }

        .time-slot-group .input-container input:hover+.radio-tile {
            border-color: var(--mustard);
        }

        /* Success Message Styling */
        .success-message {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--mustard);
            color: white;
            padding: 1rem 2rem;
            border-radius: 9999px;
            /* Rounded-full */
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

        .success-message.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-10px);
            /* Slight lift animation */
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
    </style>
</head>

<body class="text-[color:var(--dark-text)]">

    <!-- Header / Navbar -->
    <header
        class="w-full bg-white py-4 shadow-md fixed top-0 left-0 right-0 z-50 transform transition-all duration-300 ease-in-out">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Left Menu -->
            <nav class="flex justify-center md:justify-start gap-8 text-lg font-semibold">
                <a href="#"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Home</a>
                <a href="#"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Adopt
                    a Pet</a>
                <a href="post-adoption.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Post
                    Adoption</a>
                <a href="book-appointment.html"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Appointments</a>
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

    <!-- Hero Section for Book Appointment Page -->
    <section
        class="relative w-full h-[550px] flex items-center justify-center overflow-hidden rounded-t-3xl shadow-xl text-center"
        style="background-color: #eaa793;">
        <!-- Background Paw Prints -->
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>
        <div class="paw-print"></div>

        <!-- Content (now with max-w and mx-auto for internal padding) -->
        <div class="relative z-10 p-6 max-w-7xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight drop-shadow-md">
                <span class="block animate-pulse-text-h1-hero">Schedule Your Pet's Next Visit!</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto drop-shadow-sm text-[color:var(--dark-text)]">
                Expert care for your beloved companion, just a few clicks away.
            </p>
        </div>

        <!-- SVG Wave at the bottom -->
        <div class="absolute bottom-0 left-0 w-full z-10 wave-bottom" style="line-height: 0; overflow: hidden;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none"
                style="display: block; width: 100%; height: 120px;">
                <path fill="var(--cream)" fill-opacity="1"
                    d="M0,96L34.3,117.3C68.6,139,137,181,206,202.7C274.3,224,343,224,411,208C480,192,549,160,617,149.3C685.7,139,754,149,823,176C891.4,203,960,245,1029,245.3C1097.1,245,1166,203,1234,197.3C1302.9,192,1371,224,1406,240L1440,256L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                </path>
            </svg>
        </div>

    </section>

    <!-- Main Content: Appointment Form -->
    <section class="w-full py-14 px-6 bg-[color:var(--cream)] relative z-10">
        <div class="max-w-3xl mx-auto bg-white p-8 md:p-12 rounded-3xl shadow-2xl border-4 border-[color:var(--mint)] animate-fade-in-up"
            style="animation-delay: 0.4s;">
            <h2 class="text-4xl font-bold mb-8 text-center text-[color:var(--mustard)]">Book Your Appointment</h2>

            <form id="appointment-form" class="space-y-6">
                <div class="form-input-group">
                    <label for="select-pet">Select Your Pet</label>
                    <select id="select-pet" required>
                        <option value="" disabled selected>Choose your furry friend...</option>
                        <option value="mittens">Mittens 🐱</option>
                        <option value="buddy">Buddy 🐶</option>
                        <option value="hopper">Hopper 🐰</option>
                        <option value="polly">Polly 🦜</option>
                        <option value="nibbles">Nibbles 🐹</option>
                        <option value="smudge">Smudge 😻</option>
                        <option value="new-pet">New Pet (add details below)</option>
                    </select>
                </div>

                <div class="form-input-group">
                    <label for="appointment-date">Preferred Date</label>
                    <input type="date" id="appointment-date" required>
                </div>

                <div class="form-input-group">
                    <label class="mb-2">Preferred Time Slot</label>
                    <div class="time-slot-group">
                        <div class="input-container">
                            <input type="radio" id="time-morning" name="time-slot" value="09:00 - 12:00" required>
                            <div class="radio-tile">09:00 AM - 12:00 PM</div>
                        </div>
                        <div class="input-container">
                            <input type="radio" id="time-afternoon" name="time-slot" value="12:00 - 03:00">
                            <div class="radio-tile">12:00 PM - 03:00 PM</div>
                        </div>
                        <div class="input-container">
                            <input type="radio" id="time-late-afternoon" name="time-slot" value="03:00 - 06:00">
                            <div class="radio-tile">03:00 PM - 06:00 PM</div>
                        </div>
                        <div class="input-container">
                            <input type="radio" id="time-evening" name="time-slot" value="06:00 - 08:00">
                            <div class="radio-tile">06:00 PM - 08:00 PM</div>
                        </div>
                    </div>
                </div>

                <div class="form-input-group">
                    <label for="reason-for-appointment">Reason for Appointment</label>
                    <textarea id="reason-for-appointment" rows="4"
                        placeholder="e.g., Annual check-up, Vaccination, Nail trimming, Behavioral consultation, Specific health concern..."
                        required></textarea>
                </div>

                <h3 class="text-2xl font-bold mb-4 text-[color:var(--mustard)] pt-4">Your Contact Information</h3>

                <div class="form-input-group">
                    <label for="your-name">Your Full Name</label>
                    <input type="text" id="your-name" placeholder="John Doe" required>
                </div>

                <div class="form-input-group">
                    <label for="your-email">Your Email</label>
                    <input type="email" id="your-email" placeholder="john.doe@example.com" required>
                </div>

                <div class="form-input-group">
                    <label for="your-phone">Your Phone Number</label>
                    <input type="tel" id="your-phone" placeholder="+8801XXXXXXXXX" required>
                </div>

                <div class="flex justify-center mt-8">
                    <button type="submit" class="btn-primary">
                        <span class="flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Confirm Appointment!
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Information / What to expect section -->
    <section class="bg-[color:var(--sand)] py-16 px-6 relative z-10 rounded-t-3xl shadow-inner animate-fade-in-up"
        style="animation-delay: 0.6s;">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl font-bold mb-6 text-[color:var(--dark-text)]">What Happens Next?</h2>
            <p class="text-lg mb-4 text-[color:var(--dark-text)]">
                Once you confirm your appointment, our team will review your request and send a confirmation email with
                all the details. Please ensure your contact information is accurate.
            </p>
            <p class="text-lg text-[color:var(--dark-text)]">
                For urgent matters or emergencies, please contact us directly at <span
                    class="font-semibold text-[color:var(--mustard)]">01XXXXXXXXX</span>.
            </p>
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
                                d="M12 2C9.176 2 8.787 2.01 7.424 2.062c-1.365.053-2.06.27-2.618.497-.56.228-1.015.546-1.468.999-.453.453-.77 1.008-.999 1.568-.228.558-.444 1.253-.497 2.618-.052 1.363-.062 1.753-.062 4.5s.01 3.137.062 4.5c.053 1.365.27 2.06.497 2.618.228.56.546 1.015.999 1.468.453.453 1.008.77 1.568.999.558.228 1.253.444 2.618.497 1.363.052 1.753.062 4.5.062s3.137-.01 4.5-.062c1.365-.053 2.06-.27 2.618-.497.56-.228 1.015-.546 1.468-.999.453-.453.77-1.008.999-1.568.228-.558.444-1.253.497-2.618.052-1.363.062-1.753.062-4.5s-.01-3.137-.062-4.5c-.053-1.365-.27-2.06-.497-2.618-.228-.56-.546-1.015-.999-1.468-.453-.453-1.008-.77-1.568-.999-.558-.228-1.253-.444-2.618-.497C15.137 2.01 14.747 2 12 2zm0 2.164c2.784 0 3.109.011 4.2.053 1.088.043 1.638.257 2.02.417.382.16.634.357.877.6.242.242.438.495.6.877.16.382.374.932.417 2.02.042 1.091.053 1.416.053 4.2s-.011 3.109-.053 4.2c-.043 1.088-.257 1.638-.417 2.02-.16.382-.357.634-.6.877-.242.242-.495.438-.877.6-.382.16-.932.374-2.02.417-1.091.042-1.416.053-4.2.053s-3.109-.011-4.2-.053c-1.088-.043-1.638-.257-2.02-.417-.382-.16-.634-.357-.877-.6-.242-.242-.495-.438-.6-.877-.16-.382-.374-.932-.417-2.02-.042-1.091-.053-1.416-.053-4.2s.011-3.109.053-4.2c.043-1.088.257-1.638.417-2.02.16-.382.357-.634.6-.877.242-.242.495-.438.877-.6.382-.16.932-.374 2.02-.417C8.891 4.175 9.216 4.164 12 4.164zm0 3.659A4.177 4.177 0 1012 16.002a4.177 4.177 0 000-8.354zm0 2.164a2.013 2.013 0 110 4.026 2.013 2.013 0 010-4.026z" />
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

    <!-- Success Message Container -->
    <div id="success-message" class="success-message">
        Appointment Booked! ✅
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('current-year').textContent = new Date().getFullYear();

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
                        // Apply animation and remove initial hidden state
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

            // Header shrink on scroll
            const header = document.querySelector('header');
            const logo = header.querySelector('img');
            const primaryButton = header.querySelector('.btn-primary');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('header-shrink');
                    header.classList.remove('py-4', 'shadow-md');
                    logo.classList.add('h-12');
                    logo.classList.remove('h-16');
                    primaryButton.classList.add('px-6', 'py-2', 'text-base');
                    primaryButton.classList.remove('px-8', 'py-4', 'text-lg');
                } else {
                    header.classList.remove('header-shrink');
                    header.classList.add('py-4', 'shadow-md');
                    logo.classList.remove('h-12');
                    logo.classList.add('h-16');
                    primaryButton.classList.remove('px-6', 'py-2', 'text-base');
                    primaryButton.classList.add('px-8', 'py-4', 'text-lg');
                }
            });

            // Handle form submission (simulated for demonstration)
            const appointmentForm = document.getElementById('appointment-form');
            const successMessage = document.getElementById('success-message');

            if (appointmentForm) {
                appointmentForm.addEventListener('submit', (event) => {
                    event.preventDefault(); // Prevent actual form submission

                    // Simulate form validation (optional, add more robust validation if needed)
                    const requiredFields = appointmentForm.querySelectorAll('[required]');
                    let allFieldsValid = true;
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            allFieldsValid = false;
                            field.style.borderColor = 'red'; // Simple visual feedback
                        } else {
                            field.style.borderColor = ''; // Reset border
                        }
                    });

                    if (allFieldsValid) {
                        // Show success message
                        successMessage.classList.add('show');

                        // Hide success message and reset form after a delay
                        setTimeout(() => {
                            successMessage.classList.remove('show');
                            appointmentForm.reset(); // Clear form fields
                        }, 2500); // Show for 2.5 seconds
                    } else {
                        // Optionally show an error message for incomplete fields
                        console.error('Please fill in all required fields.');
                    }
                });
            }
        });
    </script>
</body>

</html>