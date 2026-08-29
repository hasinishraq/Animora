<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Adoption Pop-up</title>
    <!-- Tailwind CSS (for base utilities) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* Color Palette Variables (matching your main page) */
        :root {
            --cream: #FBF3F0;
            --mustard: #D4931F;
            --teal: #A0C0A9;
            --mint: #C2D4C8;
            --sand: #EEC3A4;
            --dark-text: #4A4A4A;
        }

        /* Base font for modal content */
        body {
            font-family: 'Nunito', sans-serif;
            color: var(--dark-text);
            /* This body style is just for viewing this standalone file */
            background-color: #f0f0f0;
            /* Light gray background for demo */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            /* Hide body scrollbar when modal is active */
        }

        /* Heading font for modal */
        h2 {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
        }

        /* Custom Buttons (matching your main page) */
        .btn-primary {
            background-color: var(--mustard);
            color: white;
            padding: 0.75rem 1.5rem;
            /* Equivalent to px-6 py-3 */
            border-radius: 9999px;
            /* Equivalent to rounded-full */
            font-weight: 700;
            /* Equivalent to font-bold */
            font-size: 1.125rem;
            /* Equivalent to text-lg */
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            transform: scale(1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
            border-color: white;
        }

        .btn-secondary {
            background-color: white;
            color: var(--mustard);
            padding: 0.75rem 1.5rem;
            /* Equivalent to px-6 py-3 */
            border-radius: 9999px;
            /* Equivalent to rounded-full */
            font-weight: 700;
            /* Equivalent to font-bold */
            font-size: 1.125rem;
            /* Equivalent to text-lg */
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            transform: scale(1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 2px solid var(--mustard);
        }

        .btn-secondary:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }

        /* Modal Specific Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background-color: #aacd8b;
            /* Specific background color for this modal */
            padding: 2.5rem;
            border-radius: 2rem;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            max-width: 90%;
            width: 550px;
            transform: scale(0.8);
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            /* Bouncy effect */
            border: 4px solid var(--mustard);
            /* Consistent border */
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--dark-text);
            /* Ensure contrast on aaccd8b background */
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .modal-close-btn:hover {
            transform: scale(1.2) rotate(5deg);
        }

        .modal-input-group {
            margin-bottom: 1.25rem;
            /* Spacing between input groups */
        }

        .modal-input-group label {
            color: var(--dark-text);
            /* Labels contrast well on aaccd8b */
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .modal-input-group input,
        .modal-input-group select,
        .modal-input-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--mint);
            border-radius: 0.75rem;
            font-size: 1rem;
            color: var(--dark-text);
            background-color: #fff;
            /* White input fields */
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .modal-input-group input:focus,
        .modal-input-group select:focus,
        .modal-input-group textarea:focus {
            outline: none;
            border-color: var(--mustard);
            box-shadow: 0 0 0 3px rgba(212, 147, 31, 0.3);
            /* Mustard shadow */
        }

        .modal-input-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Custom radio button styling for Gender */
        .radio-tile-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            /* Center the radio tiles */
            flex-wrap: wrap;
            /* Allow wrapping on smaller screens */
        }

        .radio-tile-group .input-container {
            position: relative;
            flex: 1 1 100px;
            /* Allow items to grow/shrink but prefer 100px width */
            max-width: 120px;
            /* Max width for each radio tile */
        }

        .radio-tile-group .input-container input {
            position: absolute;
            height: 100%;
            width: 100%;
            margin: 0;
            cursor: pointer;
            z-index: 2;
            opacity: 0;
            /* Hide default radio button */
        }

        .radio-tile-group .input-container .radio-tile {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            /* Make tile fill its container */
            height: 100px;
            /* Fixed height for visual consistency */
            border-radius: 15px;
            background-color: var(--mint);
            /* Base color for radio tile */
            border: 2px solid var(--mint);
            padding: 1rem;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .radio-tile-group .input-container input:checked+.radio-tile {
            background-color: var(--mustard);
            color: var(--cream);
            /* Text color when checked */
            border-color: var(--sand);
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(212, 147, 31, 0.4);
        }

        .radio-tile-group .input-container input:hover+.radio-tile {
            border-color: var(--mustard);
        }

        .radio-tile-group .input-container .radio-tile svg {
            fill: var(--dark-text);
            /* Default icon color */
            transition: fill 0.3s ease-in-out;
            height: 2rem;
            /* Size for the icon */
            width: 2rem;
        }

        .radio-tile-group .input-container input:checked+.radio-tile svg {
            fill: var(--cream);
            /* Icon color when checked */
        }

        .radio-tile-group .input-container .radio-tile span {
            font-weight: 600;
            margin-top: 0.5rem;
            color: var(--dark-text);
            /* Default text color */
            transition: color 0.3s ease-in-out;
            text-align: center;
        }

        .radio-tile-group .input-container input:checked+.radio-tile span {
            color: var(--cream);
            /* Text color when checked */
        }

        /* Image Upload Placeholder Styles */
        .image-upload-area {
            border: 2px dashed var(--mint);
            background-color: var(--cream);
            /* Cream background for the upload area */
            color: var(--mustard);
            transition: border-color 0.2s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        .image-upload-area:hover {
            border-color: var(--mustard);
        }

        .image-upload-area input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 10;
        }

        .image-upload-area label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 5;
            /* Ensure label is below input type=file but above image */
            position: absolute;
            top: 0;
            left: 0;
            padding: 1rem;
        }

        .image-upload-area label img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
            /* Below text/icon */
            border-radius: 0.75rem;
            /* Match parent border-radius */
        }

        .image-upload-area label svg,
        .image-upload-area label span {
            position: relative;
            z-index: 2;
            /* Above the image preview */
        }

        .image-placeholder-text {
            display: block;
        }

        /* Success Message Styling */
        .success-message {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--mustard);
            color: white;
            padding: 1rem 2rem;
            border-radius: 9999px;
            /* Rounded-full */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out, transform 0.5s ease-out;
            z-index: 1001;
            font-family: 'Fredoka', sans-serif;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .success-message.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-10px);
            /* Slight lift animation */
        }
    </style>
