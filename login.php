<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link rel="stylesheet" href="index.css">
    <script src="index.js"></script>
    <script src="logreg.js"></script>



    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="index.css">

<style>
</style>

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
                <a class="nav-link active" aria-current="page" href="dmTools">DM Tools</a>
              </li>
              
            </ul>
            <form class="d-flex" >
              <a style="display: block;" id="LogReg" class="btn btn-outline-warning" href="login.php">Login/Register</a>
              <a style="display: none;" id="Logged" href="profil.html">
                <img class="profKep" src=" https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQmVq-OmHL5H_5P8b1k306pFddOe3049-il2A&s" alt=""> Profil
              </a>
            </form>
          </div>
        </div>
      </nav>



    

    <?php
    session_start();
    include 'connect.php';



    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
       
        echo $password;
       
        // Prepare and bind
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $token = bin2hex(random_bytes(32));
            setcookie('auth_token', $token, time() + 3600, '/', '', true, true); // 1 órára beállítva
            $_SESSION['token'] = token;    
                      

            echo "Login successful!";
            header ("Location:index.php");
            // Redirect to another page or start a session
        }
        else 
        {
            echo "Invalid username or password.";
        }

        // Close connections
        $stmt->close();
        $conn->close();
    }
    ?>



<!-- Login/Register Form -->

<!-- Form container -->
<div class="container my-5" id="authForm">
    
    <!-- Form Title -->
    <h1 class="form-title mb-4" id="formTitle">Sign In</h1>
    
    <!-- Sign In Form (Visible by default) -->
    <div id="signIn" style="display: block;">
        <form method="post" action="http://localhost/IKT.ADENIRAN/Arkadia/php/login.php">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            </div>
            <p class="recover">
                <a href="#">Recover Password</a>
            </p>
            <input type="submit" class="btn btn-success w-100" value="Sign In" name="signIn">
            <p class="or">
                ----------or--------
            </p>
            <div class="links text-center">
                <p>Don't have an account yet?</p>
                <button type="button" class="btn valtas" onclick="toggleForms()">Register</button>
            </div>
            <p class="mt-5 mb-3 text-center">© 2024–2025</p>
        </form>
    </div>

    <!-- Sign Up Form (Initially hidden) -->
    <div id="signUp" style="display: none;">
        <form method="post" action="http://localhost/IKT.ADENIRAN/Arkadia/php/register.php">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            </div>
            <input type="submit" class="btn btn-success w-100" value="Sign Up" name="signUp">
            <p class="or">
                ----------or--------
            </p>
            <div class="links text-center">
                <p>Already have an account?</p>
                <button type="button" class="btn btn-link" onclick="toggleForms()">Log In</button>
            </div>
            <p class="mt-5 mb-3 text-center">© 2024–2025</p>
        </form>
    </div>

</div>









<script src="logreg.js"></script>

    <footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>
</body>
</html>