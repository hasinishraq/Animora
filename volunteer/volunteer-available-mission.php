<?php
session_start();
include '../config/db.php';

// ✅ Access Control: Only logged-in volunteers allowed
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'Volunteer') {
    header("Location: /animora/auth/login.php");
    exit();
}

// ✅ Fetch volunteer name
$volunteerName = "Volunteer"; // Default fallback
$userID = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT Name FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($name);
if ($stmt->fetch()) {
    $volunteerName = ucfirst(htmlspecialchars($name));
}
$stmt->close();

// ✅ Handle filter logic
$selectedDivision = $_GET['division'] ?? '';
$selectedArea = $_GET['area'] ?? '';
$reports = [];

if ($selectedDivision && $selectedArea) {
    $stmt = $conn->prepare("SELECT * FROM rescuereports WHERE Division = ? AND Area = ? ORDER BY ReportedAt DESC");
    $stmt->bind_param("ss", $selectedDivision, $selectedArea);
} elseif ($selectedDivision) {
    $stmt = $conn->prepare("SELECT * FROM rescuereports WHERE Division = ? ORDER BY ReportedAt DESC");
    $stmt->bind_param("s", $selectedDivision);
} else {
    $stmt = $conn->prepare("SELECT * FROM rescuereports ORDER BY ReportedAt DESC");
}

$stmt->execute();
$result = $stmt->get_result();
$reports = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Available Missions - PetPal</title>
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

        <main class="flex-1 bg-[#FFF7F5] p-8 overflow-y-auto">
            <div class="bg-[#E17C56] text-white rounded-3xl p-6 flex justify-between items-center shadow">
                <div>
                    <h2 class="text-2xl font-semibold">Available Missions in Bangladesh</h2>
                    <p class="mt-1 text-orange-200">Filter by location to find rescue missions near you 🐾</p>
                </div>
                <img src="https://img.icons8.com/emoji/96/cat-face.png" class="w-20 h-20 animate-float" alt="Cat" />
            </div>

            <!-- Filters -->
            <form method="GET">
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl">
                    <select name="division" class="p-3 rounded-xl border border-gray-300 bg-white shadow" required>
                        <option disabled <?= !isset($_GET['division']) ? 'selected' : '' ?>>Select Division</option>
                        <?php
                        $divisions = ['Dhaka', 'Chattogram', 'Khulna', 'Rajshahi', 'Barisal', 'Sylhet', 'Mymensingh', 'Rangpur'];
                        foreach ($divisions as $division) {
                            $selected = (isset($_GET['division']) && $_GET['division'] == $division) ? 'selected' : '';
                            echo "<option value=\"$division\" $selected>$division</option>";
                        }
                        ?>
                    </select>

                    <select name="area" class="p-3 rounded-xl border border-gray-300 bg-white shadow" required>
                        <option disabled <?= !isset($_GET['area']) ? 'selected' : '' ?>>Select Area</option>
                        <?php
                        $areas = ['Dhanmondi', 'Gulshan', 'Mirpur', 'Banani', 'Mohakhali'];
                        foreach ($areas as $area) {
                            $selected = (isset($_GET['area']) && $_GET['area'] == $area) ? 'selected' : '';
                            echo "<option value=\"$area\" $selected>$area</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mt-4 text-right max-w-4xl">
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600">Filter</button>
                </div>
            </form>


            <!-- Mission Cards -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php if (!empty($selectedDivision) && !empty($selectedArea)): ?>
                    <?php if (count($reports) > 0): ?>
                        <?php foreach ($reports as $report): ?>
                            <div class="bg-white rounded-3xl shadow-lg p-6">
                                <img src="<?= htmlspecialchars($report['Photo']) ?>" alt="Rescue Photo"
                                    class="w-full h-48 object-cover rounded-xl mb-4">

                                <h3 class="text-xl font-bold text-[#E17C56] mb-2">
                                    🐾 Report in <?= htmlspecialchars($report['Area']) ?>
                                </h3>

                                <p class="text-gray-600 text-sm mb-2">
                                    Reported at: <?= date("h:i A", strtotime($report['ReportedAt'])) ?>
                                </p>

                                <p class="text-gray-700 mb-3"><?= htmlspecialchars($report['Description']) ?></p>

                                <div class="text-sm text-gray-500 mb-3">
                                    Location: <?= htmlspecialchars($report['Location']) ?>
                                </div>

                                <div class="mb-4">
                                    <iframe class="w-full h-48 rounded-xl"
                                        src="https://www.google.com/maps?q=<?= urlencode($report['Location']) ?>&output=embed"
                                        allowfullscreen="" loading="lazy"></iframe>
                                </div>

                                <form method="POST" action="volunteer-accept-mission.php" class="flex justify-end gap-3">
                                    <input type="hidden" name="report_id" value="<?= $report['ReportID'] ?>">
                                    <button name="action" value="accept"
                                        class="bg-green-500 text-white px-4 py-2 rounded-xl hover:bg-green-600">Accept</button>
                                    <button name="action" value="decline"
                                        class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600">Decline</button>
                                </form>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="col-span-2 text-gray-500">No reports found for <?= htmlspecialchars($selectedArea) ?> in
                            <?= htmlspecialchars($selectedDivision) ?>.
                        </p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="col-span-2 text-gray-400">Please select a Division and Area to view rescue reports.</p>
                <?php endif; ?>
            </div>

        </main>
    </div>
</body>

</html>