<?php
session_start(); // Start the session
include '../config/db.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add an animal.");
}

// Fetch user's name and profile picture from the database
$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];  // Fetch the role from the session

// Prepare the query to fetch the user's name and profile picture
$stmt = $conn->prepare("SELECT Name, ProfilePhoto FROM users WHERE UserID = ?");
$stmt->bind_param("i", $user_id);  // "i" is for integer
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_name'] = $user["Name"]; // Set session variable
    $user_name = $user["Name"];
    $profile_photo = $user["ProfilePhoto"]; // This will store the path to the profile picture
} else {
    $_SESSION['user_name'] = 'Guest'; // Default name if no user is found
    $user_name = 'Guest'; // Fallback name
    $profile_photo = "https://randomuser.me/api/portraits/men/32.jpg"; // Default profile photo
}

// Get the logged-in user's ID
$ownerId = $_SESSION['user_id']; 

// Fetch species list from the database
$speciesQuery = "SELECT speciesid, speciesname FROM species";
$speciesResult = $conn->query($speciesQuery);

// Prepare breed options based on species
$breedsQuery = "SELECT breedid, breedname FROM breeds WHERE speciesid = ?";
$breedsStmt = $conn->prepare($breedsQuery);

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $animalName = filter_var($_POST['animalName'], FILTER_SANITIZE_STRING);
    $species = filter_var($_POST['species'], FILTER_VALIDATE_INT);
    $breed = filter_var($_POST['breed'], FILTER_VALIDATE_INT);
    $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
    $gender = $_POST['gender'];
    $healthStatus = filter_var($_POST['healthStatus'], FILTER_SANITIZE_STRING);
    $adoptionStatus = $_POST['adoptionStatus'];
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);

    // Handle the file upload for the animal's photo
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Set the target directory for uploads
        $targetDirectory = 'uploads/animal/';
        
        // Ensure the directory exists, otherwise create it
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }
        
        // Set the full path for the uploaded file
        $photo = $targetDirectory . basename($_FILES['photo']['name']);
        
        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo)) {
            echo "Error uploading the file.";
        }
    }

