<?php

session_start(); // Start or resume a session
include('dbcon.php');
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['car_id'] = isset($_POST['car_id']) ? $_POST['car_id'] : '';
    $_SESSION['start_date'] = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $_SESSION['end_date'] = isset($_POST['end_date']) ? $_POST['end_date'] : '';
    $_SESSION['phone_number'] = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';

    // Add other necessary session data

    // Redirect to payment.php
   header("Location: newpayment.php");
        exit();
}

// The rest of your existing code...

?>

<?php
include('dbcon.php');

$carId = isset($_GET['car_id']) ? $_GET['car_id'] : '';
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';

// Build the SQL query based on the search parameters
$query = "SELECT * FROM cars";

if (!empty($carId)) {
    $query .= " WHERE id = ?";
}

if ($availability == 'available') {
    $query .= " AND availability = 1";
} elseif ($availability == 'unavailable') {
    $query .= " AND availability = 0";
}

// Prepare and execute the query 
$stmt = $conn->prepare($query);
if (!empty($carId)) {
    $stmt->bind_param('i', $carId);
}
$stmt->execute();
$result = $stmt->get_result();


// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Booking</title>
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h1>Car Rental Booking</h1>

    <form id="bookingForm" action=" newpayment.php" method="post" onsubmit="return validateBookingForm()">
    <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
    
    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" required>

    <label for="end_date">End Date:</label>
    <input type="date" name="end_date" required>

    <label for="phone_number">Phone Number:</label>
    <input type="tel" name="phone_number" required>

    <!-- Add other necessary form fields -->

    <button type="submit">Proceed to Payment</button>
</form>

<script>
    document.getElementById('bookingForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        if (validateBookingForm()) {
            // If validation is successful, manually navigate to payment.php
            window.location.href = 'newpayment.php';
        }
    });

    function validateBookingForm() {
        // Add any additional validation logic here
        var startDate = document.getElementsByName('start_date')[0].value;
        var endDate = document.getElementsByName('end_date')[0].value;

        // Basic date validation
        if (startDate >= endDate) {
            alert('End date must be greater than start date.');
            return false;
        }

        return true;
    }
</script>
