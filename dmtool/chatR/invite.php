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
    die("Room not found.");
}

$room_id = intval($_GET['room_id']);

// Fetch friends who are not yet invited (Using Prepared Statements)
$stmt = $conn->prepare("
    SELECT u.id, u.username FROM users u
    JOIN friends f ON ((f.user_id = ? AND f.friend_id = u.id) 
    OR (f.friend_id = ? AND f.user_id = u.id)) 
    WHERE f.status = 'Friend'
    AND u.id NOT IN (SELECT user_id FROM invitations WHERE room_id = ?)
");
$stmt->bind_param("iii", $user_id, $user_id, $room_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Friends</title>
    <link rel="stylesheet" href="../../main/index.css">
    <script src="../../main/index.js"></script>

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
        .friend-list { max-width: 500px; margin: auto; }
        .friend-item { display: flex; justify-content: space-between; padding: 10px; border-bottom: 1px solid #ccc; }
        .invite-btn { background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer; }
        .invite-btn:disabled { background-color: grey; cursor: not-allowed; }
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

<div class="container mt-4">
    <h1 class="text-center">Invite Friends to Chat Room</h1>

    <ul class="list-group friend-list">
        <?php while ($friend = $result->fetch_assoc()): ?>
            <li class="list-group-item friend-item">
                <?= htmlspecialchars($friend['username']) ?>
                <button class="invite-btn" id="invite-<?= $friend['id'] ?>" onclick="inviteUser(<?= $friend['id'] ?>, <?= $room_id ?>)">Invite</button>
            </li>
        <?php endwhile; ?>
    </ul>

    <button class="btn btn-secondary mt-3" onclick="window.location.href='room_chat.php?room_id=<?= $room_id ?>'">Back to Chat</button>
</div>

<script>
    function inviteUser(friendId, roomId) {
        let button = document.getElementById(`invite-${friendId}`);
        button.disabled = true;
        button.textContent = "Inviting...";

        fetch("invite_user.php", {
            method: "POST",
            body: new URLSearchParams({ room_id: roomId, invitee_id: friendId }),
            headers: { "Content-Type": "application/x-www-form-urlencoded" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.textContent = "Invited";
                button.style.backgroundColor = "grey";
            } else {
                button.disabled = false;
                button.textContent = "Invite";
                alert("Failed to send invitation: " + data.message);
            }
        })
        .catch(error => {
            button.disabled = false;
            button.textContent = "Invite";
            alert("Error: " + error);
        });
    }
</script>

<footer class="footer mt-auto py-3">
    <div class="container">
        <span>Đ&Đ Ultimate Tools</span>
    </div>
</footer>

</body>
</html>
