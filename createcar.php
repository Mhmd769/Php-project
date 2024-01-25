<?php
// Start the session
session_start();
require_once 'dbcon.php';

if (!isset($_SESSION['email'])) {
    // Redirect to the login page or perform any other action
    header("Location: AdminLogin.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
  // Redirect to the login page or perform any other action
  header("Location: login.php");
  exit();
}

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
$availability = isset($_POST['availability']) ? 1 : 0;
$year_of_make = $_POST['year_of_make'];

// Upload photo
$targetDir = "images/";  // Specify the directory for uploaded files
$photo = $_FILES['photo']['name'];
$targetFilePath = $targetDir . basename($photo);

// Validate and sanitize file type
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$fileExtension = pathinfo($targetFilePath, PATHINFO_EXTENSION);

if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
    die("Invalid file type. Allowed types: " . implode(', ', $allowedExtensions));
}

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
    echo '<script>alert("Car record inserted successfully);</script>';

    header("Location: Records.php");
} else {
    echo "Error inserting car record: " . $stmt->error;
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
