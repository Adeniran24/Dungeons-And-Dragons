<?php
include '../../connect.php';

session_start(); // Start the session

// Check if the user is logged in by verifying session variables
if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    // If the user is not logged in, redirect to login page
    header("Location: ../main/login.php");
    exit();
} else {
    // The user is logged in, you can use the session variables
    $is_logged_in = true;
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Optional: verify token if using cookie for added security
    if (isset($_COOKIE['auth_token']) && $_COOKIE['auth_token'] !== $_SESSION['token']) {
        // Invalidate session if the token does not match
        session_unset();
        session_destroy();
        header("Location: ../main/login.php");
        exit();
    }
}

// Now you can use $user_id, $username, and other session variables


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
