<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "la");

if ($mysqli->connect_error) {
    die("Connection failed");
}

$user_id = $_SESSION['user_id'];
$hotel_name = $_POST['hotel_name'];
$guest_name = $_POST['guest_name'];
$contact = $_POST['contact'];
$checkin = $_POST['checkin_date'];
$checkout = $_POST['checkout_date'];
$rooms = (int)$_POST['number_of_rooms'];
$price_per_night = (int)$_POST['price_per_night'];

$nights = (strtotime($checkout) - strtotime($checkin)) / (60*60*24);
$total = $price_per_night * $rooms * $nights;

$discount = 0;
if ($rooms >= 22) $discount = 10;
else if ($rooms >= 13) $discount = 5;

$final_amount = $total * (1 - $discount/100);

$stmt = $mysqli->prepare("INSERT INTO hotel_bookings 
    (user_id, hotel_name, guest_name, contact, checkin_date, checkout_date, 
     number_of_rooms, total_amount, discount_percent, final_amount) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("isssssiddi", 
    $user_id, $hotel_name, $guest_name, $contact, $checkin, $checkout, 
    $rooms, $total, $discount, $final_amount);

if ($stmt->execute()) {
    echo "<h2 style='color:green; text-align:center; margin-top:50px;'>✅ Booking Confirmed Successfully!</h2>";
    echo "<p style='text-align:center;'>Our team will contact you shortly.</p>";
    echo "<p style='text-align:center;'><a href='guest-services.php'>Back to Services</a></p>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>