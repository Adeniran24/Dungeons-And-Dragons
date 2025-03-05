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
    $room_name = trim($_POST['room_name']);

    if (!empty($room_name)) {
        $stmt = $conn->prepare("INSERT INTO chat_rooms (name, owner_id) VALUES (?, ?)");
        $stmt->bind_param("si", $room_name, $user_id);
        if ($stmt->execute()) {
            $room_id = $stmt->insert_id;
            $stmt->close();

            // Automatically add the creator to the room
            $stmt = $conn->prepare("INSERT INTO invitations (room_id, user_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $room_id, $user_id);
            $stmt->execute();
            $stmt->close();

            echo "Chat room '$room_name' created successfully!";
        } else {
            echo "Error creating chat room.";
        }
    } else {
        echo "Room name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Chat Room</title>
</head>
<body>
    <h1>Create a New Chat Room</h1>
    <form method="POST">
        <input type="text" name="room_name" placeholder="Enter room name" required>
        <button type="submit">Create Room</button>
    </form>
</body>
</html>

