<?php
session_start();

require_once 'dbcon.php';


if (!isset($_SESSION['email'])) {
    header("Location: AdminLogin.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['car_id'])) {
    $carId = mysqli_real_escape_string($conn, $_POST['car_id']);

    // Check if the car is booked
    $bookingCheckSql = "SELECT id FROM bookings WHERE car_id = '$carId'";
    $bookingCheckResult = $conn->query($bookingCheckSql);

    if ($bookingCheckResult->num_rows > 0) {
        $deleteBookingSql = "DELETE FROM bookings WHERE car_id = '$carId'";
        if ($conn->query($deleteBookingSql) === TRUE) {
            echo "Booking records deleted successfully";
        } else {
            echo "Error deleting booking records: " . $conn->error;
        }
    }

    // delete the car record
    $deleteCarSql = "DELETE FROM cars WHERE id = '$carId'";
    if ($conn->query($deleteCarSql) === TRUE) {
        echo '<script>alert("Car deleted successfully);</script>';
        header("Location: Records.php");
    } else {
        echo "Error deleting car record: " . $conn->error;
    }
}

$conn->close();
?>
