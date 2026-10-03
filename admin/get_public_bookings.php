<?php
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = new mysqli("localhost", "root", "", "la");

if ($mysqli->connect_error) {
    echo json_encode(array('success' => false, 'error' => 'Database connection failed: ' . $mysqli->connect_error));
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'get_public_bookings') {
    
    // First, check if there are any bookings at all
    $check = $mysqli->query("SELECT COUNT(*) as total FROM bookings");
    $total_bookings = $check->fetch_assoc()['total'];
    
    // Get all bookings (not just future ones for testing)
    $stmt = $mysqli->prepare("
        SELECT h.hall_name, h.booking_date, h.time_slot 
        FROM bookings b 
        JOIN halls h ON b.hall_id = h.id 
        ORDER BY h.booking_date
    ");
    
    if ($stmt === false) {
        echo json_encode(array('success' => false, 'error' => 'Query preparation failed: ' . $mysqli->error));
        exit;
    }
    
    if (!$stmt->execute()) {
        echo json_encode(array('success' => false, 'error' => 'Query execution failed: ' . $stmt->error));
        $stmt->close();
        exit;
    }
    
    $result = $stmt->get_result();
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
    
    $events = array();
    foreach ($bookings as $booking) {
        $title = $booking['hall_name'] . ' (' . ($booking['time_slot'] === 'day' ? 'Day' : 'Eve') . ')';
        $events[] = array(
            'title' => $title,
            'start' => $booking['booking_date'],
            'allDay' => true,
            'color' => '#dc3545', // Simplified color property
            'extendedProps' => array(
                'hall_name' => $booking['hall_name'],
                'time_slot' => $booking['time_slot']
            )
        );
    }
    
    // If no bookings, add a test event
    if (empty($events)) {
        $events[] = array(
            'title' => 'No Bookings Yet',
            'start' => date('Y-m-d'),
            'allDay' => true,
            'color' => '#28a745',
            'extendedProps' => array(
                'hall_name' => 'Test',
                'time_slot' => 'Test'
            )
        );
    }
    
    echo json_encode(array(
        'success' => true, 
        'events' => $events,
        'debug' => array(
            'total_bookings' => $total_bookings,
            'bookings_found' => count($bookings)
        )
    ));
    $stmt->close();
} else {
    echo json_encode(array('success' => false, 'error' => 'Invalid action'));
}

$mysqli->close();
?>