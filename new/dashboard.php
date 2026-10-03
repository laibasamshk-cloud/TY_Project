<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit;
}

$user_email = $_SESSION['user_email'];
$mysqli = new mysqli("localhost", "root", "", "la");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch bookings with joined tables
$stmt = $mysqli->prepare("
    SELECT b.id, b.total_amount, h.hall_name, h.booking_date, h.time_slot, c.plates, c.catering_item, d.decoration
    FROM bookings b
    JOIN halls h ON b.hall_id = h.id
    JOIN booking_caterings c ON b.booking_catering_id = c.id
    JOIN decorations d ON b.decoration_id = d.id
    WHERE b.email = ?
    ORDER BY h.booking_date DESC, h.time_slot ASC
");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<style>
body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
.container { margin-top: 80px; }
h2 { margin-bottom: 30px; text-align: center; }
.table { background-color: #fff; }
</style>
</head>
<body>
<div class="container">
    <h2>My Bookings</h2>
    <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Hall</th>
                <th>Date</th>
                <th>Time Slot</th>
                <th>Decoration</th>
                <th>Plates</th>
                <th>Catering Items</th>
                <th>Total Amount (₹)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['hall_name']); ?></td>
                <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                <td><?php echo htmlspecialchars($row['time_slot']); ?></td>
                <td><?php echo htmlspecialchars($row['decoration']); ?></td>
                <td><?php echo htmlspecialchars($row['plates']); ?></td>
                <td><?php echo htmlspecialchars($row['catering_item'] ?: 'None'); ?></td>
                <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                <td>
                    <button class="btn btn-danger btn-sm cancel-btn" data-id="<?php echo $row['id']; ?>">Cancel</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="text-center">You have not made any bookings yet.</p>
    <?php endif; ?>
    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-primary">Book a New Hall</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $('.cancel-btn').click(function(){
        if(confirm("Are you sure you want to cancel this booking?")) {
            let bookingId = $(this).data('id');
            let row = $(this).closest('tr');

            $.ajax({
                url: 'cancel.php',
                type: 'POST',
                data: {id: bookingId},
                success: function(response){
                    alert(response);
                    row.remove();
                },
                error: function(){
                    alert("Error cancelling booking. Try again.");
                }
            });
        }
    });
});
</script>
</body>
</html>
<?php
$stmt->close();
$mysqli->close();
?>