</head>

<body>
    <!-- Post Adoption Modal HTML Structure -->
    <div id="post-adoption-modal" class="modal-overlay active">
        <div class="modal-content">
            <button class="modal-close-btn" id="close-modal-btn">&times;</button>
            <h2 class="text-4xl font-bold mb-6 text-center text-[color:var(--dark-text)]">Share Your Furry Friend!</h2>

            <form id="post-adoption-form" class="space-y-5">
                <div class="modal-input-group">
                    <label for="pet-name">Pet's Name</label>
                    <input type="text" id="pet-name" placeholder="e.g. Whiskers, Max" required>
                </div>

                <div class="modal-input-group">
                    <label for="pet-type">Pet Type</label>
                    <select id="pet-type" required>
                        <option value="" disabled selected>Select a type...</option>
                        <option value="dog">Dog 🐶</option>
                        <option value="cat">Cat 🐱</option>
                        <option value="rabbit">Rabbit 🐰</option>
                        <option value="bird">Bird 🐦</option>
                        <option value="hamster">Hamster 🐹</option>
                        <option value="other">Other 🐾</option>
                    </select>
                </div>

                <div class="modal-input-group">
                    <label for="pet-breed">Breed</label>
                    <input type="text" id="pet-breed" placeholder="e.g. Golden Retriever, Siamese">
                </div>

                <div class="modal-input-group flex flex-wrap -mx-2">
                    <div class="w-1/2 px-2 mb-4 md:mb-0">
                        <label for="pet-age">Age (Years)</label>
                        <input type="number" id="pet-age" placeholder="e.g. 2" min="0">
                    </div>
                    <div class="w-1/2 px-2">
                        <label for="pet-color">Color</label>
                        <input type="text" id="pet-color" placeholder="e.g. Calico, Brindle">
                    </div>
                </div>

                <div class="modal-input-group">
                    <label class="mb-2">Gender</label>
                    <div class="radio-tile-group">
                        <div class="input-container">
                            <input id="gender-male" type="radio" name="gender" value="male">
                            <div class="radio-tile">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 9a3 3 0 106 0 3 3 0 00-6 0zM12 11c-2.761 0-5 2.239-5 5v5h10v-5c0-2.761-2.239-5-5-5z">
                                    </path>
                                </svg>
                                <span>Male</span>
                            </div>
                        </div>
                        <div class="input-container">
                            <input id="gender-female" type="radio" name="gender" value="female">
                            <div class="radio-tile">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                                    </path>
                                </svg>
                                <span>Female</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-input-group">
                    <label for="pet-location">Location</label>
                    <input type="text" id="pet-location" placeholder="e.g. Dhaka, Bangladesh" required>
                </div>

                <div class="modal-input-group">
                    <label for="pet-description">A Little About Your Pet</label>
                    <textarea id="pet-description" rows="4"
                        placeholder="Share their personality, habits, and what kind of home they're looking for!"></textarea>
                </div>

                <div class="modal-input-group">
                    <label>Upload Photo</label>
                    <div
                        class="image-upload-area w-full h-40 rounded-xl flex items-center justify-center text-[color:var(--mustard)] cursor-pointer">
                        <input type="file" id="pet-image" accept="image/*">
                        <label for="pet-image" class="flex flex-col items-center gap-2 p-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="text-sm font-semibold image-placeholder-text">Click to upload image
                                (placeholder)</span>
                            <img id="image-preview" class="hidden" src="" alt="Image Preview">
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" id="cancel-post-button" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Post for Adoption!</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Message Container -->
    <div id="success-message" class="success-message">
        Post Submitted Successfully! 🎉
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const postAdoptionModal = document.getElementById('post-adoption-modal');
            const postAdoptionForm = document.getElementById('post-adoption-form');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const cancelPostButton = document.getElementById('cancel-post-button');
            const petImageInput = document.getElementById('pet-image');
            const imagePreview = document.getElementById('image-preview');
            const imagePlaceholderText = document.querySelector('.image-placeholder-text');
            const imagePlaceholderSvg = document.querySelector('.image-upload-area label svg');
            const successMessage = document.getElementById('success-message');

            // Close modal functions
            function closeModal() {
                postAdoptionModal.classList.remove('active');
                // Reset form fields when modal closes
                postAdoptionForm.reset();
                // Reset image preview
                imagePreview.classList.add('hidden');
                imagePreview.src = '';
                imagePlaceholderText.classList.remove('hidden');
                imagePlaceholderSvg.classList.remove('hidden');
            }

            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', closeModal);
            }
            if (cancelPostButton) {
                cancelPostButton.addEventListener('click', closeModal);
            }

            // Close modal when clicking outside of modal content
            if (postAdoptionModal) {
                postAdoptionModal.addEventListener('click', (event) => {
                    if (event.target === postAdoptionModal) {
                        closeModal();
                    }
                });
            }

            // Simulate image preview (since actual upload is not possible here)
            if (petImageInput) {
                petImageInput.addEventListener('change', (event) => {
                    if (event.target.files && event.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            imagePreview.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                            imagePlaceholderText.classList.add('hidden'); // Hide text
                            imagePlaceholderSvg.classList.add('hidden'); // Hide SVG
                        };
                        reader.readAsDataURL(event.target.files[0]);
                    } else {
                        imagePreview.classList.add('hidden');
                        imagePreview.src = '';
                        imagePlaceholderText.classList.remove('hidden'); // Show text
                        imagePlaceholderSvg.classList.remove('hidden'); // Show SVG
                    }
                });
            }

            // Handle form submission (simulated)
            if (postAdoptionForm) {
                postAdoptionForm.addEventListener('submit', (event) => {
                    event.preventDefault(); // Prevent actual form submission

                    // Simulate a successful post
                    successMessage.classList.add('show');
                    setTimeout(() => {
                        successMessage.classList.remove('show');
                        closeModal(); // Close modal after success message fades
                    }, 2500); // Show for 2.5 seconds
                });
            }
        });
    </script>
</body>

</html>