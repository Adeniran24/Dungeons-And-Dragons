<?php
include '../../connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $room_id = intval($_POST['room_id']);
    $message = trim($_POST['message']);

    $invitation_check = $conn->query("SELECT * FROM invitations WHERE room_id = $room_id AND user_id = $user_id");

    if ($invitation_check->num_rows > 0 && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO room_messages (room_id, user_id, username, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $room_id, $user_id, $username, $message);
        $stmt->execute();
        
        $timestamp = time();  // Get current UNIX timestamp
        echo json_encode(["success" => true, "username" => $username, "message" => $message, "timestamp" => $timestamp]);

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Not invited or empty message"]);
    }
}
?>
