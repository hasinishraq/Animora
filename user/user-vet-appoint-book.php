<?php
session_start();
include '../config/db.php';

// Check login & role
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'User') {
    header("Location: /animora/auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$error = '';
$success = '';

// Fetch available vets
$available_doctors = [];
$stmt = $conn->prepare("
    SELECT u.UserID, u.Name
    FROM users u
    JOIN userroles ur ON u.UserID = ur.UserID
    JOIN roles r ON ur.RoleID = r.RoleID
    WHERE r.RoleName = 'Vet'
");
$stmt->execute();
$result = $stmt->get_result();
while ($doctor = $result->fetch_assoc()) {
    $available_doctors[] = $doctor;
}
$stmt->close();

// Handle appointment submission
if (isset($_POST['confirm_appointment'])) {
    $pet_name = trim($_POST['pet_name']);
    $doctor_id = intval($_POST['doctor_id']);
    $appointment_date = $_POST['appointment_date'];
    $slot_id = intval($_POST['time-slot']);
    $reason = trim($_POST['reason']);

    if ($pet_name && $doctor_id && $appointment_date && $slot_id && $reason) {
        $conn->begin_transaction();

        try {
            // 1. Verify slot is available
            $stmt = $conn->prepare("
                SELECT SlotID FROM timeslots 
                WHERE SlotID = ? AND IsAvailable = 1
                FOR UPDATE
            ");
            $stmt->bind_param("i", $slot_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // 2. Check if user already has appointment for this slot
                $checkStmt = $conn->prepare("
                    SELECT AppointmentID FROM appointments 
                    WHERE UserID = ? AND SlotID = ?
                ");
                $checkStmt->bind_param("ii", $user_id, $slot_id);
                $checkStmt->execute();

                if ($checkStmt->get_result()->num_rows > 0) {
                    throw new Exception("You already have an appointment booked for this time slot. Please choose a different time.");
                }

                // 3. Insert appointment
                $stmt = $conn->prepare("
                    INSERT INTO appointments (UserID, DoctorID, SlotID, PetName, Reason, Status)
                    VALUES (?, ?, ?, ?, ?, 'Pending')
                ");
                $stmt->bind_param("iiiss", $user_id, $doctor_id, $slot_id, $pet_name, $reason);

                if ($stmt->execute()) {
                    // 4. Mark slot as unavailable
                    $stmt = $conn->prepare("UPDATE timeslots SET IsAvailable = 0 WHERE SlotID = ?");
                    $stmt->bind_param("i", $slot_id);

                    if ($stmt->execute()) {
                        $conn->commit();
                        $success = "Appointment successfully booked!";
                    } else {
                        throw new Exception("Failed to update timeslot availability");
                    }
                } else {
                    throw new Exception("Failed to create appointment");
                }
            } else {
                throw new Exception("Selected time slot is no longer available");
            }
        } catch (Exception $e) {
            $conn->rollback();
            $error = $e->getMessage();
            error_log("Appointment Error: " . $e->getMessage());
        }
    } else {
        $error = "Please fill all required fields";
    }
}

// Fetch available slots for AJAX
if (isset($_POST['get_slots'])) {
    $doctor_id = intval($_POST['doctor_id']);
    $appointment_date = $_POST['appointment_date'];

    $slots = [];
    $stmt = $conn->prepare("
        SELECT SlotID, TIME_FORMAT(StartTime, '%H:%i') AS StartTime, 
               TIME_FORMAT(EndTime, '%H:%i') AS EndTime
        FROM timeslots
        WHERE DoctorID = ? AND SlotDate = ? AND IsAvailable = 1
    ");
    $stmt->bind_param("is", $doctor_id, $appointment_date);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($slot = $result->fetch_assoc()) {
        $slots[] = $slot;
    }

    header('Content-Type: application/json');
    echo json_encode($slots);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawsome Adoptions - Book Appointment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --cream: #FBF3F0;
            --mustard: #D4931F;
            --teal: #A0C0A9;
            --mint: #C2D4C8;
            --sand: #EEC3A4;
            --dark-text: #4A4A4A;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--cream);
            color: var(--dark-text);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
            color: var(--mustard);
        }

        .time-slot-button {
            background-color: var(--mint);
            color: var(--dark-text);
            border: 2px solid var(--mint);
            padding: 10px 20px;
            border-radius: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .time-slot-button:hover {
            background-color: var(--mustard);
            color: var(--cream);
            border-color: var(--mustard);
        }

        .time-slot-button.selected {
            background-color: var(--mustard);
            color: var(--cream);
            border-color: var(--sand);
            box-shadow: 0 5px 15px rgba(212, 147, 31, 0.4);
        }

        .paw-print {
            position: absolute;
            width: 50px;
            height: 50px;
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23D4931F"%3E%3Cpath d="M12 2C9.23 2 7 4.23 7 7c0 2.21 1.79 4 4 4h2c2.21 0 4-1.79 4-4 0-2.77-2.23-5-5-5zM15 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM9 13c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM12 16c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"%3E%3C/path%3E%3C/svg%3E');
            background-size: contain;
            background-repeat: no-repeat;
            border-radius: 50%;
            animation: floatPaw 12s infinite linear;
            opacity: 0.6;
            z-index: 0;
            pointer-events: none;
        }

        @keyframes floatPaw {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.4;
            }

            50% {
                transform: translateY(-25px) rotate(180deg);
                opacity: 0.7;
            }

            100% {
                transform: translateY(0) rotate(360deg);
                opacity: 0.4;
            }
        }
    </style>
</head>

<body class="text-[color:var(--dark-text)]">
    <!-- Header -->
    <header class="w-full bg-white py-4 shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <nav class="flex justify-center md:justify-start gap-8 text-lg font-semibold">
                <a href="user-home.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300">Dashboard</a>
                <a href="user-vet-appoint-view.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300">View Appointments</a>
                <a href="user-vet-appoint-book.php"
                    class="text-[color:var(--mustard)] hover:text-[color:var(--sand)] transition duration-300">Book An Appointment</a>
              
            </nav>
            <div class="flex justify-center flex-shrink-0">
                <img src="https://placehold.co/280x80/D4931F/FBF3F0?text=Pawsome+Logo" alt="Pawsome Adoptions Logo"
                    class="h-16 w-auto">
            </div>
            <div class="flex justify-center md:justify-end">
                <button
                    class="bg-[color:var(--mustard)] text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 hover:scale-105 shadow-md border-2 border-transparent hover:border-white">
                    <span class="flex items-center gap-2">Post Pet</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Spacer for fixed header -->
    <div class="h-24"></div>

    <!-- Hero Section -->
    <section
        class="relative w-full h-[550px] flex items-center justify-center overflow-hidden rounded-t-3xl shadow-xl text-center"
        style="background-color: #eaa793;">
        <!-- Background Paw Prints -->
        <div class="paw-print" style="left:10%;top:15%;width:55px;height:55px;"></div>
        <div class="paw-print" style="left:80%;top:40%;width:40px;height:40px;animation-delay:2s;"></div>
        <div class="paw-print" style="left:25%;top:60%;width:60px;height:60px;animation-delay:4s;"></div>
        <div class="paw-print" style="left:60%;top:85%;width:45px;height:45px;animation-delay:1s;"></div>

        <div class="relative z-10 p-6 max-w-7xl mx-auto">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight drop-shadow-md">
                <span class="block">Schedule Your Pet's Next Visit!</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto drop-shadow-sm text-[color:var(--dark-text)]">
                Expert care for your beloved companion, just a few clicks away.
            </p>
        </div>

        <!-- SVG Wave -->
        <div class="absolute bottom-0 left-0 w-full z-10" style="line-height: 0; overflow: hidden;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none"
                style="display: block; width: 100%; height: 120px;">
                <path fill="var(--cream)" fill-opacity="1"
                    d="M0,96L34.3,117.3C68.6,139,137,181,206,202.7C274.3,224,343,224,411,208C480,192,549,160,617,149.3C685.7,139,754,149,823,176C891.4,203,960,245,1029,245.3C1097.1,245,1166,203,1234,197.3C1302.9,192,1371,224,1406,240L1440,256L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">
                </path>
            </svg>
        </div>
    </section>

    <!-- Main Content -->
    <section class="w-full py-14 px-6 bg-[color:var(--cream)] relative z-10">
        <div class="max-w-3xl mx-auto bg-white p-8 md:p-12 rounded-3xl shadow-2xl border-4 border-[color:var(--mint)]">
            <h2 class="text-4xl font-bold mb-8 text-center text-[color:var(--mustard)]">Book Your Appointment</h2>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                    role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($success) ?></span>
                </div>
            <?php endif; ?>

            <form id="appointment-form" class="space-y-6" method="POST">
                <!-- Select Pet -->
                <div class="form-input-group">
                    <label for="select-pet" class="block text-[color:var(--mustard)] font-semibold mb-2">Select Your
                        Pet</label>
                    <select id="select-pet" name="pet_name"
                        class="w-full p-3 border-2 border-[color:var(--mint)] rounded-lg focus:border-[color:var(--mustard)] focus:ring-2 focus:ring-[color:var(--mustard)]"
                        required>
                        <option value="" disabled selected>Choose your furry friend...</option>
                        <option value="Mittens">Mittens (Cat)</option>
                        <option value="Buddy">Buddy (Dog)</option>
                        <option value="Hopper">Hopper (Rabbit)</option>
                        <option value="Other">Other Pet</option>
                    </select>
                </div>

                <!-- Select Doctor -->
                <div class="form-input-group">
                    <label for="select-doctor" class="block text-[color:var(--mustard)] font-semibold mb-2">Select
                        Doctor</label>
                    <select id="select-doctor" name="doctor_id"
                        class="w-full p-3 border-2 border-[color:var(--mint)] rounded-lg focus:border-[color:var(--mustard)] focus:ring-2 focus:ring-[color:var(--mustard)]"
                        required>
                        <option value="" disabled selected>Choose a doctor...</option>
                        <?php foreach ($available_doctors as $doctor): ?>
                            <option value="<?= $doctor['UserID'] ?>"><?= htmlspecialchars($doctor['Name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Preferred Date -->
                <div class="form-input-group">
                    <label for="appointment-date" class="block text-[color:var(--mustard)] font-semibold mb-2">Preferred
                        Date</label>
                    <input type="date" id="appointment-date" name="appointment_date"
                        class="w-full p-3 border-2 border-[color:var(--mint)] rounded-lg focus:border-[color:var(--mustard)] focus:ring-2 focus:ring-[color:var(--mustard)]"
                        required>
                </div>

                <!-- Time Slots Container -->
                <div class="form-input-group">
                    <label class="block text-[color:var(--mustard)] font-semibold mb-2">Available Time Slots</label>
                    <div id="time-slot-container" class="flex flex-wrap gap-3">
                        <p class="text-gray-500">Please select a doctor and date first</p>
                    </div>
                    <input type="hidden" id="selected-time-slot" name="time-slot" required>
                </div>

                <!-- Reason for Appointment -->
                <div class="form-input-group">
                    <label for="reason-for-appointment"
                        class="block text-[color:var(--mustard)] font-semibold mb-2">Reason for Appointment</label>
                    <textarea id="reason-for-appointment" name="reason" rows="4"
                        class="w-full p-3 border-2 border-[color:var(--mint)] rounded-lg focus:border-[color:var(--mustard)] focus:ring-2 focus:ring-[color:var(--mustard)]"
                        placeholder="Describe the reason for your visit..." required></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center mt-8">
                    <button type="submit" name="confirm_appointment"
                        class="bg-[color:var(--mustard)] text-white px-8 py-4 rounded-full font-bold text-lg transition duration-300 hover:scale-105 shadow-md border-2 border-transparent hover:border-white">
                        <span class="flex items-center gap-2">Confirm Appointment</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Information Section -->
    <section class="bg-[color:var(--sand)] py-16 px-6 relative z-10 rounded-t-3xl shadow-inner">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl font-bold mb-6 text-[color:var(--dark-text)]">What Happens Next?</h2>
            <p class="text-lg mb-4 text-[color:var(--dark-text)]">
                Once you confirm your appointment, our team will review your request and send a confirmation email with
                all the details.
            </p>
            <p class="text-lg text-[color:var(--dark-text)]">
                For urgent matters, please contact us directly at <span
                    class="font-semibold text-[color:var(--mustard)]">01-234-5678</span>.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[color:var(--mustard)] text-white py-12 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">
            <div>
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--cream)]">Pawsome Adoptions</h3>
                <p class="text-[color:var(--cream)] text-sm">Where every tail finds its wag!</p>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--cream)]">Quick Links</h3>
                <ul class="space-y-3 text-[color:var(--cream)]">
                    <li><a href="#" class="hover:text-white transition duration-300">Adopt a Pet</a></li>
                    <li><a href="#" class="hover:text-white transition duration-300">Post for Adoption</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--cream)]">Support</h3>
                <ul class="space-y-3 text-[color:var(--cream)]">
                    <li><a href="#" class="hover:text-white transition duration-300">Contact Us</a></li>
                    <li><a href="#" class="hover:text-white transition duration-300">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4 text-[color:var(--cream)]">Follow Us</h3>
                <div class="flex space-x-5">
                    <a href="#" class="text-[color:var(--cream)] hover:text-white transition duration-300">FB</a>
                    <a href="#" class="text-[color:var(--cream)] hover:text-white transition duration-300">IG</a>
                    <a href="#" class="text-[color:var(--cream)] hover:text-white transition duration-300">TW</a>
                </div>
            </div>
        </div>
        <div class="text-center text-[color:var(--cream)] text-sm mt-12 border-t-2 border-[color:var(--sand)] pt-8">
            &copy; <?= date('Y') ?> Pawsome Adoptions. All rights reserved.
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const doctorSelect = document.getElementById('select-doctor');
            const dateInput = document.getElementById('appointment-date');
            const timeSlotContainer = document.getElementById('time-slot-container');
            const hiddenSlotInput = document.getElementById('selected-time-slot');
            const form = document.getElementById('appointment-form');

            // Fetch available time slots
            function fetchAvailableSlots() {
                const doctorId = doctorSelect.value;
                const appointmentDate = dateInput.value;

                if (doctorId && appointmentDate) {
                    const formData = new FormData();
                    formData.append('doctor_id', doctorId);
                    formData.append('appointment_date', appointmentDate);
                    formData.append('get_slots', '1');

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            timeSlotContainer.innerHTML = '';
                            hiddenSlotInput.value = '';

                            if (data.length > 0) {
                                data.forEach(slot => {
                                    const button = document.createElement('button');
                                    button.type = 'button';
                                    button.classList.add('time-slot-button');
                                    button.setAttribute('data-slot-id', slot.SlotID);
                                    button.textContent = `${slot.StartTime} - ${slot.EndTime}`;

                                    button.addEventListener('click', function () {
                                        document.querySelectorAll('.time-slot-button').forEach(btn => {
                                            btn.classList.remove('selected');
                                        });
                                        this.classList.add('selected');
                                        hiddenSlotInput.value = this.getAttribute('data-slot-id');
                                    });

                                    timeSlotContainer.appendChild(button);
                                });
                            } else {
                                timeSlotContainer.innerHTML = '<p class="text-red-500">No available time slots for this date.</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            timeSlotContainer.innerHTML = '<p class="text-red-500">Error loading time slots.</p>';
                        });
                }
            }

            // Event listeners
            doctorSelect.addEventListener('change', fetchAvailableSlots);
            dateInput.addEventListener('change', fetchAvailableSlots);

            // Form submission handling
            form.addEventListener('submit', function (e) {
                if (!hiddenSlotInput.value) {
                    e.preventDefault();
                    alert('Please select a time slot');
                }
            });
        });
    </script>
</body>

</html>