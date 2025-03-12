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


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $room_id = intval($_POST['room_id']);
    $invitee_id = intval($_POST['invitee_id']);

    // Ensure only the room owner can invite users
    $check_owner = $conn->query("SELECT owner_id FROM chat_rooms WHERE id = $room_id AND owner_id = $user_id");

    if ($check_owner->num_rows > 0) {
        $stmt = $conn->prepare("INSERT INTO invitations (room_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $room_id, $invitee_id);
        if ($stmt->execute()) {
            echo "User invited successfully!";
        } else {
            echo "Error inviting user.";
        }
        $stmt->close();
    } else {
        echo "You are not allowed to invite users to this room.";
    }
}
?>
