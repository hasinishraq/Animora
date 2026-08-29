<?php
// Start session to access session variables
session_start();

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user_id from the session
$ownerid = $_SESSION['user_id']; // Assuming the user_id is stored in session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $speciesid = $_POST['speciesid'];
    $breedid = $_POST['breedid'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $healthstatus = $_POST['healthstatus'];
    $adoptionstatus = $_POST['adoptionstatus'];
    $photo = $_POST['photo']; // Assuming you send a file path or URL
    $location = $_POST['location'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO animals (ownerid, name, speciesid, breedid, age, gender, healthstatus, adoptionstatus, photo, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiisssss", $ownerid, $name, $speciesid, $breedid, $age, $gender, $healthstatus, $adoptionstatus, $photo, $location);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New animal added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Animal</title>
</head>

<body>
    <h2>Add New Animal</h2>
    <form action="add_animal.php" method="POST">
        <label for="name">Animal Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="speciesid">Species ID:</label><br>
        <input type="number" id="speciesid" name="speciesid" required><br><br>

        <label for="breedid">Breed ID:</label><br>
        <input type="number" id="breedid" name="breedid" required><br><br>

        <label for="age">Age:</label><br>
        <input type="number" id="age" name="age"><br><br>

        <label for="gender">Gender:</label><br>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br><br>

        <label for="healthstatus">Health Status:</label><br>
        <input type="text" id="healthstatus" name="healthstatus"><br><br>

        <label for="adoptionstatus">Adoption Status:</label><br>
        <select id="adoptionstatus" name="adoptionstatus">
            <option value="Available">Available</option>
            <option value="Adopted">Adopted</option>
        </select><br><br>

        <label for="photo">Photo URL:</label><br>
        <input type="text" id="photo" name="photo"><br><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location"><br><br>

        <input type="submit" value="Add Animal">
    </form>
</body>

</html>