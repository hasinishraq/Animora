<?php
session_start();
require_once '../config/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /animora/auth/login.php");
    exit();
}

// Redirect if role is not Vet
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'vet') {
    header("Location: /animora/unauthorized.php");
    exit();
}

// Vet's user ID
$vetId = $_SESSION['user_id'];

// Fetch all appointments for this vet
$sql = "
    SELECT 
        a.AppointmentID,
        a.PetName,
        a.Reason,
        a.Status,
        u.Name AS OwnerName,
        t.SlotDate,
        t.StartTime,
        t.EndTime
    FROM appointments a
    JOIN users u ON a.UserID = u.UserID
    JOIN timeslots t ON a.SlotID = t.SlotID
    WHERE a.DoctorID = ?
    ORDER BY t.SlotDate ASC, t.StartTime ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vetId);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}
$stmt->close();
$conn->close();
?>




<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Vet Dashboard - Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom colors for your theme */
        :root {
            --warmTaupe: #9e7c67;
            --accent: #5c8d89;
            --pastelGreen: #a0d468;
            --pastelPink: #f78da7;
            --textPrimary: #3a3a3a;
            --textSecondary: #7b7b7b;
            --bgLight: #f9fafb;
            --secondary: #f0f4f8;
        }

        .text-textPrimary {
            color: var(--textPrimary);
        }

        .text-textSecondary {
            color: var(--textSecondary);
        }

        .bg-warmTaupe {
            background-color: var(--warmTaupe);
        }

        .bg-accent {
            background-color: var(--accent);
        }

        .bg-pastelGreen {
            background-color: var(--pastelGreen);
        }

        .bg-pastelPink {
            background-color: var(--pastelPink);
        }

        .bg-bgLight {
            background-color: var(--bgLight);
        }

        .bg-secondary {
            background-color: var(--secondary);
        }

        .border-warmTaupe {
            border-color: var(--warmTaupe);
        }

        .text-warmTaupe {
            color: var(--warmTaupe);
        }

        .text-accent {
            color: var(--accent);
        }

        .shadow-card {
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        /* Animations */
        @keyframes pawPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .animate-pawPulse {
            animation: pawPulse 2s ease-in-out infinite;
        }

        @keyframes pawBounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .animate-pawBounce {
            animation: pawBounce 1.5s ease-in-out infinite;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.5s ease forwards;
        }

        .delay-150 {
            animation-delay: 0.15s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-450 {
            animation-delay: 0.45s;
        }

        /* Scrollbar styling */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        /* Floating roaming cat and dog animations */
        @keyframes roam {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }

            25% {
                transform: translate(15px, -10px) rotate(10deg);
            }

            50% {
                transform: translate(-15px, 10px) rotate(-10deg);
            }

            75% {
                transform: translate(10px, 5px) rotate(5deg);
            }

            100% {
                transform: translate(0, 0) rotate(0deg);
            }
        }

        .animate-roam {
            animation: roam 6s ease-in-out infinite;
        }

        /* Positions and sizes of roaming icons */
        #roamingDog {
            position: fixed;
            bottom: 10%;
            left: 5%;
            font-size: 5rem;
            pointer-events: none;
            user-select: none;
            animation: roam 7s ease-in-out infinite alternate;
            z-index: 9999;
        }

        #roamingCat {
            position: fixed;
            top: 15%;
            right: 10%;
            font-size: 6rem;
            pointer-events: none;
            user-select: none;
            animation: roam 8s ease-in-out infinite alternate;
            animation-delay: 2s;
            z-index: 9999;
        }

        /* Hide the details initially */
        .appointment-details {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease;
        }

        /* Show details when active */
        .appointment-details.active {
            max-height: 500px;
            /* enough to show content */
            margin-top: 0.5rem;
        }
    </style>
</head>

