<?php



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();  // Start the session
include '../config/db.php';  // Include the database connection file (adjust path as necessary)

// Check if the user is logged in and if the role is "User"
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'User') {
    // Redirect to the login page if the user is not logged in or role is not "User"
    header("Location: /animora/auth/login.php");  // Adjust the login page URL if necessary
    exit();  // Stop further script execution after redirection
}

// Fetch user's name and profile picture from the database
$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];  // Fetch the role from the session

// Prepare the query to fetch the user's name and profile picture
$stmt = $conn->prepare("SELECT Name, ProfilePhoto FROM users WHERE UserID = ?");
$stmt->bind_param("i", $user_id);  // "i" is for integer
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = $user["Name"];
    $profile_photo = $user["ProfilePhoto"]; // This will store the path to the profile picture
} else {
    $user_name = "Guest";  // If no user found, set a default name
    $profile_photo = "https://randomuser.me/api/portraits/men/32.jpg"; // Default profile picture
}

// Query to count the number of upcoming appointments
$sql = "
    SELECT COUNT(a.AppointmentID) AS upcoming_appointments
    FROM appointments a
    INNER JOIN timeslots t ON a.SlotID = t.SlotID
    WHERE a.UserID = ? 
    AND a.Status = 'Pending'
    AND t.SlotDate >= CURDATE();";  // Ensures the SlotDate is today or in the future

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);  // Bind the user_id to the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch the result
$upcoming_appointments = 0;  // Default value in case no result is returned
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $upcoming_appointments = $data['upcoming_appointments'];  // Get the count of upcoming appointments
}

// Query to fetch upcoming appointments
$sql = "
    SELECT a.AppointmentID, a.PetName, a.Reason, a.Status, 
           DATE_FORMAT(t.SlotDate, '%Y-%m-%d') AS SlotDate, 
           t.StartTime, t.EndTime
    FROM appointments a
    INNER JOIN timeslots t ON a.SlotID = t.SlotID
    WHERE a.UserID = ? 
    AND a.Status = 'Pending'
    AND t.SlotDate >= CURDATE()
    ORDER BY t.SlotDate ASC, t.StartTime ASC";  // Ensure it's ordered by date and time

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);  // Bind the user_id to the query
$stmt->execute();
$appointments_result = $stmt->get_result();

// Query to fetch pets of the logged-in user
// Query to fetch adopted pets of the logged-in user
$sql = "
    SELECT a.animalid, a.name, a.age, a.gender, a.healthstatus, a.adoptionstatus, a.photo, a.location, 
           s.speciesname, b.breedname 
    FROM animals a
    JOIN species s ON a.speciesid = s.speciesid
    JOIN breeds b ON a.breedid = b.breedid
    JOIN adoptionrequests ar ON a.animalid = ar.animalid
    WHERE ar.requesterid = ? AND ar.status = 'Approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);  // Bind logged-in user's ID
$stmt->execute();
$pets_result = $stmt->get_result();

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);  // Bind the user_id to the query
$stmt->execute();
$pets_result = $stmt->get_result();

$stmt->close();

// Now you're sure that only users with the "User" role can access this page



