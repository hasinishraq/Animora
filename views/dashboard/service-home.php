<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Services Section</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles if needed beyond Tailwind utilities */
        .bg-pet-services {
            /* Replace with your actual image URL */
            background-image: url('/assets/images/petgroom.jpg');
            background-size: cover;
            background-position: center;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            /* 50% black overlay */
        }
    </style>
</head>

<body class="font-sans">

    <section class="relative bg-pet-services h-[500px] flex items-center justify-center text-white py-16 px-4 sm:px-8">
        <div class="absolute inset-0 overlay"></div>

        <div class="relative z-10 text-center max-w-3xl">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight mb-4 drop-shadow-md">
                Dedicated Care for Your Beloved Pets
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl mb-8 leading-relaxed drop-shadow-sm">
                From playful pups to purring pals, we offer a wide range of services including grooming, boarding,
                and personalized training to ensure their happiness and well-being.
            </p>
            <button
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-full text-lg sm:text-xl transition duration-300 ease-in-out shadow-lg">
                Discover Our Services
            </button>
        </div>
    </section>







    <div class="w-full lg:w-1/2 bg-white p-8 lg:p-12 flex flex-col justify-center">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 leading-tight mb-8 text-center lg:text-left">
            Your Pet's Journey to Happiness in 3 Simple Steps
        </h2>

        <div class="space-y-8 md:space-y-10">
            <div class="flex items-start">
                <div class="flex-shrink-0 bg-indigo-100 p-3 rounded-full mr-4 shadow-md">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">1. Explore & Select</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Dive into our diverse range of services. From cozy boarding to energetic daycare and expert
                        grooming, find the perfect fit for your pet's needs.
                    </p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 bg-green-100 p-3 rounded-full mr-4 shadow-md">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">2. Book Your Slot</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Choose your preferred date and time with our easy online booking system. It's fast, convenient,
                        and available 24/7.
                    </p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 bg-pink-100 p-3 rounded-full mr-4 shadow-md">
                    <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 14.5c-1.332 0-2.42-1.088-2.42-2.42S10.668 11.66 12 11.66s2.42 1.088 2.42 2.42-1.088 2.42-2.42 2.42zm0-4.5c-1.332 0-2.42-1.088-2.42-2.42S10.668 7.16 12 7.16s2.42 1.088 2.42 2.42-1.088 2.42-2.42 2.42z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.5 12.5a.5.5 0 100-1 .5.5 0 000 1zM13.5 12.5a.5.5 0 100-1 .5.5 0 000 1zM12 10.5a.5.5 0 100-1 .5.5 0 000 1z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">3. Relax & Enjoy!</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Once booked, you'll receive a confirmation. Look forward to seeing your happy pet after their
                        visit!
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-10 text-center lg:text-left">
            <button
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full text-xl transition duration-300 shadow-xl transform hover:scale-105">
                Book Your Pet's Adventure
            </button>
        </div>
    </div>

</body>

</html>