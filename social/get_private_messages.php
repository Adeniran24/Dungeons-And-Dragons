<?php
include '../connect.php';
include '../session_token.php';

$friend_id = intval($_GET['friend_id']);
$result = $conn->query("SELECT sender_id, message FROM private_messages 
                        WHERE (sender_id = $user_id AND receiver_id = $friend_id) 
                        OR (sender_id = $friend_id AND receiver_id = $user_id) 
                        ORDER BY timestamp ASC");

$messages = [];
while ($row = $result->fetch_assoc()) {
    $row['sender'] = ($row['sender_id'] == $user_id) ? 'You' : 'Friend';
    $messages[] = $row;
}

echo json_encode($messages);
?>
