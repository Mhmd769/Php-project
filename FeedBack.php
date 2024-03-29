<?php
 session_start();
require_once 'dbcon.php';

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
     header("Location: Login.php");
    exit();
}

$feedbackSubmitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $name = $_POST['name'];
    $rating = $_POST['rating'];
    $message = $_POST['message'];
    $photo = $_POST['photo'];
     $rating = max(1, min($rating, 5));

    $userId = $_SESSION['user_id'];
     $sql = "INSERT INTO feedback (user_id, details, name, rate, photo) VALUES (?, ?, ?, ?, ?)";

    $Feedback_stmt = $conn->prepare($sql);
    $Feedback_stmt->bind_param("issss", $userId, $message, $name, $rating, $photo);

    if ($Feedback_stmt->execute()) {
        $feedbackSubmitted = true;
        echo '<script>alert("feedback sent successfully");</script>';
      } else {
        echo "Error: " . $Feedback_stmt->error;
    }

    header("Location: index.php");
}

  $sql = "SELECT * FROM  feedback";
  $result = $conn ->query($sql);
  if($result->num_rows === 0){
    echo "No feedback available at the moment";
  }
//   else{
    
//   }
$conn->close();


// Logout Process
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: Login.php");
    exit();
}
?>

<head>
  <title>CarRent_FeedBack</title>
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
  <?php
  if ($feedbackSubmitted) : ?>
    <script>
      window.onload = function() {
        alert("Thank you for your feedback");
      };
    </script>
  <?php endif; ?>



  <style>
    .feedback-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  color:black;
}

.feedback-table th, .feedback-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.user-img {
  width: 50px;
  height: 50px;
  background-size: cover;
  background-position: center;
  border-radius: 50%;
}

.rating {
  font-weight: bold;
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

  <section class="ftco-section contact-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 block-9 mb-md-5">
          <form action="#" class="bg-light p-5 contact-form" method="post">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Your Name" name="name">
            </div>
            <div class="form-group">
              <label for="photo">Photo Of You</label>
              <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
            </div>
            <div class="form-group">
              <select class="form-control" name="rating">
                <option value="" disabled selected>Select Rating</option>
                <option value="5">5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
              </select>
            </div>
            <div class="form-group">
              <textarea name="message" id="" cols="30" rows="7" class="form-control" placeholder="Message"></textarea>
            </div>
            <div class="form-group">
              <input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
<section class="ftco-section testimony-section bg-light">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-md-7 text-center heading-section ftco-animate">
          <span class="position">Rating</span>
          <h2 class="mb-3">Happy Clients</h2>
        </div>
      </div>
      <div class="row ftco-animate">
  <div class="col-md-12">
    <table class="feedback-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Message</th>
          <th>Rating</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if ($result->num_rows > 0) {
          // Output data for each row
          while ($row = $result->fetch_assoc()) { 
            $name = $row['name'];
            $rating = $row['rate'];
            $message = $row['details'];
        ?>
          <tr>
            <td >
            <p class="name"><?php echo $name; ?></p>

            </td>
            <td class="text">
              <p class="mb-4"><?php echo $message; ?></p>
            </td>
            <td class="rating">
              <span><?php echo $rating; ?> stars</span>
            </td>
          </tr>
        <?php 
          }
        } else {
          echo '<tr><td colspan="3">No feedback available at the moment</td></tr>';
        }
        ?>
      </tbody>
    </table>
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