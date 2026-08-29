<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../config/db.php';

// Test database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    error_log("Database connection successful");
}

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle cart actions
if (isset($_GET['action']) && isset($_GET['product_id'])) {
    $product_id = (int) $_GET['product_id'];

    // Validate product ID
    if ($product_id <= 0) {
        $_SESSION['error'] = "Invalid product ID";
        header("Location: marketplace-cart.php");
        exit();
    }

    // Get or create cart for user (one cart per user in your schema)
    $cart_stmt = $conn->prepare("SELECT CartID FROM cart WHERE UserID = ?");
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    if ($cart_result->num_rows === 0) {
        // Create new cart if doesn't exist
        $create_cart = $conn->prepare("INSERT INTO cart (UserID) VALUES (?)");
        $create_cart->bind_param("i", $user_id);
        if (!$create_cart->execute()) {
            $_SESSION['error'] = "Failed to create cart";
            header("Location: marketplace-cart.php");
            exit();
        }
        $cart_id = $conn->insert_id;
    } else {
        $cart_row = $cart_result->fetch_assoc();
        $cart_id = $cart_row['CartID'];
    }

    switch ($_GET['action']) {
        case 'add':
            // Get product details - using StockQuantity instead of Stock
            $product_stmt = $conn->prepare("SELECT Price, StockQuantity FROM products WHERE ProductID = ?");
            $product_stmt->bind_param("i", $product_id);
            $product_stmt->execute();
            $product_result = $product_stmt->get_result();

            if ($product_result->num_rows === 0) {
                $_SESSION['error'] = "Product not found";
                header("Location: marketplace-cart.php");
                exit();
            }

            $product = $product_result->fetch_assoc();
            $price = $product['Price'];
            $stock = $product['StockQuantity']; // Changed from Stock to StockQuantity

            // Check if product already in cart
            $check_stmt = $conn->prepare("SELECT CartItemID, Quantity FROM cartitems WHERE CartID = ? AND ProductID = ?");
            $check_stmt->bind_param("ii", $cart_id, $product_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                // Update quantity if exists (check stock first)
                $item = $check_result->fetch_assoc();
                $new_quantity = $item['Quantity'] + 1;

                if ($new_quantity > $stock) {
                    $_SESSION['error'] = "Not enough stock available";
                    header("Location: marketplace-cart.php");
                    exit();
                }

                $update_stmt = $conn->prepare("UPDATE cartitems SET Quantity = ? WHERE CartItemID = ?");
                $update_stmt->bind_param("ii", $new_quantity, $item['CartItemID']);
                if (!$update_stmt->execute()) {
                    $_SESSION['error'] = "Failed to update cart";
                }
            } else {
                // Add new item if not exists (check stock first)
                if ($stock < 1) {
                    $_SESSION['error'] = "Product out of stock";
                    header("Location: marketplace-cart.php");
                    exit();
                }

                $insert_stmt = $conn->prepare("INSERT INTO cartitems (CartID, ProductID, Quantity, Price) VALUES (?, ?, 1, ?)");
                $insert_stmt->bind_param("iid", $cart_id, $product_id, $price);
                if (!$insert_stmt->execute()) {
                    $_SESSION['error'] = "Failed to add to cart";
                }
            }
            break;

        case 'remove':
            $delete_stmt = $conn->prepare("DELETE FROM cartitems WHERE CartID = ? AND ProductID = ?");
            $delete_stmt->bind_param("ii", $cart_id, $product_id);
            if (!$delete_stmt->execute()) {
                $_SESSION['error'] = "Failed to remove item";
            }
            break;

        case 'increase':
            // First check stock availability - using StockQuantity
            $stock_stmt = $conn->prepare("SELECT StockQuantity FROM products WHERE ProductID = ?");
            $stock_stmt->bind_param("i", $product_id);
            $stock_stmt->execute();
            $stock_result = $stock_stmt->get_result();

            if ($stock_result->num_rows > 0) {
                $stock = $stock_result->fetch_assoc()['StockQuantity']; // Changed from Stock to StockQuantity

                $current_qty_stmt = $conn->prepare("SELECT Quantity FROM cartitems WHERE CartID = ? AND ProductID = ?");
                $current_qty_stmt->bind_param("ii", $cart_id, $product_id);
                $current_qty_stmt->execute();
                $current_qty_result = $current_qty_stmt->get_result();

                if ($current_qty_result->num_rows > 0) {
                    $current_qty = $current_qty_result->fetch_assoc()['Quantity'];

                    if ($current_qty + 1 > $stock) {
                        $_SESSION['error'] = "Not enough stock available";
                        header("Location: marketplace-cart.php");
                        exit();
                    }

                    $update_stmt = $conn->prepare("UPDATE cartitems SET Quantity = Quantity + 1 WHERE CartID = ? AND ProductID = ?");
                    $update_stmt->bind_param("ii", $cart_id, $product_id);
                    if (!$update_stmt->execute()) {
                        $_SESSION['error'] = "Failed to update quantity";
                    }
                }
            }
            break;

        case 'decrease':
            // First check current quantity
            $check_stmt = $conn->prepare("SELECT Quantity FROM cartitems WHERE CartID = ? AND ProductID = ?");
            $check_stmt->bind_param("ii", $cart_id, $product_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $item = $check_result->fetch_assoc();
                if ($item['Quantity'] > 1) {
                    $update_stmt = $conn->prepare("UPDATE cartitems SET Quantity = Quantity - 1 WHERE CartID = ? AND ProductID = ?");
                    $update_stmt->bind_param("ii", $cart_id, $product_id);
                    if (!$update_stmt->execute()) {
                        $_SESSION['error'] = "Failed to update quantity";
                    }
                } else {
                    // Remove if quantity would be 0
                    $delete_stmt = $conn->prepare("DELETE FROM cartitems WHERE CartID = ? AND ProductID = ?");
                    $delete_stmt->bind_param("ii", $cart_id, $product_id);
                    if (!$delete_stmt->execute()) {
                        $_SESSION['error'] = "Failed to remove item";
                    }
                }
            }
            break;
    }

    header("Location: marketplace-cart.php");
    exit();
}

