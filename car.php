<?php
// session_start();
// require_once 'dbcon.php';


// if (!isset($_SESSION['email'])) {

// 	header("Location: login.php");
// 	exit();
// }


// if (isset($_GET['logout'])) {
// 	session_destroy();
// 	header("Location: login.php");
// 	exit();
// }


// $sql = "SELECT * FROM cars";
// $result = $conn->query($sql);
session_start();
require_once 'dbcon.php';



// Set the car ID in session


// Check if the user is not logged in
if (!isset($_SESSION['email'])) {
	// Redirect to the login page or perform any other action
	header("Location: login.php");
	exit();
}

// Logout Process
if (isset($_GET['logout'])) {
	session_destroy();
	header("Location: login.php");
	exit();
}

// Fetch all cars from the database or search results based on the query
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
	$search = $_POST["search"];
	$yearFilter = isset($_POST["year_filter"]) ? $_POST["year_filter"] : "";

	// Fetch cars based on the search query and year filter
	$sql = "SELECT * FROM cars WHERE name LIKE '%$search%' AND (year_of_make LIKE '%$yearFilter%' OR '$yearFilter' = '')";
	$result = $conn->query($sql);

	// Check if there are cars found
	if ($result->num_rows === 0) {
		$searchMessage = " ";
	}
} else {
	// Fetch all cars from the database
	$sql = "SELECT * FROM cars";
	$result = $conn->query($sql);
}

$conn->close();
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
					<li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
					<li class="nav-item active"><a href="car.php" class="nav-link">Cars</a></li>
					<li class="nav-item"><a href="FeedBack.php" class="nav-link">FeedBack</a></li>
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
					<p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Cars <i class="ion-ios-arrow-forward"></i></span></p>
					<h1 class="mb-3 bread">Choose Your Car</h1>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section bg-light">
    <div class="container">
        <div class="row">
            <?php
            // Check if there are cars to display
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $carId = $row['id'];
                    $carName = $row['name'];
                    $price = $row['price'];
                    $photo = $row['photo'];
                    $year = $row['year_of_make'];
                    $isAvailable = $row['availability']; // Assuming 'availability' is a column in your database indicating availability
                    ?>
                    <div class="col-md-4">
                        <div class="car-wrap rounded ftco-animate">
                            <div class="img rounded d-flex align-items-end" style="background-image: url('images/<?php echo $photo; ?>');">
                            </div>

                            <div class="text">
                                <h2 class="mb-0"><a href="car-single.php?id=<?php echo $carId; ?>"><?php echo $carName; ?> - <?php echo $year; ?></a></h2>
								<?php
                                // Display availability status as text
                                echo $isAvailable ? '<span class="availability text-success">&#10003; Available</span>' : '<span class="availability text-danger">&#10007; Not Available</span>';
                                ?>
                                <div class="d-flex mb-3">
                                    <p class="price ml-auto"><?php echo $price; ?> <span>/day</span></p>
                                </div>
                                <p class="d-flex mb-0 d-block">
                                    <?php
                                    // Display "Book now" button or disabled state based on availability
                                    if ($isAvailable) {
                                        echo '<a href="#" class="btn btn-primary py-2 mr-1">Book now</a>';
                                    } else {
                                        echo '<span class="btn btn-danger py-2 mr-1" disabled>Not available</span>';
                                    }
                                    ?>
                                    <a href="car-single.php?car_id=<?php echo $carId; ?>" class="btn btn-secondary py-2 ml-1">Details</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "No cars found.";
            }
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
		<div class="container">


			<!-- make a form for search -->
			<div class="for absolute right-12 top-0	 ">
				<div class="search">
					<!-- make a form for search -->
					<form action="car.php" method="post" class="search-form">
						<div class="form-group">
							<label for="year_filter">Filter by Year:</label>
							<input type="text" name="year_filter" class="form-control" placeholder="Enter Year">
						</div>

						<div class="form-group">
							<span class="icon icon-search"></span>
							<input type="text" name="search" class="form-control " placeholder="Search...">
						</div>
						<button type="submit" class="btn btn-primary">Search</button>
					</form>
				</div>
				<?php if (isset($searchMessage)) : ?>
					<p class="text-danger"><?php echo $searchMessage; ?></p>
				<?php endif; ?>
			</div>


			<div class="row">
				<?php
				// Check if there are cars to display
				if ($result->num_rows > 0) {
					// Output data of each row
					while ($row = $result->fetch_assoc()) {
						$carId = $row['id'];
						$carName = $row['name'];
						$price = $row['price'];
						$photo = $row['photo'];
						$year = $row['year_of_make'];
				?>


						<div class="col-md-4 mt-36">
							<div class="car-wrap rounded ftco-animate">
								<div class="img rounded d-flex align-items-end" style="background-image: url('images/<?php echo $photo; ?>');">
								</div>
								<div class="text">
									<h2 class="mb-0">
										<a href="car-single.php?car_id=<?php echo $carId; ?>"><?php echo $carName; ?> - <?php echo $year; ?></a>
									</h2>
									<div class="d-flex mb-3">
										<p class="price ml-auto"><?php echo $price; ?> <span>/day</span></p>
									</div>
									<p class="d-flex mb-0 d-block">
										<a href="booking.php?car_id=<?php echo $carId; ?>" class="btn btn-primary py-2 mr-1">Book now</a>
										<a href="car-single.php?car_id=<?php echo $carId; ?>" class="btn btn-secondary py-2 ml-1">Details</a>
									</p>
								</div>
							</div>
						</div>

				<?php

					}
				} else {
					echo "No cars found.";
				}
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