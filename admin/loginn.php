<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db = "la";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(array("success" => false, "message" => "Database connection failed"));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode(array("success" => false, "message" => "Email and password are required"));
        exit;
    }

    // Hash password (using md5 as per your current setup)
    $hashed_password = md5($password);

    $stmt = $conn->prepare("SELECT id, email, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['password'] === $hashed_password) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            echo json_encode(array("success" => true, "message" => "Login successful"));
        } else {
            echo json_encode(array("success" => false, "message" => "Invalid password"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "No admin found with this email"));
    }

    $stmt->close();
    $conn->close();
    exit;
} else {
    // Handle GET request by displaying the login form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - Petals & Promises</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <style>
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #9b8e91ff;
            }
            .page-wrapper {
                flex: 1;
            }
            header {
                position: fixed;
                top: 0;
                z-index: 999;
                padding: 0 1%;
                background-color: #000000;
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                height: 80px;
            }
            .logo {
                display: flex;
                align-items: center;
                gap: 15px;
            }
            .logo-img {
                height: 70px;
                width: auto;
            }
            header h3 {
                font-size: 30px;
                font-family: "Monsieur La Doulaise", cursive;
                font-style: italic;
                color: rgb(194, 123, 133);
                margin: 0;
            }
            nav ul {
                list-style: none;
                display: flex;
                gap: 3px;
                margin: 0.5rem;
                margin-top: 1.7rem;
                padding: 2rem;
            }
            nav a {
                font-weight: bold;
                color: #fff;
                text-decoration: none;
                font-size: 15px;
                display: flex;
                align-items: center;
                gap: 5px;
                margin-right: 1rem;
            }
            nav ul li a i {
                color: rgb(194, 123, 133);
                font-size: 23px;
            }
            .form-container {
                flex: 1;
                margin: 19rem auto;
                background: #fff;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 0 8px rgba(0,0,0,0.1);
                max-width: 500px;
            }
            footer {
            margin-top: 12vh;
            background-color: #000;
            color: white;
            padding: 10px 10px 10px;
            text-align: center;
            font-family: 'Georgia', serif;
        }
        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .footer-logo {
            width: 60px;
            height: auto;
            margin-bottom: 5px;
        }
        .footer-names {
            font-size: 16px;
            font-weight: bold;
            color: #c27b85;
        }
        .footer-locations {
            font-size: 13px;
            color: #ddd;
        }
        .footer-copy {
            font-size: 12px;
            color: #aaa;
            margin-top: 1px;
        }
        </style>
    </head>
    <body>
        <div class="page-wrapper">
            <header>
                <div class="logo">
                    <img src="../images/lai.png" alt="Logo" class="logo-img">
                    <h3><i class="fas fa-heart"></i> Petals & Promises</h3>
                </div>
                <nav>
                    <ul>
                        <li><a href="../demo.html"><i class="fas fa-home"></i>Home</a></li>
                        <li><a href="../about.html"><i class="fas fa-heart"></i>About Us</a></li>
                        <li><a href="../services.html"><i class="fas fa-concierge-bell"></i>Services</a></li>
                        <li><a href="../about.html#blogs"><i class="fas fa-book"></i>Blog</a></li>
                        <li><a href="../index.php"><i class="fas fa-user"></i>Booking</a></li>
                    </ul>
                </nav>
            </header>
            <div class="form-container">
                <h2>Admin Login</h2>
                <form id="loginForm" action="loginn.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <p id="loginMessage" class="mt-3"></p>
            </div>
            <footer>
                <div class="footer-content">
                    <img src="../images/lai.png" alt="LP Logo" class="footer-logo">
                    <div class="footer-names"><i class="fas fa-heart"></i> A Touch of Timeless Charm by Laiba & Punita <i class="fas fa-heart"></i></div>
                    <div class="footer-locations">Pune | Mumbai | Nashik | Ahmednagar</div>
                    <div class="footer-copy">&copy; 2025 LP Petals & Promises. All rights reserved.</div>
                </div>
            </footer>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function() {
                $('#loginForm').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: 'loginn.php',
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#loginMessage').html('<span style="color:green;">' + response.message + '</span>');
                                setTimeout(function() {
                                    window.location.href = 'admin_panel.php';
                                }, 1000);
                            } else {
                                $('#loginMessage').html('<span style="color:red;">' + response.message + '</span>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Login Error:', xhr.responseText);
                            $('#loginMessage').html('<span style="color:red;">An error occurred during login.</span>');
                        }
                    });
                });
            });
        </script>
    </body>
    </html>
    <?php
}
$conn->close();
?>