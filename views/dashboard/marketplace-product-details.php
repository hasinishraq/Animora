<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>

    <link rel="stylesheet" href="/assets/css/output.css">
    <script src="https://cdn.tailwindcss.com"></script>

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








    <section class="py-12 bg-[#F7BAA8] ">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">

                <!-- Left: Product Images -->
                <div>
                    <!-- Main Image -->
                    <div class="rounded-xl overflow-hidden shadow-lg">
                        <img src="/assets/images/petprod.jpg" alt="Product Image" class="w-full h-auto object-cover">
                    </div>

                    <!-- Thumbnails -->
                    <div class="mt-6 grid grid-cols-4 gap-4">
                        <img src="/assets/images/petprod.jpg" alt="Thumbnail 1"
                            class="rounded-lg shadow-md border border-gray-300 cursor-pointer transition-transform hover:scale-105">
                        <img src="/assets/images/petprod.jpg" alt="Thumbnail 2"
                            class="rounded-lg shadow-md border border-gray-300 cursor-pointer transition-transform hover:scale-105">
                        <img src="/assets/images/petprod.jpg" alt="Thumbnail 3"
                            class="rounded-lg shadow-md border border-gray-300 cursor-pointer transition-transform hover:scale-105">
                        <img src="/assets/images/petprod.jpg" alt="Thumbnail 4"
                            class="rounded-lg shadow-md border border-gray-300 cursor-pointer transition-transform hover:scale-105">
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div>
                    <h2 class="text-4xl font-bold text-gray-800 mb-2">Fresh Rose Mist</h2>
                    <p class="text-sm uppercase tracking-wide text-[#dc6c4d] font-medium">Scented Hydration</p>

                    <div class="mt-6 flex gap-4">
                        <button
                            class="bg-[#f5a623] hover:bg-[#d48d1a] transition text-white font-semibold py-3 px-6 rounded-lg text-sm shadow-md">
                            Add to Cart
                        </button>
                        <button
                            class="bg-white hover:bg-gray-100 transition border border-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg text-sm shadow-sm">
                            Buy Now
                        </button>
                    </div>

                    <!-- Description -->
                    <div class="mt-8 border-t border-gray-300 pt-4">
                        <button onclick="toggleDescription()"
                            class="w-full flex justify-between items-center font-semibold text-gray-700 cursor-pointer focus:outline-none">
                            <span>Description</span>
                            <svg id="descriptionArrow" class="w-5 h-5 transform transition-transform"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="descriptionContent" class="mt-3 text-gray-700 leading-relaxed hidden">
                            <p>Fresh rosewater mist delicately crafted from our sun-drenched garden. Perfect for a
                                refreshing spritz to invigorate the skin or your pet's coat.</p>
                            <ul class="list-disc list-inside mt-2">
                                <li>Rose Essence</li>
                                <li>Chamomile</li>
                                <li>Hyaluronic Acid</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Shipping -->
                    <div class="mt-6 border-t border-gray-300 pt-4">
                        <button onclick="toggleShipping()"
                            class="w-full flex justify-between items-center font-semibold text-gray-700 cursor-pointer focus:outline-none">
                            <span>Shipping</span>
                            <svg id="shippingArrow" class="w-5 h-5 transform transition-transform"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="shippingContent" class="mt-3 text-gray-700 leading-relaxed hidden">
                            <p>We offer fast and reliable worldwide shipping. Expect your delivery within 5-7 business
                                days.</p>
                        </div>
                    </div>

                    <!-- Reviews -->
                    <div class="mt-6 border-t border-gray-300 pt-4">
                        <button onclick="toggleReviews()"
                            class="w-full flex justify-between items-center font-semibold text-gray-700 cursor-pointer focus:outline-none">
                            <span>Reviews</span>
                            <svg id="reviewsArrow" class="w-5 h-5 transform transition-transform"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="reviewsContent" class="mt-3 text-gray-700 leading-relaxed hidden">
                            <p>No reviews yet. Be the first to share your thoughts!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <div style="transform: rotate(180deg); overflow: hidden;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <!-- Back layer -->
            <path fill="#fbe3db" fill-opacity="1"
                d="M0,96L30,112C60,128,120,160,180,192C240,224,300,256,360,229.3C420,203,480,117,540,112C600,107,660,181,720,176C780,171,840,85,900,74.7C960,64,1020,128,1080,176C1140,224,1200,256,1260,256C1320,256,1380,224,1410,208L1440,192L1440,320L0,320Z" />

            <!-- Middle layer -->
            <path fill="#f7c1b3" fill-opacity="0.8"
                d="M0,106L30,122C60,138,120,170,180,202C240,234,300,266,360,239.3C420,213,480,127,540,122C600,117,660,191,720,186C780,181,840,95,900,84.7C960,74,1020,138,1080,186C1140,234,1200,266,1260,266C1320,266,1380,234,1410,218L1440,202L1440,320L0,320Z" />

            <!-- Front layer -->
            <path fill="#f7b9a6" fill-opacity="0.9"
                d="M0,116L30,132C60,148,120,180,180,212C240,244,300,276,360,249.3C420,223,480,137,540,132C600,127,660,201,720,196C780,191,840,105,900,94.7C960,84,1020,148,1080,196C1140,244,1200,276,1260,276C1320,276,1380,244,1410,228L1440,212L1440,320L0,320Z" />
        </svg>
    </div>










    <section class="py-16 bg-[#fff]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">
                Your Pet May Also Like
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Product Card 1 -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 p-4">
                    <img src="/assets/images/petprod.jpg" alt="Similar Product 1"
                        class="w-full h-48 object-cover rounded-lg">
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-800">Lavender Calming Spray</h3>
                        <p class="text-sm text-gray-500 mt-1">Soothing mist for anxious pets</p>
                        <p class="mt-2 text-[#dc6c4d] font-bold">$14.99</p>
                        <button
                            class="mt-3 w-full bg-[#f5a623] hover:bg-[#d48d1a] text-white font-semibold py-2 rounded-md transition">
                            Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 p-4">
                    <img src="/assets/images/petprod.jpg" alt="Similar Product 2"
                        class="w-full h-48 object-cover rounded-lg">
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-800">Organic Paw Balm</h3>
                        <p class="text-sm text-gray-500 mt-1">Moisturizes dry and cracked paws</p>
                        <p class="mt-2 text-[#dc6c4d] font-bold">$9.99</p>
                        <button
                            class="mt-3 w-full bg-[#f5a623] hover:bg-[#d48d1a] text-white font-semibold py-2 rounded-md transition">
                            Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 p-4">
                    <img src="/assets/images/petprod.jpg" alt="Similar Product 3"
                        class="w-full h-48 object-cover rounded-lg">
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-800">Aloe Vera Fur Conditioner</h3>
                        <p class="text-sm text-gray-500 mt-1">Hydrates and smoothens fur</p>
                        <p class="mt-2 text-[#dc6c4d] font-bold">$12.49</p>
                        <button
                            class="mt-3 w-full bg-[#f5a623] hover:bg-[#d48d1a] text-white font-semibold py-2 rounded-md transition">
                            Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 p-4">
                    <img src="/assets/images/petprod.jpg" alt="Similar Product 4"
                        class="w-full h-48 object-cover rounded-lg">
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-800">Coconut Dry Shampoo</h3>
                        <p class="text-sm text-gray-500 mt-1">Quick clean for active pets</p>
                        <p class="mt-2 text-[#dc6c4d] font-bold">$11.99</p>
                        <button
                            class="mt-3 w-full bg-[#f5a623] hover:bg-[#d48d1a] text-white font-semibold py-2 rounded-md transition">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>




</body>

</html>