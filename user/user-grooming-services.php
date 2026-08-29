<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pet Grooming Services | Pawsome</title>
    <style>
        /* Root color variables */
        :root {
            --color-soft-green: #B3CBBA;
            --color-warm-terracotta: #DE8562;
            --color-golden-brown: #B87B19;
            --color-dark-text: #3a3a3a;
            --color-bg-light: #F9FAFB;
        }

        /* Reset & base */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--color-dark-text);
            background-color: var(--color-bg-light);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Header */
        header {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background: white;
            box-shadow: 0 2px 5px rgb(0 0 0 / 0.1);
        }

        header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            height: 80px;
        }

        nav {
            display: flex;
            gap: 2rem;
            font-weight: 600;
            font-size: 1.125rem;
            flex-grow: 1;
            justify-content: flex-start;
        }

        nav a {
            color: var(--color-dark-text);
            transition: color 0.2s ease;
            padding-bottom: 3px;
            border-bottom: 3px solid transparent;
        }

        nav a:hover {
            color: var(--color-warm-terracotta);
            border-bottom-color: var(--color-warm-terracotta);
        }

        nav a.active {
            color: var(--color-warm-terracotta);
            border-bottom-color: var(--color-warm-terracotta);
            font-weight: 700;
        }

        header .logo img {
            height: 64px;
            transition: transform 0.3s ease;
        }

        header .logo img:hover {
            transform: scale(1.05);
        }

        header .profile-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1.125rem;
            color: var(--color-dark-text);
            transition: color 0.2s ease;
        }

        header .profile-link:hover {
            color: var(--color-warm-terracotta);
        }

        header .profile-link svg {
            width: 28px;
            height: 28px;
            flex-shrink: 0;
            stroke: var(--color-golden-brown);
            stroke-width: 1.5;
        }

        header .profile-link img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--color-warm-terracotta);
            box-shadow: 0 0 10px rgb(222 133 98 / 0.7);
            object-fit: cover;
        }

        /* Spacer to offset fixed header */
        .header-spacer {
            height: 80px;
        }

        /* Hero section */
        .services-hero-section {
            position: relative;
            background: linear-gradient(90deg, var(--color-soft-green), var(--color-warm-terracotta), var(--color-golden-brown));
            color: white;
            padding: 6rem 1.5rem 4rem;
            text-align: center;
            overflow: hidden;
            box-shadow: inset 0 0 60px rgb(0 0 0 / 0.15);
        }

        .services-hero-section h1 {
            font-size: 3.125rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
        }

        .services-hero-section p {
            font-size: 1.25rem;
            max-width: 640px;
            margin: 0 auto;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Paw prints */
        .paw-print {
            position: absolute;
            background-image: url('https://i.postimg.cc/9MN5bFhw/paw-print.svg');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.15;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 0 2px rgba(0, 0, 0, 0.1));
        }

        .paw-soft-green {
            filter: hue-rotate(80deg) saturate(100%) brightness(1.1);
        }

        .paw-warm-terracotta {
            filter: hue-rotate(10deg) saturate(130%);
        }

        .paw-golden-brown {
            filter: hue-rotate(0deg) saturate(150%) brightness(0.9);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .paw-1 {
            left: 10%;
            top: 15%;
            width: 55px;
            height: 55px;
            animation-delay: 0s;
            filter: drop-shadow(0 0 5px var(--color-soft-green));
        }

        .paw-2 {
            left: 80%;
            top: 40%;
            width: 40px;
            height: 40px;
            animation-delay: 2s;
            filter: drop-shadow(0 0 5px var(--color-warm-terracotta));
        }

        .paw-3 {
            left: 25%;
            top: 60%;
            width: 60px;
            height: 60px;
            animation-delay: 4s;
            filter: drop-shadow(0 0 5px var(--color-golden-brown));
        }

        .paw-4 {
            left: 60%;
            top: 85%;
            width: 45px;
            height: 45px;
            animation-delay: 1s;
            filter: drop-shadow(0 0 5px var(--color-soft-green));
        }

        .paw-5 {
            left: 5%;
            top: 90%;
            width: 35px;
            height: 35px;
            animation-delay: 3s;
            filter: drop-shadow(0 0 5px var(--color-warm-terracotta));
        }

        .paw-6 {
            left: 90%;
            top: 10%;
            width: 65px;
            height: 65px;
            animation-delay: 5s;
            filter: drop-shadow(0 0 5px var(--color-golden-brown));
        }

        /* Wave divider */
        .wave-divider {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            overflow: hidden;
            line-height: 0;
            height: 80px;
            transform: translateY(1px);
        }

        .wave-divider svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
        }

        .wave-divider path {
            fill: var(--color-bg-light);
        }

        /* Main section */
        .main-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 1.5rem 6rem;
        }

        .section-heading {
            color: var(--color-golden-brown);
            font-weight: 800;
            font-size: 2.25rem;
            margin-bottom: 3rem;
            text-align: center;
            text-shadow: 0 1px 1px rgba(255 255 255 / 0.7);
        }

        /* Grid for services */
        .grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 2.5rem;
        }

        @media (min-width: 768px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Service card */
        .service-card {
            background-color: white;
            border-radius: 2rem;
            box-shadow: 0 8px 15px rgb(0 0 0 / 0.1);
            padding: 2rem;
            text-align: center;
            transition: box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 4px solid var(--color-soft-green);
        }

        .service-card:hover {
            box-shadow: 0 15px 25px rgb(0 0 0 / 0.25);
            border-color: var(--color-warm-terracotta);
            transform: translateY(-6px);
        }

        .service-card svg {
            height: 5rem;
            width: 5rem;
            margin-bottom: 1.5rem;
            stroke: var(--color-golden-brown);
            stroke-width: 2;
        }

        .service-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-warm-terracotta);
            margin-bottom: 1rem;
        }

        .service-card p {
            font-size: 1rem;
            color: #4a4a4a;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--color-warm-terracotta);
            color: white;
            border: none;
            padding: 0.75rem 1.75rem;
            font-size: 1.125rem;
            font-weight: 600;
            border-radius: 9999px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            user-select: none;
            box-shadow: 0 4px 10px rgb(222 133 98 / 0.7);
        }

        .btn-primary:hover {
            background-color: #b75e3e;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <nav>
                <a href="user-home.php">Dashboard</a>
                <a href="grooming-services.php" class="active">Grooming Services</a>
                <a href="my_bookings.php">My Bookings</a>
            </nav>
            <div class="logo">
                <a href="user-home.php" aria-label="Pawsome Home">
                    <img src="assets/images/logo2.png" alt="Pawsome Grooming Logo" />
                </a>
            </div>
            <a href="profile.php" class="profile-link" aria-label="User Profile">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                    </path>
                </svg>
                <img src="<?= $profilePhoto ?>" alt="Profile Photo" />
                <span><?= $userName ?></span>
            </a>
        </div>
    </header>

    <div class="header-spacer"></div>

    <section class="services-hero-section">
        <div class="paw-print paw-soft-green paw-1"></div>
        <div class="paw-print paw-warm-terracotta paw-2"></div>
        <div class="paw-print paw-golden-brown paw-3"></div>
        <div class="paw-print paw-soft-green paw-4"></div>
        <div class="paw-print paw-warm-terracotta paw-5"></div>
        <div class="paw-print paw-golden-brown paw-6"></div>

        <h1>Professional Pet Grooming Services</h1>
        <p>Pamper your furry friends with our expert grooming — baths, haircuts, nail trims, and more!</p>

        <div class="wave-divider">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160" preserveAspectRatio="none">
                <path d="M0,96C288,160,1152,0,1440,32L1440,160L0,160Z"></path>
            </svg>
        </div>
    </section>

    <main class="main-section">
        <h2 class="section-heading">Our Grooming Services</h2>
        <div class="grid">

            <article class="service-card">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <h3>Bath & Shampoo</h3>
                <p>Gentle cleansing and moisturizing to keep your pet’s coat shiny and healthy.</p>
                <button class="btn-primary">Book Now</button>
            </article>

            <article class="service-card">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m6 4H9m3-8v12" />
                </svg>
                <h3>Haircut & Styling</h3>
                <p>Custom haircuts and styling tailored to your pet’s breed and personality.</p>
                <button class="btn-primary">Book Now</button>
            </article>

            <article class="service-card">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m4-4H8" />
                </svg>
                <h3>Nail Trimming</h3>
                <p>Careful nail trimming for comfort and safety without stress or discomfort.</p>
                <button class="btn-primary">Book Now</button>
            </article>

            <article class="service-card">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <h3>Ear Cleaning</h3>
                <p>Gentle ear cleaning to prevent infections and keep your pet comfortable.</p>
                <button class="btn-primary">Book Now</button>
            </article>

            <article class="service-card">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12v4m-8-4v4m0-4h8m-8 0H4m12 0h4" />
                </svg>
                <h3>Teeth Brushing</h3>
                <p>Professional teeth brushing to maintain oral hygiene and fresh breath.</p>
                <button class="btn-primary">Book Now</button>
            </article>

            <article class="service-card">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 10h2v4H3v-4zm16 0h2v4h-2v-4zM7 10h2v4H7v-4zm4 0h2v4h-2v-4zm4 0h2v4h-2v-4z" />
                </svg>
                <h3>Flea & Tick Treatment</h3>
                <p>Effective treatments to keep your pet free from fleas and ticks all year round.</p>
                <button class="btn-primary">Book Now</button>
            </article>

        </div>
    </main>
</body>

</html>