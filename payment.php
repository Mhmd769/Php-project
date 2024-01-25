<?php
session_start();

require_once 'dbcon.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if a booking ID is provided in the URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Retrieve booking details from the database
    $booking_sql = "SELECT * FROM bookings WHERE id = ?";
    $booking_stmt = $conn->prepare($booking_sql);
    $booking_stmt->bind_param("i", $booking_id);
    $booking_stmt->execute();
    $booking_result = $booking_stmt->get_result();

    if ($booking_result->num_rows > 0) {
        $booking_row = $booking_result->fetch_assoc();
        $total_amount = $booking_row['total_payment'];

        // Process payment if the payment form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve and sanitize payment data
            $card_number = $_POST["card_number"];
            $cvv = $_POST["cvv"];

            $payment_sql = "UPDATE bookings 
                            SET card_number = ?, cvv = ?, status = 'paid' 
                            WHERE id = ?";
            
            $payment_stmt = $conn->prepare($payment_sql);
            $payment_stmt->bind_param("ssi", $card_number, $cvv, $booking_id);
            $payment_stmt->execute();

            if ($payment_stmt->affected_rows > 0) {
                echo '<script>alert("Payment successful. Thank you!");</script>';


                // Redirect to the home page after a successful payment
                header("Location: index.php");  // Adjust the path as needed
                exit();
            } else {
                echo "Error updating payment details: " . $payment_stmt->error;
            }
        }
    } else {
        echo "Booking not found";
    }
} else {
    echo "Booking ID not provided";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>

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

        input {
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
            padding: 10px;
            border: none;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            color: #4caf50;
            font-weight: bold;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <h2>Payment Details</h2>

    <?php if (isset($booking_row)): ?>
        <p>Total Amount: $<?php echo $total_amount; ?></p>

        <!-- Payment form -->
        <form action="payment.php?booking_id=<?php echo $booking_id; ?>" method="post">
            <label for="card_number">Card Number:</label>
            <input type="text" name="card_number" required>

            <label for="cvv">CVV:</label>
            <input type="text" name="cvv" required>

            <!-- Add other input fields as needed -->

            <button type="submit">Submit Payment</button>
        </form>
    <?php endif; ?>
</body>
</html>