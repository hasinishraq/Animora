<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: /animora/auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id']) && isset($_POST['animal_id'])) {
    $requestId = intval($_POST['request_id']);
    $animalId = intval($_POST['animal_id']);
    $userId = $_SESSION['user_id'];

    // Step 1: Check ownership of the pet
    $stmt = $conn->prepare("SELECT AnimalID FROM animals WHERE AnimalID = ? AND OwnerID = ?");
    $stmt->bind_param("ii", $animalId, $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $_SESSION['adoption_message'] = "You are not authorized to approve this request.";
        header("Location: ../user/user-home.php");
        exit();
    }
    $stmt->close();

    // Step 2: Approve the request
    $conn->begin_transaction();
    try {
        // Approve the selected request
        $stmt1 = $conn->prepare("UPDATE adoptionrequests SET Status = 'Approved' WHERE RequestID = ?");
        $stmt1->bind_param("i", $requestId);
        $stmt1->execute();
        $stmt1->close();

        // Reject other pending requests for this animal
        $stmt2 = $conn->prepare("UPDATE adoptionrequests SET Status = 'Rejected' WHERE AnimalID = ? AND RequestID != ? AND Status = 'Pending'");
        $stmt2->bind_param("ii", $animalId, $requestId);
        $stmt2->execute();
        $stmt2->close();

        // Update animal adoption status
        $stmt3 = $conn->prepare("UPDATE animals SET AdoptionStatus = 'Adopted' WHERE AnimalID = ?");
        $stmt3->bind_param("i", $animalId);
        $stmt3->execute();
        $stmt3->close();

        $conn->commit();
        $_SESSION['adoption_message'] = "Adoption request approved successfully.";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['adoption_message'] = "Something went wrong. Please try again.";
    }
} else {
    $_SESSION['adoption_message'] = "Invalid approval request.";
}

header("Location: ../user/user-home.php");
exit();
?>