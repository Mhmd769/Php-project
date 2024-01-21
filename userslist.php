<?php

session_start();
require_once 'dbcon.php';

// Logout Process
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: AdminLogin.php");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page or perform any other action
    header("Location: AdminLogin.php");
    exit();
}

 // Check if the form is submitted for deleting a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['delete_user'];

    // Perform the delete operation
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $user_id);  // Fix: Change $id to $user_id

    if ($delete_stmt->execute()) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . $delete_stmt->error;
    }

    $delete_stmt->close();
}


// Fetch users
$sql = "SELECT * FROM users WHERE role = 'user'";
$result = $conn->query($sql);
?>

<!-- HTML and Displaying User Data -->
<!DOCTYPE html>
<html lang="en">

  <head>
	<title>CarRent_Cars</title>
	    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap" rel="stylesheet">

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



    <head>
    <!-- Other head elements -->

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            color:black;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        form {
            display: inline;
        }
    </style>
  </head>
  <body>
	  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="index.php">Car<span>Book</span></a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item active"><a href="Addcar.php" class="nav-link">Addcar</a></li>
			  <li class="nav-item active"><a href="Records.php" class="nav-link">Records</a></li>
			  <li class="nav-item active"><a href="userslist.php" class="nav-link">our Users</a></li>
        <li class="nav-item active"><a href="feedbacklist.php" class="nav-link">our feedbacks</a></li>

			  <?php if (isset($_SESSION['email'])) : ?>
              <li class="nav-item">
                <a class="nav-link" href="?logout">Logout</a>
              </li>
            <?php endif; ?>
	        </ul>
	      </div>
	    </div>
	  </nav>
	  <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('images/bg_3.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
          <div class="col-md-9 ftco-animate pb-5">
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Addcar <i class="ion-ios-arrow-forward"></i></a></span> <span>Car details <i class="ion-ios-arrow-forward"></i></span></p>
            <h1 class="mb-3 bread">Add Car</h1>
          </div>
        </div>
      </div>
    </section>

	<section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
    <h2>List of Users</h2>

    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Remove</th>
                </tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>
                                <form action='' method='post'>
                                    <input type='hidden' name='delete_user' value='{$row['id']}'>
                                    <button  class='btn btn-danger py-2 mr-1' type='submit'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                }
        echo "</table>";
    } else {
        echo "No users found.";
    }
    ?>
</div>
</div>
</section>
</body>


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
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
		</div>
	  </div>
	</div>
  </footer>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


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

</html>
