<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../config/db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'User') {
    header("Location: /animora/auth/login.php");
    exit();
}

$userID = $_SESSION["user_id"];

if (!isset($_GET['service_id']) || !is_numeric($_GET['service_id'])) {
    header("Location: /animora/user/service-home.php");
    exit();
}

$serviceID = (int) $_GET['service_id'];

// Fetch service details
$stmt = $conn->prepare("SELECT s.ServiceName, s.Description, s.Price, s.Duration, c.CategoryName 
                        FROM services s 
                        JOIN servicecategories c ON s.CategoryID = c.CategoryID
                        WHERE s.ServiceID = ?");
$stmt->bind_param("i", $serviceID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Service not found.";
    exit();
}

$service = $result->fetch_assoc();
$stmt->close();

$successMessage = "";
$errorMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bookingDate = $_POST['booking_date'] ?? '';
    $bookingTime = $_POST['booking_time'] ?? '';

    if (!$bookingDate || !$bookingTime) {
        $errorMessage = "Please select both date and time.";
    } else {
        $stmt = $conn->prepare("INSERT INTO servicebookings (UserID, ServiceID, BookingDate, BookingTime) 
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $userID, $serviceID, $bookingDate, $bookingTime);
        if ($stmt->execute()) {
            $successMessage = "✅ Booking confirmed successfully!";
        } else {
            $errorMessage = "❌ Booking failed. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book <?= htmlspecialchars($service['ServiceName']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #fcefee, #eaf4fc);
            padding: 40px;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            border: 3px solid #d9d4f3;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        h1 {
            text-align: center;
            color: #6c3eb1;
        }

        p {
            margin-bottom: 1em;
        }

        .service-details {
            background: #f9f7ff;
            border: 2px solid #eee;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 2px solid #ccc;
        }

        .btn {
            background: linear-gradient(135deg, #6c63ff, #ab47bc);
            color: white;
            padding: 12px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: block;
            margin: auto;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(135deg, #4c3cc4, #9224a1);
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 1.5em;
            text-decoration: none;
            color: #6c63ff;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="javascript:history.back()" class="back-link">&larr; Back</a>
        <h1>Book: <?= htmlspecialchars($service['ServiceName']) ?></h1>

        <div class="service-details">
            <p><strong>Category:</strong> <?= htmlspecialchars($service['CategoryName']) ?></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($service['Description'])) ?></p>
            <p><strong>Price:</strong> $<?= number_format($service['Price'], 2) ?></p>
            <p><strong>Duration:</strong> <?= htmlspecialchars($service['Duration']) ?></p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="booking_date">Select Booking Date:</label>
                <input type="date" name="booking_date" id="booking_date" required>
            </div>

            <div class="form-group">
                <label for="booking_time">Select Booking Time:</label>
                <input type="time" name="booking_time" id="booking_time" required>
            </div>

            <button type="submit" class="btn">Confirm Booking</button>
        </form>

        <?php if ($successMessage): ?>
            <p class="message success"><?= $successMessage ?></p>
        <?php elseif ($errorMessage): ?>
            <p class="message error"><?= $errorMessage ?></p>
        <?php endif; ?>
    </div>

</body>

</html>