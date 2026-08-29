<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - Refined Pastel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* --- Refined Pastel Palette (Custom Classes) --- */
        /* Backgrounds */
        .bg-body-soft {
            background-color: #F7EFE5;
        }

        /* A very light, warm off-white */
        .bg-card-main {
            background-color: #FFFFFF;
        }

        /* Clean white for the main cart card */
        .bg-my-cart-highlight {
            background-color: #FFDEE9;
        }

        /* A soft, very light pink for the heading */
        .bg-button-checkout {
            background-color: #FFC0CB;
        }

        /* Classic light pink for the primary button */
        .bg-button-hover-checkout:hover {
            background-color: #FF9AA2;
        }

        /* Slightly darker pink on hover */
        .bg-qty-btn {
            background-color: #F3F4F6;
        }

        /* Light grey for quantity buttons */

        /* Borders */
        .border-accent-underline {
            border-color: #B2DFDB;
        }

        /* Soft teal/mint for the underline */
        .border-light-separator {
            border-color: #ECECEC;
        }

        /* Very light grey for item separators */

        /* Text Colors */
        .text-main-dark {
            color: #4B5563;
        }

        /* A good readable dark grey */
        .text-secondary-muted {
            color: #6B7280;
        }

        /* A slightly lighter grey for secondary info */
        .text-link-accent {
            color: #FFC0CB;
        }

        /* Matches checkout button for links */
        .accent-checkbox {
            accent-color: #FFC0CB;
        }

        /* Checkbox accent */

        /* --- Wave Pattern (Subtle & Themed) --- */
        .wave-pattern-subtle {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' preserveAspectRatio='none'%3E%3Cpath fill='%23FCE1E9' d='M0,0 H100 C75,0 50,50 100,100 V0 H0 Z' /%3E%3C/svg%3E");
            /* Light pink wave */
            background-repeat: no-repeat;
            background-position: top right;
            background-size: 50% 100%;
        }

        /* General element styles for better spacing/shadows */
        .shadow-soft {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .rounded-xl-custom {
            border-radius: 1rem;
        }
    </style>
</head>

<body class="bg-body-soft font-sans text-main-dark">

    <div class="min-h-screen flex items-center justify-center py-12 wave-pattern-subtle">
        <div class="w-full max-w-4xl bg-card-main p-8 md:p-10 shadow-soft rounded-xl-custom mx-auto">

            <div class="bg-my-cart-highlight inline-block mb-8 px-5 py-2 rounded-lg shadow-sm">
                <h1 class="text-3xl md:text-4xl font-semibold pb-2 border-b-2 border-accent-underline text-main-dark">My
                    Cart</h1>
            </div>

            <div
                class="hidden md:grid grid-cols-6 gap-4 text-secondary-muted uppercase text-sm font-medium mb-6 pb-2 border-b border-light-separator">
                <div class="col-span-3">Product</div>
                <div>Price</div>
                <div>Qty</div>
                <div class="text-right">Total</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center mb-6 pb-6 border-b border-light-separator">
                <div class="col-span-1 md:col-span-3 flex items-center">
                    <img src="https://via.placeholder.com/80x80/FFB6C1/FFFFFF?text=Item1" alt="Sandals"
                        class="w-20 h-20 object-cover rounded-lg mr-4 shadow-sm">
                    <div>
                        <p class="font-medium text-lg text-main-dark">Sandals</p>
                        <p class="text-sm text-secondary-muted">#TRGS7G2Q2001</p>
                        <p class="text-sm text-secondary-muted">Size 38 · Color White</p>
                    </div>
                </div>
                <div class="col-span-1 text-base md:text-left text-main-dark">$39.99</div>
                <div class="col-span-1 flex items-center justify-center">
                    <button
                        class="bg-qty-btn text-main-dark px-3 py-1 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">-</button>
                    <span class="mx-3 text-lg text-main-dark">1</span>
                    <button
                        class="bg-qty-btn text-main-dark px-3 py-1 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">+</button>
                </div>
                <div class="col-span-1 text-right text-lg text-main-dark">$39.99</div>
                <div class="col-span-1 md:col-span-0 flex justify-end">
                    <button class="text-secondary-muted hover:text-red-400 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 11-2 0v6a1 1 0 112 0V8z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center mb-6 pb-6 border-b border-light-separator">
                <div class="col-span-1 md:col-span-3 flex items-center">
                    <img src="https://via.placeholder.com/80x80/ADD8E6/FFFFFF?text=Item2" alt="Zebra purse"
                        class="w-20 h-20 object-cover rounded-lg mr-4 shadow-sm">
                    <div>
                        <p class="font-medium text-lg text-main-dark">Zebra purse</p>
                        <p class="text-sm text-secondary-muted">#TRGS7G2Q2001</p>
                        <p class="text-sm text-secondary-muted">Size One size · Color Mixed</p>
                    </div>
                </div>
                <div class="col-span-1 text-base md:text-left text-main-dark">$59.99</div>
                <div class="col-span-1 flex items-center justify-center">
                    <button
                        class="bg-qty-btn text-main-dark px-3 py-1 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">-</button>
                    <span class="mx-3 text-lg text-main-dark">1</span>
                    <button
                        class="bg-qty-btn text-main-dark px-3 py-1 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">+</button>
                </div>
                <div class="col-span-1 text-right text-lg text-main-dark">$59.99</div>
                <div class="col-span-1 md:col-span-0 flex justify-end">
                    <button class="text-secondary-muted hover:text-red-400 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 11-2 0v6a1 1 0 112 0V8z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center mb-8 pb-6 border-b border-light-separator">
                <div class="col-span-1 md:col-span-3 flex items-center">
                    <img src="https://via.placeholder.com/80x80/CCEEFF/FFFFFF?text=Item3" alt="Knitted top"
                        class="w-20 h-20 object-cover rounded-lg mr-4 shadow-sm">
                    <div>
                        <p class="font-medium text-lg text-main-dark">Knitted top</p>
                        <p class="text-sm text-secondary-muted">#TRGS7G2Q2001</p>
                        <p class="text-sm text-secondary-muted">Size S · Color Powder pink</p>
                    </div>
                </div>
                <div class="col-span-1 text-base md:text-left text-main-dark">$39.99</div>
                <div class="col-span-1 flex items-center justify-center">
                    <button
                        class="bg-qty-btn text-main-dark px-3 py-1 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">-</button>
                    <span class="mx-3 text-lg text-main-dark">2</span>
                    <button
                        class="bg-qty-btn text-main-dark px-3 py-1 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">+</button>
                </div>
                <div class="col-span-1 text-right text-lg text-main-dark">$79.98</div>
                <div class="col-span-1 md:col-span-0 flex justify-end">
                    <button class="text-secondary-muted hover:text-red-400 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 11-2 0v6a1 1 0 112 0V8z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mt-8">
                <div class="flex flex-col mb-6 md:mb-0">
                    <button
                        class="text-secondary-muted flex items-center text-sm mb-2 hover:text-main-dark transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add note
                    </button>
                    <button
                        class="text-secondary-muted flex items-center text-sm hover:text-main-dark transition-colors duration-200">
                        Calculate shipping
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <div class="text-right">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-main-dark text-xl">Subtotal</span>
                        <span class="font-bold text-3xl ml-4 text-main-dark">$239.96</span>
                    </div>
                    <p class="text-secondary-muted text-xs mb-4">
                        <input type="checkbox" id="terms" class="mr-2 accent-checkbox">
                        <label for="terms">I agree to
                            <a href="#" class="text-link-accent hover:underline">Terms & Conditions</a>
                        </label>
                    </p>
                    <button
                        class="bg-button-checkout bg-button-hover-checkout text-white font-bold py-3 px-8 rounded-full text-lg focus:outline-none focus:shadow-outline transition-all duration-300 transform hover:scale-105">
                        CHECKOUT
                        <span class="ml-2">&#x27A4;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>