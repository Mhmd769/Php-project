<?php
include('dbcon.php');

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: process_payment.php");
    exit();
}

$carId = isset($_POST['car_id']) ? $_POST['car_id'] : '';
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$phoneNumber = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
$cardNumber = isset($_POST['card_number']) ? $_POST['card_number'] : '';
$cardHolderName = isset($_POST['card_holder_name']) ? $_POST['card_holder_name'] : '';
$cardExpiryDate = isset($_POST['card_expiry_date']) ? $_POST['card_expiry_date'] : '';
$cvv = isset($_POST['cvv']) ? $_POST['cvv'] : '';

if (!isValidCreditCard($cardNumber) || !isValidCardExpiryDate($cardExpiryDate) || !isValidCVV($cvv)) {
    echo "Invalid credit card details. Please check and try again.";
    exit();
}

$amount = calculateRentalAmount($startDate, $endDate);

// Insert payment details into the 'payments' table
$insertQuery = "INSERT INTO payments (user_id, car_id, amount, card_number, card_holder_name, card_expiry_date, cvv)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($insertQuery);
$user_id = $_SESSION['user_id'];

$stmt->bind_param('iidsssi', $user_id, $carId, $amount, $cardNumber, $cardHolderName, $cardExpiryDate, $cvv);

if ($stmt->execute()) {
    echo "Payment successful! Thank you for your booking.";
} else {
    echo "Payment failed. Please try again.";
}

$stmt->close();
$conn->close();

function isValidCreditCard($cardNumber) {
    // Implement your validation logic (e.g., Luhn algorithm)
    return true;
}

function isValidCardExpiryDate($cardExpiryDate) {
    // Implement your validation logic
    return true;
}

function isValidCVV($cvv) {
    // Implement your validation logic
    return true;
}

function calculateRentalAmount($startDate, $endDate) {
    // Implement your pricing logic based on rental duration
    return 100.00; // Placeholder amount, replace with the
}