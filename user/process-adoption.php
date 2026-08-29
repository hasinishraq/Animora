<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../config/db.php';  // Adjust the path if needed

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to adopt a pet.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['animal_id'])) {
    $animalId = intval($_POST['animal_id']);
    $requesterId = intval($_SESSION['user_id']);

    // 1. Get the owner of the animal
    $stmtOwner = $conn->prepare("SELECT OwnerID FROM animals WHERE AnimalID = ?");
    $stmtOwner->bind_param("i", $animalId);
    $stmtOwner->execute();
    $stmtOwner->bind_result($ownerId);
    $stmtOwner->fetch();
    $stmtOwner->close();

    if (!$ownerId) {
        $_SESSION['adoption_message'] = "Invalid animal selected.";
    } elseif ($ownerId === $requesterId) {
        // 2. Block if user is the owner
        $_SESSION['adoption_message'] = "You cannot adopt your own pet.";
    } else {
        // 3. Check for duplicate adoption request
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM adoptionrequests WHERE AnimalID = ? AND RequesterID = ?");
        $stmtCheck->bind_param("ii", $animalId, $requesterId);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            $_SESSION['adoption_message'] = "You have already requested to adopt this pet.";
        } else {
            // 4. Insert the adoption request
            $stmtInsert = $conn->prepare("INSERT INTO adoptionrequests (AnimalID, RequesterID) VALUES (?, ?)");
            $stmtInsert->bind_param("ii", $animalId, $requesterId);

            if ($stmtInsert->execute()) {
                $_SESSION['adoption_message'] = "Your adoption request has been submitted successfully!";
            } else {
                $_SESSION['adoption_message'] = "Failed to submit adoption request. Please try again.";
            }
            $stmtInsert->close();
        }
    }
} else {
    $_SESSION['adoption_message'] = "Invalid request.";
}

header('Location: user-adoption.php');
exit();
