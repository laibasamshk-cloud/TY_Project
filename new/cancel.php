<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_email'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to cancel a booking']);
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "la");
if ($mysqli->connect_error) {
    error_log("Database connection failed: " . $mysqli->connect_error);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$booking_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$user_email = $_SESSION['user_email'];

if ($booking_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid booking ID']);
    exit;
}

// Verify the booking exists and belongs to the user (using email as in dashboard.php)
$stmt = $mysqli->prepare("SELECT id, email, name FROM bookings WHERE id = ? AND email = ?");
if (!$stmt) {
    error_log("Prepare failed: " . $mysqli->error);
    echo json_encode(['success' => false, 'message' => 'Database query error']);
    exit;
}
$stmt->bind_param("is", $booking_id, $user_email);
if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Database query error']);
    exit;
}
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Booking not found or you are not authorized to cancel it']);
    $stmt->close();
    exit;
}
$booking = $result->fetch_assoc();
$stmt->close();

// Delete the booking (CASCADE will handle related tables)
$stmt = $mysqli->prepare("DELETE FROM bookings WHERE id = ? AND email = ?");
if (!$stmt) {
    error_log("Prepare delete failed: " . $mysqli->error);
    echo json_encode(['success' => false, 'message' => 'Database query error']);
    exit;
}
$stmt->bind_param("is", $booking_id, $user_email);
if ($stmt->execute()) {
    // Send cancellation email (optional)
    $email_result = send_cancellation_email($booking['email'], $booking['name'], $booking_id);
    $message = 'Booking cancelled successfully!';
    if ($email_result !== true) {
        $message .= ' (Email notification failed: ' . $email_result . ')';
        error_log("Cancellation email failed: " . $email_result);
    }
    echo json_encode(['success' => true, 'message' => $message]);
} else {
    error_log("Execute delete failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Error cancelling booking: ' . $stmt->error]);
}
$stmt->close();
$mysqli->close();

function send_cancellation_email($email, $name, $booking_id) {
    // Optional: Skip if PHPMailer issues
    try {
        require 'phpmailer/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'petals.promises1814@gmail.com';
        $mail->Password = 'qdahwxnljjalnsom';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('petals.promises1814@gmail.com', 'Petals & Promises');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Booking Cancellation Confirmation';
        $mail->Body = "Dear $name,<br><br>Your booking (ID: $booking_id) has been cancelled successfully.<br><br>Thank you for using Petals & Promises!";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
?>