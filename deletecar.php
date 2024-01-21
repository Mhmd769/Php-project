<?php
session_start();

require_once 'dbcon.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['car_id'])) {
    $carId = mysqli_real_escape_string($conn, $_POST['car_id']);

    // Check if the car is booked
    $bookingCheckSql = "SELECT id FROM bookings WHERE car_id = '$carId'";
    $bookingCheckResult = $conn->query($bookingCheckSql);

    if ($bookingCheckResult->num_rows > 0) {
        // Car is booked, delete both car and associated booking records
        $deleteBookingSql = "DELETE FROM bookings WHERE car_id = '$carId'";
        if ($conn->query($deleteBookingSql) === TRUE) {
            echo "Booking records deleted successfully";
        } else {
            echo "Error deleting booking records: " . $conn->error;
        }
    }

    // Now, delete the car record
    $deleteCarSql = "DELETE FROM cars WHERE id = '$carId'";
    if ($conn->query($deleteCarSql) === TRUE) {
        echo "Car record deleted successfully";
        header("Location: Records.php");
    } else {
        echo "Error deleting car record: " . $conn->error;
    }
}

$conn->close();
?>
