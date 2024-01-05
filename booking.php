<?php
session_start();

// Include your database connection code here
require_once 'dbcon.php'; // Adjust the path as needed

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle accordingly
    header("Location: login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $car_id = $_POST["car_id"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $phone_number = $_POST["phone_number"]; 
    // Calculate the number of days
    $nb_of_days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);

    // Retrieve car price from the database
    $car_price_sql = "SELECT price FROM cars WHERE id = ?";
    $car_price_stmt = $conn->prepare($car_price_sql);
    $car_price_stmt->bind_param("i", $car_id);
    $car_price_stmt->execute();
    $car_price_result = $car_price_stmt->get_result();

    if ($car_price_result->num_rows > 0) {
        $car_row = $car_price_result->fetch_assoc();
        $car_price = $car_row["price"];

        // Calculate total amount
        $total_amount = $nb_of_days * $car_price;

        // Get user ID from the session
        $user_id = $_SESSION['user_id'];

        // Save booking details to the database
        $booking_sql = "INSERT INTO bookings (user_id, car_id, start_date, end_date, phone_number, number_of_days, total_payment, status) 
                        VALUES (?, ?, ?, ? , ?, ?, ?, 'unpaid')";

        $booking_stmt = $conn->prepare($booking_sql);
        $booking_stmt->bind_param("iissdsd", $user_id, $car_id, $start_date, $end_date, $phone_number, $nb_of_days, $total_amount);

        if ($booking_stmt->execute()) {
            // Redirect to the payment page with the booking_id parameter
            $booking_id = $booking_stmt->insert_id;
            header("Location: payment.php?booking_id=$booking_id&success=1");
            exit();
        } else {
            echo "Error: " . $booking_stmt->error;
        }
    } else {
        echo "Car not found";
    }
}

// Retrieve car data for the form
$cars_sql = "SELECT id, name FROM cars";
$cars_result = $conn->query($cars_sql);
?>
<!-- Rest of your HTML code -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Booking</title>

    <style> 
        body { 
            font-family: 'Arial', sans-serif; 
            background-color: #f4f4f4; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
        } 
 
        h2 { 
            color: #333; 
        } 
 
        form { 
            background-color: #fff; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            width: 300px; 
        } 
 
        label { 
            display: block; 
            margin-bottom: 8px; 
            color: #555; 
        } 
 
        select, 
        input, 
        button { 
            width: 100%; 
            padding: 10px; 
            margin-bottom: 16px; 
            box-sizing: border-box; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        } 
 
        button { 
            background-color: #4caf50; 
            color: #fff; 
            cursor: pointer; 
        } 
 
        button:hover { 
            background-color: #45a049; 
        } 
 
        p { 
            color: #4caf50; 
            font-weight: bold; 
        } 
    </style> 
</head>
<body>
    <h2>Car Booking</h2>

    <?php
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<p>Booking successful. Proceed to payment below:</p>";
    }
    ?>

    <form action="booking.php" method="post">
        <label for="car_id">Select Car:</label>
        <select name="car_id" required>
            <?php
            // Display car options
            while ($car_row = $cars_result->fetch_assoc()) {
                echo "<option value='{$car_row['id']}'>{$car_row['name']}</option>";
            }
            ?>
        </select>

        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" required>

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" required>

        <label for="phone_number">Phone Number:</label> 
        <input type="text" name="phone_number" required> 

        <button type="submit">Proceed to Payment</button>
    </form>

    <script>
        // Calculate and display the total amount based on car price and number of days
        document.querySelector('select[name="car_id"]').addEventListener('change', function () {
            // Retrieve car price and calculate total amount
            var carId = this.value;
            var carPriceUrl = 'get_car_price.php?car_id=' + carId;
            fetch(carPriceUrl)
                .then(response => response.json())
                .then(data => {
                    var nbOfDays = parseInt(document.querySelector('input[name="end_date"]').value) - 
                                    parseInt(document.querySelector('input[name="start_date"]').value);
                    var totalAmount = data.price * nbOfDays;
                    document.getElementById('total_amount').innerText = totalAmount.toFixed(2);
                });
        });
    </script>
</body>
</html>
