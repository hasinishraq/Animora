<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin – User Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
                        soft: '0 4px 12px rgba(0,0,0,0.1)',
                    }
                }
            }
        }
    </script>

    <!-- Floating Paw Animation -->
    <style>
        @keyframes floatPaw {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg);
                opacity: 0.3;
            }

            50% {
                opacity: 0.6;
            }

            100% {
                transform: translateY(-100vh) translateX(20px) rotate(360deg);
                opacity: 0;
            }
        }

        .paw {
            position: absolute;
            font-size: 2rem;
            animation: floatPaw linear infinite;
            opacity: 0.3;
            pointer-events: none;
            z-index: 0;
        }

        .paw:nth-child(1) {
            left: 10%;
            animation-duration: 18s;
            animation-delay: 0s;
        }

        .paw:nth-child(2) {
            left: 25%;
            animation-duration: 22s;
            animation-delay: 4s;
        }

        .paw:nth-child(3) {
            left: 40%;
            animation-duration: 20s;
            animation-delay: 2s;
        }

        .paw:nth-child(4) {
            left: 60%;
            animation-duration: 25s;
            animation-delay: 6s;
        }

        .paw:nth-child(5) {
            left: 75%;
            animation-duration: 21s;
            animation-delay: 1s;
        }

        .paw:nth-child(6) {
            left: 90%;
            animation-duration: 19s;
            animation-delay: 5s;
        }
    </style>
</head>

<body class="flex bg-white min-h-screen text-gray-800 font-sans relative overflow-hidden">

    <!-- Floating Paw Background -->
    <div class="fixed top-full w-full h-full overflow-hidden -z-10">
        <div class="paw">🐾</div>
        <div class="paw">🐾</div>
        <div class="paw">🐾</div>
        <div class="paw">🐾</div>
        <div class="paw">🐾</div>
        <div class="paw">🐾</div>
    </div>

    <!-- Sidebar -->
    <aside
        class="w-20 bg-brand rounded-tr-3xl rounded-br-3xl flex flex-col items-center py-8 space-y-10 text-white shadow-lg z-10">
        <button title="Dashboard" class="hover:text-brand-light w-6 h-6" aria-label="Dashboard">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 10.5L12 3l9 7.5v9.75a.75.75 0 01-.75.75H3.75a.75.75 0 01-.75-.75V10.5z" />
            </svg>
        </button>

        <button title="Users" class="hover:text-white w-6 h-6" aria-label="Users">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A4 4 0 019 15h6a4 4 0 013.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
            </svg>
        </button>

        <button title="Approve Posts" class="hover:text-brand-light w-6 h-6" aria-label="Approve Posts">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l4.5 4.5 10.5-10.5" />
            </svg>
        </button>

        <button title="Blocked Users" class="hover:text-brand-light w-6 h-6" aria-label="Blocked Users">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.64 5.64l12.72 12.72" />
            </svg>
        </button>

        <button title="Settings" class="hover:text-brand-light w-6 h-6 mt-auto" aria-label="Settings">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3.75" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 2.25v1.5M12 20.25v1.5M4.5 4.5l1.06 1.06M18.44 18.44l1.06 1.06M2.25 12h1.5M20.25 12h1.5M4.5 19.5l1.06-1.06M18.44 5.56l1.06-1.06" />
            </svg>
        </button>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 px-10 py-8 bg-gray-50 relative z-10">
        <h1 class="text-3xl font-bold text-brand mb-6">👥 User Management</h1>

        <div class="bg-white p-6 rounded-xl shadow-soft overflow-x-auto">
            <table class="min-w-full text-left text-sm text-gray-600">
                <thead>
                    <tr class="border-b border-gray-200 bg-brand text-white">
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Joined</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-6 py-4 font-semibold">🐶 John Paw</td>
                        <td class="px-6 py-4">johnpaw@example.com</td>
                        <td class="px-6 py-4">
                            <span class="text-green-600 bg-green-100 px-2 py-1 text-xs rounded-full">Active</span>
                        </td>
                        <td class="px-6 py-4">2024-08-01</td>
                        <td class="px-6 py-4 text-center">
                            <button
                                class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 text-xs">Block</button>
                            <button
                                class="ml-2 bg-gray-100 text-gray-800 px-3 py-1 rounded hover:bg-gray-200 text-xs">Details</button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-6 py-4 font-semibold">🐱 Luna Catson</td>
                        <td class="px-6 py-4">luna.catson@example.com</td>
                        <td class="px-6 py-4">
                            <span class="text-red-600 bg-red-100 px-2 py-1 text-xs rounded-full">Blocked</span>
                        </td>
                        <td class="px-6 py-4">2024-07-12</td>
                        <td class="px-6 py-4 text-center">
                            <button
                                class="bg-green-100 text-green-700 px-3 py-1 rounded hover:bg-green-200 text-xs">Unblock</button>
                            <button
                                class="ml-2 bg-gray-100 text-gray-800 px-3 py-1 rounded hover:bg-gray-200 text-xs">Details</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>