<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'Volunteer') {
    header("Location: /animora/auth/login.php");
    exit();
}

$volunteerID = $_SESSION["user_id"];
$reportID = $_POST['report_id'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE rescuerolunteers SET Status = ? WHERE ReportID = ? AND VolunteerID = ?");
$stmt->bind_param("sii", $status, $reportID, $volunteerID);
$stmt->execute();
$stmt->close();

header("Location: volunteer-mission.php");
exit();
