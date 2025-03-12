<?php
include '../connect.php';
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


$result = $conn->query("SELECT u.id, u.username FROM users u 
                        JOIN friends f ON ((f.user_id = $user_id AND f.friend_id = u.id) 
                        OR (f.friend_id = $user_id AND f.user_id = u.id)) 
                        WHERE f.status = 'Friend'");


$friends = [];
if ($is_logged_in) {
    $stmt = $conn->prepare("SELECT u.id, u.username, u.profile_picture, u.status    FROM friends f    JOIN users u ON f.friend_id = u.id    WHERE f.user_id = ? AND f.status = 'Friend';");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $friends[] = $row; // Save each friend's data
    }
    $stmt->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Website</title>
    <link rel="stylesheet" href="../main/index.css">
    <script src="../main/index.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid">
        <a class="navbar-brand" href="../main/index.php" style="color: rgb(255, 0, 0); background-color: black; padding: 10px 20px; border-radius: 25px; font-family: 'Cinzel', serif; font-weight: bold;">
    D&D Ultimate Tool
</a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../character/character.php">Characters</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../wiki/wiki.php">Wiki</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../dmtool/dmTools.php">DM Tools</a>
              </li>

            </ul>
            <form class="d-flex">
                <?php if ($is_logged_in): ?>
                <!-- If the user is logged in, the profile button with their username and image will be shown -->
                <a style="display: block; color:yellow;" id="Logged" href="../profile/profil.php" >
                    <!-- Display the user's profile image -->
                    <img class="profKep" id="profkep" 
                        src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? '../defaults/profile_picture.jpg'); ?>" alt="Profile Image">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <?php else: ?>
                    <!-- Ha nincs bejelentkezve, akkor a Login/Register gomb jelenik meg -->
                    <a style="display: block;" id="LogReg" class="btn btn-outline-warning" href="../main/login.php">Login/Register</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>



<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .friend-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        .friend-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 150px;
            transition: transform 0.2s;
        }
        .friend-item:hover {
            transform: scale(1.05);
        }
        .friend-item img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .friend-item a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            display: block;
        }
    </style>

    <div>

    <h1>Select a Friend to Chat</h1>
    <ul class="friend-list">
        <?php foreach ($friends as $friend): ?>
            <li class="friend-item">
                <a href="private_chat.php?friend_id=<?= $friend['id'] ?>">
                <img src="<?= htmlspecialchars($friend['profile_picture'] ?? 'default_profile.png') ?>" 
                     alt="<?= htmlspecialchars($friend['username']) ?>'s profile picture">
                
                    <?= htmlspecialchars($friend['username']) ?>
                </a>
                <p class="status"><?= htmlspecialchars($friend['status']) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

    </div>




    <footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>
</body>
</html>