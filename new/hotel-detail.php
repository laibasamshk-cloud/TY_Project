<?php 
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$hotel = isset($_GET['hotel']) ? $_GET['hotel'] : 'Hotel';
$price = isset($_GET['price']) ? (int)$_GET['price'] : 8000;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $hotel; ?> Booking</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body style="background:#fdf8f9;">

<div class="container mt-5">
  <h2 class="text-center mb-4" style="color:#c27b85;"><?php echo $hotel; ?></h2>

  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-body">
          <form method="POST" action="save-hotel-booking.php">
            <input type="hidden" name="hotel_name" value="<?php echo $hotel; ?>">
            <input type="hidden" name="price_per_night" value="<?php echo $price; ?>">

            <div class="form-group">
              <label>Guest Name</label>
              <input type="text" name="guest_name" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Contact Number</label>
              <input type="text" name="contact" class="form-control" required>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label>Check-in Date</label>
                <input type="date" name="checkin_date" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label>Check-out Date</label>
                <input type="date" name="checkout_date" class="form-control" required>
              </div>
            </div>

            <div class="form-group mt-3">
              <label>Number of Rooms</label>
              <input type="number" name="number_of_rooms" class="form-control" min="1" value="1" required>
            </div>

            <button type="submit" class="btn btn-success btn-lg btn-block">Confirm Booking</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>