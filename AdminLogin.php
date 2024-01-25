<?php
session_start();
require_once 'dbcon.php';

// Logout Process
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: AdminLogin.php");
}


// Login Process
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $row["role"];

            if ($_SESSION["role"] == "admin") {
                header("Location: addcar.php");
            } else {
                echo '<script>alert("You do not have permission to access this page. Please log in as a user.");</script>';

                echo '<script>window.location.href = "Login.php";</script>';
                exit();
            }

            exit(); 
        } else {
            echo '<script>alert("Invalid password");</script>';
        }
    } else {
        echo '<script>alert("User not found");</script>';
    }
}
?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Login</title>
        <!-- Your styles go here -->
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
    </style>
    </head>
    <body>
        <div class="container">
       
                <h2>Admin LOGIN</h2>
                <form action="" method="post">
                    <label for="email">Admin Email:</label>
                    <input type="email" name="email" required>
                    
                    <label for="password">Admin Password:</label>
                    <input type="password" name="password" required>
    
                    <button type="submit" name="login">Login</button>
                </form>
                <div>
                    <p>Don't have an account? <a href="AdminRegistration.php">Admin register</a></p>
                </div>
        </div>
    </body>
    </html>
