<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hotel Booking - Petals & Promises</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .hotel-card { transition: 0.3s; cursor: pointer; }
    .hotel-card:hover { transform: scale(1.05); box-shadow: 0 15px 30px rgba(194,123,133,0.3); }
  </style>
</head>
<body style="background:#fdf8f9;">

<div class="container mt-5">
  <h2 class="text-center mb-5" style="color:#c27b85;">🏨 Hotel Booking for Guests</h2>

  <div class="row">

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card hotel-card h-100" onclick="window.location.href='hotel-detail.php?hotel=Taj%20Pune&price=12500'">
        <img src="https://picsum.photos/id/1015/600/280" class="card-img-top">
        <div class="card-body">
          <h5>Taj Pune</h5>
          <p>5-Star • 2.5 km from venue</p>
          <p><strong>₹12,500 / night</strong></p>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card hotel-card h-100" onclick="window.location.href='hotel-detail.php?hotel=Hyatt%20Regency&price=8500'">
        <img src="https://picsum.photos/id/133/600/280" class="card-img-top">
        <div class="card-body">
          <h5>Hyatt Regency Pune</h5>
          <p>5-Star • 3 km from venue</p>
          <p><strong>₹8,500 / night</strong></p>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card hotel-card h-100" onclick="window.location.href='hotel-detail.php?hotel=Lemon%20Tree&price=5500'">
        <img src="https://picsum.photos/id/201/600/280" class="card-img-top">
        <div class="card-body">
          <h5>Lemon Tree Premier</h5>
          <p>4-Star • 1.8 km from venue</p>
          <p><strong>₹5,500 / night</strong></p>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>