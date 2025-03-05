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
    $message = trim($_POST['message']);

    // Check if the user is invited
    $invitation_check = $conn->query("SELECT * FROM invitations WHERE room_id = $room_id AND user_id = $user_id");

    if ($invitation_check->num_rows > 0 && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO room_messages (room_id, user_id, username, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $room_id, $user_id, $username, $message);
        $stmt->execute();
        $stmt->close();
    }
}
?>
