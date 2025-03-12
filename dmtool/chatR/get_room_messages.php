<?php
include '../../connect.php';

$room_id = intval($_GET['room_id']);
$last_timestamp = isset($_GET['last_timestamp']) ? intval($_GET['last_timestamp']) : 0;

$sql = "SELECT username, message, UNIX_TIMESTAMP(timestamp) as timestamp 
        FROM room_messages 
        WHERE room_id = $room_id AND UNIX_TIMESTAMP(timestamp) > $last_timestamp 
        ORDER BY timestamp ASC";

$result = $conn->query($sql);

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// Debugging: Log output
if (empty($messages)) {
    error_log("No new messages found for room_id: $room_id after timestamp: $last_timestamp");
} else {
    error_log("Fetched messages for room_id: $room_id, last timestamp: $last_timestamp");
}

echo json_encode($messages);
?>
