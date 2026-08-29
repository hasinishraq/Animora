<?php
session_start();
include '../config/db.php'; // Make sure this path is correct relative to vet-manage-slots.php

// Check if the user is logged in and if the role is "Vet"
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'Vet') {
    // Redirect to the login page if the user is not logged in or role is not "Vet"
    header("Location: /animora/auth/login.php");  // Adjust the login page URL if necessary
    exit();  // Stop further script execution after redirection
}

// Fetch user's name and profile picture
$user_name = "Guest";
$profile_photo = "https://randomuser.me/api/portraits/men/32.jpg"; // Default profile picture

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("SELECT Name, ProfilePhoto FROM users WHERE UserID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_name = $user["Name"];
        // Only override default if a specific photo is set in the database
        if (!empty($user["ProfilePhoto"])) {
            $profile_photo = $user["ProfilePhoto"];
        }
    }

    // Handle form submission for adding a new appointment slot
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_appointment') {
        $new_date = $_POST['slot_date'] ?? '';
        $new_start_time = $_POST['start_time'] ?? '';
        $new_end_time = $_POST['end_time'] ?? '';

        if (!empty($new_date) && !empty($new_start_time) && !empty($new_end_time)) {
            // Check if the slot already exists for this date and time
            $stmt = $conn->prepare("SELECT * FROM timeslots WHERE SlotDate = ? AND StartTime = ? AND EndTime = ?");
            $stmt->bind_param("sss", $new_date, $new_start_time, $new_end_time);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['error_message'] = "This slot is already booked. Please select another time.";
                header("Location: vet-manage-slots.php");
                exit();
            } else {
                // Insert into the 'timeslots' table
                $stmt = $conn->prepare("INSERT INTO timeslots (DoctorID, SlotDate, StartTime, EndTime) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, $new_date, $new_start_time, $new_end_time);

                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "Appointment slot added successfully!";
                } else {
                    $_SESSION['error_message'] = "Error adding appointment. Please try again.";
                }

                $stmt->close();
                // Redirect to clear POST data and prevent re-submission on refresh
                header("Location: vet-manage-slots.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Please fill all fields for the new appointment slot.";
            header("Location: vet-manage-slots.php");
            exit();
        }
    }

    // Fetch existing slots from the database for the logged-in vet
    $stmt = $conn->prepare("SELECT SlotID, SlotDate, StartTime, EndTime FROM timeslots WHERE DoctorID = ? ORDER BY SlotDate, StartTime");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $existing_slots = [];
    while ($slot = $result->fetch_assoc()) {
        $existing_slots[] = $slot;
    }

    $stmt->close();
}

// Handle messages
$success_message = '';
$error_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Slots – VetCare Portal</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Poppins:wght@300;600&display=swap"
        rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3B82F6",
                        secondary: "#60A5FA",
                        accent: "#F97316",
                        pastelGreen: "#D1FAE5",
                        pastelPink: "#FCE7F3",
                        bgLight: "#F9FAFB",
                        textPrimary: "#111827",
                        textSecondary: "#6B7280",
                        warmTaupe: "#a89486", // main accent
                    },
                    fontFamily: {
                        montserrat: ["Montserrat", "sans-serif"],
                        poppins: ["Poppins", "sans-serif"],
                    },
                    boxShadow: {
                        card: "0 10px 15px -3px rgba(59,130,246,.1), 0 4px 6px -2px rgba(59,130,246,.05)",
                        sidebar: "2px 0 8px rgba(0,0,0,.1)",
                        glow: "0 0 12px 3px rgba(168,148,134,.6)",
                    },
                    animation: {
                        pawBounce: 'pawBounce 2s ease-in-out infinite',
                        pawPulse: 'pawPulse 3s ease-in-out infinite',
                        fadeInUp: 'fadeInUp .8s ease forwards',
                        roamCat: 'roamAround 30s ease-in-out infinite'
                    },
                    keyframes: {
                        pawBounce: {
                            '0%,100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        pawPulse: {
                            '0%,100%': { transform: 'scale(1)', opacity: '1' },
                            '50%': { transform: 'scale(1.1)', opacity: '.7' }
                        },
                        fadeInUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' }
                        },
                        roamAround: {
                            '0%': { transform: 'translate(-50%, -50%)' },
                            '20%': { transform: 'translate(-20vw, -40vh)' },
                            '40%': { transform: 'translate(25vw, -15vh)' },
                            '60%': { transform: 'translate(-10vw, 35vh)' },
                            '80%': { transform: 'translate(30vw, 10vh)' },
                            '100%': { transform: 'translate(-50%, -50%)' }
                        }
                    }
                }
            }
        };
    </script>

    <style>
        /* Thin custom scrollbar */
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #9ca3af transparent;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 6px;
        }
    </style>
</head>

