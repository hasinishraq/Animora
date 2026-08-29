<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();



include '../config/db.php';  // Adjust path as needed


// Define filters config (same as you provided)
$filters = [
    'Breed' => [
        'label' => 'Breed',
        'type' => 'select',
        'query' => "SELECT DISTINCT b.breedname AS value 
                    FROM breeds b 
                    JOIN animals a ON a.breedid = b.breedid 
                    ORDER BY b.breedname ASC",
        'name' => 'breed',
    ],
    'Age' => [
        'label' => 'Age (Years)',
        'type' => 'select',
        'query' => "SELECT DISTINCT age AS value FROM animals WHERE age IS NOT NULL ORDER BY age ASC",
        'name' => 'age',
    ],
    'Gender' => [
        'label' => 'Gender',
        'type' => 'select',
        'query' => "SELECT DISTINCT gender AS value FROM animals WHERE gender IS NOT NULL",
        'name' => 'gender',
    ],

    'Location' => [
        'label' => 'Location',
        'type' => 'select',
        'query' => "SELECT DISTINCT location AS value FROM animals WHERE location IS NOT NULL AND location != '' ORDER BY location ASC",
        'name' => 'location',
    ],


];

// Handle filtering inputs from GET parameters
$whereClauses = [];
$params = [];
$types = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Sanitize and collect filters
    foreach ($filters as $filter) {
        $key = $filter['name'];
        if (isset($_GET[$key]) && $_GET[$key] !== '') {
            $value = trim($_GET[$key]);
            if ($filter['type'] === 'text') {
                // For text filter (color), use LIKE for partial match
                $whereClauses[] = "a.$key LIKE ?";
                $params[] = '%' . $value . '%';
                $types .= 's';
            } else {
                // For select filters, exact match
                if ($key === 'breed') {
                    // breed is from breeds table, join needed (will handle below)
                    $whereClauses[] = "b.breedname = ?";
                    $params[] = $value;
                    $types .= 's';
                } else {
                    $whereClauses[] = "a.$key = ?";
                    $params[] = $value;
                    $types .= 's';
                }
            }
        }
    }
}

// Build SQL query
$sql = "SELECT a.animalid, a.name, a.age, a.gender, a.location, a.photo, b.breedname
        FROM animals a
        JOIN breeds b ON a.breedid = b.breedid";

if (count($whereClauses) > 0) {
    $sql .= " WHERE " . implode(' AND ', $whereClauses);
}

$sql .= " ORDER BY a.name ASC";

// Prepare and execute query safely using prepared statements



$stmt = $conn->prepare($sql);

