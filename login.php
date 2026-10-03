<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "la";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(array("success" => false, "message" => "Database connection failed: " . $conn->connect_error));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Hash password same as signup (md5 for now)
    $hashed_password = md5($password);

    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($row['password'] === $hashed_password) {
            // Generate and send OTP for 2FA
            $otp = rand(100000, 999999);
            $expiration = date('Y-m-d H:i:s', time() + 300); // 5 minutes

            $update_otp = $conn->prepare("UPDATE users SET otp = ?, otp_expiration = ? WHERE id = ?");
            $update_otp->bind_param("ssi", $otp, $expiration, $row['id']);
            $update_otp->execute();

            $otp_result = send_otp($email, $otp);
            if ($otp_result === true) {
                echo json_encode(array("success" => true, "message" => "OTP sent to your email. Please verify."));
            } else {
                echo json_encode(array("success" => false, "message" => "Failed to send OTP: " . $otp_result));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Invalid password"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "No user found with this email"));
    }

    $stmt->close();
}
$conn->close();

function send_otp($email, $otp) {
    require 'phpmailer/autoload.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'petals.promises1814@gmail.com';
        $mail->Password = 'qdahwxnljjalnsom'; // Verify this is correct
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('petals.promises1814@gmail.com', 'Petals & Promises');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'OTP Verification';
        $mail->Body = "Your OTP is <b>$otp</b>. It expires in 5 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $e->getMessage());
        return $e->getMessage(); // Return the error message for JSON
    }
}
?>