<?php
session_start(); // Start the session

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
                <a class="nav-link active" aria-current="page" href="../character.php">Characters</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../wiki.php">Wiki</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../dmTools.php">DM Tools</a>
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
                    <a style="display: block;" id="LogReg" class="btn btn-outline-warning" href="login.php">Login/Register</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>


    <main>
        <h1>Welcome to the D&D Website</h1>
        <p>Your one-stop destination for all things Dungeons & Dragons.</p>
    </main>


    <div class="slideshow-container">
    <div class="mySlides fade">
        <a href="">
            <img src="https://soliloquywp.com/wp-content/uploads/2017/05/randomize-wordpress-slider-images.png" style="width:100%">
            <div class="text"><h3>Custom Weapons</h3></div>
        </a>
    </div>
    
    <div class="mySlides fade">
        <a href="">
            <img src="https://soliloquywp.com/wp-content/uploads/2017/05/randomize-wordpress-slider-images.png" style="width:100%">
            <div class="text"><h3>Custom Stories</h3></div>
        </a>
    </div>
    
    <div class="mySlides fade">
        <a href="">
            <img src="https://soliloquywp.com/wp-content/uploads/2017/05/randomize-wordpress-slider-images.png" style="width:100%">
            <div class="text"><h3>Custom Maps</h3></div>
        </a>
    </div>
    
    <div class="mySlides fade">
        <a href="">
            <img src="https://soliloquywp.com/wp-content/uploads/2017/05/randomize-wordpress-slider-images.png" style="width:100%">
            <div class="text"><h3>Custom Enemies</h3></div>
        </a>
    </div>
    
    <div class="mySlides fade">
        <a href="">
            <img src="https://soliloquywp.com/wp-content/uploads/2017/05/randomize-wordpress-slider-images.png" style="width:100%">
            <div class="text"><h3>Custom Races</h3></div>
        </a>
    </div>
</div>

<br>

<div style="text-align:center">
    <span class="dot"></span> 
    <span class="dot"></span> 
    <span class="dot"></span> 
    <span class="dot"></span> 
    <span class="dot"></span> 
</div>


















    <footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>
</body>
</html>