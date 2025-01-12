<?php
session_start();
require_once 'connect.php';

$user_id = $_SESSION['user_id'];
$friend_id = $_POST['friend_id'];
$action = $_POST['action']; // Can be 'accept' or 'deny'

// Update the status of the friend request
if ($action === 'accept') {
    // Accept the friend request (update both user and friend status)
    $stmt = $conn->prepare("UPDATE friends SET status = 'accepted' WHERE user_id = ? AND friend_id = ?");
    $stmt->bind_param("ii", $friend_id, $user_id);
    $stmt->execute();

    // Add a reciprocal entry for the other user (so both users can see each other as friends)
    $stmt = $conn->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'accepted')");
    $stmt->bind_param("ii", $user_id, $friend_id);
    $stmt->execute();

    echo "Friend request accepted!";
} else if ($action === 'deny') {
    // Deny the friend request (delete the request record)
    $stmt = $conn->prepare("DELETE FROM friends WHERE user_id = ? AND friend_id = ?");
    $stmt->bind_param("ii", $friend_id, $user_id);
    $stmt->execute();

    echo "Friend request denied.";
}

$stmt->close();
?>
