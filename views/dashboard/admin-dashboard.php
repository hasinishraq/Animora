<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pet Platform Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#FF8051',
                        'brand-light': '#FFA577',
                    },
                    boxShadow: {
                        soft: '0 4px 12px rgba(0, 0, 0, 0.1)'
                    },
                    keyframes: {
                        pulsePop: {
                            '0%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.05)' },
                            '100%': { transform: 'scale(1)' },
                        }
                    },
                    animation: {
                        pulsePop: 'pulsePop 2s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
</head>

<body class="flex bg-white min-h-screen font-sans text-gray-800">

    <!-- Sidebar -->
    <aside
        class="w-20 bg-brand rounded-tr-3xl rounded-br-3xl flex flex-col items-center py-8 space-y-10 text-white shadow-lg">
        <!-- Dashboard -->
        <button title="Dashboard" class="hover:text-brand-light w-6 h-6" aria-label="Dashboard">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 10.5L12 3l9 7.5v9.75a.75.75 0 01-.75.75H3.75a.75.75 0 01-.75-.75V10.5z" />
            </svg>
        </button>

        <!-- Users -->
        <button title="Users" class="hover:text-brand-light w-6 h-6" aria-label="Users">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A4 4 0 019 15h6a4 4 0 013.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
            </svg>
        </button>

        <!-- Approve Posts -->
        <button title="Approve Posts" class="hover:text-brand-light w-6 h-6" aria-label="Approve Posts">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l4.5 4.5 10.5-10.5" />
            </svg>
        </button>

        <!-- Blocked Users -->
        <button title="Blocked Users" class="hover:text-brand-light w-6 h-6" aria-label="Blocked Users">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.64 5.64l12.72 12.72" />
            </svg>
        </button>

        <!-- Settings -->
        <button title="Settings" class="hover:text-brand-light w-6 h-6 mt-auto" aria-label="Settings">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3.75" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 2.25v1.5M12 20.25v1.5M4.5 4.5l1.06 1.06M18.44 18.44l1.06 1.06M2.25 12h1.5M20.25 12h1.5M4.5 19.5l1.06-1.06M18.44 5.56l1.06-1.06" />
            </svg>
        </button>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">
        <h1 class="text-3xl font-bold text-brand mb-6">Welcome, Admin!</h1>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Users Card -->
            <div
                class="bg-white p-6 rounded-xl shadow-soft hover:shadow-md hover:scale-105 transition-all duration-300 cursor-pointer animate-pulsePop">
                <div class="flex items-center space-x-4">
                    <div class="bg-brand p-3 rounded-full">
                        🐾
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">Total Users</p>
                        <p class="text-2xl font-bold text-brand">1,205</p>
                    </div>
                </div>
            </div>

            <!-- Pending Posts Card -->
            <div
                class="bg-white p-6 rounded-xl shadow-soft hover:shadow-md hover:scale-105 transition-all duration-300 cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="bg-brand p-3 rounded-full">
                        📤
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">Pending Posts</p>
                        <p class="text-2xl font-bold text-brand">37</p>
                    </div>
                </div>
            </div>

            <!-- Blocked Users Card -->
            <div
                class="bg-white p-6 rounded-xl shadow-soft hover:shadow-md hover:scale-105 transition-all duration-300 cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="bg-brand p-3 rounded-full">
                        🚫
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700">Blocked Users</p>
                        <p class="text-2xl font-bold text-brand">12</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Placeholder for tables, post lists, etc. -->
        <div class="mt-10 p-6 border rounded-xl shadow-inner">
            <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
            <p class="text-gray-500">Feature under development.</p>
        </div>

    </main>
</body>

</html>