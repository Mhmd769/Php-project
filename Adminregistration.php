<?php
session_start();
require_once 'dbcon.php';

// Function to hash passwords (use this when registering a new admin)
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Insert Admin Process
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register_admin"])) {
    $adminName = $_POST["admin_name"];
    $adminEmail = $_POST["admin_email"];
    $adminPassword = hashPassword($_POST["admin_password"]);
    $adminRole = "admin"; // Assuming "admin" is the role for administrators

    // Check if the email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailQuery);

    if (!$checkEmailStmt) {
        die("Error preparing check email query: " . $conn->error);
    }

    $checkEmailStmt->bind_param("s", $adminEmail);
    $checkEmailStmt->execute();
    $result = $checkEmailStmt->get_result();

    if ($result->num_rows > 0) {
        echo '<script>alert("Error: Email already exists. Please choose a different email.");</script>';
    } else {
        // Use a prepared statement to prevent SQL injection
        $insertAdminQuery = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $insertAdminStmt = $conn->prepare($insertAdminQuery);

        if (!$insertAdminStmt) {
            die("Error preparing insert admin query: " . $conn->error);
        }

        $insertAdminStmt->bind_param("ssss", $adminName, $adminEmail, $adminPassword, $adminRole);

        if ($insertAdminStmt->execute()) {
            // Log in the newly registered admin
            $_SESSION["email"] = $adminEmail;
            $_SESSION["role"] = $adminRole;

            // Redirect to a welcome or dashboard page
            echo '<script>alert("Admin user successfully registered and logged in!");</script>';
            echo '<script>window.location.href = "Addcar.php";</script>';
            exit();
        } else {
            echo '<script>alert("Error registering admin user: ' . $insertAdminStmt->error . '");</script>';
        }

        $insertAdminStmt->close();
    }

    $checkEmailStmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <!-- Add your styles here -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #555;
        }

        input {
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s ease-in-out;
        }

        input:focus {
            border-color: #4CAF50;
        }

        button {
            padding: 12px;
            margin-top: 10px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #45a049;
        }
        </style>
</head>
<body>
    <!-- Your HTML form for registering a new admin -->
    <div class="container">
        <h2>Register as new Admin</h2>
        <form action="" method="post">
            <label for="admin_name">Admin Name:</label>
            <input type="text" name="admin_name" required>

            <label for="admin_email">Admin Email:</label>
            <input type="email" name="admin_email" required>

            <label for="admin_password">Admin Password:</label>
            <input type="password" name="admin_password" required>

            <button type="submit" name="register_admin">Register Admin</button>

            <div>
                <p>Already have an account? <a href="AdminLogin.php">Admin Login</a></p>
            </div>
        </form>
    </div>
</body>
</html>