<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Payment</title>
  <style>
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

    .success-message {
      color: #4caf50;
      font-weight: bold;
      margin-top: 10px;
    }

    .error-message {
      color: #ff0000;
      font-weight: bold;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<?php
// Include your database connection file or code here
// Example: include 'db_connection.php';
include("dbcon.php");

$success_message = $error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and process the form data
  $user_id = 1; // Assuming the user ID is fixed or obtained from a session
  $car_id = $_POST["car_id"];
  $amount = $_POST["amount"];
  $card_number = $_POST["card_number"];
  $card_holder_name = $_POST["card_holder_name"];
  $card_expiry_date = $_POST["card_expiry_date"];
  $cvv = $_POST["cvv"];

  // Add appropriate validation and sanitation measures
  $user_id = intval($user_id);
  $car_id = intval($car_id);
  $amount = floatval($amount);
  $card_number = mysqli_real_escape_string($conn, $card_number);
  $card_holder_name = mysqli_real_escape_string($conn, $card_holder_name);
  $card_expiry_date = mysqli_real_escape_string($conn, $card_expiry_date);
  $cvv = intval($cvv);

  // Check if the provided car_id exists in the cars table
  $check_car_query = "SELECT id FROM cars WHERE id = '$car_id'";
  $result = mysqli_query($conn, $check_car_query);

  if (mysqli_num_rows($result) == 0) {
    $error_message = "Error: The provided car ID does not exist.";
  } else {
    // Insert data into the payments table
    $sql = "INSERT INTO payments (user_id, car_id, amount, card_number, card_holder_name, card_expiry_date, cvv)
            VALUES ('$user_id', '$car_id', '$amount', '$card_number', '$card_holder_name', '$card_expiry_date', '$cvv')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
      $success_message = "Payment successful. Thank you!";
    } else {
      $error_message = "Error: " . mysqli_error($conn);
    }
  }
}

// Close the database connection
$conn->close();
?>

<form method="post" action="newpayment.php" onsubmit="return validateForm()">
  <!-- User ID is hidden -->
  <input type="hidden" name="user_id" value="1">

  <!-- Car ID is hidden -->
  <input type="hidden" name="car_id" value="3"> <!-- Replace "3" with the actual value or mechanism to get the car_id -->

  <label for="amount">Amount:</label>
  <input type="text" name="amount" required>

  <label for="card_number">Card Number:</label>
  <input type="text" name="card_number" required>

  <label for="card_holder_name">Card Holder Name:</label>
  <input type="text" name="card_holder_name" required>

  <label for="card_expiry_date">Card Expiry Date:</label>
  <input type="text" name="card_expiry_date" placeholder="MM/YYYY" required>

  <label for="cvv">CVV:</label>
  <input type="text" name="cvv" required>

  <button type="submit">Pay Now</button>

  <?php
  if (!empty($success_message)) {
    echo '<p class="success-message">' . $success_message . '</p>';
  } elseif (!empty($error_message)) {
    echo '<p class="error-message">' . $error_message . '</p>';
  }
  ?>
</form>

<script>
  function validateForm() {
    // Add client-side validation if needed
    return true;
  }
</script>

</body>
</html>

