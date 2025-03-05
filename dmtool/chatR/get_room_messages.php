<?php
include 'connect.php';

$room_id = intval($_GET['room_id']);
$result = $conn->query("SELECT username, message FROM room_messages WHERE room_id = $room_id ORDER BY timestamp ASC");

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
