<?php
include '../../connect.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    header("Location: ../main/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Verify token for security
if (isset($_COOKIE['auth_token']) && $_COOKIE['auth_token'] !== $_SESSION['token']) {
    session_unset();
    session_destroy();
    header("Location: ../main/login.php");
    exit();
}

// Check if room_id is provided
if (!isset($_GET['room_id'])) {
    die("Room not selected.");
}

$room_id = intval($_GET['room_id']);

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT name FROM chat_rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();
$stmt->close();

// Check if room exists
if (!$room) {
    die("Chat room not found.");
}

// Check if user is invited
$stmt = $conn->prepare("SELECT * FROM invitations WHERE room_id = ? AND user_id = ?");
$stmt->bind_param("ii", $room_id, $user_id);
$stmt->execute();
$invitation_check = $stmt->get_result();
$stmt->close();

if ($invitation_check->num_rows === 0) {
    die("You are not invited to this chat room.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($room['name']) ?> - Chat Room</title>
    <link rel="stylesheet" href="../../main/index.css">
    <script src="../../main/index.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include Chat Script -->
    <script defer src="room_chat.js"></script>

    <style>
        #chat-box {
            width: 100%;
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            background: #f9f9f9;
        }
        .profKep {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="../../main/index.php" style="color: red; background-color: black; padding: 10px 20px; border-radius: 25px; font-family: 'Cinzel', serif; font-weight: bold;">
            D&D Ultimate Tool
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="../../character/character.php">Characters</a></li>
                <li class="nav-item"><a class="nav-link active" href="../../wiki/wiki.php">Wiki</a></li>
                <li class="nav-item"><a class="nav-link active" href="../../dmtool/dmTools.php">DM Tools</a></li>
            </ul>

            <form class="d-flex">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a id="Logged" href="../../profile/profil.php" style="display: block; color: yellow;">
                        <img class="profKep" src="<?= htmlspecialchars('../' . ($_SESSION['profile_picture'] ?? '../../defaults/profile_picture.jpg')) ?>" alt="Profile Image"> 
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </a>
                <?php else: ?>
                    <a id="LogReg" class="btn btn-outline-warning" href="../../main/login.php">Login/Register</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>

<!-- Chat Room Section -->
<div class="container mt-4">
    <h1>Welcome to <?= htmlspecialchars($room['name']) ?></h1>
    <div id="chat-box"></div>
    <input type="hidden" id="room_id" value="<?= $room_id ?>">
    <div class="input-group mt-3">
        <input type="text" id="message-input" class="form-control" placeholder="Type your message...">
        <button id="send-btn" class="btn btn-primary">Send</button>
    </div>
    <button class="btn btn-secondary mt-2" onclick="window.location.href='invite.php?room_id=<?= $room_id ?>'">Invite Friends</button>
</div>

<footer class="footer mt-auto py-3">
    <div class="container">
        <span>Đ&Đ Ultimate Tools</span>
    </div>
</footer>

</body>
</html>
