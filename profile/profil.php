<?php
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
    $profile_picture = $_SESSION['profile_picture'] ?? './defaults/profile_picture.jpg';

    // Store the profile image URL in session (assume profile picture is already set in the session)
    $profil_img['profile_picture'] = $_SESSION['profile_picture']; 
    
    // Optional: verify token if using cookie for added security
    if (isset($_COOKIE['auth_token']) && $_COOKIE['auth_token'] !== $_SESSION['token']) {
        // Invalidate session if the token does not match
        session_unset();
        session_destroy();
        header("Location: ../main/login.php");
        exit();
    }
}

// Fetch the list of friends from the database
require_once '../connect.php'; // Include your database connection

$friends = [];
if ($is_logged_in) {
    $stmt = $conn->prepare("SELECT u.id, u.username, u.profile_picture, u.status FROM friends f JOIN users u ON f.friend_id = u.id WHERE f.user_id = ? AND f.status = 'Friend';");
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
    <title>D&D Ultimate Tool</title>
    <link rel="stylesheet" href="../main/index.css">
    <link rel="stylesheet" href="profil.css">  
    <script src="../main/index.js"></script>
    <script src="profil.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
</head>
<body style="background-color: #1e1e1e; color: #c0c0c0; font-family: 'Cinzel', serif;">

    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #333;">
        <div class="container-fluid">
            <a class="navbar-brand" href="../main/index.php" style="color: gold; font-size: 24px; padding: 10px 20px; border-radius: 25px; font-family: 'Cinzel', serif; font-weight: bold;">
                D&D Ultimate Tool
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../character/character.php">Character Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../wiki/wiki.php">Wiki</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../dmtool/dmTools.php">DM Tools</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <!-- Profile Box -->
            <div class="col-md-4">
                <div class="profile-box">
                    <img src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? '../defaults/profile_picture.jpg'); ?>" alt="Profile Picture" class="profile-pic">
                    <h2 class="username"><?php echo $username; ?></h2>
                    <p class="registration-date">Joined on: <?php echo $_SESSION['registration_date']; ?></p>
                    <button class="change-pic-btn" onclick="openModal()">Change Picture</button>
                    <a href="../main/logout.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <!-- Friend List -->
            <div class="col-md-4">
                <h3 class="text-center" style="color: gold;">Your Allies</h3>
                <ul class="friends-list">
                    <?php foreach ($friends as $friend): ?>
                        <li class="friend">
                            <img src="<?php echo htmlspecialchars($friend['profile_picture'] ?? '../defaults/profile_picture.jpg'); ?>" alt="Profile Picture" class="friend-pic">
                            <div class="friend-info">
                                <span class="friend-name"><?php echo htmlspecialchars($friend['username']); ?></span>
                                <span class="friend-status <?php echo ($friend['status'] === 'Online') ? 'online' : 'offline'; ?>"><?php echo htmlspecialchars($friend['status']); ?></span>
                            </div>
                            <form action="../friends/remove_friend.php" method="POST" onsubmit="return confirm('Are you sure you want to remove this ally?');">
                                <input type="hidden" name="friend_id" value="<?php echo $friend['id']; ?>">
                                <button type="submit" class="btn btn-danger">Remove Ally</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Friend Requests -->
            <div class="col-md-4">
                <h3 class="text-center" style="color: gold;">Pending Requests</h3>
                <?php if (empty($friend_requests)): ?>
                    <p>You have no pending requests.</p>
                <?php else: ?>
                    <div class="friend-requests-container">
                        <?php foreach ($friend_requests as $request): ?>
                            <div class="friend-request">
                                <img src="<?php echo htmlspecialchars($request['profile_picture']); ?>" alt="Profile Picture" class="request-profile-pic">
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

    



    <style>
        /*Dungeons and Dragons Stlying*/
        .profile-box {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
        }

        .username {
            color: gold;
            font-size: 24px;
            margin-top: 10px;
        }

        .registration-date {
            color: #c0c0c0;
            font-size: 14px;
            margin-top: 5px;
        }

        .change-pic-btn {
            background-color: gold;
            color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }

        .logout-btn {
            background-color: #c0c0c0;
            color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            display: block;
            text-decoration: none;
            margin: 0 auto;
        }

        .friends-list {
            list-style: none;
            padding: 0;
        }

        .friend {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            background-color: #333;
            padding: 10px;
            border-radius: 5px;
        }

        .friend-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .friend-info {
            flex: 1;
        }

        .friend-name {
            color: gold;
            font-weight: bold;
        }

        .friend-status {
            color: #c0c0c0;
            font-size: 14px;
        }

        .online {
            color: lightgreen;
        }

        .offline {
            color: red;
        }

        .friend-requests-container {
            background-color: #333;
            padding: 10px;
            border-radius: 5px;
        }

        .friend-request {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .request-profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .request-username {
            color: gold;
            font-weight: bold;
        }

        .btn {
            padding: 5px 10px;
            margin-right: 5px;
            cursor: pointer;
        }

        .btn-success {
            background-color: lightgreen;
            color: #333;
            border: none;
            border-radius: 5px;
        }

        .btn-danger {
            background-color: red;
            color: #333;
            border: none;
            border-radius: 5px;
        }

        .text-center {
            text-align: center;
        }

     

    </style>
</body>
</html>