// Prepare the SQL query to insert data into the animals table
$stmt = $conn->prepare("INSERT INTO animals (ownerid, name, speciesid, breedid, age, gender, healthstatus, adoptionstatus, photo, location)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// The type definition string should be "isiiisssss" corresponding to the variables
$stmt->bind_param("isiiisssss", $ownerId, $animalName, $species, $breed, $age, $gender, $healthStatus, $adoptionStatus, $photo, $location);

// Execute the query
if ($stmt->execute()) {
    echo "Animal added successfully!";
} else {
    echo "Error: " . $stmt->error;
}

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Pawsome Adoptions</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream: #FBF3F0;
            --mustard: #D4931F;
            --teal: #A0C0A9;
            --mint: #C2D4C8;
            --sand: #EEC3A4;
            --dark-text: #4A4A4A;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--cream);
            color: var(--dark-text);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
            color: var(--mustard);
        }

        .btn-primary {
            @apply bg-[color:var(--mustard)] text-white px-6 py-3 rounded-full font-bold transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-transparent hover:border-white;
        }

        .btn-secondary {
            @apply bg-white text-[color:var(--mustard)] px-6 py-3 rounded-full font-bold transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-[color:var(--mustard)];
        }

        .badge-primary {
            background-color: var(--mustard);
            color: white;
        }

        .badge-warning {
            background-color: var(--sand);
            color: var(--dark-text);
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="w-full bg-white py-4 shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center">
                <img src="/animora/assets/images/logo2.png" alt="Pawsome Adoptions Logo" class="h-12 w-auto">
                <span class="ml-3 text-xl font-bold text-[color:var(--mustard)]">Pawsome Adoptions</span>
            </div>

            <!-- User Profile -->
            <div class="flex items-center space-x-4">
                <div class="relative group">
                    <button class="flex items-center space-x-2 focus:outline-none">
                        <img src="../path/to/profile/photo.jpg" alt="User Profile" class="h-10 w-10 rounded-full border-2 border-[color:var(--mustard)]">
                        <span class="hidden md:inline-block font-medium"><?php echo $_SESSION['user_name']; ?></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="h-20"></div> <!-- Spacer for fixed header -->

    <!-- Dashboard Layout -->
    <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row gap-6">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 flex-shrink-0 dashboard-nav rounded-2xl p-4 h-fit sticky top-24">
            <nav class="space-y-2">
                <div class="px-3 py-2 text-sm font-medium text-gray-500 uppercase tracking-wider">Main Menu</div>
                <a href="#" class="nav-item active flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Dashboard
                </a>
                <a href="user-vet-appoint-view.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Appointments
                </a>
                <a href="user-adoption.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Adoption
                </a>
                <a href="user-post-adoption.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Post Adoption
                </a>
                <a href="/animora/user/marketplacehome.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Marketplace
                </a>
                <a href="service-home.php" class="nav-item flex items-center px-3 py-3 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-[color:var(--mustard)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Pet Services
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-[color:var(--mustard)]">Welcome back, <?php echo $_SESSION['user_name']; ?>!</h1>
                        <p class="text-gray-600">Here's what's happening with your pet adoption journey today.</p>
                    </div>
                </div>
            </div>

          <!-- Add Pet Form Section -->
<div class="bg-white rounded-2xl shadow-md p-6 mb-6">
    <h2 class="text-2xl font-bold text-[color:var(--mustard)] mb-6">Add a New Pet</h2>
    <form action="user-add-pet.php" method="POST" enctype="multipart/form-data">
        <div class="space-y-4">
            <!-- Animal Name -->
            <div>
                <label for="animalName" class="block text-sm font-medium text-gray-700">Animal Name</label>
                <input type="text" id="animalName" name="animalName" required class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)] focus:border-[color:var(--mustard)]">
            </div>

            <!-- Species -->
            <div>
                <label for="species" class="block text-sm font-medium text-gray-700">Species</label>
                <select id="species" name="species" required class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)] focus:border-[color:var(--mustard)]" onchange="loadBreeds(this.value)">
                    <option value="">Select Species</option>
                    <?php while($species = $speciesResult->fetch_assoc()): ?>
                        <option value="<?= $species['speciesid']; ?>"><?= $species['speciesname']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Breed -->
            <div>
                <label for="breed" class="block text-sm font-medium text-gray-700">Breed</label>
                <select id="breed" name="breed" required class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)] focus:border-[color:var(--mustard)]">
                    <option value="">Select Breed</option>
                </select>
            </div>

            <!-- Other fields: age, gender, health status, adoption status, photo, location -->
             <div>
                            <label for="age" class="block text-sm font-medium text-gray-700">Age (in years)</label>
                            <input type="number" id="age" name="age" min="0" required class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)] focus:border-[color:var(--mustard)]">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select id="gender" name="gender" required class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)] focus:border-[color:var(--mustard)]">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div>
                            <label for="healthStatus" class="block text-sm font-medium text-gray-700">Health Status</label>
                            <input type="text" id="healthStatus" name="healthStatus" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)] focus:border-[color:var(--mustard)]">
                        </div>

                        <div>
                            <label for="adoptionStatus" class="block text-sm font-medium text-gray-700">Adoption Status</label>
                            <select id="adoptionStatus" name="adoptionStatus" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)] focus:border-[color:var(--mustard)]">
                                <option value="Available">Available</option>
                                <option value="Adopted">Adopted</option>
                            </select>
                        </div>

                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-700">Animal Photo</label>
                            <input type="file" id="photo" name="photo" accept="image/*" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)] focus:border-[color:var(--mustard)]">
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" id="location" name="location" required class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-[color:var(--mustard)] focus:border-[color:var(--mustard)]">
                        </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="btn-primary">Add Pet</button>
            </div>
        </div>
    </form>
</div>

<script>
// Function to load breeds based on selected species
function loadBreeds(speciesId) {
    var breedSelect = document.getElementById('breed');
    breedSelect.innerHTML = '<option value="">Loading...</option>'; // Show loading state

    // Make an AJAX request to fetch breeds based on species ID
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_breeds.php?species_id=' + speciesId, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var breeds = JSON.parse(xhr.responseText);
            breedSelect.innerHTML = '<option value="">Select Breed</option>'; // Reset breeds dropdown

            breeds.forEach(function(breed) {
                var option = document.createElement('option');
                option.value = breed.breedid;
                option.textContent = breed.breedname;
                breedSelect.appendChild(option);
            });
        }
    };
    xhr.send();
}
</script>

        </main>
    </div>
</body>

</html>