if ($stmt) {
    if (count($params) > 0) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("SQL prepare error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawsome Adoptions - Find Your Purrfect Friend!</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* New Color Palette Variables */
        :root {
            --cream: #FBF3F0;
            --mustard: #D4931F;
            --teal: #A0C0A9;
            --mint: #C2D4C8;
            --sand: #EEC3A4;
            --dark-text: #4A4A4A;
            --furry-friends-text: #1C2A39;
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
            /* Direct CSS usage is fine */
        }

        /* Custom Buttons */
        .btn-primary {
            /* Corrected Tailwind arbitrary values for background */
            @apply bg-[color:var(--mustard)] text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-transparent hover:border-white;
        }

        .btn-secondary {
            /* Corrected Tailwind arbitrary values for text and border */
            @apply bg-white text-[color:var(--mustard)] px-8 py-4 rounded-full font-bold text-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-[color:var(--mustard)];
        }

        /* Card Hover Effect */
        .card-pop-shadow:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
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
        .header-shrink.py-3 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Hero Section Animations */
        .animate-pulse-text-mustard {
            animation: pulseTextMustard 2s infinite alternate;
        }

        @keyframes pulseTextMustard {
            0% {
                color: var(--mustard);
                transform: scale(1);
            }

            50% {
                color: #F0B050;
                transform: scale(1.02);
            }

            /* A lighter mustard for pulse */
            100% {
                color: var(--mustard);
                transform: scale(1);
            }
        }

        .paw-print {
            position: absolute;
            width: 50px;
            /* Slightly larger */
            height: 50px;
            /* Slightly larger */
            /* Corrected fill color in inline SVG data for consistency with --mustard */
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23D4931F"%3E%3Cpath d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"%3E%3C/path%3E%3C/svg%3E');
            background-size: contain;
            background-repeat: no-repeat;
            border-radius: 50%;
            animation: floatPaw 12s infinite linear;
            opacity: 0.6;
            /* Increased opacity */
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

        /* Added more */
        .paw-print:nth-child(8) {
            left: 70%;
            top: 70%;
            animation-delay: 3.5s;
            opacity: 0.6;
            width: 50px;
            height: 50px;
        }

        /* Added more */

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

        /* Peeking Cat Animation */
        .peeking-cat {
            position: absolute;
            width: 160px;
            /* Slightly larger */
            height: auto;
            transform: translateY(100%);
            /* Start hidden below */
            transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            /* Bouncier transition */
            z-index: 20;
            pointer-events: none;
        }

        .peeking-cat.active {
            transform: translateY(-20px);
            /* Peek higher */
        }

        .peeking-cat-hidden {
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
        }

        /* Wave animations - REMOVED ANIMATION */
        /* .wave-top path {
            animation: waveFlowTop 18s linear infinite alternate;
        }
        .wave-bottom path {
            animation: waveFlowBottom 18s linear infinite alternate;
        }
        @keyframes waveFlowTop {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100px); }
        }
        @keyframes waveFlowBottom {
            0% { transform: translateX(0); }
            100% { transform: translateX(100px); }
        } */

        /* Filter Section Animations */
        .filter-slide-in {
            animation: slideInLeft 0.8s ease-out forwards;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .filter-scrollable::-webkit-scrollbar {
            width: 8px;
        }

        .filter-scrollable::-webkit-scrollbar-track {
            background: var(--mint);
            border-radius: 10px;
        }

        .filter-scrollable::-webkit-scrollbar-thumb {
            background: var(--sand);
            border-radius: 10px;
        }

        .filter-scrollable::-webkit-scrollbar-thumb:hover {
            background: var(--mustard);
        }

        .animate-wiggle-pulse {
            animation: wigglePulse 2s infinite ease-in-out;
        }

        @keyframes wigglePulse {

            0%,
            100% {
                transform: rotate(0deg) scale(1);
            }

            25% {
                transform: rotate(-3deg) scale(1.02);
            }

            75% {
                transform: rotate(3deg) scale(1.02);
            }
        }

        .animate-shake-subtle {
            animation: shakeSubtle 1.5s infinite ease-in-out;
        }

        @keyframes shakeSubtle {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-3px);
            }

            75% {
                transform: translateX(3px);
            }
        }

        /* Pet Card Animations */
        .sticker-effect {
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }

        .sticker-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 4px dashed var(--sand);
            border-radius: 15px;
            pointer-events: none;
            z-index: 1;
        }

        .animate-heartbeat-card-btn {
            animation: heartbeatCardBtn 1.5s infinite alternate;
        }

        @keyframes heartbeatCardBtn {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.03);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Testimonial Section Animations */
        .sparkle-effect {
            position: absolute;
            background-color: var(--mustard);
            width: 8px;
            /* Slightly larger sparkles */
            height: 8px;
            border-radius: 50%;
            opacity: 0.8;
            /* Increased opacity */
            animation: twinkle 1.8s infinite alternate;
            box-shadow: 0 0 5px var(--mustard);
            /* Added subtle glow */
            z-index: 5;
        }

        .testimonial-card:hover .sparkle-effect {
            animation-duration: 0.8s;
            /* Speed up on hover */
            opacity: 1;
        }

        .testimonial-card .sparkle-effect:nth-child(1) {
            top: 15%;
            left: 10%;
            animation-delay: 0.1s;
        }

        .testimonial-card .sparkle-effect:nth-child(2) {
            top: 30%;
            right: 5%;
            animation-delay: 0.6s;
        }

        .testimonial-card .sparkle-effect:nth-child(3) {
            bottom: 10%;
            left: 20%;
            animation-delay: 1.1s;
        }

        .testimonial-card .sparkle-effect:nth-child(4) {
            top: 5%;
            right: 25%;
            animation-delay: 0.3s;
        }

        .testimonial-card .sparkle-effect:nth-child(5) {
            bottom: 5%;
            right: 10%;
            animation-delay: 0.9s;
        }

        @keyframes twinkle {
            from {
                opacity: 0.8;
                transform: scale(1);
            }

            to {
                opacity: 1;
                transform: scale(1.3);
            }

            /* More pronounced twinkle */
        }

        /* CTA Section Animations */
        .animate-float-decorative {
            animation: floatDecorative 6s infinite ease-in-out;
            opacity: 0.9;
            /* Increased opacity */
            width: 250px;
            /* Larger */
            height: auto;
        }

        @keyframes floatDecorative {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            25% {
                transform: translateY(-15px) rotate(8deg);
            }

            /* More movement */
            75% {
                transform: translateY(15px) rotate(-8deg);
            }

            /* More movement */
        }

        .cta-content-pop {
            animation: ctaPop 1.5s ease-out forwards;
            /* Faster pop */
            opacity: 0;
            transform: scale(0.8);
            /* Start smaller */
        }

        @keyframes ctaPop {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Footer Animation */
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
                <a href="user-home.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Dashboard</a>
                <a href="user-post-adoption.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300 transform hover:scale-105">Post
                    Adoption</a>

            </nav>

            <!-- Center Logo -->
            <div class="flex justify-center flex-shrink-0">
                <img src="/assets/images/logo2.png" alt="Pawsome Adoptions Logo"
                    class="h-16 w-auto transition-all duration-300 ease-in-out">
            </div>

            <!-- Right Button -->
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

    <!-- Hero Section -->
    <section
        class="relative w-full h-[650px] bg-[#f7b9a6] flex items-center justify-center text-[color:var(--mustard)] overflow-hidden rounded-3xl shadow-xl">
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
        <div class="relative z-10 text-center p-6 animate-fade-in-up">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight drop-shadow-md">
                <span class="block animate-pulse-text-mustard">Find Your Furry Soulmate!</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto drop-shadow-sm text-[color:var(--dark-text)]">
                Connecting adorable pets with their forever families, one happy paw at a time!
            </p>

            <!-- Center Filter Box -->
            <div
                class="max-w-xl mx-auto p-8 bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl text-center border-2 border-[color:var(--mustard)]">
                <h2 class="text-3xl font-bold mb-5 text-[color:var(--mustard)]">
                    What kind of fluff are you searching for?
                </h2>
                <p class="text-lg text-[color:var(--dark-text)]">
                    Choose your perfect paw-some pal from dogs, cats, rabbits, and more!
                </p>
            </div>

        </div>

        <!-- Peeking Cat -->
        <img src="https://assets.website-files.com/6045d4484b554e2fe821f5fb/6064f7b6070656a815a5f1e1_cat-peeking-right-01.svg"
            alt="Cute peeking cat" class="peeking-cat hidden md:block" id="peeking-cat-right"
            style="right: -40px; bottom: -40px; transform: rotate(5deg);">
        <img src="https://assets.website-files.com/6045d4484b554e2fe821f5fb/6064f7b6070656a815a5f1e1_cat-peeking-right-01.svg"
            alt="Cute peeking cat" class="peeking-cat hidden md:block transform scale-x-[-1]" id="peeking-cat-left"
            style="left: -40px; bottom: -40px; transform: rotate(-5deg) scaleX(-1);">

        <!-- SVG Wave at the bottom -->
        <div class="absolute bottom-0 left-0 w-full z-10 wave-bottom">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="var(--cream)" fill-opacity="1"
                    d="M0,96L34.3,117.3C68.6,139,137,181,206,202.7C274.3,224,343,224,411,208C480,192,549,160,617,149.3C685.7,139,754,149,823,176C891.4,203,960,245,1029,245.3C1097.1,245,1166,203,1234,197.3C1302.9,192,1371,224,1406,240L1440,256L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                </path>
            </svg>
        </div>
    </section>


    <?php if (isset($_SESSION['adoption_message'])): ?>
        <script>
            alert("<?= addslashes($_SESSION['adoption_message']) ?>");
        </script>
        <?php unset($_SESSION['adoption_message']); ?>
    <?php endif; ?>

    <!-- Main Content: Filters and Pet Cards -->
    <section class="w-full py-14 px-6 bg-[color:var(--cream)] relative z-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-10">

            <!-- Left Filter Menu (Sticky) -->


            <aside
                class="w-full md:w-1/4 bg-white p-6 rounded-2xl shadow-2xl space-y-5 sticky top-24 self-start filter-scrollable filter-slide-in border-2 border-[color:var(--mint)]">
                <h2 class="text-3xl font-bold mb-4 text-[color:var(--mustard)]">Refine Your Search!</h2>
                <form method="GET" action="user-adoption.php" class="space-y-4">
                    <?php foreach ($filters as $filter): ?>
                        <div>
                            <label for="<?= htmlspecialchars($filter['name']) ?>"
                                class="block font-semibold text-lg text-[color:var(--mustard)] mb-1">
                                <?= htmlspecialchars($filter['label']) ?>
                            </label>

                            <?php if ($filter['type'] === 'select'): ?>
                                <select id="<?= htmlspecialchars($filter['name']) ?>"
                                    name="<?= htmlspecialchars($filter['name']) ?>"
                                    class="w-full px-4 py-2 border-2 border-[color:var(--mint)] rounded-md text-[color:var(--dark-text)] focus:outline-none focus:border-[color:var(--mustard)]">
                                    <option value="" <?= (!isset($_GET[$filter['name']]) || $_GET[$filter['name']] === '') ? 'selected' : '' ?>>Any 🐾</option>
                                    <?php
                                    $filterResult = $conn->query($filter['query']);
                                    while ($row = $filterResult->fetch_assoc()):
                                        $value = htmlspecialchars($row['value']);
                                        $selected = (isset($_GET[$filter['name']]) && $_GET[$filter['name']] === $value) ? 'selected' : '';
                                        ?>
                                        <option value="<?= $value ?>" <?= $selected ?>><?= $value ?></option>
                                    <?php endwhile; ?>

                                </select>

                            <?php elseif ($filter['type'] === 'text'): ?>
                                <input type="text" id="<?= htmlspecialchars($filter['name']) ?>"
                                    name="<?= htmlspecialchars($filter['name']) ?>"
                                    placeholder="<?= htmlspecialchars($filter['placeholder'] ?? '') ?>"
                                    class="w-full px-4 py-2 border-2 border-[color:var(--mint)] rounded-md focus:outline-none focus:border-[color:var(--mustard)] transition duration-300 shadow-sm text-[color:var(--dark-text)]">
                            <?php endif; ?>
                        </div>


                    <?php endforeach; ?>

                    <button type="submit" class="w-full btn-primary mt-5 animate-wiggle-pulse hover:animate-none">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Apply Magic!
                        </span>
                    </button>

                    <button type="reset" class="w-full btn-secondary mt-3 animate-shake-subtle hover:animate-none">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear Whiskers
                        </span>
                    </button>
                </form>
            </aside>





            <!-- Feedback Message (if any) -->
            <?php if (isset($_SESSION['adoption_message'])): ?>
                <div class="text-center text-lg font-semibold text-green-700 bg-green-100 p-4 rounded mb-4 col-span-full">
                    <?= htmlspecialchars($_SESSION['adoption_message']) ?>
                </div>
                <?php unset($_SESSION['adoption_message']); ?>
            <?php endif; ?>

            <!-- Right Pet Cards -->
            <!-- Right Pet Cards -->
            <div class="w-full md:w-3/4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php
                if (!$result) {
                    echo "<p class='text-center col-span-full text-lg text-red-600 font-semibold'>Error executing query: " . htmlspecialchars($conn->error) . "</p>";
                } elseif ($result->num_rows === 0) {
                    echo "No matching records found.";
                    echo "<p class='text-center col-span-full text-lg text-[color:var(--mustard)] font-semibold'>No pets found matching your criteria.</p>";
                } else {
                    echo "<p class='text-center col-span-full text-lg text-green-600 font-semibold'>Found {$result->num_rows} pets.</p>";

                    $delay = 0.1;
                    while ($pet = $result->fetch_assoc()):
                        // Sanitize output
                        $petName = htmlspecialchars($pet['name']);
                        $petBreed = htmlspecialchars($pet['breedname']);
                        $petAge = htmlspecialchars($pet['age']);
                        $petGender = htmlspecialchars($pet['gender']);
                        $petColor = htmlspecialchars($pet['color'] ?? '');
                        $petLocation = htmlspecialchars($pet['location']);
                        $petImage = htmlspecialchars($pet['photo'] ?: 'https://via.placeholder.com/400x300?text=No+Image');

                        // Gender emoji
                        $genderEmoji = ($petGender === 'Male') ? '🐶' : (($petGender === 'Female') ? '🐱' : '🐾');
                        ?>
                        <div class="bg-white p-5 rounded-2xl shadow-2xl border-2 border-[color:var(--sand)] transition duration-300 ease-in-out card-pop-shadow flex flex-col items-center text-center animate-fade-in-up"
                            style="animation-delay: <?= $delay ?>s;">
                            <div class="sticker-effect mb-3 w-full">
                                <img src="<?= $petImage ?>" alt="Photo of <?= $petName ?>"
                                    class="w-full h-56 object-cover rounded-md shadow-sm transform transition duration-500 hover:scale-102">
                            </div>
                            <div class="mt-1 w-full">
                                <h3 class="text-2xl font-bold mb-1 text-[color:var(--mustard)]">
                                    <?= $petName ?> <span class="text-lg"><?= $genderEmoji ?></span>
                                </h3>
                                <p class="text-[color:var(--teal)] text-sm mb-2">
                                    <?= $petLocation ?> • Breed: <?= $petBreed ?> • Age: <?= $petAge ?> years •
                                    <?= $petGender ?>
                                </p>
                                <form method="POST" action="process-adoption.php">
                                    <input type="hidden" name="animal_id" value="<?= htmlspecialchars($pet['animalid']) ?>">
                                    <button type="submit"
                                        class="btn-primary text-sm px-4 py-2 animate-heartbeat-card-btn hover:animate-none">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.102 1.101">
                                                </path>
                                            </svg>
                                            Adopt Me!
                                        </span>
                                    </button>
                                </form>

                            </div>
                        </div>
                        <?php
                        $delay += 0.1;
                    endwhile;
                }
                ?>

            </div>

        </div>

        <!-- Pagination Section -->
        <!---   <div class="w-full flex justify-center mt-16 mb-8">
            <nav
                class="inline-flex items-center gap-3 rounded-full bg-white px-6 py-3 shadow-md border-2 border-[color:var(--sand)]">
                <button
                    class="px-5 py-2 text-base font-semibold text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <button
                    class="px-5 py-2 text-base font-bold text-white bg-[color:var(--mustard)] rounded-full shadow-lg transform hover:scale-110 transition duration-200">
                    1
                </button>
                <button
                    class="px-5 py-2 text-base font-semibold text-[color:var(--mustard)] hover:bg-[color:var(--cream)] rounded-full transform hover:scale-110 transition duration-200">
                    2
                </button>
                <button
                    class="px-5 py-2 text-base font-semibold text-[color:var(--mustard)] hover:bg-[color:var(--cream)] rounded-full transform hover:scale-110 transition duration-200">
                    3
                </button>
                <button
                    class="px-5 py-2 text-base font-semibold text-[color:var(--mustard)] hover:bg-[color:var(--cream)] rounded-full hidden sm:block transform hover:scale-110 transition duration-200">
                    4
                </button>
                <button
                    class="px-5 py-2 text-base font-semibold text-[color:var(--mustard)] hover:bg-[color:var(--cream)] rounded-full hidden sm:block transform hover:scale-110 transition duration-200">
                    5
                </button>
                <span class="text-[color:var(--teal)] font-semibold px-2 hidden sm:block">...</span>
                <button
                    class="px-5 py-2 text-base font-semibold text-[color:var(--mustard)] hover:bg-[color:var(--cream)] rounded-full transform hover:scale-110 transition duration-200">
                    10
                </button>

                <button
                    class="px-5 py-2 text-base font-semibold text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition">
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </nav>
        </div>

            -->
    </section>

    <!-- Call to Action: Post a Pet for Adoption -->
    <section
        class="relative overflow-hidden h-[600px] flex items-center justify-center py-20 bg-gradient-to-br from-[color:var(--teal)] to-[color:var(--mint)] rounded-3xl shadow-xl">
        <!-- Top Wave -->
        <div class="absolute top-0 left-0 w-full leading-[0] z-10 wave-top">
            <svg class="block w-full h-[180px]" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="var(--cream)"
                    d="M0,64C120,106.7,240,213,360,224C480,235,600,149,720,122.7C840,96,960,128,1080,149.3C1200,171,1320,181,1380,186.7L1440,192L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z" />
            </svg>
        </div>

        <!-- Decorative Floating Animals -->
        <img src="/assets/images/dog.gif" alt="Floating dog"
            class="absolute left-[-50px] top-[15%] w-[250px] md:w-[300px] opacity-90 z-0 animate-float-decorative hidden md:block"
            style="animation-delay: 0.5s;">
        <img src="/assets/images/sleeping-cat.png" alt="Floating cat"
            class="absolute right-[-50px] bottom-[10%] w-[220px] md:w-[270px] opacity-90 z-0 animate-float-decorative transform scale-x-[-1] hidden md:block"
            style="animation-delay: 1.5s;">

        <!-- Centered Content -->
        <div
            class="relative z-20 flex flex-col items-center justify-center text-center text-white h-full px-6 cta-content-pop">
            <h2
                class="text-4xl md:text-5xl font-extrabold mb-5 drop-shadow-lg leading-tight text-[color:var(--dark-text)]">
                <span class="block animate-pulse-text- #fff">Have a Little One Who Needs a Home?</span>
            </h2>
            <p class="text-xl md:text-2xl max-w-xl mb-10 drop-shadow-lg text-[color:var(--dark-text)]">
                Post your pet's adorable photos and details here. Let's find them the purrfect loving family!
            </p>
            <a href="user-post-adoption.php"> <button
                    class="btn-secondary !bg-white !text-[color:var(--mustard)] shadow-xl hover:!bg-[color:var(--cream)] border-4 border-[color:var(--mustard)] transform hover:rotate-3">
                    <span class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m0 0h-3m-1.293-7.293A9.994 9.994 0 0012 2c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10A9.994 9.994 0 0021 10.707">
                            </path>
                        </svg>
                        Post an Adoption Story!
                    </span>
                </button>
            </a>
        </div>

        <!-- Bottom Wave -->
        <div class="absolute bottom-0 left-0 w-full z-10 wave-bottom">
            <svg class="block w-full h-[180px] rotate-180" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="var(--cream)"
                    d="M0,64C120,106.7,240,213,360,224C480,235,600,149,720,122.7C840,96,960,128,1080,149.3C1200,171,1320,181,1380,186.7L1440,192L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z" />
            </svg>
        </div>
    </section>

    <!-- Testimonials / Success Stories Section -->
    <section class="bg-[color:var(--cream)] py-20 px-6 relative z-10">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-5xl font-extrabold mb-12 text-[color:var(--mustard)]">Our Happy Tails Hall of Fame! ✨</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Testimonial Card 1 -->
                <div class="bg-white p-8 rounded-3xl shadow-2xl flex flex-col items-center animate-fade-in-up card-pop-shadow border-2 border-[color:var(--sand)] relative testimonial-card"
                    style="animation-delay: 0.1s;">
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sticker-effect mb-6">
                        <img src="https://images.unsplash.com/photo-1549880181-5660f9b69b76?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80"
                            alt="Sarah and Luna"
                            class="w-32 h-32 rounded-full object-cover border-4 border-[color:var(--mustard)] shadow-lg transform transition duration-500 hover:scale-110">
                    </div>
                    <p class="text-xl text-[color:var(--dark-text)] italic mb-6">"Adopting Luna through Pawsome
                        Adoptions was simply magical! She's filled our home with so much purrs and joy. So grateful!"
                    </p>
                    <p class="font-bold text-2xl text-[color:var(--mustard)]">- Sarah M. & Luna 💖</p>
                </div>

                <!-- Testimonial Card 2 -->
                <div class="bg-white p-8 rounded-3xl shadow-2xl flex flex-col items-center animate-fade-in-up card-pop-shadow border-2 border-[color:var(--teal)] relative testimonial-card"
                    style="animation-delay: 0.2s;">
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sticker-effect mb-6">
                        <img src="https://images.unsplash.com/photo-1576201836106-53094c927f05?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                            alt="David and Max"
                            class="w-32 h-32 rounded-full object-cover border-4 border-[color:var(--mustard)] shadow-lg transform transition duration-500 hover:scale-110">
                    </div>
                    <p class="text-xl text-[color:var(--dark-text)] italic mb-6">"Max is the most playful pup, and we
                        found him right here! The whole process felt so caring and delightful. Woof-tastic experience!"
                    </p>
                    <p class="font-bold text-2xl text-[color:var(--mustard)]">- David L. & Max 🐾</p>
                </div>

                <!-- Testimonial Card 3 -->
                <div class="bg-white p-8 rounded-3xl shadow-2xl flex flex-col items-center animate-fade-in-up card-pop-shadow border-2 border-[color:var(--mint)] relative testimonial-card"
                    style="animation-delay: 0.3s;">
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sparkle-effect"></div>
                    <div class="sticker-effect mb-6">
                        <img src="https://images.unsplash.com/photo-1560762484-bc327b5e4088?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80"
                            alt="Emily and Chloe"
                            class="w-32 h-32 rounded-full object-cover border-4 border-[color:var(--mustard)] shadow-lg transform transition duration-500 hover:scale-110">
                    </div>
                    <p class="text-xl text-[color:var(--dark-text)] italic mb-6">"Little Chloe is the purrfect cuddle
                        bug! Pawsome Adoptions made finding our dream cat a joy. Five stars for furry happiness!"</p>
                    <p class="font-bold text-2xl text-[color:var(--mustard)]">- Emily R. & Chloe ✨</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Manage Your Posts Section -->
    <section
        class="relative bg-gradient-to-br from-[color:var(--teal)] to-[color:var(--mint)] py-20 overflow-hidden rounded-3xl shadow-xl">
        <!-- Decorative images (more stylized and dynamic) -->
        <img src="/assets/images/cute-decorative-dog-.png" alt="Cute decorative dog"
            class="absolute left-[-50px] top-[10%] w-[250px] md:w-[300px] opacity-90 z-0 animate-float-decorative hidden md:block"
            style="animation-duration: 7s;">
        <img src="/assets/images/cute-decorative-cat-.png" alt="Cute decorative cat"
            class="absolute right-[-50px] bottom-[5%] w-[220px] md:w-[270px] opacity-90 z-0 animate-float-decorative transform scale-x-[-1] hidden md:block"
            style="animation-delay: 1s; animation-duration: 8s;">

        <!-- Centered Content -->
        <div
            class="relative z-10 max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl p-10 text-center flex flex-col justify-center items-center transform transition duration-500 hover:scale-103 border-2 border-[color:var(--sand)] cta-content-pop">
            <h2 class="text-4xl md:text-5xl font-extrabold text-[color:var(--mustard)] mb-6 leading-tight">Your Adoption
                Adventure Hub!</h2>
            <p class="text-xl text-[color:var(--dark-text)] mb-8">
                Whether you're looking to adopt or helping a furball find their place, manage your journey with ease!
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-6 w-full">
                <button class="btn-primary flex-grow animate-wiggle-pulse hover:animate-none">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m0 0h-3m-1.293-7.293A9.994 9.994 0 0012 2c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10A9.994 9.994 0 0021 10.707">
                            </path>
                        </svg>
                        Start a New Listing!
                    </span>
                </button>
                <button class="btn-secondary flex-grow animate-shake-subtle hover:animate-none">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.527.272 1.033.564 1.543.944z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Manage My Paws!
                    </span>
                </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('current-year').textContent = new Date().getFullYear();

            // Intersection Observer for fade-in animations on scroll
            const faders = document.querySelectorAll('.animate-fade-in-up, .filter-slide-in, .cta-content-pop');
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
                        if (entry.target.classList.contains('animate-fade-in-up') || entry.target.classList.contains('filter-slide-in') || entry.target.classList.contains('cta-content-pop')) {
                            // Staggering delay for cards/testimonials
                            const delay = entry.target.dataset.animationDelay || '0s';
                            entry.target.style.animationDelay = delay;
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0) scale(1)'; // Ensure elements start at correct position/scale
                        }
                        observer.unobserve(entry.target);
                    }
                });
            }, appearOptions);

            faders.forEach(fader => {
                // Initialize hidden state based on animation type
                if (fader.classList.contains('animate-fade-in-up')) {
                    fader.style.opacity = '0';
                    fader.style.transform = 'translateY(20px)';
                } else if (fader.classList.contains('filter-slide-in')) {
                    fader.style.opacity = '0';
                    fader.style.transform = 'translateX(-100px)';
                } else if (fader.classList.contains('cta-content-pop')) {
                    fader.style.opacity = '0';
                    fader.style.transform = 'scale(0.8)'; // Set initial scale for pop animation
                }
                appearOnScroll.observe(fader);
            });


            // Peeking Cat Animation Logic
            const catLeft = document.getElementById('peeking-cat-left');
            const catRight = document.getElementById('peeking-cat-right');

            function showCat(catElement) {
                catElement.classList.add('active');
                catElement.classList.remove('peeking-cat-hidden');

                const hideDelay = Math.random() * (4000 - 2000) + 2000; // 2-4 seconds
                setTimeout(() => {
                    catElement.classList.remove('active');
                    catElement.classList.add('peeking-cat-hidden');
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

            // Header shrink on scroll
            const header = document.querySelector('header');
            const logo = header.querySelector('img');
            const primaryButton = header.querySelector('.btn-primary');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('py-2', 'shadow-xl');
                    header.classList.remove('py-4', 'shadow-md');
                    logo.classList.add('h-12'); // Make logo smaller
                    logo.classList.remove('h-16');
                    primaryButton.classList.add('px-6', 'py-2', 'text-base'); // Make button smaller
                    primaryButton.classList.remove('px-8', 'py-4', 'text-lg');
                } else {
                    header.classList.remove('py-2', 'shadow-xl');
                    header.classList.add('py-4', 'shadow-md');
                    logo.classList.remove('h-12');
                    logo.classList.add('h-16');
                    primaryButton.classList.remove('px-6', 'py-2', 'text-base');
                    primaryButton.classList.add('px-8', 'py-4', 'text-lg');
                }
            });
        });
    </script>
</body>

</html>