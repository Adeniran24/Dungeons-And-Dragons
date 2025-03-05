<?php
include '../../connect.php';
include '../../session_token.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $room_id = intval($_POST['room_id']);
    $message = trim($_POST['message']);

    // Check if the user is invited
    $invitation_check = $conn->query("SELECT * FROM invitations WHERE room_id = $room_id AND user_id = $user_id");

    if ($invitation_check->num_rows > 0 && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO room_messages (room_id, user_id, username, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $room_id, $user_id, $username, $message);
        $stmt->execute();
        $stmt->close();
    }
}
?>