// Get cart items from database
$cart_items = [];
$subtotal = 0;

// Get cart ID for user
$cart_stmt = $conn->prepare("SELECT CartID FROM cart WHERE UserID = ?");
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

if ($cart_result->num_rows > 0) {
    $cart_row = $cart_result->fetch_assoc();
    $cart_id = $cart_row['CartID'];

    // Get cart items with product details - using StockQuantity
    $items_stmt = $conn->prepare("
        SELECT ci.CartItemID, ci.ProductID, ci.Quantity, ci.Price, 
               p.Name, p.ImageURL, p.StockQuantity
        FROM cartitems ci
        JOIN products p ON ci.ProductID = p.ProductID
        WHERE ci.CartID = ?
    ");
    $items_stmt->bind_param("i", $cart_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();

    while ($item = $items_result->fetch_assoc()) {
        $cart_items[] = $item;
        $subtotal += $item['Price'] * $item['Quantity'];
    }
}

// Calculate totals
$shipping = 5.00;
$tax = $subtotal * 0.08;
$total = $subtotal + $shipping + $tax;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawsome Adoptions - Shopping Cart</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-orange: #F5A623;
            --light-cream: #FFFAF5;
            --light-orange-accent: #FFE9BD;
            --medium-orange-accent: #FCC370;
            --purple-accent: #E0BBE4;
            --pinkish-orange: #f8bba2;
            --wave-lightest: #ffe6dc;
            --wave-middle: #f5a18c;
            --wave-front: #f7b9a6;
            --dark-hover-orange: #D98E0B;
            --light-hover-cream: #fff3e0;
            --text-dark: #4A4A4A;
            --text-medium: #5A5A5A;
            --text-light: #718096;
            --blue-title: #4A6D90;
            --blue-title-light: #7DA4C5;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--wave-front);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
            color: var(--primary-orange);
        }

        .quantity-selector {
            @apply flex items-center bg-white rounded-full border-2 border-[color:var(--pinkish-orange)] p-1 shadow-inner;
        }

        .quantity-button {
            @apply bg-[color:var(--light-cream)] text-[color:var(--primary-orange)] font-bold w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[color:var(--light-orange-accent)] hover:text-white;
        }

        .quantity-input {
            @apply w-16 text-center text-xl font-bold text-[color:var(--text-dark)] bg-transparent focus:outline-none;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-orange), var(--dark-hover-orange));
            color: white;
            padding: 12px 32px;
            border-radius: 9999px;
            font-weight: bold;
            font-size: 1.125rem;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            border-color: white;
        }

        .btn-secondary {
            @apply bg-white text-[color:var(--primary-orange)] px-8 py-4 rounded-full font-bold text-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-md border-2 border-[color:var(--primary-orange)];
        }

        .cart-counter {
            @apply bg-red-500 text-white rounded-full px-2 py-1 text-xs font-bold absolute -top-1 -right-1;
        }

        .cart-container {
            @apply bg-white p-8 rounded-3xl shadow-xl border-4 border-[color:var(--pinkish-orange)];
        }

        .cart-table {
            @apply w-full text-left border-collapse;
        }

        .cart-table th,
        .cart-table td {
            @apply py-4 px-6 border-b border-[color:var(--light-cream)];
        }

        .cart-table th {
            @apply text-lg font-semibold text-[color:var(--primary-orange)];
        }

        .cart-table td {
            @apply text-[color:var(--text-medium)];
        }

        .cart-item-image {
            @apply w-20 h-20 object-cover rounded-xl shadow-sm;
        }

        .cart-summary {
            @apply bg-white p-6 rounded-3xl shadow-xl border-4 border-[color:var(--purple-accent)];
        }

        .cart-summary-row {
            @apply flex justify-between items-center text-lg font-medium text-[color:var(--text-dark)] py-2;
        }

        .cart-total-row {
            @apply flex justify-between items-center text-2xl font-bold text-[color:var(--primary-orange)] py-4 border-t-2 border-[color:var(--pinkish-orange)] mt-4;
        }

        .empty-cart-message {
            @apply text-center p-10 bg-white rounded-3xl shadow-xl border-4 border-[color:var(--pinkish-orange)] mt-10;
        }

        .add-to-cart-btn {
            background-color: #F5A623;
            color: #4A6D90;
            padding: 10px 20px;
            border-radius: 9999px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #D98E0B;
            transform: scale(1.05);
        }
    </style>
