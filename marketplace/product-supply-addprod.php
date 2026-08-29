<?php
session_start();  // Start the session
include '../config/db.php';  // Include the database connection file

// Check if the user is logged in (session variable is set)
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: /animora/auth/login.php");  // Adjust the login page URL if necessary
    exit();  // Stop further script execution after redirection
}

// Initialize message variables
$success_message = "";
$error_message = "";

// Handle form submission for adding product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle the image upload
    $imageURL = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = "uploads/" . uniqid() . "." . $image_ext;

        // Move the uploaded image to the "uploads" folder
        if (move_uploaded_file($image_tmp, "../" . $image_new_name)) {
            $imageURL = $image_new_name;
        } else {
            $error_message = "Error uploading the image.";
        }
    }

    // Insert the product into the database
    if (!empty($name) && !empty($category) && !empty($price)) {
        $stmt = $conn->prepare("INSERT INTO products (SupplierID, Name, CategoryID, Price, Description, ImageURL) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isisds", $user_id, $name, $category, $price, $description, $imageURL);

        if ($stmt->execute()) {
            $success_message = "Product added successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "All fields are required.";
    }
}

// Fetch categories for the dropdown
$categories = [];
$stmt = $conn->prepare("SELECT CategoryID, Name FROM productcategories");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Product - Pet Supplier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#FCBF01', light: '#FFFBEB', dark: '#B45309' },
                        petgray: '#4B5563'
                    },
                    boxShadow: { soft: '0 8px 20px rgba(0,0,0,.08)' },
                    keyframes: {
                        float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                        wiggle: { '0%,100%': { transform: 'rotate(-3deg)' }, '50%': { transform: 'rotate(3deg)' } },
                        pulsefast: {
                            '0%, 100%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.1)' }
                        }
                    },
                    animation: {
                        float: 'float 4s ease-in-out infinite',
                        wiggle: 'wiggle 2.5s ease-in-out infinite',
                        pulsefast: 'pulsefast 1.5s ease-in-out infinite'
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-brand-light min-h-screen flex text-petgray">
    <!-- Sidebar -->
    <aside class="w-16 bg-brand rounded-tr-3xl rounded-br-3xl flex flex-col items-center py-8 space-y-8 text-white shadow-soft select-none">
        <!-- Sidebar buttons... -->
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="mb-10">
            <h1 class="text-4xl font-semibold">➕ Add New Product</h1>
            <p class="text-gray-600 mt-2">Fill the form below to add a new item to your catalogue.</p>
        </header>

        <!-- Success/Error Message Display -->
        <?php if ($success_message): ?>
            <div class="bg-green-200 text-green-800 p-4 rounded-lg mb-4"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="bg-red-200 text-red-800 p-4 rounded-lg mb-4"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Product Add Form -->
        <form class="bg-white rounded-3xl p-8 shadow-soft max-w-3xl mx-auto space-y-6" method="POST" enctype="multipart/form-data">
            <div>
                <label class="block mb-1 font-semibold">Product Name</label>
                <input type="text" name="name" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand" placeholder="Enter product name" required />
            </div>
            <div>
                <label class="block mb-1 font-semibold">Category</label>
                <select name="category" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['CategoryID']; ?>"><?php echo $category['Name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Price ($)</label>
                <input type="number" name="price" step="0.01" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand" placeholder="0.00" required />
            </div>
            <div>
                <label class="block mb-1 font-semibold">Description</label>
                <textarea name="description" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand" placeholder="Enter product description" required></textarea>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Upload Image</label>
                <input type="file" name="image" class="w-full px-4 py-2 bg-brand-light text-brand rounded-lg border border-dashed border-brand cursor-pointer" />
            </div>
            <button type="submit" class="bg-brand text-white px-6 py-3 rounded-full font-semibold hover:bg-brand-dark">Add Product</button>
        </form>

        <!-- Fun Animated Pet Icon -->
        <div class="fixed bottom-6 right-6 hover:animate-wiggle cursor-pointer">
            <img src="https://img.icons8.com/color/96/kitten.png" alt="Animated Kitten" class="w-14 h-14" />
        </div>
    </main>
</body>

</html>
