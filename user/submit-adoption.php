<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../config/db.php'; // Your DB connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /animora/auth/login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

// Get form inputs, sanitize if needed
$name = $_POST['pet_name'] ?? '';
$species_name = $_POST['pet_type'] ?? '';
$breed_name = $_POST['breed'] ?? '';
$age = $_POST['age'] ?? 0;
$description = $_POST['description'] ?? '';
$gender = $_POST['gender'] ?? '';
$location = $_SESSION['address'] ?? 'Unknown';

// Validate gender
if (!in_array($gender, ['Male', 'Female'])) {
    die("Invalid gender selected.");
}

// Handle image upload
$photoPath = 'assets/default-pet.jpg'; // Default fallback
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/animal/'; // Use relative path from this script to your uploads folder

    // Check if directory exists or create it
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . '_' . basename($_FILES['photo']['name']);
    $targetFilePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
        // Save relative path for DB (remove ../)
        $photoPath = 'uploads/animal/' . $fileName;
    } else {
        die("Error uploading the photo.");
    }
}

// 1. Insert species if not exists
$speciesID = null;
$stmt = $conn->prepare("SELECT SpeciesID FROM species WHERE SpeciesName = ?");
$stmt->bind_param("s", $species_name);
$stmt->execute();
$stmt->bind_result($speciesID);
$stmt->fetch();
$stmt->close();

if (!$speciesID) {
    $stmt = $conn->prepare("INSERT INTO species (SpeciesName) VALUES (?)");
    $stmt->bind_param("s", $species_name);
    if ($stmt->execute()) {
        $speciesID = $stmt->insert_id;
    } else {
        die("Error inserting species: " . $stmt->error);
    }
    $stmt->close();
}

// 2. Insert breed if not exists
$breedID = null;
$stmt = $conn->prepare("SELECT BreedID FROM breeds WHERE BreedName = ? AND SpeciesID = ?");
$stmt->bind_param("si", $breed_name, $speciesID);
$stmt->execute();
$stmt->bind_result($breedID);
$stmt->fetch();
$stmt->close();

if (!$breedID) {
    $stmt = $conn->prepare("INSERT INTO breeds (BreedName, SpeciesID) VALUES (?, ?)");
    $stmt->bind_param("si", $breed_name, $speciesID);
    if ($stmt->execute()) {
        $breedID = $stmt->insert_id;
    } else {
        die("Error inserting breed: " . $stmt->error);
    }
    $stmt->close();
}

// 3. Insert into animals
// Assuming columns: OwnerID (int), Name (string), SpeciesID (int), BreedID (int), Age (float), Gender (string),
// HealthStatus (string), AdoptionStatus (string), Photo (string), Location (string)

// Set a default health status or add a field for it if needed
$healthStatus = 'Unknown';
$adoptionStatus = 'Available';

$stmt = $conn->prepare("INSERT INTO animals (OwnerID, Name, SpeciesID, BreedID, Age, Gender, HealthStatus, AdoptionStatus, Photo, Location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// types: i = int, s = string, d = double (float)
$stmt->bind_param("isiiisssss", $owner_id, $name, $speciesID, $breedID, $age, $gender, $healthStatus, $adoptionStatus, $photoPath, $location);

if ($stmt->execute()) {
    header("Location: user-adoption.php?posted=success");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>