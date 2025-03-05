<?php
include '../../connect.php';
include '../../session_token.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $room_id = intval($_POST['room_id']);
    $invitee_id = intval($_POST['invitee_id']);

    // Ensure only the room owner can invite users
    $check_owner = $conn->query("SELECT owner_id FROM chat_rooms WHERE id = $room_id AND owner_id = $user_id");

    if ($check_owner->num_rows > 0) {
        $stmt = $conn->prepare("INSERT INTO invitations (room_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $room_id, $invitee_id);
        if ($stmt->execute()) {
            echo "User invited successfully!";
        } else {
            echo "Error inviting user.";
        }
        $stmt->close();
    } else {
        echo "You are not allowed to invite users to this room.";
    }
}
?>
