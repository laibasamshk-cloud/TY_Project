<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Guest Services - Petals & Promises</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .service-card { transition: 0.3s; }
    .service-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(194,123,133,0.3); }
  </style>
</head>
<body style="background:#fdf8f9;">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">Petals & Promises</a>
  <div class="ml-auto">
    <a href="index.php" class="btn btn-outline-light">← Back to Booking</a>
  </div>
</nav>

<div class="container mt-5">
  <h2 class="text-center mb-5" style="color:#c27b85;">🛎️ Guest Services Booking</h2>

  <div class="row">

    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card service-card h-100 text-center">
        <div class="card-body">
          <i class="fas fa-hotel fa-3x mb-3 text-primary"></i>
          <h5>Hotel Booking</h5>
          <a href="book-hotel.php" class="btn btn-primary btn-block">Book Hotels</a>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card service-card h-100 text-center">
        <div class="card-body">
          <i class="fas fa-sparkles fa-3x mb-3 text-danger"></i>
          <h5>Makeup Artist</h5>
          <a href="book-makeup.php" class="btn btn-primary btn-block">Book Makeup</a>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card service-card h-100 text-center">
        <div class="card-body">
          <i class="fas fa-bus fa-3x mb-3 text-success"></i>
          <h5>Bus Booking</h5>
          <a href="book-bus.php" class="btn btn-primary btn-block">Book Bus</a>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card service-card h-100 text-center">
        <div class="card-body">
          <i class="fas fa-taxi fa-3x mb-3 text-warning"></i>
          <h5>Cab Booking</h5>
          <a href="book-cab.php" class="btn btn-primary btn-block">Book Cab</a>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>