<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'User') {
    header("Location: /animora/auth/login.php");
    exit();
}

$categoryID = $_POST['category_id'] ?? $_GET['category_id'] ?? null;
if (!$categoryID) {
    header("Location: service-home.php");
    exit();
}
$categoryID = intval($categoryID);

// Fetch category info
$stmt = $conn->prepare("SELECT CategoryName, Description FROM servicecategories WHERE CategoryID = ?");
$stmt->bind_param("i", $categoryID);
$stmt->execute();
$stmt->bind_result($categoryName, $categoryDescription);
if (!$stmt->fetch()) {
    $stmt->close();
    header("Location: service-home.php");
    exit();
}
$stmt->close();

// Fetch all services in that category
$stmt = $conn->prepare("SELECT ServiceID, ServiceName, Description, Price, Duration FROM services WHERE CategoryID = ?");
$stmt->bind_param("i", $categoryID);
$stmt->execute();
$result = $stmt->get_result();
$services = [];
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($categoryName) ?> Services</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom right, #fff7f0, #fefefe);
            color: #333;
        }

        .hero {
            background: linear-gradient(120deg, #ffecd2 0%, #fcb69f 100%);
            padding: 70px 20px 100px;
            text-align: center;
            color: #4b0082;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.1rem;
            max-width: 650px;
            margin: 0 auto;
            color: #6b2f7b;
        }

        .wave-divider svg {
            display: block;
            width: 100%;
            height: 100px;
            fill: #fff;
        }

        .section {
            padding: 50px 20px;
            max-width: 1200px;
            margin: auto;
        }

        .section h2 {
            text-align: center;
            font-size: 2.3rem;
            color: #222;
            margin-bottom: 40px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 2em;
            color: #6c63ff;
            font-weight: bold;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .service-card {
            background: #ffffff;
            border: 3px solid #e2d1f9;
            border-radius: 20px;
            padding: 25px 20px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(80, 0, 120, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 35px rgba(120, 0, 200, 0.2);
            border-color: #d1b3ff;
        }

        .service-card h3 {
            font-size: 1.5rem;
            color: #7e22ce;
            margin-bottom: 10px;
        }

        .service-card p {
            color: #4f4f4f;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .service-card strong {
            color: #222;
        }

        .btn-book {
            background: linear-gradient(135deg, #6c63ff, #ab47bc);
            color: white;
            padding: 10px 18px;
            font-size: 1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-book:hover {
            background: linear-gradient(135deg, #5944d1, #9d3bb1);
        }

        @media screen and (max-width: 600px) {
            .hero h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero">
        <h1><?= htmlspecialchars($categoryName) ?> Services</h1>
        <p><?= htmlspecialchars($categoryDescription) ?></p>
        <div class="wave-divider">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160" preserveAspectRatio="none">
                <path d="M0,96C288,160,1152,0,1440,32L1440,160L0,160Z"></path>
            </svg>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section">
        <a href="service-home.php" class="back-link">&larr; Back to All Services</a>
        <h2>Explore Services in <?= htmlspecialchars($categoryName) ?></h2>

        <?php if (empty($services)): ?>
            <p style="text-align: center;">No services available in this category right now.</p>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($services as $service): ?>
                    <div class="service-card">
                        <h3><?= htmlspecialchars($service['ServiceName']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($service['Description'])) ?></p>
                        <p><strong>Price:</strong> $<?= number_format($service['Price'], 2) ?><br>
                            <strong>Duration:</strong> <?= htmlspecialchars($service['Duration']) ?>
                        </p>
                        <a href="service-booking-process.php?service_id=<?= (int) $service['ServiceID'] ?>">
                            <button class="btn-book">Book Now</button>
                        </a>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

</body>

</html>