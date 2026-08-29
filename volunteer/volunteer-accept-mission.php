<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'Volunteer') {
    header("Location: /animora/auth/login.php");
    exit();
}

$volunteerID = $_SESSION["user_id"];
$reportID = $_POST['report_id'] ?? null;
$action = $_POST['action'] ?? '';

if ($action === 'accept') {
    $stmt = $conn->prepare("INSERT IGNORE INTO rescuerolunteers (ReportID, VolunteerID, Status) VALUES (?, ?, 'Accepted')");
    $stmt->bind_param("ii", $reportID, $volunteerID);
    $stmt->execute();
    $stmt->close();

    // 🔁 After accept, go to mission list
    header("Location: volunteer-mission.php");
    exit();
}

if ($action === 'decline') {
    $stmt = $conn->prepare("INSERT IGNORE INTO rescuerolunteers (ReportID, VolunteerID, Status) VALUES (?, ?, 'Declined')");
    $stmt->bind_param("ii", $reportID, $volunteerID);
    $stmt->execute();
    $stmt->close();

    // 🔁 After decline, stay on available missions
    header("Location: volunteer-available-mission.php");
    exit();
}

// Default fallback
header("Location: volunteer-available-mission.php");
exit();