</head>

<body class="text-[color:var(--text-dark)]">

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="fixed inset-0 bg-white bg-opacity-80 flex items-center justify-center z-50 hidden">
        <div
            class="w-16 h-16 border-8 border-[color:var(--light-cream)] border-t-[color:var(--primary-orange)] rounded-full animate-spin">
        </div>
    </div>

    <!-- Header / Navbar -->
    <header class="w-full bg-white py-4 shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Left Menu -->
            <nav class="flex justify-center md:justify-start gap-8 text-lg font-semibold">
                <a href="index.php"
                    class="text-[color:var(--primary-orange)] hover:text-[color:var(--dark-hover-orange)] transition duration-300">Home</a>
                <a href="marketplace-home.php"
                    class="text-[color:var(--primary-orange)] hover:text-[color:var(--dark-hover-orange)] transition duration-300">Shop</a>
                <a href="post-adoption.php"
                    class="text-[color:var(--primary-orange)] hover:text-[color:var(--dark-hover-orange)] transition duration-300">Contact</a>
            </nav>

            <!-- Center Logo -->
            <div class="flex justify-center flex-shrink-0">
                <img src="/assets/images/logo2.png" alt="Pawsome Adoptions Logo" class="h-16 w-auto">
            </div>

            <!-- Right Buttons -->
            <div class="w-full md:w-auto flex justify-center md:justify-end gap-4">
                <!-- Profile Button -->
                <a href="profile.php"
                    class="relative bg-white text-[color:var(--primary-orange)] p-3 rounded-full shadow-md transition duration-300 hover:scale-110">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
                <!-- Cart Button -->
                <a href="marketplace-cart.php"
                    class="relative bg-[color:var(--primary-orange)] text-white p-3 rounded-full shadow-md transition duration-300 hover:scale-110">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 12.356 2.62 13.542 2.62 14.5c0 .828.672 1.5 1.5 1.5h10.243a.5.5 0 000-1H4.122c-.172 0-.25-.132-.217-.183l.222-.221.75-.75L6.96 7.464l.877 3.51a.5.5 0 00.99.248l.94-3.763 3.657-3.657A1 1 0 0016 4V3a1 1 0 00-1-1H4.5a1 1 0 00-.97 1.24L3 4.5V3a1 1 0 00-1-1zm14 0a1 1 0 00-1 1v1h-1a1 1 0 100 2h1v1a1 1 0 102 0V5h1a1 1 0 100-2h-1V1a1 1 0 00-1-1z" />
                    </svg>
                    <span id="cart-counter" class="cart-counter"><?= count($cart_items) ?></span>
                </a>
            </div>
        </div>
    </header>

    <!-- Spacer for fixed header -->
    <div class="h-24"></div>

    <!-- Error Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div
            class="fixed top-24 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
            <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20">
                    <title>Close</title>
                    <path
                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Cart Section -->
    <section class="bg-[color:var(--light-cream)] py-16 relative overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-center mb-10">Your Shopping Cart</h1>

            <div class="lg:flex gap-10">
                <!-- Cart Items Table -->
                <div class="lg:w-2/3 cart-container">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="hidden sm:table-cell">Price</th>
                                <th>Quantity</th>
                                <th class="text-right">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($cart_items)): ?>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td class="flex items-center gap-4">
                                            <img src="<?= htmlspecialchars($item['ImageURL']) ?>"
                                                alt="<?= htmlspecialchars($item['Name']) ?>" class="cart-item-image"
                                                style="width:60px;">
                                            <span class="font-semibold"><?= htmlspecialchars($item['Name']) ?></span>
                                        </td>
                                        <td class="hidden sm:table-cell">$<?= number_format($item['Price'], 2) ?></td>
                                        <td>
                                            <div class="quantity-selector">
                                                <a href="?action=decrease&product_id=<?= $item['ProductID'] ?>"
                                                    class="quantity-button">-</a>
                                                <input type="number" value="<?= $item['Quantity'] ?>" min="1"
                                                    class="quantity-input" disabled>
                                                <a href="?action=increase&product_id=<?= $item['ProductID'] ?>"
                                                    class="quantity-button">+</a>
                                            </div>
                                            <?php if ($item['Quantity'] > $item['StockQuantity']): ?>
                                                <p class="text-red-500 text-sm mt-1">Only <?= $item['StockQuantity'] ?> available
                                                </p>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right font-semibold">
                                            $<?= number_format($item['Price'] * $item['Quantity'], 2) ?>
                                        </td>
                                        <td>
                                            <a href="?action=remove&product_id=<?= $item['ProductID'] ?>"
                                                class="text-red-500 hover:text-red-700">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-lg text-gray-500">
                                        Your cart is empty. <a href="marketplace-home.php"
                                            class="text-blue-600 underline">Start shopping</a>.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/3 mt-10 lg:mt-0 cart-summary">
                    <h2 class="text-3xl font-bold mb-6">Order Summary</h2>
                    <div class="space-y-3">
                        <div class="cart-summary-row">
                            <span>Subtotal</span>
                            <span>$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="cart-summary-row">
                            <span>Shipping</span>
                            <span>$<?= number_format($shipping, 2) ?></span>
                        </div>
                        <div class="cart-summary-row">
                            <span>Tax (8%)</span>
                            <span>$<?= number_format($tax, 2) ?></span>
                        </div>
                        <div class="cart-total-row">
                            <span>Order Total</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 mt-8">
                        <a href="checkout.php" class="btn-primary w-full text-center">Proceed to Checkout</a>
                        <a href="marketplace-home.php" class="btn-secondary w-full text-center">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[color:var(--primary-orange)] text-white py-12 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">
            <div>
                <h3 class="text-2xl font-bold mb-4">Pawsome Adoptions</h3>
                <p class="text-sm">Where every tail finds its wag! We're dedicated to uniting furry hearts with forever
                    homes.</p>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4">Quick Links</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:underline">Adopt a Pet</a></li>
                    <li><a href="#" class="hover:underline">Post for Adoption</a></li>
                    <li><a href="#" class="hover:underline">Success Stories</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4">Support</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:underline">Contact Us</a></li>
                    <li><a href="#" class="hover:underline">Privacy Policy</a></li>
                    <li><a href="#" class="hover:underline">Terms of Service</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4">Follow Us</h3>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-[color:var(--light-orange-accent)]">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.776-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.247 0-1.646.773-1.646 1.572V12h2.77l-.443 2.89h-2.327v6.987C18.343 21.128 22 16.991 22 12z" />
                        </svg>
                    </a>
                    <a href="#" class="hover:text-[color:var(--light-orange-accent)]">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" />
                        </svg>
                    </a>
                    <a href="#" class="hover:text-[color:var(--light-orange-accent)]">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="text-center text-sm mt-12 border-t-2 border-[color:var(--light-orange-accent)] pt-8">
            &copy; <?= date('Y') ?> Pawsome Adoptions. All rights reserved.
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" id="scrollToTopBtn"
        class="fixed bottom-8 right-8 bg-[color:var(--primary-orange)] text-white p-3 rounded-full shadow-lg hidden">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            // Scroll to top button functionality
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');

            // Show/hide scroll to top button based on scroll position
            window.addEventListener('scroll', function () {
                if (window.pageYOffset > 300) {
                    scrollToTopBtn.classList.remove('hidden');
                } else {
                    scrollToTopBtn.classList.add('hidden');
                }
            });

            // Loading spinner for cart actions
            const loadingSpinner = document.getElementById('loading-spinner');

            // Show spinner when clicking on cart action links
            document.querySelectorAll('a[href*="action="]').forEach(link => {
                link.addEventListener('click', function (e) {
                    // Only show spinner for cart actions, not for remove/delete actions
                    if (!this.href.includes('action=remove') && !this.href.includes('action=delete')) {
                        loadingSpinner.classList.remove('hidden');
                    }
                });
            });

            // Hide spinner when page finishes loading (including after AJAX calls if any)
            window.addEventListener('load', function () {
                loadingSpinner.classList.add('hidden');
            });

            // Auto-hide error messages after 5 seconds
            const errorMessage = document.querySelector('.fixed.top-24');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }

            // Quantity input validation (though inputs are disabled in your HTML)
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function () {
                    const min = parseInt(this.getAttribute('min'));
                    const value = parseInt(this.value);

                    if (isNaN(value)) {
                        this.value = min;
                    } else if (value < min) {
                        this.value = min;
                    }
                });
            });

            // Prevent form submission if cart is empty on checkout
            const checkoutBtn = document.querySelector('a[href="checkout.php"]');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', function (e) {
                    const cartItems = <?= json_encode($cart_items) ?>;
                    if (cartItems.length === 0) {
                        e.preventDefault();
                        alert('Your cart is empty. Please add items before checking out.');
                    }
                });
            }
        });

        // Function to update cart counter (could be used with AJAX)
        function updateCartCounter(count) {
            const counter = document.getElementById('cart-counter');
            if (counter) {
                counter.textContent = count;
                // Add animation
                counter.classList.add('animate-ping');
                setTimeout(() => {
                    counter.classList.remove('animate-ping');
                }, 500);
            }
        }

        // Function to show loading spinner
        function showLoading() {
            document.getElementById('loading-spinner').classList.remove('hidden');
        }

        // Function to hide loading spinner
        function hideLoading() {
            document.getElementById('loading-spinner').classList.add('hidden');
        }
    </script>
</body>

</html>