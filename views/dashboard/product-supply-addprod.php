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

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="mb-10">
            <h1 class="text-4xl font-semibold">➕ Add New Product</h1>
            <p class="text-gray-600 mt-2">Fill the form below to add a new item to your catalogue.</p>
        </header>

        <form class="bg-white rounded-3xl p-8 shadow-soft max-w-3xl mx-auto space-y-6">
            <div>
                <label class="block mb-1 font-semibold">Product Name</label>
                <input type="text"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand"
                    placeholder="Enter product name" />
            </div>
            <div>
                <label class="block mb-1 font-semibold">Category</label>
                <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand">
                    <option>Dog</option>
                    <option>Cat</option>
                    <option>Bird</option>
                    <option>Hamster</option>
                    <option>Rabbit</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Price ($)</label>
                <input type="number" step="0.01"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-brand"
                    placeholder="0.00" />
            </div>
            <div>
                <label class="block mb-1 font-semibold">Upload Image</label>
                <input type="file"
                    class="w-full px-4 py-2 bg-brand-light text-brand rounded-lg border border-dashed border-brand cursor-pointer" />
            </div>
            <button type="submit"
                class="bg-brand text-white px-6 py-3 rounded-full font-semibold hover:bg-brand-dark">Add
                Product</button>
        </form>

        <!-- Fun Animated Pet Icon -->
        <div class="fixed bottom-6 right-6 hover:animate-wiggle cursor-pointer">
            <img src="https://img.icons8.com/color/96/kitten.png" alt="Animated Kitten" class="w-14 h-14" />
        </div>
    </main>
</body>

</html>