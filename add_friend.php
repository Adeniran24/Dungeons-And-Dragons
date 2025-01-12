<?php
session_start();
require_once 'connect.php';

if (isset($_POST['friend_id'])) {
    $user_id = $_SESSION['user_id'];
    $friend_id = $_POST['friend_id'];

    // Prevent sending a friend request to oneself
    if ($user_id == $friend_id) {
        echo "You can't send a friend request to yourself!";
        exit();
    }

    // Insert a new friend request into the database
    $stmt = $conn->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ii", $user_id, $friend_id);

    if ($stmt->execute()) {
        echo "Friend request sent!";
    } else {
        echo "Error sending friend request: " . $conn->error;
    }

    $stmt->close();
}
?>
