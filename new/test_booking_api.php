<?php
// Simple test file to check the API response
$url = '../admin/get_public_bookings.php';
$data = array('action' => 'get_public_bookings');

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    die('Error fetching data');
}

echo "<h2>API Response:</h2>";
echo "<pre>";
print_r(json_decode($result, true));
echo "</pre>";

// Also try a direct database query to see if there are bookings
$mysqli = new mysqli("localhost", "root", "", "la");
if (!$mysqli->connect_error) {
    $result = $mysqli->query("
        SELECT b.id, h.hall_name, h.booking_date, h.time_slot 
        FROM bookings b 
        JOIN halls h ON b.hall_id = h.id 
        ORDER BY h.booking_date
    ");
    
    echo "<h2>Direct Database Query:</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Hall</th><th>Date</th><th>Time Slot</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['hall_name'] . "</td>";
        echo "<td>" . $row['booking_date'] . "</td>";
        echo "<td>" . $row['time_slot'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p>Total bookings: " . $result->num_rows . "</p>";
    $mysqli->close();
}
?>