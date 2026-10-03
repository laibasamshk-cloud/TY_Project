<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "root", "", "la");
if ($mysqli->connect_error) {
    error_log("Database connection failed: " . $mysqli->connect_error);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($name) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Check duplicate email
$stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
if (!$stmt) {
    error_log("Prepare failed: " . $mysqli->error);
    echo json_encode(['success' => false, 'message' => 'Database query error']);
    exit;
}
$stmt->bind_param("s", $email);
if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Database query error']);
    exit;
}
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already exists']);
    $stmt->close();
    exit;
}
$stmt->close();

// Hash password with MD5 (consider upgrading to password_hash for security)
$hashed_password = md5($password);

// Insert new user with is_verified=0
$stmt = $mysqli->prepare("INSERT INTO users (name, email, password, is_verified) VALUES (?, ?, ?, 0)");
if (!$stmt) {
    error_log("Prepare insert failed: " . $mysqli->error);
    echo json_encode(['success' => false, 'message' => 'Database query error']);
    exit;
}
$stmt->bind_param("sss", $name, $email, $hashed_password);
if ($stmt->execute()) {
    $user_id = $mysqli->insert_id;
    $otp = rand(100000, 999999);
    $expiration = date('Y-m-d H:i:s', time() + 300); // 5 minutes

    // Update OTP and expiration
    $update_otp = $mysqli->prepare("UPDATE users SET otp = ?, otp_expiration = ? WHERE id = ?");
    if (!$update_otp) {
        error_log("Prepare OTP update failed: " . $mysqli->error);
        echo json_encode(['success' => false, 'message' => 'Failed to prepare OTP update']);
        exit;
    }
    $update_otp->bind_param("ssi", $otp, $expiration, $user_id);
    if ($update_otp->execute()) {
        $otp_result = send_otp($email, $otp);
        if ($otp_result === true) {
            // Store user_id for OTP verification
            $_SESSION['signup_user_id'] = $user_id;
            echo json_encode(['success' => true, 'message' => 'OTP sent to your email. Please verify.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send OTP: ' . $otp_result]);
        }
    } else {
        error_log("Execute OTP update failed: " . $update_otp->error);
        echo json_encode(['success' => false, 'message' => 'Error updating OTP']);
    }
    $update_otp->close();
} else {
    error_log("Execute insert failed: " . $mysqli->error);
    echo json_encode(['success' => false, 'message' => 'Error creating account: ' . $mysqli->error]);
}
$stmt->close();
$mysqli->close();

function send_otp($email, $otp) {
    require 'phpmailer/autoload.php'; // Assumes files in phpmailer/ directory
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'petals.promises1814@gmail.com';
        $mail->Password = 'qdahwxnljjalnsom'; // Verify this is a valid App Password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('petals.promises1814@gmail.com', 'Petals & Promises');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'OTP Verification';
        $mail->Body = "Your OTP is <b>$otp</b>. It expires in 5 minutes.";

        $mail->send();
        return true;
    } catch (PHPMailer\PHPMailer\Exception $e) {
        error_log("PHPMailer Error: " . $e->getMessage());
        return $e->getMessage();
    }
}
?>