<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Volunteer Dashboard - PetPal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        @keyframes peek-icon {

            0%,
            100% {
                transform: translateX(-140px);
                opacity: 0.6;
            }

            10%,
            90% {
                transform: translateX(0);
                opacity: 1;
            }

            50% {
                transform: translateX(10px);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-peek-icon {
            animation: peek-icon 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-white font-sans text-gray-800">

    <!-- 🐱 Peeking Cat Icon (Flaticon) -->
    <div class="fixed left-0 bottom-10 z-50 pointer-events-none">
        <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Peeking Cat"
            class="w-32 h-32 animate-peek-icon" />
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-20 bg-[#E17C56] flex flex-col items-center py-6 space-y-8 rounded-r-3xl shadow-md">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/paw.png" class="w-8 h-8" alt="Logo" />
            <nav class="flex flex-col gap-6">
                <img src="https://img.icons8.com/ios-glyphs/30/ffffff/home.png" />
                <img src="https://img.icons8.com/ios-glyphs/30/ffffff/cat.png" />
                <img src="https://img.icons8.com/ios-glyphs/30/ffffff/task.png" />
                <img src="https://img.icons8.com/ios-glyphs/30/ffffff/gift.png" />
                <img src="https://img.icons8.com/ios-glyphs/30/ffffff/settings.png" />
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-[#FFF7F5] p-8 flex gap-6 overflow-y-auto">

            <!-- Center Section -->
            <section class="flex-1">

                <!-- Welcome Section -->
                <div class="bg-[#E17C56] text-white rounded-3xl p-6 flex justify-between items-center shadow">
                    <div>
                        <h2 class="text-2xl font-semibold">Welcome, Jack! 🐾</h2>
                        <p class="mt-1">Ready to rescue some furry friends today?</p>
                    </div>
                    <img src="https://img.icons8.com/emoji/96/cat-face.png" class="w-20 h-20 animate-float" alt="Cat" />
                </div>

                <!-- Available Rescue Missions -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-3 text-[#E17C56]">Available Rescue Missions</h3>
                    <div class="space-y-4">

                        <!-- Mission 1 -->
                        <div class="bg-white rounded-xl p-4 shadow-md flex justify-between items-center">
                            <div>
                                <p class="font-medium">🐶 Injured Dog near Riverside</p>
                                <p class="text-sm text-gray-500">Reported: 9:30 AM • Needs first aid & shelter</p>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    class="px-4 py-1 bg-green-500 text-white rounded-xl hover:bg-green-600">Accept</button>
                                <button
                                    class="px-4 py-1 bg-red-500 text-white rounded-xl hover:bg-red-600">Decline</button>
                            </div>
                        </div>

                        <!-- Mission 2 -->
                        <div class="bg-white rounded-xl p-4 shadow-md flex justify-between items-center">
                            <div>
                                <p class="font-medium">🐱 Stray Kitten in Parking Lot</p>
                                <p class="text-sm text-gray-500">Reported: 11:15 AM • Needs rescue and feeding</p>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    class="px-4 py-1 bg-green-500 text-white rounded-xl hover:bg-green-600">Accept</button>
                                <button
                                    class="px-4 py-1 bg-red-500 text-white rounded-xl hover:bg-red-600">Decline</button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Active Missions -->
                <div class="mt-10">
                    <h3 class="text-lg font-semibold mb-3 text-[#E17C56]">Your Active Missions</h3>
                    <div class="space-y-4">
                        <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center">
                            <div>
                                <p class="font-medium">🐕 Walked Max – Dog Shelter</p>
                                <p class="text-sm text-gray-500">Started: 8:00 AM</p>
                            </div>
                            <select class="border border-gray-300 rounded px-3 py-1">
                                <option>Pending</option>
                                <option selected>In Progress</option>
                                <option>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Right Sidebar -->
            <aside class="w-80 bg-white rounded-3xl p-6 shadow shrink-0">
                <div class="flex items-center gap-4 mb-6">
                    <img src="https://i.pravatar.cc/100?img=12" class="w-14 h-14 rounded-full" />
                    <div>
                        <h4 class="text-lg font-semibold">Jack</h4>
                        <p class="text-sm text-gray-500">Volunteer</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h5 class="text-sm font-semibold text-[#E17C56] mb-2">Your Stats</h5>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span>Missions Done</span><span
                                class="font-bold text-blue-600">15</span></div>
                        <div class="flex justify-between"><span>Hours Volunteered</span><span
                                class="font-bold text-green-600">30h</span></div>
                        <div class="flex justify-between"><span>Reward Points</span><span
                                class="font-bold text-yellow-500">480</span></div>
                    </div>
                </div>

                <div>
                    <h5 class="text-sm font-semibold text-[#E17C56] mb-2">Next Event</h5>
                    <div class="flex items-center gap-2">
                        <img src="https://img.icons8.com/color/48/dog-park.png" class="w-10 h-10" />
                        <div class="text-sm">
                            <p class="font-medium">Dog Playdate Festival</p>
                            <p class="text-gray-500">June 25 – 10:00 AM</p>
                        </div>
                    </div>
                </div>
            </aside>

        </main>
    </div>

</body>

</html>