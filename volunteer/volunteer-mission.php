<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'Volunteer') {
    header("Location: /animora/auth/login.php");
    exit();
}

$volunteerID = $_SESSION["user_id"];

// Fetch accepted missions
$stmt = $conn->prepare("
    SELECT r.*, rv.Status AS VolunteerStatus
    FROM rescuerolunteers rv
    JOIN rescuereports r ON rv.ReportID = r.ReportID
    WHERE rv.VolunteerID = ? AND rv.Status != 'Declined'
    ORDER BY rv.RescueVolunteerID DESC
");
$stmt->bind_param("i", $volunteerID);
$stmt->execute();
$result = $stmt->get_result();
$missions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Active Missions - PetPal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        @keyframes peek-icon {

            0%,
            100% {
                transform: translateX(-140px);
                opacity: 0.6;
            }

            10%,
            90% {
                transform: translateX(0);
                opacity: 1;
            }

            50% {
                transform: translateX(10px);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-peek-icon {
            animation: peek-icon 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-white font-sans text-gray-800">

    <!-- 🐱 Peeking Cat Icon -->
    <div class="fixed left-0 bottom-10 z-50 pointer-events-none">
        <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Peeking Cat"
            class="w-32 h-32 animate-peek-icon" />
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-20 bg-[#E17C56] flex flex-col items-center py-6 space-y-8 rounded-r-3xl shadow-md">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/paw.png" class="w-8 h-8" alt="Logo" />
            <a class="flex flex-col gap-6">
                <a href="volunteer-dashboard.php"> <img src="https://img.icons8.com/ios-glyphs/30/ffffff/home.png"
                        alt="Dashboard icon" title="Dashboard" /> </a>
                <a href="volunteer-available-mission.php"> <img
                        src="https://img.icons8.com/ios-glyphs/30/ffffff/cat.png" alt="Rescue icon" title="Rescue" />
                </a>
                <a href="volunteer-mission.php"> <img src="https://img.icons8.com/ios-glyphs/30/ffffff/task.png"
                        alt="My Missions icon" title="My Missions" /> </a>

                <a href="volunteer-top-list.php"> <img src="https://img.icons8.com/ios-glyphs/30/ffffff/settings.png"
                        alt="Top Volunteers icon" title="Top Volunteers" /> </a>

                <a href="/animora/auth/logout.php" title="Logout" style="margin-top:auto;">
                    <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded-left.png" alt="Logout icon" />
                </a>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 bg-[#FFF7F5] p-8 flex flex-col overflow-y-auto">

            <!-- Page Header -->
            <div class="bg-[#E17C56] text-white rounded-3xl p-6 flex justify-between items-center shadow">
                <div>
                    <h2 class="text-2xl font-semibold">Your Active Missions</h2>
                    <p class="mt-1 text-orange-200">Helping furry friends one mission at a time 🐾</p>
                </div>
                <img src="https://img.icons8.com/emoji/96/cat-face.png" class="w-20 h-20 animate-float" alt="Cat" />
            </div>

            <!-- Active Missions List -->
            <section class="mt-8 space-y-8 max-w-4xl mx-auto">
                <?php foreach ($missions as $mission): ?>
                    <article class="bg-white rounded-3xl p-6 shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-2xl font-bold text-[#E17C56]">🐾
                                <?= htmlspecialchars($mission['Description']) ?>
                            </h3>
                            <form method="POST" action="update_mission_status.php">
                                <input type="hidden" name="report_id" value="<?= $mission['ReportID'] ?>">
                                <select name="status" onchange="this.form.submit()"
                                    class="border border-gray-300 rounded-xl px-4 py-2 text-gray-700 font-semibold cursor-pointer">
                                    <option <?= $mission['VolunteerStatus'] == 'Accepted' ? 'selected' : '' ?>>Accepted
                                    </option>
                                    <option <?= $mission['VolunteerStatus'] == 'Going to Venue' ? 'selected' : '' ?>>Going to
                                        Venue</option>
                                    <option <?= $mission['VolunteerStatus'] == 'On Scene' ? 'selected' : '' ?>>On Scene
                                    </option>
                                    <option <?= $mission['VolunteerStatus'] == 'In Progress' ? 'selected' : '' ?>>In Progress
                                    </option>
                                    <option <?= $mission['VolunteerStatus'] == 'Completed' ? 'selected' : '' ?>>Completed
                                    </option>
                                    <option <?= $mission['VolunteerStatus'] == 'Failed' ? 'selected' : '' ?>>Failed</option>
                                </select>
                            </form>
                        </div>

                        <div class="text-gray-700 space-y-2 text-lg">
                            <p><span class="font-semibold">Reported:</span>
                                <?= date('g:i A, F j, Y', strtotime($mission['ReportedAt'])) ?></p>
                            <p><span class="font-semibold">Location:</span> <?= htmlspecialchars($mission['Location']) ?>,
                                <?= htmlspecialchars($mission['Area']) ?>, <?= htmlspecialchars($mission['Division']) ?>
                            </p>
                            <p><span class="font-semibold">Needs:</span> <?= htmlspecialchars($mission['Description']) ?>
                            </p>
                            <p><span class="font-semibold">Status:</span>
                                <?= htmlspecialchars($mission['VolunteerStatus']) ?></p>
                        </div>

                        <div class="mt-6 space-y-4">
                            <label class="block font-semibold text-gray-800">Mission Notes / Updates</label>
                            <textarea rows="3"
                                class="w-full border border-gray-300 rounded-lg p-3 resize-y focus:outline-none focus:ring-2 focus:ring-[#E17C56]"
                                placeholder="Add any notes or updates here..."></textarea>

                            <div class="flex justify-end gap-4">
                                <button
                                    class="px-5 py-2 bg-[#E17C56] text-white font-semibold rounded-xl shadow hover:bg-[#ca6a48] transition">
                                    Update Progress
                                </button>
                                <button
                                    class="px-5 py-2 bg-green-500 text-white font-semibold rounded-xl shadow hover:bg-green-600 transition">
                                    Mark as Complete
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>

        </main>
    </div>

</body>

</html>