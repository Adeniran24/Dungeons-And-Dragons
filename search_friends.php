<?php
session_start();
require_once 'connect.php';

// Check if the user is logged in by verifying session variables
if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    // If the user is not logged in, redirect to login page
    header("Location: login.php");
    exit();
} else {
    // The user is logged in, you can use the session variables
    $is_logged_in = true;
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Store the profile image URL in session (assume profile picture is already set in the session)
    $profil_img['profile_picture'] = $_SESSION['profile_picture']; 
    
    // Optional: verify token if using cookie for added security
    if (isset($_COOKIE['auth_token']) && $_COOKIE['auth_token'] !== $_SESSION['token']) {
        // Invalidate session if the token does not match
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}


if (isset($_GET['search'])) {
    $search = $_GET['search'];

    $query = "
        SELECT 
            u.id, 
            u.username, 
            u.profile_picture
        FROM 
            users u
        WHERE 
            u.username LIKE ? 
            AND u.id != ?
            AND NOT EXISTS (
                SELECT 1 
                FROM friends f 
                WHERE 
                    (f.user_id = ? AND f.friend_id = u.id) OR 
                    (f.friend_id = ? AND f.user_id = u.id)
                AND f.status = 'Friend'
            )
        ORDER BY 
            (SELECT COUNT(*) 
             FROM friends f 
             WHERE 
                 (f.user_id = ? AND f.friend_id = u.id) OR 
                 (f.friend_id = ? AND f.user_id = u.id)) DESC
    ";

    $stmt = $conn->prepare($query);
    $search_term = '%' . $search . '%';
    $logged_in_user_id = $_SESSION['user_id'];

    // Bind parameters for the query
    $stmt->bind_param("siiiii", $search_term, $logged_in_user_id, $logged_in_user_id, $logged_in_user_id, $logged_in_user_id, $logged_in_user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
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
    <link rel="stylesheet" href="index.css">
    <script src="index.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid">
        <a class="navbar-brand" href="index.php" style="color: rgb(255, 0, 0); background-color: black; padding: 10px 20px; border-radius: 25px; font-family: 'Cinzel', serif; font-weight: bold;">
    D&D Ultimate Tool
</a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="character.php">Characters</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="wiki.php">Wiki</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="dmTools.php">DM Tools</a>
              </li>

            </ul>
            <form class="d-flex">
                <?php if ($is_logged_in): ?>
                <!-- If the user is logged in, the profile button with their username and image will be shown -->
                <a style="display: block; color:yellow;" id="Logged" href="profil.php" >
                    <!-- Display the user's profile image -->
                    <img class="profKep" id="profkep" 
                        src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? './defaults/profile_picture.jpg'); ?>" alt="Profile Image">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <?php else: ?>
                    <!-- Ha nincs bejelentkezve, akkor a Login/Register gomb jelenik meg -->
                    <a style="display: block;" id="LogReg" class="btn btn-outline-warning" href="login.php">Login/Register</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>


<style>
    
.friends {
  border-radius: 50%;
  width: 50%;
  height: 55%;
  margin: 0 auto;
  display: block;
  border: 5px solid #d4af37;
  box-shadow: 0 0 10px rgba(212, 175, 55, 0.7);
  transition: transform 0.3s, box-shadow 0.3s;
}

.friends_req {
  border-radius: 50%;
  width: 25%;
  height: 50%;
  margin: 0 auto;
  display: block;
  border: 5px solid #d4af37;
  box-shadow: 0 0 10px rgba(212, 175, 55, 0.7);
  transition: transform 0.3s, box-shadow 0.3s;
}
</style>

<div class="container ">
    <div class="row">
        <h1>Friends</h1>
        <!-- Search Users -->
        <div class="card col-md-8" >
            <div class="card-header">
                Search for Users
            </div>
            <div class="card-body">
                <form action="search_friends.php" method="GET">
                    <input type="text" name="search" placeholder="Search for users" class="form-control mb-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        
        <?php if (isset($users)): ?>
            <div class="row">
                <?php foreach ($users as $user): ?>
                    <div class="col-md-4" style="list-style-type: none;">
                        <img class="friends" src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                        <?php echo htmlspecialchars($user['username']); ?>
                        <form action="add_friend.php" method="POST">
                            <input type="hidden" name="friend_id" value="<?php echo $user['id']; ?>" />
                            <button type="submit">Add Friend</button>
                        </form>
                </div>
                <?php endforeach; ?>
                </div>
        <?php endif; ?>
    </div>


    <!-- Friend Requests -->
    <?php

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id']; // Logged-in receiver's ID

$stmt = $conn->prepare("
    SELECT fr.id, u.username, u.profile_picture 
    FROM friend_requests fr 
    JOIN users u ON fr.sender_id = u.id 
    WHERE fr.receiver_id = ? AND fr.status = 'pending'
");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();
$friend_requests = [];
while ($row = $result->fetch_assoc()) {
    $friend_requests[] = $row;
}
$stmt->close();
?>

    <div class="card col-md-4">
    <h3>Friend Requests</h3>
<?php if (empty($friend_requests)): ?>
    <p>You have no pending friend requests.</p>
<?php else: ?>
    <div class="friend-requests-container">
        <?php foreach ($friend_requests as $request): ?>
            <div class="friend-request">
                <img class="friends_req" src="<?php echo htmlspecialchars($request['profile_picture']); ?>" alt="Profile Picture" class="request-profile-pic">
                <p class="request-username"><?php echo htmlspecialchars($request['username']); ?></p>
                <form action="respond_friend_request.php" method="POST">
                    <input type="hidden" name="friend_request_id" value="<?php echo $request['id']; ?>">
                    <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                    <button type="submit" name="action" value="deny" class="btn btn-danger">Deny</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>
    </div>
</div>



<footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>
</body>
</html>