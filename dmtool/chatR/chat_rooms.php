<?php
include '../../connect.php';
include '../../session_token.php';

$result = $conn->query("SELECT cr.id, cr.name FROM chat_rooms cr
                        JOIN invitations i ON cr.id = i.room_id
                        WHERE i.user_id = $user_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chat Rooms</title>
</head>
<body>
    <h1>Your Chat Rooms</h1>
    <ul>
        <?php while ($room = $result->fetch_assoc()): ?>
            <li><a href="room_chat.php?room_id=<?= $room['id'] ?>"><?= htmlspecialchars($room['name']) ?></a></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
