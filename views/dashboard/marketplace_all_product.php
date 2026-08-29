<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>

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
                    <a href="#" class="text-gray-700 hover:text-blue-500">Shop by Category</a>
                    <a href="#" class="text-gray-700 hover:text-blue-500"> Deals & Offers</a>

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








    <section class="relative h-[30rem] overflow-hidden"
        style="background-image: url('/assets/images/cute.jpg'); background-size: cover; background-position: center;">
        <div
            class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-25 flex flex-col justify-center items-center">
            <h2 class="text-white text-3xl md:text-4xl font-bold mb-6 text-center max-w-[80%]">
                Discover Perfect Pet Products
            </h2>
            <div class="relative w-3/4 md:w-1/2 lg:w-1/3">
                <input type="text"
                    class="w-full bg-white rounded-full py-3 px-6 shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Search for products..." />
                <button
                    class="absolute top-1/2 right-3 -translate-y-1/2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-6a7 7 0 10-14 0 7 7 0 0014 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>












    <section class="bg-white py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 lg:flex">

            <aside class="bg-[#f8bba2] p-6 rounded-md shadow-md lg:w-1/4 lg:sticky top-20 h-fit">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 text-[#F5A623]">Filters</h3>
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-600 mb-2">Pet Type</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Dog">
                            <span class="ml-2 text-gray-700">Dog</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Cat">
                            <span class="ml-2 text-gray-700">Cat</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Bird">
                            <span class="ml-2 text-gray-700">Bird</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Small Animals">
                            <span class="ml-2 text-gray-700">Small Animals</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Fish">
                            <span class="ml-2 text-gray-700">Fish</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Reptiles">
                            <span class="ml-2 text-gray-700">Reptiles</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="font-semibold text-gray-600 mb-2">Category</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Food">
                            <span class="ml-2 text-gray-700">Food</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Toys">
                            <span class="ml-2 text-gray-700">Toys</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Beds">
                            <span class="ml-2 text-gray-700">Beds & Furniture</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Grooming">
                            <span class="ml-2 text-gray-700">Grooming</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Health">
                            <span class="ml-2 text-gray-700">Health & Wellness</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Accessories">
                            <span class="ml-2 text-gray-700">Accessories</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Aquariums">
                            <span class="ml-2 text-gray-700">Aquariums & Supplies</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Cages">
                            <span class="ml-2 text-gray-700">Cages & Habitats</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="font-semibold text-gray-600 mb-2">Price Range</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-700">Min:</span>
                            <input type="number" id="min-price"
                                class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:ring-[#F5A623] focus:border-[#F5A623] text-gray-900"
                                value="0">
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-700">Max:</span>
                            <input type="number" id="max-price"
                                class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:ring-[#F5A623] focus:border-[#F5A623] text-gray-900"
                                value="100">
                        </div>
                        <div id="price-range-slider" class="mt-4">
                            <input type="range" min="0" max="100" value="0" id="price-min" class="w-full">
                            <input type="range" min="0" max="100" value="100" id="price-max" class="w-full">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="font-semibold text-gray-600 mb-2">Age Range</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Kitten/Puppy">
                            <span class="ml-2 text-gray-700">Kitten/Puppy</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Adult">
                            <span class="ml-2 text-gray-700">Adult</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Senior">
                            <span class="ml-2 text-gray-700">Senior</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="font-semibold text-gray-600 mb-2">Brand</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Happy Paws">
                            <span class="ml-2 text-gray-700">Happy Paws</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Cozy Comfort">
                            <span class="ml-2 text-gray-700">Cozy Comfort</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Feathered Friends">
                            <span class="ml-2 text-gray-700">Feathered Friends</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Aqua Life">
                            <span class="ml-2 text-gray-700">Aqua Life</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Small World">
                            <span class="ml-2 text-gray-700">Small World</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="form-checkbox h-4 w-4 text-[#F5A623] focus:ring-[#F5A623] rounded border-gray-300"
                                value="Jungle Life">
                            <span class="ml-2 text-gray-700">Jungle Life</span>
                        </label>
                    </div>
                </div>
            </aside>


            <div class="lg:w-3/4 pl-8">
                <h2 class="text-4xl font-extrabold text-[#f5a623] text-center mb-10">Our Featured Pet Products</h2>
                <br>
                <br>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>



                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>




                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>



                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>



                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>



                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>



                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>



                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>


                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>



                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>


                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>



                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>



                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
                    </div>


                    <div class="bg-white  border-4 border-solid border-[#f8c453] rounded-lg shadow-md overflow-hidden">
                        <img src="path/to/product1.jpg" alt="Product 1" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-[#F5A623]">Product Name 1</h3>
                            <p class="mt-2 text-gray-600">$19.99</p>
                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">
                                View Details</button>

                            <button
                                class="mt-4 bg-[#F5A623] hover:bg-[#D98E0B] text-white font-semibold py-2 px-4 rounded-md">Buy
                                Now</button>
                        </div>
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


</body>

</html>