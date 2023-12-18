<?php
// Start the session
session_start();
require_once 'dbcon.php';
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
	$price = $_POST['price']; // New field

	// Insert data into the 'cars' table
	$sql = "INSERT INTO cars (name, mileage, transmission, seats, luggage, fuel, description, price) 
			VALUES ('$carName', $mileage, '$transmission', $seats, $luggage, '$fuel', '$description', $price)";

	if ($conn->query($sql) === TRUE) {
		echo "Car record inserted successfully";
        header("Location: Records.php");
	} else {
		echo '<script>alert("Error inserting car record");</script>';
	}

	// Close the database connection
	$conn->close();
?>