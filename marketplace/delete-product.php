<?php
session_start();
include '../config/db.php';  // Include the database connection file

// Check if the user is logged in (session variable is set)
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: /animora/auth/login.php");  // Adjust the login page URL if necessary
    exit();  // Stop further script execution after redirection
}

// Check if the product ID is provided
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION["user_id"];

    // Prepare the query to delete the product
    $stmt = $conn->prepare("DELETE FROM Products WHERE ProductID = ? AND SupplierID = ?");
    $stmt->bind_param("ii", $product_id, $user_id);  // "ii" for integer (ProductID and SupplierID)

    // Execute the delete query
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete the product.']);
    }

    $stmt->close();
}
?>
