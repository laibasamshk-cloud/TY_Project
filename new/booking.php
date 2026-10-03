<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<span style='color:red'>Please login to book.</span>";
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "la");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$user_id = $_SESSION['user_id'];
$form_name = $_POST['name'];
$contact = $_POST['contact'];
$hall_name = $_POST['hall_name'];
$booking_date = $_POST['booking_date'];
$time_slot = $_POST['time_slot'];
$plates = $_POST['plates'];
$catering_item = isset($_POST['catering_item']) ? $_POST['catering_item'] : '';
$decoration = $_POST['decoration'];
$total_amount = $_POST['total_amount'];

// Ensure booking date is greater than today
$currentDate = date("Y-m-d");
if ($booking_date <= $currentDate) {
    echo "<span style='color:red'>Booking date must be greater than today.</span>";
    exit;
}

// Fetch logged-in user name and email from users table
$stmt_user = $mysqli->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$stmt_user->bind_result($logged_in_name, $logged_in_email);
$stmt_user->fetch();
$stmt_user->close();

// Calculate discount
$discount = 0;
$eligibleHalls = array("Oasis Lawn", "Celestial Luxe");
if (in_array($hall_name, $eligibleHalls)) {
    if ($decoration === "Premium") {
        $discount = 0.05; // 5%
    } elseif ($decoration === "Luxury") {
        $discount = 0.10; // 10%
    }
}
if ($discount > 0) {
    $discountAmount = $total_amount * $discount;
    $finalAmount = $total_amount - $discountAmount;
} else {
    $finalAmount = $total_amount;
}

// Check for existing booking in halls table
$stmt_select = $mysqli->prepare("SELECT * FROM halls WHERE hall_name = ? AND booking_date = ? AND time_slot = ?");
if ($stmt_select === false) {
    die("Prepare failed: " . $mysqli->error);
}
$stmt_select->bind_param("sss", $hall_name, $booking_date, $time_slot);
$stmt_select->execute();
$result = $stmt_select->get_result();
if ($result->num_rows > 0) {
    echo "<span style='color:red'>Sorry! This hall is already booked for this slot.</span>";
    $stmt_select->close();
    $mysqli->close();
    exit;
}
$stmt_select->close();

// Start transaction to ensure data consistency
$mysqli->begin_transaction();

try {
    // Insert into halls table
    $stmt_hall = $mysqli->prepare("INSERT INTO halls (hall_name, booking_date, time_slot) VALUES (?, ?, ?)");
    if ($stmt_hall === false) {
        throw new Exception("Prepare failed for halls: " . $mysqli->error);
    }
    $stmt_hall->bind_param("sss", $hall_name, $booking_date, $time_slot);
    $stmt_hall->execute();
    $hall_id = $mysqli->insert_id;
    $stmt_hall->close();

    // Insert into booking_caterings table
    $stmt_catering = $mysqli->prepare("INSERT INTO booking_caterings (plates, catering_item) VALUES (?, ?)");
    if ($stmt_catering === false) {
        throw new Exception("Prepare failed for booking_caterings: " . $mysqli->error);
    }
    $stmt_catering->bind_param("is", $plates, $catering_item);
    $stmt_catering->execute();
    $booking_catering_id = $mysqli->insert_id;
    $stmt_catering->close();

    // Insert into decorations table
    $stmt_decoration = $mysqli->prepare("INSERT INTO decorations (decoration) VALUES (?)");
    if ($stmt_decoration === false) {
        throw new Exception("Prepare failed for decorations: " . $mysqli->error);
    }
    $stmt_decoration->bind_param("s", $decoration);
    $stmt_decoration->execute();
    $decoration_id = $mysqli->insert_id;
    $stmt_decoration->close();

    // Insert into bookings table
    $stmt_insert = $mysqli->prepare("INSERT INTO bookings (user_id, name, form_name, email, contact, hall_id, booking_catering_id, decoration_id, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt_insert === false) {
        throw new Exception("Prepare failed for bookings: " . $mysqli->error);
    }
    $stmt_insert->bind_param("issssiiii", $user_id, $logged_in_name, $form_name, $logged_in_email, $contact, $hall_id, $booking_catering_id, $decoration_id, $finalAmount);
    $stmt_insert->execute();
    $stmt_insert->close();

    // Commit transaction
    $mysqli->commit();

    // Output success message
    if ($discount > 0) {
        echo "<span style='color:green'>Booking Confirmed! 🎉 You received a " . ($discount * 100) . "% discount. Final Amount: ₹" . $finalAmount . "</span>";
    } else {
        echo "<span style='color:green'>Booking Confirmed! Total Amount: ₹" . $finalAmount . "</span>";
    }

    // Optional redirect to Guest Services
echo '<br><br>
      <a href="guest-services.php" class="btn btn-info">Continue to Guest Services →</a>
      <a href="index.php" class="btn btn-secondary ml-3">Back to Dashboard</a>';

      
} catch (Exception $e) {
    // Rollback transaction on error
    $mysqli->rollback();
    echo "Error booking hall: " . $e->getMessage();
}

$mysqli->close();
?>