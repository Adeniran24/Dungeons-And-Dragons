<?php
session_start(); // Start the session

// Check if the user is logged in by verifying session variables






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
    $profile_picture = $_SESSION['profile_picture'] ?? './defaults/profile_picture.jpg';

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



// Fetch the list of friends from the database
require_once 'connect.php'; // Include your database connection

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



// Now you can use $user_id, $username, and other session variables
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Website</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="profil.css">  
    <script src="index.js"></script>
    <script src="profil.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid">
        <a class="navbar-brand" href="index.php" style="color: rgb(255, 0, 0); background-color: black; padding: 10px 20px; border-radius: 25px; font-family: 'Cinzel', serif; font-weight: bold;">
    D&D Ultimate Tool
</a>          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
          </div>
        </div>
      </nav>


</div>

<!-- Profile Box -->
<!-- Profile Box -->
<div class="profile-box">
    <img src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? './defaults/profile_picture.jpg'); ?>" alt="Profile Picture" class="profile-pic">
    <h2 class="username"><?php echo $username; ?></h2>
    <p class="registration-date">Joined on: <?php echo $_SESSION['registration_date']; ?></p>
    <button class="change-pic-btn" onclick="openModal()">Change Picture</button>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>


<!-- Modal Window -->
<div id="pictureModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Select a Profile Picture</h2>
        <div class="image-options">
            <?php
            // Fetch all images from the ./defaults directory
            
            $directory = './defaults/';
            $images = glob($directory . "*.jpg"); // Adjust extension as needed (e.g., .png, .jpeg)

            foreach ($images as $image) {
                echo '<img src="' . htmlspecialchars($image) . '" alt="Default Profile" class="default-pic" onclick="selectImage(\'' . htmlspecialchars($image) . '\')">';
            }
            ?>
        </div>
    </div>
</div>


<!-- Friend List -->
<div class="friend-list">
    <h3>Your Friends</h3>
    <ul class="friends">
        <?php foreach ($friends as $friend): ?>
            <li class="friend">
                <img src="<?php echo htmlspecialchars($friend['profile_picture'] ?? './defaults/profile_picture.jpg'); ?>" alt="Profile Picture" class="friend-pic">
                <div class="friend-info">
                    <span class="friend-name"><?php echo htmlspecialchars($friend['username']); ?></span>
                    <span class="friend-status <?php echo ($friend['status'] === 'Online') ? 'Online' : 'Offline'; ?>">
                        <?php echo htmlspecialchars($friend['status']); ?>
                    </span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<style>.friend-status.online {
    color: green; /* Green color for available (online) friends */
}

.friend-status.offline {
    color: red; /* Red color for offline friends */
}
</style>








<style>
.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
    text-align: center;
    position: relative;
}
</style>



      <footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>
</body>
</html>



