<?php
include '../config/db.php';  // Include the database connection

// Fetch available time slots for a specific doctor and date
if (isset($_POST['doctor_id']) && isset($_POST['appointment_date'])) {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];

    // Fetch available time slots from the database
    $stmt = $conn->prepare("
        SELECT SlotID, StartTime, EndTime
        FROM timeslots
        WHERE DoctorID = ? AND SlotDate = ? AND IsAvailable = 1
    ");
    $stmt->bind_param("is", $doctor_id, $appointment_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $time_slots = [];
    while ($slot = $result->fetch_assoc()) {
        $time_slots[] = [
            'SlotID' => $slot['SlotID'],
            'StartTime' => $slot['StartTime'],
            'EndTime' => $slot['EndTime']
        ];
    }

    echo json_encode($time_slots); // Return available time slots as JSON
} else {
    echo json_encode([]); // No available slots if input is missing
}
?>
