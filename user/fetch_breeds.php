<?php
include '../config/db.php'; // Include the database connection file

if (isset($_GET['species_id'])) {
    $species_id = $_GET['species_id'];

    // Prepare query to fetch breeds based on species
    $stmt = $conn->prepare("SELECT breedid, breedname FROM breeds WHERE speciesid = ?");
    $stmt->bind_param("i", $species_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $breeds = [];
    while ($breed = $result->fetch_assoc()) {
        $breeds[] = $breed;
    }

    // Return the breeds in JSON format
    echo json_encode($breeds);

    $stmt->close();
    $conn->close();
}
?>
