<?php
require_once 'connect.php';

session_start();

if (isset($_POST['friend_request_id']) && isset($_POST['action'])) {
    $friend_request_id = $_POST['friend_request_id'];
    $action = $_POST['action']; // "accept" or "deny"

    if ($action === "accept") {
        // Update the friend request status to "accepted"
        $stmt = $conn->prepare("UPDATE friend_requests SET status = 'accepted' WHERE id = ?");
        $stmt->bind_param("i", $friend_request_id);
        $stmt->execute();
        $stmt->close();

        // Add both users to the `friends` table
        $stmt = $conn->prepare("
            INSERT INTO friends (user_id, friend_id, status)
            SELECT sender_id, receiver_id, 'Friend' FROM friend_requests WHERE id = ?
        ");
        $stmt->bind_param("i", $friend_request_id);
        $stmt->execute();
        $stmt->close();

        // Also insert the reverse relation for bidirectional friendship
        $stmt = $conn->prepare("
            INSERT INTO friends (user_id, friend_id, status)
            SELECT receiver_id, sender_id, 'Friend' FROM friend_requests WHERE id = ?
        ");
        $stmt->bind_param("i", $friend_request_id);
        $stmt->execute();
        $stmt->close();

        echo "Friend request accepted!";
    } elseif ($action === "deny") {
        // Update the friend request status to "denied"
        $stmt = $conn->prepare("UPDATE friend_requests SET status = 'denied' WHERE id = ?");
        $stmt->bind_param("i", $friend_request_id);
        $stmt->execute();
        $stmt->close();

        echo "Friend request denied.";
    }
}
header("Location: profil.php"); // Redirect back to profile or requests page
exit();
?>
