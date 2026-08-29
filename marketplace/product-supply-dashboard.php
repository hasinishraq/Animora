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
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pet Product Supplier Dashboard</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#FCBF01',
                            light: '#FFFBEB',
                            dark: '#B45309',
                        },
                        petgray: '#4B5563',
                    },
                    boxShadow: {
                        soft: '0 8px 20px rgba(0,0,0,.08)',
                    },
                    keyframes: {
                        float: {
                            '0%,100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        wiggle: {
                            '0%,100%': { transform: 'rotate(-3deg)' },
                            '50%': { transform: 'rotate(3deg)' },
                        },
                        peekRight: {
                            '0%': { right: '-220px', opacity: '0' },
                            '20%': { right: '-40px', opacity: '1' },
                            '50%': { right: '-20px', opacity: '1' },
                            '80%': { right: '-40px', opacity: '1' },
                            '100%': { right: '-220px', opacity: '0' },
                        },
                        peekLeft: {
                            '0%': { left: '-220px', opacity: '0' },
                            '20%': { left: '-40px', opacity: '1' },
                            '50%': { left: '-20px', opacity: '1' },
                            '80%': { left: '-40px', opacity: '1' },
                            '100%': { left: '-220px', opacity: '0' },
                        },
                        peekTop: {
                            '0%': { top: '-220px', opacity: '0' },
                            '20%': { top: '-40px', opacity: '1' },
                            '50%': { top: '-20px', opacity: '1' },
                            '80%': { top: '-40px', opacity: '1' },
                            '100%': { top: '-220px', opacity: '0' },
                        },
                        peekBottom: {
                            '0%': { bottom: '-220px', opacity: '0' },
                            '20%': { bottom: '-40px', opacity: '1' },
                            '50%': { bottom: '-20px', opacity: '1' },
                            '80%': { bottom: '-40px', opacity: '1' },
                            '100%': { bottom: '-220px', opacity: '0' },
                        },
                    },
                    animation: {
                        float: 'float 4s ease-in-out infinite',
                        wiggle: 'wiggle 2.5s ease-in-out infinite',
                        peekRight: 'peekRight 8s ease-in-out infinite',
                        peekLeft: 'peekLeft 8s ease-in-out infinite',
                        peekTop: 'peekTop 8s ease-in-out infinite',
                        peekBottom: 'peekBottom 8s ease-in-out infinite',
                    },
                },
            },
        };
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #fcbf0177;
            border-radius: 8px;
        }

        /* floating paw prints */
        .paw {
            position: fixed;
            width: 40px;
            height: 40px;
            opacity: 0.22;
            animation: float 7s infinite;
            filter: drop-shadow(0 0 2px rgba(180, 83, 9, 0.6));
            pointer-events: none;
            user-select: none;
        }

        /* Peeking cats */
        .peek-cat {
            position: fixed;
            width: 180px;
            z-index: 50;
            pointer-events: none;
            filter: drop-shadow(0 0 3px rgba(0, 0, 0, 0.7));
            user-select: none;
        }

        .peek-cat.right {
            bottom: 0;
            right: -220px;
            animation: peekRight 8s ease-in-out infinite;
        }

        .peek-cat.left {
            top: 50%;
            left: -220px;
            transform: translateY(-50%);
            animation: peekLeft 8s ease-in-out infinite;
            animation-delay: 2s;
        }

        .peek-cat.top {
            top: -220px;
            left: 50%;
            transform: translateX(-50%);
            animation: peekTop 8s ease-in-out infinite;
            animation-delay: 4s;
        }

        .peek-cat.bottom {
            bottom: -220px;
            left: 50%;
            transform: translateX(-50%);
            animation: peekBottom 8s ease-in-out infinite;
            animation-delay: 6s;
        }
    </style>
</head>

