<?php
require_once '../connect.php';

session_start();


if (isset($_POST['action'], $_POST['friend_id'])) {
    $user_id = $_SESSION['user_id'];
    $friend_id = $_POST['friend_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        // Check if a record already exists
        $check_stmt = $conn->prepare("
            SELECT * FROM friends 
            WHERE (user_id = ? AND friend_id = ?) 
               OR (user_id = ? AND friend_id = ?)
        ");

        $check_stmt->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Update the existing record to 'Friend'
            $update_stmt = $conn->prepare("
                UPDATE friends 
                SET status = 'Friend' 
                WHERE (user_id = ? AND friend_id = ?) 
                   OR (user_id = ? AND friend_id = ?)
            ");
            $update_stmt->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            // Insert a new friendship record
            $insert_stmt = $conn->prepare("
                INSERT INTO friends (user_id, friend_id, status) 
                VALUES (?, ?, 'Friend')
            ");
            $insert_stmt->bind_param("ii", $user_id, $friend_id);
            $insert_stmt->execute();
            $insert_stmt->close();
        }

        echo "Friend request accepted!";

        header("Location: profil.php");
    } elseif ($action === 'deny') {
        // Remove the friend request
        $delete_stmt = $conn->prepare("
            DELETE FROM friends 
            WHERE (user_id = ? AND friend_id = ?) 
              AND status = 'RequestSent'
        ");
        $delete_stmt->bind_param("ii", $friend_id, $user_id); // Opposite direction for request
        $delete_stmt->execute();
        $delete_stmt->close();

        echo "Friend request denied.";
        header("Location: profil.php");
    }
}
?>