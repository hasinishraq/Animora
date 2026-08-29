<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: /animora/auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {
    $requestId = intval($_POST['request_id']);
    $userId = $_SESSION['user_id'];

    // Make sure this request belongs to a pet owned by this user
    $stmt = $conn->prepare("
        SELECT a.OwnerID 
        FROM adoptionrequests ar
        JOIN animals a ON ar.AnimalID = a.AnimalID
        WHERE ar.RequestID = ?
    ");
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $stmt->bind_result($ownerId);
    $stmt->fetch();
    $stmt->close();

    if ($ownerId != $userId) {
        $_SESSION['adoption_message'] = "You are not authorized to decline this request.";
        header("Location: ../user/user-home.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE adoptionrequests SET Status = 'Rejected' WHERE RequestID = ?");
    $stmt->bind_param("i", $requestId);
    if ($stmt->execute()) {
        $_SESSION['adoption_message'] = "Adoption request declined.";
    } else {
        $_SESSION['adoption_message'] = "Failed to decline the request.";
    }
    $stmt->close();
} else {
    $_SESSION['adoption_message'] = "Invalid decline request.";
}

header("Location: ../user/user-home.php");
exit();
?>