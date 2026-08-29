<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Orders - Pet Supplier</title>
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
                        spinbounce: {
                            '0%, 100%': { transform: 'rotate(0deg) scale(1)' },
                            '50%': { transform: 'rotate(10deg) scale(1.1)' }
                        }
                    },
                    animation: {
                        float: 'float 4s ease-in-out infinite',
                        wiggle: 'wiggle 2.5s ease-in-out infinite',
                        spinbounce: 'spinbounce 3s ease-in-out infinite'
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

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #fcbf0177;
            border-radius: 8px;
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
        <header class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-10">
            <h1 class="text-4xl font-semibold">📦 Orders Overview</h1>
            <div class="flex gap-4">
                <select class="px-4 py-2 rounded-lg bg-white shadow-soft text-petgray">
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>Shipped</option>
                    <option>Delivered</option>
                    <option>Cancelled</option>
                </select>
            </div>
        </header>

        <!-- Orders List -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl p-6 shadow-soft hover:shadow-lg transition group">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://img.icons8.com/color/96/pet-commands-train.png" class="w-14 h-14 animate-wiggle"
                        alt="Order icon">
                    <div>
                        <h2 class="text-xl font-semibold">Order #1023</h2>
                        <p class="text-sm text-gray-500">Placed on: Jun 19, 2025</p>
                    </div>
                </div>
                <ul class="text-gray-600 text-sm space-y-1">
                    <li>🐶 2x Chew Bone</li>
                    <li>🐱 1x Yarn Ball</li>
                </ul>
                <div class="flex justify-between items-center mt-4">
                    <select class="text-sm bg-brand-light text-brand px-3 py-1 rounded hover:bg-brand">
                        <option>Pending</option>
                        <option>Shipped</option>
                        <option>Delivered</option>
                    </select>
                    <button class="text-sm bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">Cancel</button>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-soft hover:shadow-lg transition group">
                <div class="flex items-center gap-4 mb-4">
                    <img src="https://img.icons8.com/color/96/rabbit.png" class="w-14 h-14 animate-float"
                        alt="Order icon">
                    <div>
                        <h2 class="text-xl font-semibold">Order #1024</h2>
                        <p class="text-sm text-gray-500">Placed on: Jun 18, 2025</p>
                    </div>
                </div>
                <ul class="text-gray-600 text-sm space-y-1">
                    <li>🐦 1x Bird Seed Mix</li>
                    <li>🐹 1x Hamster Wheel</li>
                </ul>
                <div class="flex justify-between items-center mt-4">
                    <select class="text-sm bg-brand-light text-brand px-3 py-1 rounded hover:bg-brand">
                        <option>Shipped</option>
                        <option>Delivered</option>
                    </select>
                    <button class="text-sm bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">Cancel</button>
                </div>
            </div>
        </section>

        <!-- Animated Pet Icon -->
        <div class="fixed bottom-6 right-6 animate-spinbounce">
            <img src="https://img.icons8.com/color/96/cat-footprint.png" alt="Bouncing Paw" class="w-12 h-12">
        </div>
    </main>
</body>

</html>