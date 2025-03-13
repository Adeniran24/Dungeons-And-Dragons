<?php
include '../session_token.php';
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Website</title>
    <link rel="stylesheet" href="index.css">



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

    <!--  -->












<br>


<style>

    /* General Styles */
body {
    background: url('https://th.bing.com/th/id/R.3450efdf110514ba56e0b1d0d9bef123?rik=HYX0OvdGxWjl7Q&pid=ImgRaw&r=0 ') no-repeat;
    
    /*Background no repeat*/
    background-size: cover;

    background-attachment: fixed;
    background-position: center;
    color: #ffcc80;
    /*No Repeat*/
    font-family: 'Cinzel', serif;
    margin: 0;
    padding: 0;
}



h1, h2, h3, h4, h5 {
    font-family: 'Cinzel Decorative', cursive;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
}

.navbar {
    background: linear-gradient(to right, #3e2723, #5d4037);
    border-bottom: 3px solid #8d6e63;
    margin-bottom: 20px;
}

.navbar-brand {
    color: #ffcc80 !important;
    font-size: 24px;
    text-transform: uppercase;
}

.navbar-nav .nav-link {
    color: #ffb74d !important;
    font-weight: bold;
}

.navbar-nav .nav-link:hover {
    color: #ff9800 !important;
    text-shadow: 1px 1px 3px #000;
}

/* Buttons */
.btn-outline-warning {
    border: 2px solid #ffcc80;
    color: #ffcc80;
    font-weight: bold;
}

.btn-outline-warning:hover {
    background: #ffcc80;
    color: black;
}

/.slideshow-container {
    position: relative;
    max-width: 800px;
    height: 1000px; /* Set a fixed height */
    margin: auto;
    border-radius: 15px;
    overflow: hidden;
    border: 3px solid #8d6e63;
}

.mySlides {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.mySlides img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures images fill the space without distortion */
    border-radius: 15px;
}



.text {
    position: absolute;
    bottom: 8px;
    width: 100%;
    text-align: center;
    font-size: 20px;
    background: rgba(0, 0, 0, 0.7);
    padding: 10px 0;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
}

/* Footer */
.footer {
    background: #3e2723;
    color: #ffcc80;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
}

</style>





    <footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>
</body>
</html>