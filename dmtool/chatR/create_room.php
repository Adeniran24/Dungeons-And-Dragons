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

$message = ""; // Message to show user

// Handle room creation
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

            // Redirect to room_chat.php
            header("Location: room_chat.php?room_id=$room_id");
            exit();
        } else {
            $message = "Error creating chat room.";
        }
    } else {
        $message = "Room name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Chat Room</title>
    <link rel="stylesheet" href="../../main/index.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
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
    <style>
        .container { max-width: 500px; margin: auto; margin-top: 50px; }
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

<div class="container">
    <h1 class="text-center">Create a New Chat Room</h1>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Room Name</label>
            <input type="text" name="room_name" class="form-control" placeholder="Enter room name" required>
        </div>
        <button type="submit" class="btn btn-success">Create Room</button>
        <a href="room_chat.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<footer class="footer mt-auto py-3">
    <div class="container">
        <span>Đ&Đ Ultimate Tools</span>
    </div>
</footer>

</body>
</html>
