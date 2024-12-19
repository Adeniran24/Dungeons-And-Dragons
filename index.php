<?php
session_start(); // Szesszió indítása

// Ellenőrizd, hogy a felhasználó be van-e jelentkezve
$if (!isset($_SESSION['token'])) {
    header ("Location:login.php")
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
                    <!-- Ha be van jelentkezve a felhasználó, a profil gomb jelenik meg -->
                    <a style="display: block;" id="Logged" href="profil.php">
                        <img class="profKep" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQmVq-OmHL5H_5P8b1k306pFddOe3049-il2A&s" alt=""> Profil
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