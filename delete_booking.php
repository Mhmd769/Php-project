<?php
session_start();
require_once 'dbcon.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Logout Process
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_booking'])) {
    $booking_id_to_delete = $_POST['delete_booking'];

    // Retrieve booking details to get the car_id
    $booking_details_query = "SELECT * FROM bookings WHERE id = $booking_id_to_delete";
    $booking_details_result = $conn->query($booking_details_query);

    if ($booking_details_result && $booking_details_result->num_rows > 0) {
        $booking_details = $booking_details_result->fetch_assoc();
        $car_id = $booking_details['car_id'];

        // Perform the deletion operation
        $delete_query = "DELETE FROM bookings WHERE id = $booking_id_to_delete";
        $delete_result = $conn->query($delete_query);

        // Update car availability to 1
        if ($delete_result) {
            $update_availability_query = "UPDATE cars SET availability = 1 WHERE id = $car_id";
            $conn->query($update_availability_query);

            $message = "Booking canceled successfully! Car availability updated.";
            $message_type = "success";
        } else {
            $message = "Error canceling booking. Please try again.";
            $message_type = "danger";
        }
    } else {
        $message = "Invalid booking details. Please try again.";
        $message_type = "danger";
    }
} else {
    $message = "Invalid request. Please try again.";
    $message_type = "danger";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Delete Booking - CarRent_Cars</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: 1px solid #dcdcdc;
            border-radius: 8px;
        }

        .card-body {
            padding: 30px;
        }

        .card-title {
            color: #343a40;
        }

        .alert {
            margin-bottom: 20px;
            color:green;
        }

        .btn-primary {
            padding:10px;
            border-radius:20px;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {

            color: #fff;
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            color: #fff;
            background-color: #bd2130;
            border-color: #bd2130;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Booking Cancellation</h2>
                        <?php if (isset($message)) : ?>
                            <div class="alert alert-<?php echo $message_type; ?>" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                        <div class="text-center">
                            <a href="index.php" class="btn btn-primary">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add your scripts and footer content here -->

</body>

</html>
