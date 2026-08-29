<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Active Missions - PetPal</title>
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

    <!-- 🐱 Peeking Cat Icon -->
    <div class="fixed left-0 bottom-10 z-50 pointer-events-none">
        <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Peeking Cat"
            class="w-32 h-32 animate-peek-icon" />
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-20 bg-[#E17C56] flex flex-col items-center py-6 space-y-8 rounded-r-3xl shadow-md">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/paw.png" class="w-8 h-8" alt="Logo" />
            <nav class="flex flex-col gap-6">
                <img src="https://img.icons8.com/ios-glyphs/30/ffffff/home.png" alt="Home" />
                <img src="https://img.icons8.com/ios-glyphs/30/ffffff/cat.png" alt="Missions" />
                <img src="https://img.icons8.com/ios-glyphs/30/ffffff/task.png" alt="Tasks" />
                <img src="https://img.icons8.com/ios-glyphs/30/ffffff/gift.png" alt="Rewards" />
                <img src="https://img.icons8.com/ios-glyphs/30/ffffff/settings.png" alt="Settings" />
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-[#FFF7F5] p-8 flex flex-col overflow-y-auto">

            <!-- Page Header -->
            <div class="bg-[#E17C56] text-white rounded-3xl p-6 flex justify-between items-center shadow">
                <div>
                    <h2 class="text-2xl font-semibold">Your Active Missions</h2>
                    <p class="mt-1 text-orange-200">Helping furry friends one mission at a time 🐾</p>
                </div>
                <img src="https://img.icons8.com/emoji/96/cat-face.png" class="w-20 h-20 animate-float" alt="Cat" />
            </div>

            <!-- Active Missions List -->
            <section class="mt-8 space-y-8 max-w-4xl mx-auto">

                <!-- Mission Card -->
                <article class="bg-white rounded-3xl p-6 shadow-md">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-[#E17C56]">🐶 Injured Dog near Riverside</h3>
                        <select
                            class="border border-gray-300 rounded-xl px-4 py-2 text-gray-700 font-semibold cursor-pointer"
                            aria-label="Mission status">
                            <option>Pending</option>
                            <option selected>In Progress</option>
                            <option>Completed</option>
                        </select>
                    </div>

                    <div class="text-gray-700 space-y-2 text-lg">
                        <p><span class="font-semibold">Reported:</span> 9:30 AM, June 15, 2025</p>
                        <p><span class="font-semibold">Location:</span> Riverside Park, Block B, Near the Oak Tree</p>
                        <p><span class="font-semibold">Needs:</span> First aid, Shelter, Medical Attention</p>
                        <p><span class="font-semibold">Reporter Contact:</span> +1 234 567 890</p>
                        <p>A dog was found injured near Riverside Park with a broken leg and needs urgent care.
                            Volunteers
                            should
                            proceed with necessary first aid and arrange shelter until a vet arrives.</p>
                    </div>

                    <div class="mt-6 space-y-4">

                        <!-- Notes/Updates Textarea -->
                        <label for="notes1" class="block font-semibold text-gray-800">Mission Notes / Updates</label>
                        <textarea id="notes1" rows="3"
                            class="w-full border border-gray-300 rounded-lg p-3 resize-y focus:outline-none focus:ring-2 focus:ring-[#E17C56]"
                            placeholder="Add any notes or updates here..."></textarea>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-4">
                            <button
                                class="px-5 py-2 bg-[#E17C56] text-white font-semibold rounded-xl shadow hover:bg-[#ca6a48] transition">Update
                                Progress</button>
                            <button
                                class="px-5 py-2 bg-green-500 text-white font-semibold rounded-xl shadow hover:bg-green-600 transition">Mark
                                as Complete</button>
                        </div>

                    </div>

                </article>

                <!-- Mission Card -->
                <article class="bg-white rounded-3xl p-6 shadow-md">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-[#E17C56]">🐱 Rescue Stray Kitten in Parking Lot</h3>
                        <select
                            class="border border-gray-300 rounded-xl px-4 py-2 text-gray-700 font-semibold cursor-pointer"
                            aria-label="Mission status">
                            <option selected>Pending</option>
                            <option>In Progress</option>
                            <option>Completed</option>
                        </select>
                    </div>

                    <div class="text-gray-700 space-y-2 text-lg">
                        <p><span class="font-semibold">Reported:</span> 11:15 AM, June 15, 2025</p>
                        <p><span class="font-semibold">Location:</span> Parking Lot, Block C, Near the Mall Entrance</p>
                        <p><span class="font-semibold">Needs:</span> Rescue, Feeding</p>
                        <p><span class="font-semibold">Reporter Contact:</span> +1 987 654 321</p>
                        <p>A stray kitten was spotted in the parking lot looking hungry and scared. Volunteers should
                            rescue
                            and
                            provide feeding immediately.</p>
                    </div>

                    <div class="mt-6 space-y-4">

                        <!-- Notes/Updates Textarea -->
                        <label for="notes2" class="block font-semibold text-gray-800">Mission Notes / Updates</label>
                        <textarea id="notes2" rows="3"
                            class="w-full border border-gray-300 rounded-lg p-3 resize-y focus:outline-none focus:ring-2 focus:ring-[#E17C56]"
                            placeholder="Add any notes or updates here..."></textarea>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-4">
                            <button
                                class="px-5 py-2 bg-[#E17C56] text-white font-semibold rounded-xl shadow hover:bg-[#ca6a48] transition">Update
                                Progress</button>
                            <button
                                class="px-5 py-2 bg-green-500 text-white font-semibold rounded-xl shadow hover:bg-green-600 transition">Mark
                                as Complete</button>
                        </div>

                    </div>

                </article>

            </section>
        </main>
    </div>

</body>

</html>