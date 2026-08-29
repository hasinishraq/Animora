<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Vet Dashboard – VetCare Portal</title>

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Poppins:wght@300;600&display=swap"
        rel="stylesheet" />

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3B82F6",
                        secondary: "#60A5FA",
                        accent: "#F97316",
                        pastelGreen: "#D1FAE5",
                        pastelPink: "#FCE7F3",
                        bgLight: "#F9FAFB",
                        textPrimary: "#111827",
                        textSecondary: "#6B7280",
                        warmTaupe: "#a89486",  // main accent
                    },
                    fontFamily: {
                        montserrat: ["Montserrat", "sans-serif"],
                        poppins: ["Poppins", "sans-serif"],
                    },
                    boxShadow: {
                        card: "0 10px 15px -3px rgba(59,130,246,.1), 0 4px 6px -2px rgba(59,130,246,.05)",
                        sidebar: "2px 0 8px rgba(0,0,0,.1)",
                        glow: "0 0 12px 3px rgba(168,148,134,.6)",
                    },
                    animation: {
                        pawBounce: 'pawBounce 2s ease-in-out infinite',
                        pawPulse: 'pawPulse 3s ease-in-out infinite',
                        fadeInUp: 'fadeInUp .8s ease forwards',
                        roamCat: 'roamAround 30s ease-in-out infinite'
                    },
                    keyframes: {
                        pawBounce: {
                            '0%,100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        pawPulse: {
                            '0%,100%': { transform: 'scale(1)', opacity: '1' },
                            '50%': { transform: 'scale(1.1)', opacity: '.7' }
                        },
                        fadeInUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' }
                        },
                        /* roaming cat path – adjust points to taste */
                        roamAround: {
                            '0%': { transform: 'translate(-50%, -50%)' },
                            '20%': { transform: 'translate(-20vw, -40vh)' },
                            '40%': { transform: 'translate(25vw, -15vh)' },
                            '60%': { transform: 'translate(-10vw, 35vh)' },
                            '80%': { transform: 'translate(30vw, 10vh)' },
                            '100%': { transform: 'translate(-50%, -50%)' }
                        }
                    }
                }
            }
        };
    </script>

    <style>
        /* Thin custom scrollbar */
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #9ca3af transparent;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 6px;
        }
    </style>
</head>

