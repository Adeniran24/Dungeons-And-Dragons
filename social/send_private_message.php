<?php
include '../connect.php';
include '../session_token.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $friend_id = intval($_POST['friend_id']);
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO private_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $friend_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}
?>
