<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin – Approve Posts (Card Layout with Floating Pets)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
                    },
                    animation: {
                        bounceX: 'bounceX 1s infinite',
                    },
                    keyframes: {
                        bounceX: {
                            '0%, 100%': { transform: 'translateX(0)' },
                            '50%': { transform: 'translateX(-5px)' },
                        },
                        floatPaw: {
                            '0%': { transform: 'translateY(0) rotate(0deg)', opacity: '0.3' },
                            '50%': { opacity: '0.6' },
                            '100%': { transform: 'translateY(-100vh) rotate(360deg)', opacity: '0' },
                        },
                        floatUpDown: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        },
                        driftSide: {
                            '0%, 100%': { transform: 'translateX(0)' },
                            '50%': { transform: 'translateX(10px)' },
                        },
                    },
                },
            },
        };
    </script>

    <style>
        /* Floating Paw Animation */
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

        /* Floating Pet Animations */
        .floating-pet {
            position: fixed;
            font-size: 3rem;
            opacity: 0.15;
            pointer-events: none;
            user-select: none;
            animation-timing-function: ease-in-out;
            z-index: 0;
        }

        .pet1 {
            top: 10%;
            left: 15%;
            animation: floatUpDown 6s infinite alternate;
        }

        .pet2 {
            top: 30%;
            left: 80%;
            animation: floatUpDown 5s infinite alternate;
            animation-delay: 1s;
        }

        .pet3 {
            top: 60%;
            left: 25%;
            animation: driftSide 7s infinite alternate;
        }

        .pet4 {
            top: 75%;
            left: 70%;
            animation: floatUpDown 8s infinite alternate;
            animation-delay: 2s;
        }

        .pet5 {
            top: 50%;
            left: 50%;
            animation: driftSide 6s infinite alternate;
        }

        .pet6 {
            top: 85%;
            left: 40%;
            animation: floatUpDown 5s infinite alternate;
            animation-delay: 1.5s;
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

    <!-- Floating cute pet emojis -->
    <div>
        <div class="floating-pet pet1">🐶</div>
        <div class="floating-pet pet2">🐱</div>
        <div class="floating-pet pet3">🐾</div>
        <div class="floating-pet pet4">🐕</div>
        <div class="floating-pet pet5">🐈</div>
        <div class="floating-pet pet6">🐩</div>
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
        <button title="Approve Posts" class="text-white w-6 h-6" aria-label="Approve Posts">
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
        <h1 class="text-3xl font-bold text-brand mb-8">📥 Approve Posts</h1>

        <div class="flex flex-wrap gap-6">

            <!-- Card example -->
            <div
                class="bg-white shadow-soft rounded-xl p-6 w-full sm:w-[48%] lg:w-[31%] hover:shadow-lg transition-shadow relative">
                <h2 class="font-semibold text-lg mb-2 flex items-center gap-2">
                    🐕 Found a stray near Park
                </h2>
                <p class="text-gray-600 mb-4"><strong>User:</strong> John Paw</p>
                <p class="mb-4">
                    <span
                        class="inline-block bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-semibold">Lost</span>
                </p>
                <p class="text-gray-500 text-sm mb-6">Submitted on 2024-08-01</p>
                <div class="flex justify-between">
                    <button
                        class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 text-sm animate-bounceX">
                        Approve
                    </button>
                    <button
                        class="bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-200 text-sm animate-bounceX">
                        Reject
                    </button>
                </div>
            </div>

            <div
                class="bg-white shadow-soft rounded-xl p-6 w-full sm:w-[48%] lg:w-[31%] hover:shadow-lg transition-shadow relative">
                <h2 class="font-semibold text-lg mb-2 flex items-center gap-2">
                    🐈 3 kittens for adoption
                </h2>
                <p class="text-gray-600 mb-4"><strong>User:</strong> Luna Catson</p>
                <p class="mb-4">
                    <span
                        class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-semibold">Adopt</span>
                </p>
                <p class="text-gray-500 text-sm mb-6">Submitted on 2024-07-30</p>
                <div class="flex justify-between">
                    <button
                        class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 text-sm animate-bounceX">
                        Approve
                    </button>
                    <button
                        class="bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-200 text-sm animate-bounceX">
                        Reject
                    </button>
                </div>
            </div>

            <!-- Add more cards here -->

        </div>
    </main>
</body>

</html>