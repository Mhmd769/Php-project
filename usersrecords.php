<?php
session_start();
require_once 'dbcon.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch user details along with bookings using a JOIN
$query = "SELECT u.name, u.email, b.id, b.start_date, b.end_date, b.total_payment, b.status, c.name as car_name, c.year_of_make
          FROM users u
          LEFT JOIN bookings b ON u.id = b.user_id
          LEFT JOIN cars c ON b.car_id = c.id
          WHERE u.id = $user_id";

$result = $conn->query($query);

// Logout Process
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>CarRent_Cars</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap" rel="stylesheet">
	<script src="https://cdn.tailwindcss.com"></script>

	<link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
	<link rel="stylesheet" href="css/animate.css">

	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">
	<link rel="stylesheet" href="css/magnific-popup.css">

	<link rel="stylesheet" href="css/aos.css">

	<link rel="stylesheet" href="css/ionicons.min.css">

	<link rel="stylesheet" href="css/bootstrap-datepicker.css">
	<link rel="stylesheet" href="css/jquery.timepicker.css">


	<link rel="stylesheet" href="css/flaticon.css">
	<link rel="stylesheet" href="css/icomoon.css">
	<link rel="stylesheet" href="css/style.css">

    <style>
        .user-details {
            margin-top: 20px;
        }

        .user-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .user-info h2 {
            color: #343a40;
        }

        .user-info p {
            color: #6c757d;
        }

        .user-bookings {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        .user-bookings h2 {
            color: #343a40;
        }

        .table-responsive {
            margin-top: 15px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .btn-danger {
            color: red;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            color: #fff;
            background-color: #bd2130;
            border-color: #bd2130;
        }
    </style>

</head>

<body>

    

<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="index.html">Car<span>Book</span></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>
      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
        <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
	          <li class="nav-item"><a href="car.php" class="nav-link">Cars</a></li>
            <li class="nav-item"><a href="stat.php" class="nav-link">stat</a></li>
	          <li class="nav-item"><a href="FeedBack.php" class="nav-link">FeedBack</a></li>
            <li class="nav-item"><a href="usersrecords.php" class="nav-link">Records</a></li>
          <?php if (isset($_SESSION['email'])) : ?>
            <li class="nav-item">
              <a class="nav-link" href="?logout">Logout</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <!-- END nav -->
<section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('images/bg_3.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
        <div class="col-md-9 ftco-animate pb-5">
          <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>FeedBacks <i class="ion-ios-arrow-forward"></i></span></p>
          <h1 class="mb-3 bread">FeedBacks</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section bg-light">
        <div class="container">
            <div class="user-details">
                <?php
                // Check if there are results
                if ($result && $result->num_rows > 0) {
                    // Display user information
                    $user_details = $result->fetch_assoc();
                    echo "<div class='user-info'>
                            <h2>User Information</h2>
                            <p><strong>Email:</strong> " . $user_details['email'] . "</p>
                          </div>";

                    // Display user bookings
                    echo "<div class='user-bookings'>
                            <h2>User Bookings</h2>";

                    // Check if there are bookings
                    echo "<div class='table-responsive'>
                            <table class='table table-striped'>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Car Name</th>
                                        <th>Year</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total Payment</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>";

                    // Fetch and display bookings
                    do {
                        echo "<tr>
                                <td>{$user_details['id']}</td>
                                <td>{$user_details['car_name']}</td>
                                <td>{$user_details['year_of_make']}</td>
                                <td>{$user_details['start_date']}</td>
                                <td>{$user_details['end_date']}</td>
                                <td>{$user_details['total_payment']}</td>
                                <td>{$user_details['status']}</td>
                                <td>
                                    <form method='post' action='delete_booking.php'>
                                        <input type='hidden' name='delete_booking' value='" . $user_details['id'] . "'>
                                        <button type='submit' class='btn btn-danger'>Cancel</button>
                                    </form>
                                </td>
                            </tr>";
                    } while ($user_details = $result->fetch_assoc());

                    echo "</tbody></table></div>";
                } else {
                    // No bookings, display a message
                    echo "<p>No bookings right now.</p>";
                }

                echo "</div>";
                ?>
            </div>
        </div>
    </section>


<footer class="ftco-footer ftco-bg-dark ftco-section">
    <div class="container">
      <div class="row mb-5">
        <div class="col-md">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2"><a href="#" class="logo">Car<span>book</span></a></h2>
            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
            <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
              <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
              <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
            </ul>
          </div>
        </div>
        <div class="col-md">
          <div class="ftco-footer-widget mb-4 ml-md-5">
            <h2 class="ftco-heading-2">Pages</h2>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item"><a href="#index" class="nav-link">Home</a></li>
              <li class="nav-item"><a href="#Car" class="nav-link">Cars</a></li>
              <li class="nav-item"><a href="#FeedBack" class="nav-link">Feedbacks</a></li>
            </ul>
          </div>
        </div>

        <div class="col-md">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Have a Questions?</h2>
            <div class="block-23 mb-3">
              <ul>
                <li><span class="icon icon-map-marker"></span><span class="text">203 Fake St. Mountain View, San Francisco, California, USA</span></li>
                <li><a href="#"><span class="icon icon-phone"></span><span class="text">+2 392 3929 210</span></a></li>
                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@yourdomain.com</span></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">

          <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            Copyright &copy;<script>
              document.write(new Date().getFullYear());
            </script> All rights reserved
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
    </svg></div>
    <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>

</body>

</html>

