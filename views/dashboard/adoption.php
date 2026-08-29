<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption</title>
    <link rel="stylesheet" href="/assets/css/output.css">
    <script src="https://cdn.tailwindcss.com"></script>





</head>


<body>


    <section class="w-full bg-white py-2 shadow-md">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">

            <!-- Left Menu -->
            <div class="w-full md:w-auto">
                <nav class="flex justify-center md:justify-start gap-4">
                    <a href="#" class="text-gray-700 hover:text-blue-500">Home</a>
                    <a href="#" class="text-gray-700 hover:text-blue-500">About</a>
                    <a href="#" class="text-gray-700 hover:text-blue-500">Services</a>
                </nav>
            </div>

            <!-- Center Logo -->
            <div class="w-full md:w-auto flex justify-center">
                <img src="/assets/images/logo2.png" alt="Logo" class="h-22 w-[300px]">
            </div>

            <!-- Right Button -->
            <div class="w-full md:w-auto flex justify-center md:justify-end">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Post Adoption
                </button>
            </div>

        </div>
    </section>













    <section class="relative w-full h-[700px] bg-[url('/assets/images/adoption1.jpeg')] bg-cover bg-center">
        <!-- Center Filter Box -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-white/80 backdrop-blur-md p-6 rounded-lg shadow-md text-center max-w-md">
                <h2 class="text-2xl font-bold mb-4">What pet are you looking for?</h2>
                <select class="w-full px-4 py-2 rounded border text-gray-700">
                    <option value="dogs">Dogs</option>
                    <option value="cats">Cats</option>
                    <option value="rabbits">Rabbits</option>
                    <option value="parrots">Parrots</option>
                    <option value="hamsters">Hamsters</option>
                </select>
            </div>
        </div>


        <!-- SVG Wave at the bottom -->
        <div class="absolute bottom-0 w-full ">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#fff" fill-opacity="1"
                    d="M0,96L34.3,117.3C68.6,139,137,181,206,202.7C274.3,224,343,224,411,208C480,192,549,160,617,149.3C685.7,139,754,149,823,176C891.4,203,960,245,1029,245.3C1097.1,245,1166,203,1234,197.3C1302.9,192,1371,224,1406,240L1440,256L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                </path>
            </svg>
        </div>
    </section>















    <section class="w-full bg-white py-10 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8">

            <!-- Left Filter Menu (Now Sticky) -->
            <aside class="w-full md:w-1/4 bg-[#f4bd9f] p-6 rounded-lg shadow-md space-y-4 self-start sticky top-24">
                <h2 class="text-xl font-bold mb-4">Filter Pets</h2>
                <div class="space-y-2">
                    <label class="block font-medium">Breed</label>
                    <input type="text" placeholder="e.g. Labrador" class="w-full px-3 py-2 border rounded">

                    <label class="block font-medium">Age</label>
                    <input type="number" placeholder="e.g. 2" class="w-full px-3 py-2 border rounded">

                    <label class="block font-medium">Gender</label>
                    <select class="w-full px-3 py-2 border rounded">
                        <option>Any</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>

                    <label class="block font-medium">Color</label>
                    <input type="text" placeholder="e.g. Brown" class="w-full px-3 py-2 border rounded">

                    <label class="block font-medium">Location</label>
                    <input type="text" placeholder="e.g. Dhaka" class="w-full px-3 py-2 border rounded">
                </div>
            </aside>

            <!-- Right Pet Cards (No Change) -->
            <div class="w-full md:w-3/4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Pet Cards Repeated -->
                <!-- ... your cards as-is ... -->


                <!-- 15 Pet Cards -->
                <!-- Repeat this block 15 times with different content -->
                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>


                <!-- 15 Pet Cards -->
                <!-- Repeat this block 15 times with different content -->
                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>




                <!-- 15 Pet Cards -->
                <!-- Repeat this block 15 times with different content -->
                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>



                <!-- 15 Pet Cards -->
                <!-- Repeat this block 15 times with different content -->
                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>


                <!-- 15 Pet Cards -->
                <!-- Repeat this block 15 times with different content -->
                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>



                <!-- 15 Pet Cards -->
                <!-- Repeat this block 15 times with different content -->
                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>



                <!-- 15 Pet Cards -->
                <!-- Repeat this block 15 times with different content -->
                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>




                <!-- 15 Pet Cards -->
                <!-- Repeat this block 15 times with different content -->
                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>





                <!-- 15 Pet Cards -->
                <!-- Repeat this block 15 times with different content -->
                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>


                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>




                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>




                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>




                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>




                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>





                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>




                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>




                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>




                <div class="bg-white p-4 rounded-lg shadow-md border" style="border-color: #F4BD9F;">
                    <img src="/assets/images/cat.jpg" alt="Pet" class="w-full h-48 object-cover rounded">
                    <div class="mt-4">
                        <h3 class="text-lg font-bold">Bruno</h3>
                        <p class="text-gray-600 text-sm">Dhaka, Age: 2 years</p>
                    </div>
                </div>




            </div>
        </div>






    </section>

    <!-- Pagination Section -->
    <div class="w-full flex justify-center mt-10 mb-16">
        <nav class="inline-flex items-center gap-3 rounded-lg bg-white px-6 py-3 shadow-lg border-2 border-[#f4bd9f]">
            <button class="px-5 py-2 text-base font-semibold text-gray-500 hover:text-black transition" disabled>
                Previous
            </button>

            <button class="px-5 py-2 text-base font-bold text-white bg-[#f4bd9f] rounded-lg shadow-sm">
                1
            </button>
            <button class="px-5 py-2 text-base font-semibold text-gray-700 hover:bg-gray-100 rounded-lg">
                2
            </button>
            <button class="px-5 py-2 text-base font-semibold text-gray-700 hover:bg-gray-100 rounded-lg">
                3
            </button>
            <button class="px-5 py-2 text-base font-semibold text-gray-700 hover:bg-gray-100 rounded-lg">
                4
            </button>
            <button class="px-5 py-2 text-base font-semibold text-gray-700 hover:bg-gray-100 rounded-lg">
                5
            </button>
            <button class="px-5 py-2 text-base font-semibold text-gray-700 hover:bg-gray-100 rounded-lg">
                6
            </button>

            <button class="px-5 py-2 text-base font-semibold text-gray-700 hover:text-black transition">
                Next
            </button>
        </nav>
    </div>













    <section class="relative overflow-hidden h-[700px]">
        <!-- Top Wave -->
        <div class="absolute top-0 left-0 w-full leading-[0] z-10">
            <svg class="block w-full h-[100px]" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="white"
                    d="M0,64C120,106.7,240,213,360,224C480,235,600,149,720,122.7C840,96,960,128,1080,149.3C1200,171,1320,181,1380,186.7L1440,192L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z" />
            </svg>
        </div>

        <!-- Background Image + Overlay -->
        <div class="absolute inset-0">
            <img src="/assets/images/adoption2.jpeg" alt="Background" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>

        <!-- Centered Content -->
        <div class="relative z-20 flex flex-col items-center justify-center text-center text-white h-full px-6">
            <h2 class="text-4xl font-bold mb-4">Post a Pet for Adoption</h2>
            <p class="text-lg max-w-xl mb-6">Share your pet's photo and details — we’ll help them reach potential
                adopters.</p>
            <button
                class="bg-white text-yellow-700 px-6 py-3 rounded-full font-semibold hover:bg-yellow-100 transition">
                Post Adoption
            </button>
        </div>

        <!-- Bottom Wave -->
        <div class="absolute bottom-0 left-0 w-full leading-[0] z-10 ">
            <svg class="block w-full h-[100px] rotate-180" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="#E5D5BB"
                    d="M0,64C120,106.7,240,213,360,224C480,235,600,149,720,122.7C840,96,960,128,1080,149.3C1200,171,1320,181,1380,186.7L1440,192L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z" />
            </svg>
        </div>
    </section>










    <section class="relative bg-[#E5D5BB] h-[600px] py-20 overflow-hidden">
        <!-- Left image -->
        <img src="/assets/images/image.png" alt="Decorative dog left"
            class="absolute left-0 top-1/2 transform -translate-y-1/2 w-[300px] md:w-[350px] animate-slide-in-left hidden md:block" />

        <!-- Right image -->
        <img src="/assets/images/testimonialmain.png" alt="Decorative cat right"
            class="absolute right-0 top-1/2 transform -translate-y-1/2 w-[300px] md:w-[350px]animate-slide-in-right hidden md:block" />

        <!-- Center content -->
        <div
            class="relative z-10 max-w-2xl h-[400px] mx-auto bg-white rounded-xl shadow-lg p-10 text-center flex flex-col justify-center items-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Your Adoption Posts</h2>
            <p class="text-gray-600 mb-6">
                View and manage all your adoption listings here. Help your furry friends find a loving home!
            </p>
            <div class="flex justify-center gap-4">
                <button class="bg-yellow-600 text-white px-6 py-2 rounded-full hover:bg-yellow-700 transition">Create
                    New Post</button>
                <button class="bg-gray-200 text-gray-800 px-6 py-2 rounded-full hover:bg-gray-300 transition">Manage
                    Posts</button>
            </div>
        </div>

    </section>



</body>

</html>