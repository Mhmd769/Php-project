<?php
// Start the session
session_start();
require_once 'dbcon.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$carName = $_POST['carName'];
$mileage = $_POST['mileage'];
$transmission = $_POST['transmission'];
$seats = $_POST['seats'];
$luggage = $_POST['luggage'];
$fuel = $_POST['fuel'];
$description = $_POST['description'];
$price = $_POST['price'];
$photo = $_FILES['photo']['name'];
$availability = isset($_POST['availability']) ? 1 : 0;
$year_of_make = $_POST['year_of_make'];

// Upload photo
$targetDir = "./";
$targetFilePath = $targetDir . basename($photo);

// Move uploaded file and check for errors
if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
    echo "File uploaded successfully";
} else {
    die("Error moving file.");
}

// Prepare and bind the SQL statement
$sql = "INSERT INTO cars (name, mileage, transmission, seats, luggage, fuel, description, price, photo, availability, year_of_make) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error in preparing the statement: " . $conn->error);
}

// Bind parameters
$stmt->bind_param("sssiissdssd", $carName, $mileage, $transmission, $seats, $luggage, $fuel, $description, $price, $photo, $availability, $year_of_make);

// Execute the statement and check for errors
if ($stmt->execute()) {
    echo "Car record inserted successfully";
    header("Location: Records.php");
} else {
    echo "Error inserting car record: " . $stmt->error;
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