<body class="bg-bgLight font-poppins text-textPrimary flex min-h-screen">

    <aside class="w-64 bg-white shadow-sidebar sticky top-0 h-screen flex flex-col">
        <div class="px-6 py-8 flex items-center space-x-3 border-b border-gray-200">
            <div class="text-warmTaupe text-3xl font-semibold animate-pawBounce">🐾</div>
            <h1 class="text-2xl font-semibold font-montserrat tracking-wide text-warmTaupe">VetCare</h1>
        </div>

        <nav class="flex-1 px-6 py-8 space-y-2 text-sm font-semibold text-textSecondary">
            <a href="vet-dashboard.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12l2-2 4 4 8-8 4 4v6H3z" />
                </svg>
                Dashboard
            </a>
            <a href="vet-appointment.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4m0-4h.01" />
                </svg>
                Appointments
            </a>
            <a href="vet-manage-slots.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg text-warmTaupe bg-warmTaupe/20 hover:bg-warmTaupe/40 transition shadow-glow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Manage Slots
            </a>
            <a href="vet-message.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H5l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                Messages
            </a>

            <a href="../auth/logout.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H5l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                Logout
            </a>

        </nav>

        <div class="p-6 border-t border-gray-200">
            <div class="flex items-center gap-3">
                <img src="<?php echo htmlspecialchars($profile_photo); ?>"
                    alt="<?php echo htmlspecialchars($user_name); ?> Avatar"
                    class="rounded-full w-10 h-10 border-2 border-warmTaupe" />

                <div>
                    <p class="font-semibold text-textPrimary"><?php echo htmlspecialchars($user_name); ?></p>
                    <p class="text-xs text-textSecondary">Veterinarian</p>
                </div>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">

        <header class="flex justify-between items-center px-8 py-6 bg-white shadow-md sticky top-0 z-30">
            <div>
                <h2 class="text-2xl font-semibold text-textPrimary flex items-center gap-2">
                    Manage Your Availability
                    <span class="animate-pawBounce text-warmTaupe text-3xl select-none">🐾</span>
                </h2>
                <p class="text-sm text-textSecondary mt-1">Add, edit, or remove your available appointment slots.</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="search" placeholder="Search slots..."
                        class="rounded-full border border-gray-300 px-4 py-2 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-warmTaupe focus:border-transparent transition" />
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </div>
                <img src="<?php echo htmlspecialchars($profile_photo); ?>"
                    alt="<?php echo htmlspecialchars($user_name); ?> Avatar"
                    class="rounded-full w-10 h-10 border-2 border-warmTaupe" />
            </div>
        </header>

        <main class="flex-1 p-8 max-w-7xl mx-auto w-full">

            <?php if ($success_message): ?>
                <div class="bg-pastelGreen text-green-700 px-4 py-3 rounded-lg shadow-md mb-6 animate-fadeInUp"
                    role="alert">
                    <p class="font-semibold">Success!</p>
                    <p class="text-sm"><?php echo htmlspecialchars($success_message); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg shadow-md mb-6 animate-fadeInUp" role="alert">
                    <p class="font-semibold">Error!</p>
                    <p class="text-sm"><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            <?php endif; ?>

            <section class="bg-white rounded-xl shadow-card p-8 mb-8 animate-fadeInUp">
                <h3 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                    <span class="animate-pawBounce">➕</span> Add New Appointment Slot
                </h3>

                <form action="vet-manage-slots.php" method="POST" class="space-y-6">
                    <input type="hidden" name="action" value="add_appointment">
                    <div>
                        <label for="slot_date" class="block text-sm font-medium text-textSecondary mb-1">Date:</label>
                        <input type="date" id="slot_date" name="slot_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-warmTaupe focus:ring-warmTaupe p-2 transition"
                            required>
                    </div>
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-textSecondary mb-1">Start
                            Time:</label>
                        <input type="time" id="start_time" name="start_time"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-warmTaupe focus:ring-warmTaupe p-2 transition"
                            required>
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-textSecondary mb-1">End
                            Time:</label>
                        <input type="time" id="end_time" name="end_time"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-warmTaupe focus:ring-warmTaupe p-2 transition"
                            required>
                    </div>
                    <button type="submit"
                        class="bg-warmTaupe text-white rounded-full px-8 py-3 font-semibold text-lg hover:bg-opacity-90 transition shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-warmTaupe focus:ring-opacity-75 animate-pawPulse">
                        Add Appointment
                    </button>
                </form>

            </section>

            <section class="bg-white rounded-xl shadow-card p-8 mb-8 animate-fadeInUp">
                <h3 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                    <span class="animate-pawBounce">🕒</span> Your Current Available Slots
                </h3>

                <?php if (empty($existing_slots)): ?>
                    <p class="text-textSecondary italic">You haven't added any slots yet. Add one above!</p>
                <?php else: ?>
                    <div
                        class="divide-y divide-gray-200 max-h-96 overflow-y-auto scrollbar-thin border border-gray-200 rounded-lg">
                        <?php foreach ($existing_slots as $slot): ?>
                            <article
                                class="flex justify-between items-center py-4 px-6 hover:bg-warmTaupe/10 rounded-lg transition cursor-pointer group">
                                <div>
                                    <p class="font-semibold text-textPrimary">
                                        <?php echo htmlspecialchars($slot['SlotDate']); ?> from
                                        <?php echo htmlspecialchars($slot['StartTime']); ?> to
                                        <?php echo htmlspecialchars($slot['EndTime']); ?>
                                    </p>
                                </div>
                                <div class="flex space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium px-3 py-1 rounded-md border border-blue-600 hover:border-blue-800 transition">
                                        Edit
                                    </button>
                                    <button
                                        class="text-red-600 hover:text-red-800 text-sm font-medium px-3 py-1 rounded-md border border-red-600 hover:border-red-800 transition">
                                        Delete
                                    </button>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

        </main>
    </div>

    <div id="floatingCat" class="fixed top-1/2 left-1/2 text-5xl select-none pointer-events-none animate-roamCat z-50"
        aria-hidden="true">
        🐱
    </div>

</body>

</html>