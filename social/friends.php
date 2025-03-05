<?php
include '../connect.php';
include '../session_token.php';

$result = $conn->query("SELECT u.id, u.username FROM users u 
                        JOIN friends f ON ((f.user_id = $user_id AND f.friend_id = u.id) 
                        OR (f.friend_id = $user_id AND f.user_id = u.id)) 
                        WHERE f.status = 'Friend'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Friends List</title>
</head>
<body>
    <h1>Select a Friend to Chat</h1>
    <ul>
        <?php while ($friend = $result->fetch_assoc()): ?>
            <li><a href="private_chat.php?friend_id=<?= $friend['id'] ?>"><?= htmlspecialchars($friend['username']) ?></a></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
