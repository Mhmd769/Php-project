
<?php
$servername = 'localhost';
$username = 'root';
$password = 'PH{}alia1b2c5d123';
$dbname = 'user_auth';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
