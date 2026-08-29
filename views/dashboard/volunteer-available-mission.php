<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Available Missions - PetPal</title>
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
    <div class="fixed left-0 bottom-10 z-50 pointer-events-none">
        <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Peeking Cat"
            class="w-32 h-32 animate-peek-icon" />
    </div>

    <div class="flex h-screen overflow-hidden">
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

        <main class="flex-1 bg-[#FFF7F5] p-8 overflow-y-auto">
            <div class="bg-[#E17C56] text-white rounded-3xl p-6 flex justify-between items-center shadow">
                <div>
                    <h2 class="text-2xl font-semibold">Available Missions in Bangladesh</h2>
                    <p class="mt-1 text-orange-200">Filter by location to find rescue missions near you 🐾</p>
                </div>
                <img src="https://img.icons8.com/emoji/96/cat-face.png" class="w-20 h-20 animate-float" alt="Cat" />
            </div>

            <!-- Filters -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl">
                <select class="p-3 rounded-xl border border-gray-300 bg-white shadow">
                    <option selected disabled>Select Division</option>
                    <option>Dhaka</option>
                    <option>Chattogram</option>
                    <option>Khulna</option>
                    <option>Rajshahi</option>
                    <option>Barisal</option>
                    <option>Sylhet</option>
                    <option>Mymensingh</option>
                    <option>Rangpur</option>
                </select>
                <select class="p-3 rounded-xl border border-gray-300 bg-white shadow">
                    <option selected disabled>Select Area</option>
                    <option>Dhanmondi</option>
                    <option>Gulshan</option>
                    <option>Mirpur</option>
                    <option>Banani</option>
                    <option>Mohakhali</option>
                </select>
            </div>

            <!-- Mission Cards -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mission Card -->
                <div class="bg-white rounded-3xl shadow-lg p-6">
                    <img src="https://images.unsplash.com/photo-1617814072870-dc3c6bfc16a6?auto=format&fit=crop&w=800&q=80"
                        alt="Injured Dog" class="w-full h-48 object-cover rounded-xl mb-4">
                    <h3 class="text-xl font-bold text-[#E17C56] mb-2">🐶 Injured Dog in Mirpur</h3>
                    <p class="text-gray-600 text-sm mb-2">Reported at: 10:45 AM</p>
                    <p class="text-gray-700 mb-3">Found limping and bleeding near Mirpur 1 bus stand. Needs first aid
                        and transport to shelter. Brown, medium-sized dog.</p>
                    <div class="text-sm text-gray-500 mb-3">Contact: +880 1712 345678</div>
                    <div class="mb-4">
                        <iframe class="w-full h-48 rounded-xl"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.902939847084!2d90.3654214149827!3d23.75090358459181!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c156c9aaf933%3A0x5c08fc6f9f9f7db0!2sMirpur%201!5e0!3m2!1sen!2sbd!4v1626453631234!5m2!1sen!2sbd"
                            allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button class="bg-green-500 text-white px-4 py-2 rounded-xl hover:bg-green-600">Accept</button>
                        <button class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600">Decline</button>
                    </div>
                </div>

                <!-- Add more mission cards similarly -->
            </div>
        </main>
    </div>
</body>

</html>