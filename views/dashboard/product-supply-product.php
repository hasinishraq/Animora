<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Products - Pet Supplier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#FCBF01', light: '#FFFBEB', dark: '#B45309' },
                        petgray: '#4B5563',
                    },
                    boxShadow: { soft: '0 8px 20px rgba(0,0,0,.08)' },
                    keyframes: {
                        float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                        wiggle: { '0%,100%': { transform: 'rotate(-3deg)' }, '50%': { transform: 'rotate(3deg)' } },
                        pop: {
                            '0%': { transform: 'scale(1)' },
                            '50%': { transform: 'scale(1.2)' },
                            '100%': { transform: 'scale(1)' }
                        }
                    },
                    animation: {
                        float: 'float 4s ease-in-out infinite',
                        wiggle: 'wiggle 2.5s ease-in-out infinite',
                        pop: 'pop 0.35s ease-in-out'
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif
        }

        ::-webkit-scrollbar {
            width: 8px
        }

        ::-webkit-scrollbar-thumb {
            background: #fcbf0177;
            border-radius: 8px
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
    <!-- Main content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-10">
            <h1 class="text-4xl font-semibold">🐾 All Products</h1>
            <div class="flex gap-4 w-full sm:w-auto">
                <select class="px-4 py-2 rounded-lg bg-white shadow-soft text-petgray">
                    <option>All Categories</option>
                    <option>Dog</option>
                    <option>Cat</option>
                    <option>Bird</option>
                    <option>Hamster</option>
                    <option>Rabbit</option>
                </select>
                <input type="text" placeholder="Search products..."
                    class="px-4 py-2 rounded-lg bg-white shadow-soft text-petgray w-full sm:w-64" />
                <button class="bg-brand text-white px-6 py-2 rounded-full font-semibold shadow hover:bg-brand-dark">+
                    Add Product</button>
            </div>
        </header>

        <!-- Product Grid -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <!-- Dog -->
            <div class="bg-white p-4 rounded-2xl shadow-soft hover:shadow-lg transition group">
                <img src="https://img.icons8.com/color/96/dog-bone.png" alt="Dog Bone" data-sound="dog"
                    class="w-20 h-20 mx-auto mb-2 cursor-pointer animate-float pet-img" />
                <h2 class="text-xl font-semibold text-center">Chew Bone</h2>
                <p class="text-center text-gray-500">Category: Dog</p>
                <p class="text-center font-bold text-brand mt-1">$5.99</p>
                <div class="mt-4 flex justify-center gap-3"><button
                        class="text-xs bg-brand-light text-brand px-3 py-1 rounded hover:bg-brand">Edit</button><button
                        class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">Delete</button></div>
            </div>
            <!-- Cat -->
            <div class="bg-white p-4 rounded-2xl shadow-soft hover:shadow-lg transition group">
                <img src="https://img.icons8.com/color/96/cat.png" alt="Cat Toy" data-sound="cat"
                    class="w-20 h-20 mx-auto mb-2 cursor-pointer animate-wiggle pet-img" />
                <h2 class="text-xl font-semibold text-center">Yarn Ball</h2>
                <p class="text-center text-gray-500">Category: Cat</p>
                <p class="text-center font-bold text-brand mt-1">$2.49</p>
                <div class="mt-4 flex justify-center gap-3"><button
                        class="text-xs bg-brand-light text-brand px-3 py-1 rounded hover:bg-brand">Edit</button><button
                        class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">Delete</button></div>
            </div>
            <!-- Bird -->
            <div class="bg-white p-4 rounded-2xl shadow-soft hover:shadow-lg transition group">
                <img src="https://img.icons8.com/color/96/bird.png" alt="Bird Seed" data-sound="bird"
                    class="w-20 h-20 mx-auto mb-2 cursor-pointer animate-float pet-img" />
                <h2 class="text-xl font-semibold text-center">Bird Seed Mix</h2>
                <p class="text-center text-gray-500">Category: Bird</p>
                <p class="text-center font-bold text-brand mt-1">$4.75</p>
                <div class="mt-4 flex justify-center gap-3"><button
                        class="text-xs bg-brand-light text-brand px-3 py-1 rounded hover:bg-brand">Edit</button><button
                        class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">Delete</button></div>
            </div>
            <!-- Hamster -->
            <div class="bg-white p-4 rounded-2xl shadow-soft hover:shadow-lg transition group">
                <img src="https://img.icons8.com/color/96/hamster.png" alt="Hamster Wheel" data-sound="hamster"
                    class="w-20 h-20 mx-auto mb-2 cursor-pointer animate-wiggle pet-img" />
                <h2 class="text-xl font-semibold text-center">Hamster Wheel</h2>
                <p class="text-center text-gray-500">Category: Hamster</p>
                <p class="text-center font-bold text-brand mt-1">$6.30</p>
                <div class="mt-4 flex justify-center gap-3"><button
                        class="text-xs bg-brand-light text-brand px-3 py-1 rounded hover:bg-brand">Edit</button><button
                        class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">Delete</button></div>
            </div>
            <!-- Rabbit -->
            <div class="bg-white p-4 rounded-2xl shadow-soft hover:shadow-lg transition group">
                <img src="https://img.icons8.com/color/96/rabbit.png" alt="Rabbit Treat" data-sound="rabbit"
                    class="w-20 h-20 mx-auto mb-2 cursor-pointer animate-float pet-img" />
                <h2 class="text-xl font-semibold text-center">Carrot Crunch</h2>
                <p class="text-center text-gray-500">Category: Rabbit</p>
                <p class="text-center font-bold text-brand mt-1">$3.15</p>
                <div class="mt-4 flex justify-center gap-3"><button
                        class="text-xs bg-brand-light text-brand px-3 py-1 rounded hover:bg-brand">Edit</button><button
                        class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">Delete</button></div>
            </div>
        </section>
    </main>

    <!-- Sounds -->
    <audio id="sound-dog" src="https://assets.mixkit.co/active_storage/sfx/1644/1644-preview.mp3"></audio>
    <audio id="sound-cat" src="https://assets.mixkit.co/active_storage/sfx/1811/1811-preview.mp3"></audio>
    <audio id="sound-bird" src="https://assets.mixkit.co/active_storage/sfx/1645/1645-preview.mp3"></audio>
    <audio id="sound-hamster" src="https://assets.mixkit.co/active_storage/sfx/2541/2541-preview.mp3"></audio>
    <audio id="sound-rabbit" src="https://assets.mixkit.co/active_storage/sfx/1473/1473-preview.mp3"></audio>

    <script>
        document.querySelectorAll('.pet-img').forEach(img => {
            img.addEventListener('click', () => {
                const id = `sound-${img.dataset.sound}`;
                const snd = document.getElementById(id);
                if (snd) {
                    snd.currentTime = 0;
                    snd.play();
                }
                img.classList.add('animate-pop');
                setTimeout(() => img.classList.remove('animate-pop'), 350);
            });
        });
    </script>
</body>

</html>