// Fetch incoming adoption requests for user's pets
$sql = "
    SELECT ar.RequestID, ar.Message, ar.Status, ar.RequestDate,
           u.UserID AS RequesterID, u.Name AS RequesterName, u.Email, u.Phone,
           a.AnimalID, a.Name AS AnimalName, a.Photo, a.AdoptionStatus
    FROM adoptionrequests ar
    JOIN animals a ON ar.AnimalID = a.AnimalID
    JOIN users u ON ar.RequesterID = u.UserID
    WHERE a.OwnerID = ? AND ar.Status = 'Pending'
    ORDER BY ar.RequestDate DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$adoption_requests_result = $stmt->get_result();

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Pawsome Adoptions</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* Reuse the same color palette */
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
        }

        /* Dashboard specific styles */
        .dashboard-nav {
            background-color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .nav-item {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .nav-item:hover {
            background-color: var(--mint);
            border-left: 4px solid var(--mustard);
        }

        .nav-item.active {
            background-color: var(--mint);
            border-left: 4px solid var(--mustard);
        }

        .stat-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--mustard);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .pet-card {
            transition: all 0.3s ease;
            border: 2px solid var(--mint);
        }

        .pet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--mustard);
        }

        .message-card {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .message-card.unread {
            border-left: 4px solid var(--mustard);
            background-color: rgba(210, 147, 31, 0.05);
        }

        .message-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .appointment-card {
            border: 2px solid var(--mint);
            transition: all 0.3s ease;
        }

        .appointment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-color: var(--mustard);
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
        }

        .badge-primary {
            background-color: var(--mustard);
            color: white;
        }

        .badge-secondary {
            background-color: var(--teal);
            color: white;
        }

        .badge-warning {
            background-color: var(--sand);
            color: var(--dark-text);
        }

        .tab-button {
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab-button.active {
            border-bottom: 3px solid var(--mustard);
            color: var(--mustard);
            font-weight: bold;
        }

        .btn-primary {
            @apply bg-[color:var(--mustard)] text-white px-6 py-3 rounded-full font-bold transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-transparent hover:border-white;
        }

        .btn-secondary {
            @apply bg-white text-[color:var(--mustard)] px-6 py-3 rounded-full font-bold transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-[color:var(--mustard)];
        }

        .btn-small {
            @apply px-4 py-2 text-sm;
        }

        /* Animation for new notifications */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .notification-pulse {
            animation: pulse 1.5s infinite;
        }
    </style>
</head>

<body class="text-[color:var(--dark-text)] bg-[color:var(--cream)]">
    <!-- Header -->
    <header class="w-full bg-white py-4 shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-6 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <img src="/animora/assets/images/logo2.png" alt="Pawsome Adoptions Logo" class="h-12 w-auto">
                <span class="ml-3 text-xl font-bold text-[color:var(--mustard)] hidden md:block">Pawsome
                    Adoptions</span>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button class="p-2 rounded-full hover:bg-[color:var(--mint)] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[color:var(--mustard)]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            class="absolute top-0 right-0 h-4 w-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center notification-pulse">3</span>
                    </button>
                </div>
                <div class="relative group">
                    <button class="flex items-center space-x-2 focus:outline-none">
                        <img src="../<?php echo htmlspecialchars($profile_photo); ?>" alt="User Profile"
                            class="h-10 w-10 rounded-full border-2 border-[color:var(--mustard)]">

                        <span class="hidden md:inline-block font-medium">
                            <?php echo htmlspecialchars($user_name); ?>
                        </span>

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[color:var(--mustard)]"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-[color:var(--mint)]">Profile</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-[color:var(--mint)]">Settings</a>
                        <a href="/animora/auth/logout.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-[color:var(--mint)]">Sign
                            out</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Spacer for fixed header -->
    <div class="h-20"></div>

    <!-- Dashboard Layout -->
    <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row gap-6">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 flex-shrink-0 dashboard-nav rounded-2xl p-4 h-fit sticky top-24">
            <nav class="space-y-2">
                <div class="px-3 py-2 text-sm font-medium text-gray-500 uppercase tracking-wider">
                    Main Menu
                </div>
                <a href="#" class="nav-item active flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Dashboard
                </a>
                <a href="user-vet-appoint-view.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Appointments
                </a>
                <a href="user-adoption.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Adoption
                </a>
                <a href="user-post-adoption.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Post Adoption
                </a>
                <a href="/animora/user/marketplacehome.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Marketplace
                </a>

                <a href="service-home.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Pet Services
                </a>

                <a href="#" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    Articles
                </a>
                <a href="#" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Messages
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">5</span>
                </a>

                <div class="px-3 py-2 text-sm font-medium text-gray-500 uppercase tracking-wider mt-6">
                    Account
                </div>
                <a href="#" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.527.272 1.033.564 1.543.944z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
                <a href="/animora/auth/logout.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1">
            <!-- Welcome Banner -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-[color:var(--mustard)]">
                            Welcome back, <?php echo htmlspecialchars($user_name); ?>!
                        </h1>


                        <p class="text-gray-600">Here's what's happening with your pet adoption journey today.</p>
                    </div>
                    <a href="user-post-adoption.php"> <button class="btn-primary mt-4 md:mt-0">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                New Adoption Post
                            </span>
                        </button>
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="stat-card bg-white rounded-2xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-[color:var(--mint)] text-[color:var(--mustard)] mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500">Active Adoptions</p>
                            <h3 class="text-2xl font-bold">5</h3>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white rounded-2xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-[color:var(--mint)] text-[color:var(--mustard)] mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500">Upcoming Appointments</p>
                            <h3 class="text-2xl font-bold"><?php echo $upcoming_appointments; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white rounded-2xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-[color:var(--mint)] text-[color:var(--mustard)] mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500">Marketplace Orders</p>
                            <h3 class="text-2xl font-bold">2</h3>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white rounded-2xl shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-[color:var(--mint)] text-[color:var(--mustard)] mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500">Unread Messages</p>
                            <h3 class="text-2xl font-bold">5</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex overflow-x-auto mb-6 bg-white rounded-2xl shadow-md">
                <button class="tab-button active px-6 py-4 text-sm font-medium">Dashboard</button>
                <button class="tab-button px-6 py-4 text-sm font-medium">Adoptions</button>
                <button class="tab-button px-6 py-4 text-sm font-medium">Appointments</button>
                <button class="tab-button px-6 py-4 text-sm font-medium">Marketplace</button>
                <button class="tab-button px-6 py-4 text-sm font-medium">Messages</button>
            </div>



            <?php if ($adoption_requests_result->num_rows > 0): ?>
                <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-[color:var(--mustard)] mb-4">Incoming Adoption Requests</h2>

                    <div class="space-y-6">
                        <?php while ($request = $adoption_requests_result->fetch_assoc()): ?>
                            <div
                                class="border border-gray-200 rounded-lg p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <p class="text-lg font-semibold text-gray-800">
                                        <?= htmlspecialchars($request['RequesterName']) ?> wants to adopt
                                        <strong><?= htmlspecialchars($request['AnimalName']) ?></strong>
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">Email: <?= htmlspecialchars($request['Email']) ?> |
                                        Phone: <?= htmlspecialchars($request['Phone']) ?></p>
                                    <p class="text-sm text-gray-600 mt-1 italic">
                                        "<?= htmlspecialchars($request['Message'] ?: 'No message provided.') ?>"</p>
                                    <p class="text-xs text-gray-500 mt-1">Requested on:
                                        <?= htmlspecialchars($request['RequestDate']) ?>
                                    </p>
                                </div>

                                <div class="flex gap-2">
                                    <form method="POST" action="process-approve.php">
                                        <input type="hidden" name="request_id" value="<?= $request['RequestID'] ?>">
                                        <input type="hidden" name="animal_id" value="<?= $request['AnimalID'] ?>">
                                        <button type="submit" class="btn-primary btn-small">Approve</button>
                                    </form>

                                    <form method="POST" action="process-decline.php">
                                        <input type="hidden" name="request_id" value="<?= $request['RequestID'] ?>">
                                        <button type="submit"
                                            class="btn-secondary btn-small text-red-600 border-red-500 hover:bg-red-50">Decline</button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-6">
                    <p class="text-yellow-800">You have no pending adoption requests at the moment.</p>
                </div>
            <?php endif; ?>



            <!-- Recent Activity Section -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-[color:var(--mustard)]">Recent Activity</h2>
                    <button class="text-sm text-[color:var(--mustard)] hover:underline">View All</button>
                </div>

                <div class="space-y-4">
                    <!-- Activity Item -->
                    <div class="flex items-start p-4 hover:bg-[color:var(--cream)] rounded-lg transition">
                        <div class="flex-shrink-0 mr-4">
                            <div
                                class="h-10 w-10 rounded-full bg-[color:var(--mint)] flex items-center justify-center text-[color:var(--mustard)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium">New adoption request for <span
                                        class="text-[color:var(--mustard)]">Buddy</span></p>
                                <span class="text-xs text-gray-500">2 hours ago</span>
                            </div>
                            <p class="text-sm text-gray-600">John D. wants to adopt your pet Buddy. Please review the
                                request.</p>
                        </div>
                    </div>

                    <!-- Activity Item -->
                    <div class="flex items-start p-4 hover:bg-[color:var(--cream)] rounded-lg transition">
                        <div class="flex-shrink-0 mr-4">
                            <div
                                class="h-10 w-10 rounded-full bg-[color:var(--mint)] flex items-center justify-center text-[color:var(--mustard)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium">Appointment confirmed</p>
                                <span class="text-xs text-gray-500">Yesterday</span>
                            </div>
                            <p class="text-sm text-gray-600">Your vet appointment for Luna is confirmed for June 25 at
                                2:00 PM.</p>
                        </div>
                    </div>

                    <!-- Activity Item -->
                    <div class="flex items-start p-4 hover:bg-[color:var(--cream)] rounded-lg transition">
                        <div class="flex-shrink-0 mr-4">
                            <div
                                class="h-10 w-10 rounded-full bg-[color:var(--mint)] flex items-center justify-center text-[color:var(--mustard)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="font-medium">Order shipped</p>
                                <span class="text-xs text-gray-500">2 days ago</span>
                            </div>
                            <p class="text-sm text-gray-600">Your order #PAW-12345 has been shipped and will arrive
                                soon.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Displaying user profile and pets -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-[color:var(--mustard)]">Your Pets</h2>
                    <button class="btn-secondary btn-small">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Pet
                        </span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if ($pets_result->num_rows > 0): ?>
                        <?php while ($pet = $pets_result->fetch_assoc()) { ?>
                            <div class="pet-card bg-white rounded-xl p-4">
                                <div class="relative h-40 mb-4 rounded-lg overflow-hidden">
                                    <img src="<?php echo $pet['photo']; ?>" alt="<?php echo $pet['name']; ?>"
                                        class="w-full h-full object-cover">
                                    <div class="absolute top-2 right-2">
                                        <span class="badge badge-primary"><?php echo $pet['adoptionstatus']; ?></span>
                                    </div>
                                </div>
                                <h3 class="text-xl font-bold mb-1"><?php echo $pet['name']; ?></h3>
                                <p class="text-sm text-gray-600 mb-3"><?php echo $pet['speciesname']; ?> •
                                    <?php echo $pet['age']; ?> years old
                                </p>
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <span class="badge badge-secondary"><?php echo $pet['gender']; ?></span>
                                        <span class="badge badge-warning"><?php echo $pet['location']; ?></span>
                                    </div>
                                    <button class="btn-secondary btn-small">
                                        <span class="flex items-center gap-1">View</span>
                                    </button>
                                </div>
                            </div>
                        <?php } ?>
                    <?php else: ?>
                        <p class="text-gray-500 italic">You haven’t adopted any pets yet.</p>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Displaying Upcoming Appointments -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-[color:var(--mustard)]">Upcoming Appointments
                    (<?php echo $upcoming_appointments; ?>)</h2>
                <?php while ($appointment = $appointments_result->fetch_assoc()) { ?>
                    <div class="appointment-card bg-white p-4 rounded-xl mb-4">
                        <h3 class="text-xl font-bold"><?php echo $appointment['PetName']; ?></h3>
                        <p><strong>Reason:</strong> <?php echo $appointment['Reason']; ?></p>
                        <p><strong>Date:</strong> <?php echo $appointment['SlotDate']; ?> <strong>Time:</strong>
                            <?php echo $appointment['StartTime']; ?> - <?php echo $appointment['EndTime']; ?></p>
                        <p><strong>Status:</strong> <?php echo $appointment['Status']; ?></p>
                    </div>
                <?php } ?>
            </div>

            <!-- Recent Messages Section -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-[color:var(--mustard)]">Recent Messages</h2>
                    <button class="btn-secondary btn-small">
                        <span class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            New Message
                        </span>
                    </button>
                </div>

                <div class="space-y-3">
                    <!-- Message Card -->
                    <div class="message-card unread bg-white rounded-lg p-4">
                        <div class="flex items-start">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="John D."
                                class="h-10 w-10 rounded-full mr-4">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="font-bold">John D.</h3>
                                    <span class="text-xs text-gray-500">10 min ago</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Hi Sarah! I'm very interested in adopting Buddy.
                                    Can we schedule a meetup?</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-[color:var(--mustard)]">Adoption: Buddy</span>
                                    <button class="text-xs text-[color:var(--mustard)] hover:underline">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Card -->
                    <div class="message-card unread bg-white rounded-lg p-4">
                        <div class="flex items-start">
                            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Dr. Smith"
                                class="h-10 w-10 rounded-full mr-4">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="font-bold">Dr. Smith</h3>
                                    <span class="text-xs text-gray-500">1 hour ago</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Just confirming your appointment for Luna on June
                                    25 at 2:00 PM.</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-[color:var(--mustard)]">Appointment: Annual Checkup</span>
                                    <button class="text-xs text-[color:var(--mustard)] hover:underline">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Card -->
                    <div class="message-card bg-white rounded-lg p-4">
                        <div class="flex items-start">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Pawsome Support"
                                class="h-10 w-10 rounded-full mr-4">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="font-bold">Pawsome Support</h3>
                                    <span class="text-xs text-gray-500">2 days ago</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Your order #PAW-12345 has been shipped and will
                                    arrive in 2-3 business days.</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-[color:var(--mustard)]">Order: #PAW-12345</span>
                                    <button class="text-xs text-[color:var(--mustard)] hover:underline">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Simple tab functionality
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.tab-button');

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Remove active class from all buttons
                    tabButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    this.classList.add('active');

                    // Here you would typically load the corresponding content
                    // For this example, we're just changing the button state
                });
            });

            // Nav item active state
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                item.addEventListener('click', function () {
                    // Remove active class from all items
                    navItems.forEach(nav => nav.classList.remove('active'));

                    // Add active class to clicked item
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>

</html>