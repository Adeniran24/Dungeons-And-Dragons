<?php
session_start();
require_once '../connect.php';

if (isset($_POST['friend_id'])) {
    $user_id = $_SESSION['user_id'];
    $friend_id = $_POST['friend_id'];

    // Remove friendship from the `friends` table
    $delete_friends = $conn->prepare("
        DELETE FROM friends 
        WHERE (user_id = ? AND friend_id = ?) 
           OR (user_id = ? AND friend_id = ?)
    ");
    $delete_friends->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
    $delete_friends->execute();
    $delete_friends->close();

    // Remove any pending or past friend requests between these users
    $delete_requests = $conn->prepare("
        DELETE FROM friend_requests 
        WHERE (sender_id = ? AND receiver_id = ?) 
           OR (sender_id = ? AND receiver_id = ?)
    ");
    $delete_requests->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
    $delete_requests->execute();
    $delete_requests->close();

    echo "Friend removed successfully!";
    header("Location: ../profile/profil.php");
}
?>
