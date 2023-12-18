<?php
session_start();
require_once 'dbcon.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}   

if (isset($_GET['car_id'])) {
    $carId = $_GET['car_id'];
    $result = $conn->query("SELECT * FROM cars WHERE id = $carId");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No car found with the provided ID.";
        exit; // Stop execution if no car is found
    }
} else {
    echo "Car ID is not set.";
    exit; // Stop execution if car_id is not set
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the car_id is set
    if (isset($_POST['car_id'])) {
        $carId = $_POST['car_id'];

        // Collect form data
        $carName = $_POST['carName'];
        $mileage = $_POST['mileage'];
        $transmission = $_POST['transmission'];
        $seats = $_POST['seats'];
        $luggage = $_POST['luggage'];
        $fuel = $_POST['fuel'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        // Update data in the 'cars' table
        $sql = "UPDATE cars SET 
                name = '$carName',
                mileage = '$mileage',
                transmission = '$transmission',
                seats = '$seats',
                luggage = '$luggage',
                fuel = '$fuel',
                description = '$description',
                price = '$price'
                WHERE id = $carId";

        if ($conn->query($sql) === TRUE) {
            echo "Car record updated successfully";
            header("Location: Records.php");

        } else {
            echo "Error updating car record: " . $conn->error;
        }
    } else {
        // Handle if car_id is not set
        echo "Car ID is not set.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Update Form</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        button {
            margin-top: 10px;
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

    <div class="container">
        <form id="editForm" action="" method="post">
            <h2 class="mb-4">Update Car Record</h2>

            <input type="hidden" name="car_id" value="<?= $row['id'] ?>">

            <div class="form-group">
                <label for="carName">Car Name:</label>
                <input type="text" class="form-control" name="carName" value="<?= $row['name'] ?>">
            </div>

            <div class="form-group">
                <label for="mileage">Mileage:</label>
                <input type="text" class="form-control" name="mileage" value="<?= $row['mileage'] ?>">
            </div>

            <div class="form-group">
                <label for="transmission">Transmission:</label>
                <input type="text" class="form-control" name="transmission" value="<?= $row['transmission'] ?>">
            </div>

            <!-- Add other fields here -->

            <div class="form-group">
                <label for="seats">Seats:</label>
                <input type="text" class="form-control" name="seats" value="<?= $row['seats'] ?>">
            </div>

            <div class="form-group">
                <label for="luggage">Luggage:</label>
                <input type="text" class="form-control" name="luggage" value="<?= $row['luggage'] ?>">
            </div>

            <div class="form-group">
                <label for="fuel">Fuel:</label>
                <input type="text" class="form-control" name="fuel" value="<?= $row['fuel'] ?>">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" name="description" value="<?= $row['description'] ?>">
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" name="price" value="<?= $row['price'] ?>">
            </div>

            <button type='submit' class='btn btn-primary btn-sm'>Update</button>
        </form>
    </div>

    <script>
    $(document).ready(function () {
        $("#saveBtn").click(function () {

            var carId = $("input[name='car_id']").val();

            if (carId) {
                $.post("update_record.php", $("#editForm").serialize(), function (data) {
                    alert(data); 
                });
            } else {
                alert("Car ID not set. Please make sure to select a car before updating.");
            }
        });
    });
</script>

</body>
</html>