<body class="bg-bgLight font-poppins text-textPrimary flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-sidebar sticky top-0 h-screen flex flex-col">
        <div class="px-6 py-8 flex items-center space-x-3 border-b border-gray-200">
            <div class="text-warmTaupe text-3xl font-semibold animate-pawBounce">🐾</div>
            <h1 class="text-2xl font-semibold font-montserrat tracking-wide text-warmTaupe">VetCare</h1>
        </div>

        <nav class="flex-1 px-6 py-8 space-y-2 text-sm font-semibold text-textSecondary">
            <a href="#"
                class="flex items-center gap-3 py-3 px-4 rounded-lg text-warmTaupe bg-warmTaupe/20 hover:bg-warmTaupe/40 transition shadow-glow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12l2-2 4 4 8-8 4 4v6H3z" />
                </svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4m0-4h.01" />
                </svg>
                Appointments
            </a>

            <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H5l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                Messages
            </a>

        </nav>

        <div class="p-6 border-t border-gray-200">
            <div class="flex items-center gap-3">
                <img src="https://i.pravatar.cc/40" alt="Dr. Jane Avatar"
                    class="rounded-full border-2 border-warmTaupe" />
                <div>
                    <p class="font-semibold text-textPrimary">Dr. Jane</p>
                    <p class="text-xs text-textSecondary">Veterinarian</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main content column -->
    <div class="flex-1 flex flex-col">

        <!-- Top Header -->
        <header class="flex justify-between items-center px-8 py-6 bg-white shadow-md sticky top-0 z-30">
            <div>
                <h2 class="text-2xl font-semibold text-textPrimary flex items-center gap-2">
                    Welcome back, Dr. Jane
                    <span class="animate-pawBounce text-warmTaupe text-3xl select-none">🐾</span>
                </h2>
                <p class="text-sm text-textSecondary mt-1">Here’s your pet‑care summary for today.</p>
            </div>

            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="search" placeholder="Search appointments or pets..."
                        class="rounded-full border border-gray-300 px-4 py-2 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-warmTaupe focus:border-transparent transition" />
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </div>
                <img src="https://i.pravatar.cc/40" alt="User avatar"
                    class="rounded-full w-10 h-10 border-2 border-warmTaupe" />
            </div>
        </header>

        <!-- Stat cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 p-8 bg-bgLight">
            <div
                class="bg-white rounded-xl p-6 shadow-card flex items-center space-x-4 hover:shadow-lg transition cursor-pointer animate-fadeInUp">
                <div class="bg-warmTaupe/20 p-3 rounded-full animate-pawPulse">
                    <svg class="w-7 h-7 text-warmTaupe" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 7V3h8v4" />
                        <path d="M4 21h16v-7H4z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-textSecondary uppercase">Appointments</p>
                    <p class="text-3xl font-bold text-textPrimary">08</p>
                </div>
            </div>

            <div
                class="bg-white rounded-xl p-6 shadow-card flex items-center space-x-4 hover:shadow-lg transition cursor-pointer animate-fadeInUp delay-150">
                <div class="bg-accent/20 p-3 rounded-full animate-pawPulse">
                    <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10h-6a4 4 0 1 0 0 8h6" />
                        <path d="M21 14v-4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-textSecondary uppercase">Messages</p>
                    <p class="text-3xl font-bold text-textPrimary">03</p>
                </div>
            </div>

            <div
                class="bg-white rounded-xl p-6 shadow-card flex items-center space-x-4 hover:shadow-lg transition cursor-pointer animate-fadeInUp delay-300">
                <div class="bg-pastelGreen p-3 rounded-full animate-pawPulse">
                    <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="8" />
                        <path d="M12 14v-4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-textSecondary uppercase">New Clients</p>
                    <p class="text-3xl font-bold text-textPrimary">05</p>
                </div>
            </div>

            <div
                class="bg-white rounded-xl p-6 shadow-card flex items-center space-x-4 hover:shadow-lg transition cursor-pointer animate-fadeInUp delay-450">
                <div class="bg-pastelPink p-3 rounded-full animate-pawPulse">
                    <svg class="w-7 h-7 text-pink-400" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-textSecondary uppercase">Pet Reviews</p>
                    <p class="text-3xl font-bold text-textPrimary">12</p>
                </div>
            </div>
        </section>

        <!-- Detail sections -->
        <main class="flex flex-col gap-12 p-8 max-w-7xl mx-auto">

            <!-- Upcoming appointments -->
            <section class="bg-white rounded-xl shadow-card p-8 animate-fadeInUp">
                <h3 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                    <span class="animate-pawBounce">🗓️</span> Upcoming Appointments
                </h3>
                <div class="divide-y divide-gray-200 max-h-72 overflow-y-auto scrollbar-thin">
                    <article
                        class="flex justify-between items-center py-4 hover:bg-warmTaupe/10 rounded-lg px-4 transition cursor-pointer">
                        <div>
                            <p class="font-semibold">🐕 Max (Sarah)</p>
                            <p class="text-sm text-textSecondary">9 :00 AM</p>
                        </div>
                        <button
                            class="bg-warmTaupe text-white rounded-full px-5 py-2 font-medium text-sm hover:bg-opacity-90 transition">View</button>
                    </article>

                    <article
                        class="flex justify-between items-center py-4 hover:bg-warmTaupe/10 rounded-lg px-4 transition cursor-pointer">
                        <div>
                            <p class="font-semibold">🐩 Bella (John)</p>
                            <p class="text-sm text-textSecondary">11 :30 AM</p>
                        </div>
                        <button
                            class="bg-warmTaupe text-white rounded-full px-5 py-2 font-medium text-sm hover:bg-opacity-90 transition">View</button>
                    </article>

                    <article
                        class="flex justify-between items-center py-4 hover:bg-warmTaupe/10 rounded-lg px-4 transition cursor-pointer">
                        <div>
                            <p class="font-semibold">🐈 Luna (Emma)</p>
                            <p class="text-sm text-textSecondary">2 :00 PM</p>
                        </div>
                        <button
                            class="bg-warmTaupe text-white rounded-full px-5 py-2 font-medium text-sm hover:bg-opacity-90 transition">View</button>
                    </article>
                </div>
            </section>

            <!-- Recent messages -->
            <section class="bg-white rounded-xl shadow-card p-8 animate-fadeInUp delay-150">
                <h3 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                    <span class="animate-pawBounce">✉️</span> Recent Messages
                </h3>
                <div class="divide-y divide-gray-200 max-h-72 overflow-y-auto scrollbar-thin">
                    <article
                        class="flex justify-between items-start py-4 hover:bg-secondary/10 rounded-lg px-4 transition cursor-pointer">
                        <div>
                            <p class="font-semibold mb-1">Sarah</p>
                            <p class="text-sm italic text-textSecondary">"Thank you for helping Max last week! He’s
                                doing much better now."</p>
                        </div>
                        <button
                            class="text-warmTaupe underline font-semibold text-sm hover:text-opacity-80 transition">Reply</button>
                    </article>

                    <article
                        class="flex justify-between items-start py-4 hover:bg-secondary/10 rounded-lg px-4 transition cursor-pointer">
                        <div>
                            <p class="font-semibold mb-1">John</p>
                            <p class="text-sm italic text-textSecondary">"Can we reschedule Bella’s vaccination
                                appointment to next week?"</p>
                        </div>
                        <button
                            class="text-warmTaupe underline font-semibold text-sm hover:text-opacity-80 transition">Reply</button>
                    </article>
                </div>
            </section>

        </main>
    </div>

    <!-- Cute roaming cat -->
    <div id="floatingCat" class="fixed top-1/2 left-1/2 text-5xl select-none pointer-events-none animate-roamCat z-50"
        aria-hidden="true">
        🐱
    </div>

</body>

</html>