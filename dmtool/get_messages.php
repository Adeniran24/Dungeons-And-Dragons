<?php
include '../connect.php';

$result = $conn->query("SELECT * FROM messages ORDER BY timestamp DESC LIMIT 20");
$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode(array_reverse($messages)); // Send messages in chronological order
?>
