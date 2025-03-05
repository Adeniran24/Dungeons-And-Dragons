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


if (!isset($_GET['room_id'])) {
    die("Room not found.");
}

$room_id = intval($_GET['room_id']);

// Fetch friends who are not yet invited
$result = $conn->query("
    SELECT u.id, u.username FROM users u
    JOIN friends f ON ((f.user_id = $user_id AND f.friend_id = u.id) 
    OR (f.friend_id = $user_id AND f.user_id = u.id)) 
    WHERE f.status = 'Friend'
    AND u.id NOT IN (SELECT user_id FROM invitations WHERE room_id = $room_id)
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invite Friends</title>
</head>
<body>
    <h1>Invite Friends</h1>
    <ul>
        <?php while ($friend = $result->fetch_assoc()): ?>
            <li>
                <?= htmlspecialchars($friend['username']) ?>
                <button onclick="inviteUser(<?= $friend['id'] ?>, <?= $room_id ?>)">Invite</button>
            </li>
        <?php endwhile; ?>
    </ul>

    <script>
        function inviteUser(friendId, roomId) {
            fetch("invite_user.php", {
                method: "POST",
                body: new URLSearchParams({ room_id: roomId, invitee_id: friendId }),
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
            }).then(() => alert("Invitation sent!"));
        }
    </script>
</body>
</html>
