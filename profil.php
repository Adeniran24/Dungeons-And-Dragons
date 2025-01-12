<?php
session_start(); // Start the session

// Check if the user is logged in by verifying session variables





/*
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
*/






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

<div class="profile-box">
    <div class="profile-left">
        <img src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? './defaults/profile_picture.jpg'); ?>" alt="Profile Picture" class="profile-pic">
        <p class="registration-date">Joined on: <?php echo $registration_date; ?></p>
        <button class="change-pic-btn" onclick="openModal()">Change Picture</button>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    <div class="profile-right">
        <h2 class="username"><?php echo $username; ?></h2>
    </div>
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






      <footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>










    <?php

// Ensure the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'profile_picture' parameter exists in the request
    if (isset($_POST['profile_picture'])) {
        // Sanitize the input to prevent directory traversal or malicious input
        $profile_picture = filter_var($_POST['profile_picture'], FILTER_SANITIZE_STRING);

        // Ensure the file exists and is in the './defaults/' directory
        if (file_exists($profile_picture) && strpos(realpath($profile_picture), realpath('./defaults/')) === 0) {
            // Save the selected profile picture path to the session
            $_SESSION['profile_picture'] = $profile_picture;

            // Optional: Save to the database if needed
            // Uncomment and customize if you're storing the profile picture in the database
            
            require_once 'db_connection.php'; // Replace with your database connection file
            $user_id = $_SESSION['user_id']; // Assuming the user's ID is stored in the session
            $stmt = $db->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            $stmt->bind_param("si", $profile_picture, $user_id);
            $stmt->execute();
            

            // Send a success response
            echo "Profile picture updated successfully.";
            exit;
        } else {
            // File does not exist or is not in the allowed directory
            http_response_code(400);
            echo "Invalid profile picture.";
            exit;
        }
    } else {
        // 'profile_picture' parameter is missing
        http_response_code(400);
        echo "No profile picture provided.";
        exit;
    }
} else {
    // If the request is not POST, return a 405 Method Not Allowed response
    http_response_code(405);
    echo "Method not allowed.";
    exit;
}
?>







</body>
</html>



