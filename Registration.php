<?php
session_start();
require_once 'dbcon.php';

$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if the email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailQuery);

    if (!$checkEmailStmt) {
        die("Error preparing email check query: " . $conn->error);
    }

    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $result = $checkEmailStmt->get_result();

    if ($result->num_rows > 0) {
        $errorMsg = "Error: Email is already taken.";
        echo '<script>alert("'.$errorMsg.'");</script>';
    } else {
        $role = 'user'; // Default role for registered users

        $insertUserQuery = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $insertUserStmt = $conn->prepare($insertUserQuery);

        if (!$insertUserStmt) {
            die("Error preparing insert user query: " . $conn->error);
        }

        $insertUserStmt->bind_param("ssss", $name, $email, $password, $role);

        if ($insertUserStmt->execute()) {
            // Log in the newly registered user
            $user_id = $conn->insert_id;
            $_SESSION["user_id"] = $user_id;
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;

            $errorMsg = "Registration successful!";
            echo '<script>alert("'.$errorMsg.'");</script>';
            echo '<script>window.location.href = "index.php";</script>';
            exit();

        } else {
            $errorMsg = "Error: " . $insertUserStmt->error;
            echo '<script>alert("'.$errorMsg.'");</script>';
        }

        $insertUserStmt->close();
    }

    $checkEmailStmt->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
        }

        input {
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .logout-link {
            margin-top: 10px;
            text-align: center;
        }
        select {
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%; 
        }

        select option {
            padding: 10px;
        }

        /* Display errors at the bottom of the container in red text */
        .error-msg {
            color: red;
            margin-top: 10px;            
        }
           
    </style>
    <script>
        <?php if ($errorMsg !== '') : ?>
            alert("<?php echo $errorMsg; ?>");
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="container">
        <h2>Register a new account</h2>
        <form action="" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit" name="register">Register</button>
            <div>
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </form>
    </div>
</body>
</html>
