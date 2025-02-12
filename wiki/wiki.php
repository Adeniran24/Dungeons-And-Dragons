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
                        src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? './defaults/profile_picture.jpg'); ?>" alt="Profile Image">
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
</head>



    
    <style>
        .container {
            display: grid;
            grid-template-columns: repeat(6, 1fr); /* Maximum 6 oszlop egy sorban */
            gap: 10px;
            max-width: 800px;
            margin: 0 auto;
        }
        .container a {
            display: block;
            text-align: center;
            color: #333;
            text-decoration: none;
            font-size: 14px;
        }
        .container img {
            max-width: 100%;
            height: auto;
            border: 2px solid #ddd;
            border-radius: 5px;
            transition: transform 0.2s ease;
        }
        .container img:hover {
            transform: scale(1.1);
            border-color: #007BFF;
        }
        .container a:hover {
            color: #007BFF;
        }
    </style>

<body>
    <div class="container">
        <?php
        $items = [
            ['src' => 'image1.jpg', 'link' => 'wikiSubPages/spells/spells.php', 'name' => 'Spells'],
            ['src' => 'image2.jpg', 'link' => 'https://example.com/page2', 'name' => 'Races'],
            ['src' => 'image3.jpg', 'link' => 'https://example.com/page3', 'name' => 'Background'],
            ['src' => 'image4.jpg', 'link' => 'https://example.com/page4', 'name' => 'Heroic Chronicle'],
            ['src' => 'image5.jpg', 'link' => 'https://example.com/page5', 'name' => 'Classes'],
            ['src' => 'image6.jpg', 'link' => 'https://example.com/page6', 'name' => 'Items'],
            ['src' => 'image7.jpg', 'link' => 'https://example.com/page7', 'name' => 'Feats'],
            ['src' => 'image8.jpg', 'link' => 'https://example.com/page8', 'name' => 'Racial Feats'],
            ['src' => 'image9.jpg', 'link' => 'https://example.com/page9', 'name' => 'Miscellaneous'],
            ['src' => 'image10.jpg', 'link' => 'https://example.com/page10', 'name' => 'Homebrew'],
            ['src' => 'image11.jpg', 'link' => 'https://example.com/page11', 'name' => 'UA'],
        ];

        foreach ($items as $item) {
            echo '<a href="' . $item['link'] . '">';
            echo '<img src="' . $item['src'] . '" alt="' . $item['name'] . '">';
            echo '<p>' . $item['name'] . '</p>';
            echo '</a>';
        }
        ?>
    </div>





      <footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>
</body>
</html>