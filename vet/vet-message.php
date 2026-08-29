<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Vet Care – Messages</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        warmTaupe: "#a89486",
                        accent: "#60A5FA",
                        pastelGreen: "#D1FAE5",
                        pastelPink: "#FCE7F3",
                        bgLight: "#F9FAFB",
                        textPrimary: "#111827",
                        textSecondary: "#6B7280"
                    },
                    fontFamily: {
                        montserrat: ["Montserrat", "sans-serif"],
                        poppins: ["Poppins", "sans-serif"],
                    },
                    boxShadow: {
                        card: "0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05)",
                        sidebar: "2px 0 8px rgba(0,0,0,0.1)",
                    },
                    animation: {
                        pawBounce: "pawBounce 1.5s ease-in-out infinite",
                        roamDog: "roamDog 22s linear infinite alternate",
                        roamCat: "roamCat 26s ease-in-out infinite alternate",
                        fadeInUp: "fadeInUp 0.5s ease forwards"
                    },
                    keyframes: {
                        pawBounce: {
                            "0%,100%": { transform: "translateY(0)" },
                            "50%": { transform: "translateY(-8px)" }
                        },
                        roamDog: {
                            "0%": { transform: "translate(0,0) rotate(0deg)" },
                            "20%": { transform: "translate(80vw,15vh) rotate(8deg)" },
                            "40%": { transform: "translate(60vw,70vh) rotate(-6deg)" },
                            "60%": { transform: "translate(15vw,85vh) rotate(5deg)" },
                            "80%": { transform: "translate(5vw,40vh) rotate(-4deg)" },
                            "100%": { transform: "translate(0,0) rotate(0deg)" }
                        },
                        roamCat: {
                            "0%": { transform: "translate(90vw,10vh) rotate(0deg)" },
                            "25%": { transform: "translate(70vw,60vh) rotate(-8deg)" },
                            "50%": { transform: "translate(30vw,35vh) rotate(6deg)" },
                            "75%": { transform: "translate(10vw,75vh) rotate(-5deg)" },
                            "100%": { transform: "translate(90vw,10vh) rotate(0deg)" }
                        },
                        fadeInUp: {
                            "0%": { opacity: 0, transform: "translateY(15px)" },
                            "100%": { opacity: 1, transform: "translateY(0)" }
                        }
                    }
                }
            }
        };
    </script>
    <style>
        /* Thin custom scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 3px;
        }
    </style>
</head>

<body class="bg-bgLight font-poppins text-textPrimary flex min-h-screen relative overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-sidebar sticky top-0 h-screen flex flex-col">
        <div class="px-6 py-8 flex items-center space-x-3 border-b border-gray-200">
            <div class="text-warmTaupe text-3xl font-semibold animate-pawBounce">🐾</div>
            <h1 class="text-2xl font-semibold font-montserrat tracking-wide text-warmTaupe">VetCare</h1>
        </div>

        <nav class="flex-1 px-6 py-8 space-y-2 text-sm font-semibold text-textSecondary">
            <a href="vet-dashboard.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg text-warmTaupe bg-warmTaupe/20 hover:bg-warmTaupe/40 transition shadow-glow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12l2-2 4 4 8-8 4 4v6H3z" />
                </svg>
                Dashboard
            </a>
            <a href="vet-appointment.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4m0-4h.01" />
                </svg>
                Appointments
            </a>
            <a href="vet-manage-slots.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Manage Slots
            </a>
            <a href="vet-message.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H5l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                Messages
            </a>

            <a href="../auth/logout.php"
                class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-warmTaupe/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H5l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                Logout
            </a>


        </nav>

        <div class="p-6 border-t border-gray-200">
            <div class="flex items-center gap-3">
                <img src="<?php echo htmlspecialchars($profile_photo); ?>"
                    alt="<?php echo htmlspecialchars($user_name); ?> Avatar"
                    class="rounded-full border-2 border-warmTaupe" />

                <div>
                    <p class="font-semibold text-textPrimary"><?php echo htmlspecialchars($user_name); ?></p>
                    <p class="text-xs text-textSecondary">Veterinarian</p>
                </div>
            </div>
        </div>

    </aside>

    <!-- Mobile header -->
    <header class="bg-white shadow-md sticky top-0 z-40 flex items-center justify-between px-4 py-4 md:hidden">
        <button id="mobile-menu-btn" aria-label="Toggle menu" class="text-warmTaupe focus:outline-none">
            <!-- Hamburger icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="flex items-center gap-3 select-none">
            <div class="text-warmTaupe text-3xl font-semibold animate-pawBounce">🐾</div>
            <h1 class="text-2xl font-semibold font-montserrat tracking-wide text-warmTaupe">Vet Care</h1>
        </div>
        <div></div> <!-- empty to balance flex -->
    </header>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header
            class="hidden md:flex justify-between items-center px-8 py-6 bg-white shadow-md sticky top-0 z-30 select-none">
            <div>
                <h2 class="text-2xl font-semibold flex items-center gap-2">Welcome back, <span
                        class="text-warmTaupe">Dr. Jane</span> <span
                        class="animate-pawBounce text-warmTaupe text-3xl">🐾</span></h2>
                <p class="text-sm text-textSecondary mt-1">Here are your latest pet owner messages.</p>
            </div>
        </header>

        <main class="p-6 md:p-8 bg-bgLight flex-grow overflow-y-auto scrollbar-thin">
            <section class="bg-white rounded-xl shadow-card p-6 md:p-8 animate-fadeInUp max-w-4xl mx-auto">
                <h3 class="text-2xl font-semibold mb-6 flex items-center gap-2"><span
                        class="animate-pawBounce">✉️</span> All Messages</h3>

                <div class="space-y-6 max-h-[60vh] overflow-y-auto scrollbar-thin pr-2">
                    <!-- Message 1 -->
                    <article class="space-y-2">
                        <div
                            class="flex justify-between items-start hover:bg-warmTaupe/10 p-4 rounded-lg transition cursor-pointer">
                            <div>
                                <p class="font-semibold mb-1">Sarah</p>
                                <p class="text-sm italic text-textSecondary">“Max is doing much better now. Thank you
                                    for your care!”</p>
                            </div>
                            <button
                                class="toggle-reply text-warmTaupe underline font-semibold text-sm hover:text-opacity-80">Reply</button>
                        </div>
                        <div class="reply-box hidden px-4">
                            <textarea class="w-full border border-gray-300 rounded-lg p-2 text-sm" rows="3"
                                placeholder="Write your reply..."></textarea>
                            <button
                                class="mt-2 bg-warmTaupe text-white px-4 py-1 rounded-full text-sm hover:bg-opacity-90 transition">Send</button>
                        </div>
                    </article>

                    <!-- Message 2 -->
                    <article class="space-y-2">
                        <div
                            class="flex justify-between items-start hover:bg-warmTaupe/10 p-4 rounded-lg transition cursor-pointer">
                            <div>
                                <p class="font-semibold mb-1">John</p>
                                <p class="text-sm italic text-textSecondary">“Can we reschedule Bella’s vaccination to
                                    Friday?”</p>
                            </div>
                            <button
                                class="toggle-reply text-warmTaupe underline font-semibold text-sm hover:text-opacity-80">Reply</button>
                        </div>
                        <div class="reply-box hidden px-4">
                            <textarea class="w-full border border-gray-300 rounded-lg p-2 text-sm" rows="3"
                                placeholder="Write your reply..."></textarea>
                            <button
                                class="mt-2 bg-warmTaupe text-white px-4 py-1 rounded-full text-sm hover:bg-opacity-90 transition">Send</button>
                        </div>
                    </article>

                    <!-- Message 3 -->
                    <article class="space-y-2">
                        <div
                            class="flex justify-between items-start hover:bg-warmTaupe/10 p-4 rounded-lg transition cursor-pointer">
                            <div>
                                <p class="font-semibold mb-1">Emma</p>
                                <p class="text-sm italic text-textSecondary">“Luna has started eating again! Just wanted
                                    to update you.”</p>
                            </div>
                            <button
                                class="toggle-reply text-warmTaupe underline font-semibold text-sm hover:text-opacity-80">Reply</button>
                        </div>
                        <div class="reply-box hidden px-4">
                            <textarea class="w-full border border-gray-300 rounded-lg p-2 text-sm" rows="3"
                                placeholder="Write your reply..."></textarea>
                            <button
                                class="mt-2 bg-warmTaupe text-white px-4 py-1 rounded-full text-sm hover:bg-opacity-90 transition">Send</button>
                        </div>
                    </article>
                </div>
            </section>
        </main>
    </div>

    <!-- Floating Pets roaming full-screen -->
    <div class="absolute inset-0 pointer-events-none select-none z-10">
        <div class="absolute text-5xl animate-roamDog">🐶</div>
        <div class="absolute text-5xl animate-roamCat">🐱</div>
    </div>

    <script>
        // Toggle reply boxes
        document.querySelectorAll('.toggle-reply').forEach(btn => {
            btn.addEventListener('click', () => {
                const box = btn.parentElement.nextElementSibling;
                box.classList.toggle('hidden');
                btn.textContent = box.classList.contains('hidden') ? 'Reply' : 'Cancel';
            });
        });

        // Mobile menu toggle
        const btn = document.getElementById('mobile-menu-btn');
        const sidebar = document.querySelector('aside');
        btn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });
    </script>
</body>

</html>