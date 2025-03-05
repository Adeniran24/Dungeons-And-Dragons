<?php
include '../connect.php';
include '../session_token.php';

if (!isset($_GET['friend_id'])) {
    die("Friend not selected.");
}

$friend_id = intval($_GET['friend_id']);
$friend_query = $conn->query("SELECT username FROM users WHERE id = $friend_id");

if ($friend_query->num_rows == 0) {
    die("User not found.");
}

$friend = $friend_query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chat with <?= htmlspecialchars($friend['username']) ?></title>
    <script src="private_chat.js" defer></script>
</head>
<body>
    <h1>Chat with <?= htmlspecialchars($friend['username']) ?></h1>
    <div id="chat-box"></div>
    <input type="hidden" id="friend_id" value="<?= $friend_id ?>">
    <input type="text" id="message-input">
    <button id="send-btn">Send</button>
</body>
</html>
