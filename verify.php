<?php
session_start();
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "root", "", "la");
if ($mysqli->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Database connection failed'));
    exit;
}

$email = isset($_POST['email']) ? $_POST['email'] : '';
$otp = isset($_POST['otp']) ? $_POST['otp'] : '';

if (empty($email) || empty($otp)) {
    echo json_encode(array('success' => false, 'message' => 'Email and OTP are required'));
    exit;
}

$stmt = $mysqli->prepare("SELECT id, otp, otp_expiration FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_time = time();
    $expiration_time = strtotime($row['otp_expiration']);

    if ($row['otp'] == $otp && $current_time < $expiration_time) {
        // Clear OTP and expiration, set verified
        $update = $mysqli->prepare("UPDATE users SET otp = NULL, otp_expiration = NULL, is_verified = 1 WHERE id = ?");
        $update->bind_param("i", $row['id']);
        $update->execute();

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_email'] = $email;

        echo json_encode(array('success' => true, 'message' => 'Verified successfully!'));
    } else if ($current_time >= $expiration_time) {
        echo json_encode(array('success' => false, 'message' => 'OTP has expired'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Invalid OTP'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'User not found'));
}

$stmt->close();
$mysqli->close();
?>