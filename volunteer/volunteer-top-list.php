<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: /animora/auth/login.php");
    exit();
}

$query = "
    SELECT u.UserID, u.Name, COUNT(rv.ReportID) AS CompletedMissions
    FROM users u
    JOIN rescuerolunteers rv ON u.UserID = rv.VolunteerID
    WHERE rv.Status = 'Completed'
    GROUP BY u.UserID, u.Name
    ORDER BY CompletedMissions DESC
    LIMIT 10
";

$result = $conn->query($query);
$topVolunteers = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Top Volunteers</title>
    <style>
        /* Reset & basics */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar styles */
        aside {
            width: 80px;
            background-color: #E17C56;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 0;
            gap: 32px;
            border-top-right-radius: 24px;
            border-bottom-right-radius: 24px;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.12);
        }

        aside img {
            width: 32px;
            height: 32px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        aside img:hover {
            transform: scale(1.2);
        }

        /* Main container next to sidebar */
        .container {
            flex-grow: 1;
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Heading */
        h1 {
            color: #E17C56;
            font-size: 28px;
            margin-bottom: 25px;
            text-align: center;
        }

        /* Volunteer cards */
        .volunteer-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fdfdfd;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease;
        }

        .volunteer-card:hover {
            transform: translateY(-3px);
        }

        .volunteer-info {
            display: flex;
            flex-direction: column;
        }

        .volunteer-name {
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        .volunteer-missions {
            font-size: 14px;
            color: #777;
        }

        .rank-badge {
            background-color: #E17C56;
            color: white;
            font-size: 14px;
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 20px;
            min-width: 40px;
            text-align: center;
        }

        /* Empty message */
        .empty {
            text-align: center;
            color: #888;
            font-size: 16px;
            padding: 40px 0;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside>
        <img src="https://img.icons8.com/ios-filled/50/ffffff/paw.png" alt="Logo" />
        <a href="volunteer-dashboard.php" title="Dashboard">
            <img src="https://img.icons8.com/ios-glyphs/30/ffffff/home.png" alt="Dashboard icon" />
        </a>
        <a href="volunteer-available-mission.php" title="Rescue">
            <img src="https://img.icons8.com/ios-glyphs/30/ffffff/cat.png" alt="Rescue icon" />
        </a>
        <a href="volunteer-mission.php" title="My Missions">
            <img src="https://img.icons8.com/ios-glyphs/30/ffffff/task.png" alt="My Missions icon" />
        </a>
        <a href="volunteer-top-list.php" title="Top Volunteers">
            <img src="https://img.icons8.com/ios-glyphs/30/ffffff/settings.png" alt="Top Volunteers icon" />
        </a>

        <a href="/animora/auth/logout.php" title="Logout" style="margin-top:auto;">
            <img src="https://img.icons8.com/ios-glyphs/30/ffffff/logout-rounded-left.png" alt="Logout icon" />
        </a>
    </aside>

    <!-- Main content -->
    <div class="container">
        <h1>🏆 Top Volunteers</h1>

        <?php if (empty($topVolunteers)): ?>
            <p class="empty">No volunteers have completed any missions yet.</p>
        <?php else: ?>
            <?php foreach ($topVolunteers as $index => $volunteer): ?>
                <div class="volunteer-card">
                    <div class="volunteer-info">
                        <span class="volunteer-name"><?= htmlspecialchars($volunteer['Name']) ?></span>
                        <span class="volunteer-missions"><?= (int) $volunteer['CompletedMissions'] ?> missions completed</span>
                    </div>
                    <span class="rank-badge">#<?= $index + 1 ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>