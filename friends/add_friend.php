<?php
session_start();
require_once '../connect.php';

// Check if friend_id is provided
if (isset($_POST['friend_id'])) {
    $user_id = $_SESSION['user_id'];
    $friend_id = $_POST['friend_id'];

    // Prevent sending a friend request to oneself
    if ($user_id == $friend_id) {
        echo "You can't send a friend request to yourself!";
        exit();
    }

    // Check if a friend request or friendship already exists
    $check_stmt = $conn->prepare("SELECT * FROM friend_requests WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)");
    $check_stmt->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "Friend request already sent or already friends!";
        exit();
    }
    $check_stmt->close();

    // Insert a new friend request into the database
    $stmt = $conn->prepare("INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ii", $user_id, $friend_id);

    if ($stmt->execute()) {
        echo "Friend request sent!";
        header("Location: profil.php");
    } else {
        echo "Error sending friend request: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "No friend specified.";
}
?>
