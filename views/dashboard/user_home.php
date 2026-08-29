<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General User Dashboard</title>
    <link rel="stylesheet" href="/assets/css/user_home.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cherry+Bomb+One&family=Dongle&family=Zain:ital,wght@0,200;0,300;0,400;0,700;0,800;0,900;1,300;1,400&display=swap"
        rel="stylesheet">
</head>

<body>

    <section class="above-nav">
        <div class="above-nav-box">
            Announce something fun here!
        </div>
    </section>


    <section class="nav">
        <div class="nav-left">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <div class="nav-center">
            <a href="#" class="logo">
                <img src="/assets/images/logo2.png" alt="Logo">
            </a>
        </div>

        <div class="nav-right">
            <ul>

                <ul>
                    <li><a href="#"><img src="/assets/images/facebook (2).png" alt="" class="social-icon"></a></li>
                    <li><a href="#"><img src="/assets/images/instagram.png" alt="" class="social-icon"></a></li>
                    <li><a href="#" class="button">Login</a></li>
                    <li><a href="#" class="button">Sign Up</a></li>
                </ul>
            </ul>
        </div>

    </section>




    <section class="dashboard">
        <div class="centered-boxes-container">
            <div class="box">Box 1</div>
            <div class="box">Box 2</div>
            <div class="box">Box 3</div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#fff" fill-opacity="1"
                d="M0,160L80,176C160,192,320,224,480,218.7C640,213,800,171,960,160C1120,149,1280,171,1360,181.3L1440,192L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z">
            </path>
        </svg>
    </section>



    <section class="waiting">
        <h2>Who are waiting for You?</h2>
        <p>If you want to know more about a pet, just click on its box.</p>
        <div class="pet-grid">
            <div class="pet-card">
                <img src="/assets/images/cat1.jpg" alt="Esme and Ralda">
                <h3>Esme and Ralda</h3>
            </div>
            <div class="pet-card">
                <img src="/assets/images/cat1.jpg " alt=" Layla">
                <h3>Layla</h3>
            </div>
            <div class="pet-card">
                <img src="/assets/images/cat1.jpg" alt="Brown">
                <h3>Brown</h3>
            </div>
            <div class="pet-card">
                <img src="/assets/images/cat1.jpg" alt="Roy">
                <h3>Roy</h3>
            </div>
            <div class="pet-card">
                <img src="/assets/images/cat1.jpg" alt="Kristen">
                <h3>Kristen</h3>
            </div>
            <div class="pet-card">
                <img src="/assets/images/cat1.jpg" alt="Jack and Daniel">
                <h3>Jack and Daniel</h3>
            </div>
        </div>
        <button class="more-button">See All</button>
        <button class="more-button">Post Adoption</button>
    </section>







    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <!-- Back wave -->
        <path fill="#ffe6dc" fill-opacity="1"
            d="M0,224L40,213.3C80,203,160,181,240,192C320,203,400,245,480,240C560,235,640,181,720,170.7C800,160,880,192,960,197.3C1040,203,1120,181,1200,170.7C1280,160,1360,160,1400,165.3L1440,171L1440,320L0,320Z" />

        <!-- Middle wave -->
        <path fill="#f5a18c" fill-opacity="1"
            d="M0,192L40,176C80,160,160,128,240,144C320,160,400,224,480,240C560,256,640,224,720,197.3C800,171,880,149,960,154.7C1040,160,1120,192,1200,197.3C1280,203,1360,181,1400,170.7L1440,160L1440,320L0,320Z" />

        <!-- Front wave (your background color) -->
        <path fill="#f7b9a6" fill-opacity="1"
            d="M0,160L40,149.3C80,139,160,117,240,138.7C320,160,400,224,480,240C560,256,640,224,720,213.3C800,203,880,213,960,202.7C1040,192,1120,160,1200,160C1280,160,1360,192,1400,208L1440,224L1440,320L0,320Z" />
    </svg>



    <section class="vet">
        <h2>Schedule Your Pet's Health Check Today</h2>
        <p>
            Ensure your pet receives the expert care they deserve. Booking an appointment is quick and convenient. <br>
            From routine wellness exams to specialized treatments, we're here to support your pet's health journey.
        </p>

        <div class="vet-section">
            <div class="img1">
                <img src="/assets/images/vetpic2.png" alt="" srcset="">

            </div>


            <div class="img2">
                <img src="/assets/images/vetpic1.png" alt="" srcset="">
            </div>
        </div>


        <div class="vet-section">
            <button>Book a Slot</button>
            <button> View Appointments</button>
        </div>
    </section>



</body>

</html>