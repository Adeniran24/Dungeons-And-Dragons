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


if (!isset($_GET['room_id'])) {
    die("Room not selected.");
}

$room_id = intval($_GET['room_id']);
$room_query = $conn->query("SELECT name FROM chat_rooms WHERE id = $room_id");

if ($room_query->num_rows == 0) {
    die("Chat room not found.");
}

$room = $room_query->fetch_assoc();

// Check if the user is invited
$invitation_check = $conn->query("SELECT * FROM invitations WHERE room_id = $room_id AND user_id = $user_id");

if ($invitation_check->num_rows == 0) {
    die("You are not invited to this chat room.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chat Room - <?= htmlspecialchars($room['name']) ?></title>
    <script src="room_chat.js" defer></script>
</head>
<body>
    <h1>Welcome to <?= htmlspecialchars($room['name']) ?></h1>
    <div id="chat-box"></div>
    <input type="hidden" id="room_id" value="<?= $room_id ?>">
    <input type="text" id="message-input">
    <button id="send-btn">Send</button>
    <button onclick="window.location.href='invite.php?room_id=<?= $room_id ?>'">Invite Friends</button>

</body>
</html>