<body class="bg-brand-light min-h-screen flex text-petgray">

    <!-- Sidebar -->
    <aside
        class="w-16 bg-brand rounded-tr-3xl rounded-br-3xl flex flex-col items-center py-8 space-y-8 text-white shadow-soft select-none">

        <!-- Dashboard -->
        <button title="Dashboard" class="hover:text-brand-light w-6 h-6" aria-label="Dashboard">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 10.5L12 3l9 7.5v9.75a.75.75 0 01-.75.75H3.75a.75.75 0 01-.75-.75V10.5z" />
            </svg>
        </button>

        <!-- Products -->
        <button title="Products" class="hover:text-brand-light w-6 h-6" aria-label="Products">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 6.75L12 12l9.75-5.25M2.25 17.25L12 22.5l9.75-5.25M2.25 6.75v10.5M21.75 6.75v10.5" />
            </svg>
        </button>

        <!-- Add product -->
        <button title="Add Product" class="hover:text-brand-light w-6 h-6" aria-label="Add Product">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </button>

        <!-- Orders -->
        <button title="Orders" class="hover:text-brand-light w-6 h-6" aria-label="Orders">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="3" width="18" height="18" rx="2" />
            </svg>
        </button>

        <!-- Inventory -->
        <button title="Inventory" class="hover:text-brand-light w-6 h-6" aria-label="Inventory">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Settings -->
        <button title="Settings" class="hover:text-brand-light w-6 h-6 mt-auto" aria-label="Settings">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="3.75" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 2.25v1.5M12 20.25v1.5M4.5 4.5l1.06 1.06M18.44 18.44l1.06 1.06M2.25 12h1.5M20.25 12h1.5M4.5 19.5l1.06-1.06M18.44 5.56l1.06-1.06" />
            </svg>
        </button>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-8 overflow-y-auto min-h-screen">
        <header class="mb-10">
            <h1 class="text-4xl font-semibold mb-4 text-center text-blue-600">
    Welcome back, <span class="text-green-500"><?php echo htmlspecialchars($user_name); ?></span> 👋
</h1>

            <p class="text-lg text-gray-600">Quick view of your products &amp; orders.</p>
        </header>

        <!-- Stats -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12 select-none">
            <article
                class="bg-white rounded-3xl p-6 shadow-soft flex items-center gap-4 hover:shadow-lg transition cursor-default">
                <img src="https://img.icons8.com/color/96/dog-footprint.png" class="w-16 h-16 animate-float"
                    alt="Dog footprint icon" />
                <div>
                    <p class="uppercase text-sm font-semibold text-gray-400">Total Products</p>
                    <h3 class="text-3xl font-bold text-brand"><?php echo $total_products; ?></h3>
                </div>
            </article>

            <article
                class="bg-white rounded-3xl p-6 shadow-soft flex items-center gap-4 hover:shadow-lg transition cursor-default">
                <img src="https://img.icons8.com/color/96/cat-footprint.png" class="w-16 h-16 animate-wiggle"
                    alt="Cat footprint icon" />
                <div>
                    <p class="uppercase text-sm font-semibold text-gray-400">Pending Orders</p>
                    <h3 class="text-3xl font-bold text-brand">12</h3>
                </div>
            </article>

            <article
                class="bg-white rounded-3xl p-6 shadow-soft flex items-center gap-4 hover:shadow-lg transition cursor-default">
                <img src="https://img.icons8.com/color/96/hamster.png" class="w-16 h-16 animate-float"
                    alt="Hamster icon" />
                <div>
                    <p class="uppercase text-sm font-semibold text-gray-400">Delivered Items</p>
                    <h3 class="text-3xl font-bold text-brand">37</h3>
                </div>
            </article>
        </section>

        <!-- Action cards -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <article
                class="relative bg-white rounded-3xl p-6 shadow-soft overflow-hidden hover:shadow-lg transition cursor-default">
                <img src="https://img.icons8.com/clouds/200/husky.png"
                    class="absolute -top-12 -right-8 w-40 animate-float select-none pointer-events-none"
                    alt="Husky icon" />
                <div class="relative z-10">
                    <h3 class="text-2xl font-semibold text-brand mb-2">Add New Product</h3>
                    <p class="text-gray-600 mb-6">Expand your catalogue instantly.</p>
                    <!-- Add product -->
