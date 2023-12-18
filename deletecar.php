<?php
session_start();

require_once 'dbcon.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['car_id'])) {

    $carId = mysqli_real_escape_string($conn, $_POST['car_id']);

    $sql = "DELETE FROM cars WHERE id = '$carId'";

    if ($conn->query($sql) === TRUE) {
        echo "Car record deleted successfully";
        header("Location: Records.php");

    } else {
        echo "Error deleting car record: " . $conn->error;
    }
}

$conn->close();
?>
