<?php
session_start();
include '../config/db.php';

// Check if the user is logged in and if the role is "Volunteer"
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'Volunteer') {
    header("Location: /animora/auth/login.php");
    exit();
}

$userID = $_SESSION["user_id"];
$volunteerName = "Volunteer";
$profilePhoto = "https://i.pravatar.cc/100"; // Default photo
$userDivision = "";
$userArea = "";
$missionsCompleted = 0;
$acceptedMissionsCount = 0;

// Fetch volunteer details
$stmt = $conn->prepare("SELECT Name, Division, Area, ProfilePhoto FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($name, $division, $area, $photo);

if ($stmt->fetch()) {
    $volunteerName = htmlspecialchars($name);
    $userDivision = $division;
    $userArea = $area;
    if (!empty($photo)) {
        $profilePhoto = htmlspecialchars($photo);
    }
}
$stmt->close();

// Fetch up to 5 available missions in volunteer's division and area (excluding accepted/declined)
$missions = [];
$missionQuery = $conn->prepare("
    SELECT * FROM rescuereports 
    WHERE Division = ? AND Area = ? 
    AND ReportID NOT IN (
        SELECT ReportID FROM rescuerolunteers WHERE VolunteerID = ?
    )
    AND Status = 'Open'
    ORDER BY ReportedAt DESC
    LIMIT 5
");
$missionQuery->bind_param("ssi", $userDivision, $userArea, $userID);
$missionQuery->execute();
$result = $missionQuery->get_result();
$missions = $result->fetch_all(MYSQLI_ASSOC);
$missionQuery->close();

// Fetch all accepted missions (active)
$acceptedMissions = [];
$acceptedQuery = $conn->prepare("
    SELECT rr.*, rv.Status AS VolunteerStatus
    FROM rescuereports rr
    INNER JOIN rescuerolunteers rv ON rr.ReportID = rv.ReportID
    WHERE rv.VolunteerID = ?
    AND rv.Status IN ('Accepted', 'Going to Venue', 'On Scene', 'In Progress')
    ORDER BY rr.ReportedAt DESC
    LIMIT 5
");
$acceptedQuery->bind_param("i", $userID);
$acceptedQuery->execute();
$acceptedResult = $acceptedQuery->get_result();
$acceptedMissions = $acceptedResult->fetch_all(MYSQLI_ASSOC);
$acceptedQuery->close();

// Count total completed missions
$completedQuery = $conn->prepare("
    SELECT COUNT(*) FROM rescuerolunteers
    WHERE VolunteerID = ? AND Status = 'Completed'
");
$completedQuery->bind_param("i", $userID);
$completedQuery->execute();
$completedQuery->bind_result($missionsCompleted);
$completedQuery->fetch();
$completedQuery->close();

// Count how many missions currently accepted/in progress
$activeCountQuery = $conn->prepare("
    SELECT COUNT(*) FROM rescuerolunteers
    WHERE VolunteerID = ? AND Status IN ('Accepted', 'Going to Venue', 'On Scene', 'In Progress')
");
$activeCountQuery->bind_param("i", $userID);
$activeCountQuery->execute();
$activeCountQuery->bind_result($acceptedMissionsCount);
$activeCountQuery->fetch();
$activeCountQuery->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Volunteer Dashboard - PetPal</title>
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

    <!-- 🐱 Peeking Cat Icon (Flaticon) -->
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
        <main class="flex-1 bg-[#FFF7F5] p-8 flex gap-6 overflow-y-auto">

            <!-- Center Section -->
            <section class="flex-1">

                <!-- Welcome Section -->
                <div class="bg-[#E17C56] text-white rounded-3xl p-6 flex justify-between items-center shadow">
                    <div>
                        <h2 class="text-2xl font-semibold">
                            Welcome, <?php echo $volunteerName; ?>! 🐾
                        </h2>

                        <p class="mt-1">Ready to rescue some furry friends today?</p>
                    </div>
                    <img src="https://img.icons8.com/emoji/96/cat-face.png" class="w-20 h-20 animate-float" alt="Cat" />
                </div>

                <!-- Available Rescue Missions -->
                <!-- Available Rescue Missions -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-3 text-[#E17C56]">Available Rescue Missions</h3>
                    <div class="space-y-4">
                        <?php if (empty($missions)): ?>
                            <p class="text-gray-500">No missions available in your area right now.</p>
                        <?php else: ?>
                            <?php foreach ($missions as $mission): ?>
                                <div class="bg-white rounded-xl p-4 shadow-md flex justify-between items-center">
                                    <div>
                                        <p class="font-medium"><?= htmlspecialchars($mission['Description']) ?></p>
                                        <p class="text-sm text-gray-500">
                                            Reported: <?= date("g:i A", strtotime($mission['ReportedAt'])) ?> •
                                            <?= htmlspecialchars($mission['Location']) ?>
                                        </p>
                                    </div>
                                    <form method="POST" action="volunteer-accept-mission.php" class="flex gap-2">
                                        <input type="hidden" name="report_id" value="<?= (int) $mission['ReportID'] ?>">
                                        <button type="submit" name="action" value="accept"
                                            class="px-4 py-1 bg-green-500 text-white rounded-xl hover:bg-green-600">Accept</button>
                                        <button type="submit" name="action" value="decline"
                                            class="px-4 py-1 bg-red-500 text-white rounded-xl hover:bg-red-600">Decline</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- See All Button -->
                    <div class="mt-4 text-right">
                        <a href="volunteer-available-mission.php"
                            class="inline-block bg-[#E17C56] text-white px-5 py-2 rounded-xl hover:bg-[#c8653c] transition">
                            See All
                        </a>
                    </div>
                </div>


                <!-- Active Missions -->
                <!-- Your Active Missions -->
                <div class="mt-10">
                    <h3 class="text-lg font-semibold mb-3 text-[#E17C56]">Your Active Missions</h3>
                    <div class="space-y-4">
                        <?php if (empty($acceptedMissions)): ?>
                            <p class="text-gray-500">You have no active missions.</p>
                        <?php else: ?>
                            <?php foreach ($acceptedMissions as $mission): ?>
                                <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
                                    <div>
                                        <p class="font-medium"><?= htmlspecialchars($mission['Description']) ?></p>
                                        <p class="text-sm text-gray-500">Started:
                                            <?= date("g:i A, M j, Y", strtotime($mission['ReportedAt'])) ?>
                                        </p>
                                    </div>
                                    <select class="border border-gray-300 rounded px-3 py-1">
                                        <option <?= ($mission['VolunteerStatus'] === 'Accepted') ? 'selected' : '' ?>>Pending
                                        </option>
                                        <option <?= ($mission['VolunteerStatus'] === 'In Progress') ? 'selected' : '' ?>>In
                                            Progress</option>
                                        <option <?= ($mission['VolunteerStatus'] === 'Resolved' || $mission['VolunteerStatus'] === 'Closed') ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- See All Button -->
                    <div class="mt-4 text-right">
                        <a href="volunteer-mission.php"
                            class="inline-block bg-[#E17C56] text-white px-5 py-2 rounded-xl hover:bg-[#c8653c] transition">
                            See All
                        </a>
                    </div>
                </div>
            </section>

            <!-- Right Sidebar -->
            <aside class="w-80 bg-white rounded-3xl p-6 shadow shrink-0">
                <div class="flex items-center gap-4 mb-6">
                    <img src="<?= $profilePhoto ?>" class="w-14 h-14 rounded-full border-4 border-[#E17C56]" />

                    <div>
                        <h4 class="text-lg font-semibold"><?= $volunteerName ?></h4>
                        <p class="text-sm text-gray-500"><?= $userRole ?></p>
                    </div>
                </div>

                <div class="mb-6">
                    <h5 class="text-sm font-semibold text-[#E17C56] mb-2">Location Info</h5>
                    <div class="space-y-1 text-sm text-gray-700">
                        <p><strong>Division:</strong> <?= $userDivision ?></p>
                        <p><strong>Area:</strong> <?= $userArea ?></p>
                        <p><strong>Address:</strong> <?= $address ?></p>
                    </div>
                </div>

                <div class="mb-6">
                    <h5 class="text-sm font-semibold text-[#E17C56] mb-2">Mission Stats</h5>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span>Missions Done</span><span
                                class="font-bold text-blue-600"><?= $missionsCompleted ?></span></div>
                        <div class="flex justify-between"><span>Accepted Now</span><span
                                class="font-bold text-green-600"><?= $acceptedMissionsCount ?></span></div>
                        <!-- Uncomment if you add reward points later -->
                        <!-- <div class="flex justify-between"><span>Reward Points</span><span class="font-bold text-yellow-500">480</span></div> -->
                    </div>
                </div>

                <div>
                    <h5 class="text-sm font-semibold text-[#E17C56] mb-2">Next Event</h5>
                    <div class="flex items-center gap-2">
                        <img src="https://img.icons8.com/color/48/dog-park.png" class="w-10 h-10" />
                        <div class="text-sm">
                            <p class="font-medium">Dog Playdate Festival</p>
                            <p class="text-gray-500">June 25 – 10:00 AM</p>
                        </div>
                    </div>
                </div>
            </aside>


        </main>
    </div>

</body>

</html>