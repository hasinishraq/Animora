<?php
session_start();  // Start the session
include '../config/db.php';  // Include the database connection file

// Check if the user is logged in (session variable is set)
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: /animora/auth/login.php");  // Adjust the login page URL if necessary
    exit();  // Stop further script execution after redirection
}

// Fetch user's name and profile picture from the database
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    // Prepare the query to fetch the user's name and profile picture
    $stmt = $conn->prepare("SELECT Name, ProfilePhoto FROM users WHERE UserID = ?");
    $stmt->bind_param("i", $user_id);  // "i" is for integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_name = $user["Name"];
        $profile_photo = $user["ProfilePhoto"]; // This will store the path to the profile picture
    } else {
        $user_name = "Guest";  // If no user found, set a default name
        $profile_photo = "https://randomuser.me/api/portraits/men/32.jpg"; // Default profile picture
    }

    $stmt->close();
} else {
    $user_name = "Guest";  // If no session, set default name
    $profile_photo = "https://randomuser.me/api/portraits/men/32.jpg"; // Default profile picture
}

// Fetch the total number of products added by the supplier
$stmt = $conn->prepare("SELECT COUNT(*) AS total_products FROM products WHERE SupplierID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_products = $row['total_products'];  // Store the count in the variable

$stmt->close();

// Get category filter
$category_id = isset($_GET['category']) ? $_GET['category'] : null;

// Fetch products based on category filter
if ($category_id) {
    $stmt = $conn->prepare("SELECT p.ProductID, p.Name, p.Price, p.Description, p.ImageURL, c.Name AS CategoryName 
                            FROM products p 
                            JOIN productcategories c ON p.CategoryID = c.CategoryID 
                            WHERE p.SupplierID = ? AND p.CategoryID = ?");
    $stmt->bind_param("ii", $user_id, $category_id);
} else {
    $stmt = $conn->prepare("SELECT p.ProductID, p.Name, p.Price, p.Description, p.ImageURL, c.Name AS CategoryName 
                            FROM products p 
                            JOIN productcategories c ON p.CategoryID = c.CategoryID 
                            WHERE p.SupplierID = ?");
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$product_result = $stmt->get_result();
$products = [];
while ($product = $product_result->fetch_assoc()) {
    $products[] = $product;
}
$stmt->close();

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
    <title>Products - Pet Supplier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#FCBF01', light: '#FFFBEB', dark: '#B45309' },
                        petgray: '#4B5563',
                    },
                    boxShadow: { soft: '0 8px 20px rgba(0,0,0,.08)' },
                    keyframes: {
                        float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                        wiggle: { '0%,100%': { transform: 'rotate(-3deg)' }, '50%': { transform: 'rotate(3deg)' } },
                        pop: {
                            '0%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.2)' },
                            '100%': { transform: 'scale(1)' }
                        }
                    },
                    animation: {
                        float: 'float 4s ease-in-out infinite',
                        wiggle: 'wiggle 2.5s ease-in-out infinite',
                        pop: 'pop 0.35s ease-in-out'
                    }
                }
            }
        }

        // Function to update the page based on selected category
        function filterCategory() {
            const selectedCategory = document.getElementById('category-select').value;
            const url = new URL(window.location.href);
            if (selectedCategory === "All Categories") {
                url.searchParams.delete('category'); // Remove category filter
            } else {
                url.searchParams.set('category', selectedCategory); // Add category filter
            }
            window.location.href = url;  // Reload the page with updated URL
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif
        }

        ::-webkit-scrollbar {
            width: 8px
        }

        ::-webkit-scrollbar-thumb {
            background: #fcbf0177;
            border-radius: 8px
        }
    </style>
</head>

<body class="bg-brand-light min-h-screen flex text-petgray">
    <!-- Sidebar -->
    <aside
        class="w-16 bg-brand rounded-tr-3xl rounded-br-3xl flex flex-col items-center py-8 space-y-8 text-white shadow-soft select-none">
        <!-- Sidebar buttons... -->
    </aside>
    <!-- Main content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-10">
            <h1 class="text-4xl font-semibold">🐾 All Products</h1>
            <div class="flex gap-4 w-full sm:w-auto">
                <!-- Category Filter Dropdown -->
                <select id="category-select" onchange="filterCategory()" class="px-4 py-2 rounded-lg bg-white shadow-soft text-petgray">
                    <option>All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['CategoryID']; ?>" <?php echo isset($_GET['category']) && $_GET['category'] == $category['CategoryID'] ? 'selected' : ''; ?>>
                            <?php echo $category['Name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" placeholder="Search products..."
                    class="px-4 py-2 rounded-lg bg-white shadow-soft text-petgray w-full sm:w-64" />
                <a href="/animora/marketplace/product-supply-addprod.php">
    <button class="bg-brand text-white px-6 py-2 rounded-full font-semibold shadow hover:bg-brand-dark">
        + Add Product
    </button>
</a>

            </div>
        </header>

        <!-- Product Grid -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach ($products as $product): ?>
    <div id="product-<?php echo $product['ProductID']; ?>" class="bg-white p-4 rounded-2xl shadow-soft hover:shadow-lg transition group">
        <!-- Image Display with Correct Path -->
        <img src="<?php echo 'http://localhost/animora/' . $product['ImageURL']; ?>" 
             alt="<?php echo $product['Name']; ?>" 
             class="w-20 h-20 mx-auto mb-2 cursor-pointer animate-float pet-img" />
        <h2 class="text-xl font-semibold text-center"><?php echo $product['Name']; ?></h2>
        <p class="text-center text-gray-500">Category: <?php echo $product['CategoryName']; ?></p>
        <p class="text-center font-bold text-brand mt-1">$<?php echo number_format($product['Price'], 2); ?></p>
        <div class="mt-4 flex justify-center gap-3">
            <button class="text-xs bg-brand-light text-brand px-3 py-1 rounded hover:bg-brand">Edit</button>
            <button class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">Delete</button>
        </div>
    </div>
<?php endforeach; ?>

        </section>
    </main>
</body>

</html>