<a href="/animora/marketplace/product-supply-addprod.php" title="Add Product">
    <button class="bg-brand hover:bg-brand-dark text-white px-5 py-2 rounded-full font-semibold transition focus:outline-none focus:ring-2 focus:ring-brand-dark">
        Add Product
    </button>
</a>

                </div>
            </article>

            <article
                class="relative bg-white rounded-3xl p-6 shadow-soft overflow-hidden hover:shadow-lg transition cursor-default">
                <img src="https://img.icons8.com/clouds/200/cat.png"
                    class="absolute -top-12 -right-8 w-40 animate-wiggle select-none pointer-events-none"
                    alt="Cat icon" />
                <div class="relative z-10">
                    <h3 class="text-2xl font-semibold text-brand mb-2">Manage Inventory</h3>
                    <p class="text-gray-600 mb-6">Keep stock levels in check.</p>
                    <button
                        class="bg-brand hover:bg-brand-dark text-white px-5 py-2 rounded-full font-semibold transition focus:outline-none focus:ring-2 focus:ring-brand-dark">
                        Manage Stock
                    </button>
                </div>
            </article>

            <article
                class="relative bg-white rounded-3xl p-6 shadow-soft overflow-hidden hover:shadow-lg transition cursor-default">
                <img src="https://img.icons8.com/clouds/200/bird.png"
                    class="absolute -top-12 -right-8 w-40 animate-float select-none pointer-events-none"
                    alt="Bird icon" />
                <div class="relative z-10">
                    <h3 class="text-2xl font-semibold text-brand mb-2">Track Orders</h3>
                    <p class="text-gray-600 mb-6">Monitor shipping progress.</p>
                    <button
                        class="bg-brand hover:bg-brand-dark text-white px-5 py-2 rounded-full font-semibold transition focus:outline-none focus:ring-2 focus:ring-brand-dark">
                        View Orders
                    </button>
                </div>
            </article>
        </section>
    </main>

    <!-- Floating paws -->
    <img src="https://img.icons8.com/color/48/dog-paw-print.png" class="paw" style="top:10%; left:6%;" alt="Dog paw" />
    <img src="https://img.icons8.com/color/48/cat-paw-print.png" class="paw"
        style="top:32%; left:22%; animation-delay:1.2s" alt="Cat paw" />
    <img src="https://img.icons8.com/color/48/hamster.png" class="paw" style="top:55%; left:48%; animation-delay:2.4s"
        alt="Hamster paw" />
    <img src="https://img.icons8.com/color/48/bird.png" class="paw" style="top:78%; left:80%; animation-delay:3.6s"
        alt="Bird paw" />

    <!-- Peeking cats animation -->
    <img src="https://img.icons8.com/external-flatart-icons-outline-flatarticons/96/000000/external-cat-animal-flatart-icons-outline-flatarticons-1.png"
        alt="peeking cat right" class="peek-cat right" />
    <img src="https://img.icons8.com/external-flatart-icons-outline-flatarticons/96/000000/external-cat-animal-flatart-icons-outline-flatarticons-1.png"
        alt="peeking cat left" class="peek-cat left" />
    <img src="https://img.icons8.com/external-flatart-icons-outline-flatarticons/96/000000/external-cat-animal-flatart-icons-outline-flatarticons-1.png"
        alt="peeking cat top" class="peek-cat top" />
    <img src="https://img.icons8.com/external-flatart-icons-outline-flatarticons/96/000000/external-cat-animal-flatart-icons-outline-flatarticons-1.png"
        alt="peeking cat bottom" class="peek-cat bottom" />

</body>

</html>