<body class="bg-bgLight text-textPrimary font-sans min-h-screen flex">

    <!-- Sidebar navigation -->
    <aside class="w-64 bg-white shadow-sidebar sticky top-0 h-screen flex flex-col">
        <div class="px-6 py-8 flex items-center space-x-3 border-b border-gray-200">
            <div class="text-warmTaupe text-3xl font-semibold animate-pawBounce">🐾</div>
            <h1 class="text-2xl font-semibold font-montserrat tracking-wide text-warmTaupe">VetCare</h1>
        </div>

        <nav class="flex-1 px-6 py-8 space-y-2 text-sm font-semibold text-textSecondary">
            <a href="vet-dashboard.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg text-warmTaupe bg-warmTaupe/20 hover:bg-warmTaupe/40 transition shadow-glow">
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
                class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
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
                    class="rounded-full border-2 border-warmTaupe" />

                <div>
                    <p class="font-semibold text-textPrimary"><?php echo htmlspecialchars($user_name); ?></p>
                    <p class="text-xs text-textSecondary">Veterinarian</p>
                </div>
            </div>
        </div>

    </aside>

    <!-- Main content column -->
    <div class="flex-1 flex flex-col">

        <!-- Top Header -->
        <header class="flex justify-between items-center px-8 py-6 bg-white shadow-md sticky top-0 z-30">
            <div>
                <h2 class="text-2xl font-semibold text-textPrimary flex items-center gap-2">
                    Welcome back, Dr. Jane
                    <span class="animate-pawBounce text-warmTaupe text-3xl select-none">🐾</span>
                </h2>
                <p class="text-sm text-textSecondary mt-1">Here’s your pet‑care summary for today.</p>
            </div>

            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="search" placeholder="Search appointments or pets..."
                        class="rounded-full border border-gray-300 px-4 py-2 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-warmTaupe focus:border-transparent transition" />
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </div>
                <img src="https://i.pravatar.cc/40" alt="User avatar"
                    class="rounded-full w-10 h-10 border-2 border-warmTaupe" />
            </div>
        </header>

        <!-- Detail sections -->
        <main class="flex flex-col gap-12 p-8 max-w-7xl mx-auto">

            <!-- All Appointments details -->
            <section class="bg-white rounded-xl shadow-card p-8 animate-fadeInUp">
                <h3 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                    <span class="animate-pawBounce">🗓️</span> Upcoming Appointments
                </h3>

                <div class="space-y-6 max-h-[480px] overflow-y-auto scrollbar-thin">
                    <?php if (count($appointments) === 0): ?>
                        <p class="text-gray-500">No upcoming appointments found.</p>
                    <?php else: ?>
                        <?php foreach ($appointments as $appointment): ?>
                            <article
                                class="border border-warmTaupe rounded-lg p-6 hover:shadow-lg transition cursor-pointer flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="text-5xl select-none">🐾</div>
                                    <div>
                                        <p class="font-semibold text-lg"><?= htmlspecialchars($appointment['PetName']) ?>
                                            (<?= htmlspecialchars($appointment['OwnerName']) ?>)</p>
                                        <p class="text-textSecondary">Date:
                                            <?= date('F j, Y', strtotime($appointment['SlotDate'])) ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-textSecondary md:text-right md:flex md:flex-col md:items-end md:gap-1">
                                    <p><span class="font-semibold text-textPrimary">Time:</span>
                                        <?= date('g:i A', strtotime($appointment['StartTime'])) ?></p>
                                    <p><span class="font-semibold text-textPrimary">Purpose:</span>
                                        <?= htmlspecialchars($appointment['Reason']) ?></p>
                                </div>
                                <button
                                    class="view-details-btn bg-warmTaupe text-white rounded-full px-6 py-2 font-medium text-sm hover:bg-opacity-90 transition whitespace-nowrap">
                                    View Details
                                </button>
                                <div
                                    class="appointment-details text-textSecondary mt-2 md:mt-0 md:col-span-full max-w-full md:max-w-xs">
                                    Status: <?= htmlspecialchars($appointment['Status']) ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

        </main>
    </div>

    <!-- Roaming Dog -->
    <div id="roamingDog" aria-hidden="true">🐕</div>

    <!-- Roaming Cat -->
    <div id="roamingCat" aria-hidden="true">🐱</div>

    <script>
        // Attach toggle behavior to all view details buttons
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const detailsDiv = button.nextElementSibling;
                const isActive = detailsDiv.classList.toggle('active');
                button.textContent = isActive ? 'Hide Details' : 'View Details';
            });
        });
    </script>

</body>

</html>