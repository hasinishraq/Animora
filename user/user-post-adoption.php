<?php
session_start();
include '../config/db.php';

// Protect route if user not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /animora/auth/login.php');
    exit();
}

// Fetch user info (optional)
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'] ?? 'User';
$profile_photo = $_SESSION['profile_photo'] ?? 'assets/default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Post for Adoption</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --mustard: #E39F1F;
            --mint: #C2D4C8;
            --cream: #fdfaf3;
            --dark-text: #333333;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
        }
    </style>
</head>

<body class="text-[color:var(--dark-text)] bg-[color:var(--cream)]">

    <!-- Header -->
    <header class="w-full bg-white py-4 shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-6 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <img src="/animora/assets/images/logo2.png" alt="Logo" class="h-12 w-auto">
                <span class="ml-3 text-xl font-bold text-[color:var(--mustard)] hidden md:block">Pawsome
                    Adoptions</span>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <div class="relative group">
                    <button class="flex items-center space-x-2 focus:outline-none">
                        <img src="../<?php echo htmlspecialchars($profile_photo); ?>" alt="User"
                            class="h-10 w-10 rounded-full border-2 border-[color:var(--mustard)]">
                        <span class="hidden md:inline-block font-medium">
                            <?php echo htmlspecialchars($user_name); ?>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[color:var(--mustard)]"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.25 7.75l4.5 4.5 4.5-4.5" stroke="currentColor" stroke-width="1.5" fill="none" />
                        </svg>
                    </button>
                    <div
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-[color:var(--mint)]">Profile</a>
                        <a href="/animora/auth/logout.php"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-[color:var(--mint)]">Sign out</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Spacer for header -->
    <div class="h-20"></div>

    <!-- Main Layout -->
    <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row gap-6">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 flex-shrink-0 dashboard-nav rounded-2xl p-4 h-fit sticky top-24">
            <nav class="space-y-2">
                <div class="px-3 py-2 text-sm font-medium text-gray-500 uppercase tracking-wider">
                    Main Menu
                </div>
                <a href="user-home.php" class="nav-item active flex items-center px-3 py-3 rounded-md">
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

        <!-- Main Form Content -->
        <main class="flex-1 bg-white rounded-2xl p-8 shadow-md border border-[color:var(--mint)]">
            <h2 class="text-2xl font-bold text-[color:var(--mustard)] mb-6">🐾 Post a Pet for Adoption</h2>

            <form action="submit-adoption.php" method="POST" enctype="multipart/form-data" class="space-y-5">

                <!-- Pet Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pet Name</label>
                    <input type="text" name="pet_name" required
                        class="w-full border border-[color:var(--mint)] rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)]" />
                </div>

                <!-- Pet Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pet Type</label>
                    <select name="pet_type" required
                        class="w-full border border-[color:var(--mint)] rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)]">
                        <option value="">Select type</option>
                        <option>Dog</option>
                        <option>Cat</option>
                        <option>Rabbit</option>
                        <option>Bird</option>
                        <option>Other</option>
                    </select>
                </div>

                <!-- Breed -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Breed</label>
                    <input type="text" name="breed"
                        class="w-full border border-[color:var(--mint)] rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)]" />
                </div>

                <!-- Age -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Age (in years)</label>
                    <input type="number" name="age" step="0.1" required
                        class="w-full border border-[color:var(--mint)] rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)]" />
                </div>


                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select name="gender" required
                        class="w-full border border-[color:var(--mint)] rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)]">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>


                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" required
                        class="w-full border border-[color:var(--mint)] rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)]"
                        placeholder="Describe the pet's personality, health condition, etc."></textarea>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pet Photo</label>
                    <input type="file" name="photo" accept="image/*" required
                        class="w-full border border-[color:var(--mint)] rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)]" />
                </div>

                <!-- Submit Button -->
                <div class="text-right">
                    <button type="submit"
                        class="bg-[color:var(--mustard)] text-white px-6 py-2 rounded-full shadow hover:bg-opacity-90 transition">
                        📤 Submit for Adoption
                    </button>
                </div>
            </form>
        </main>
    </div>

</body>

</html>