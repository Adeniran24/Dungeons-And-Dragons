<?php
session_start();
// Include the database connection, the connect.php is in the parent directory
require '../connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST variables
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit();
    }

    // Prepare and execute SQL query
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verify the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password using MD5 (use a more secure hashing mechanism in real applications)
        if (md5($password) === $user['password']) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile_picture'] = $user['profile_picture'];
            $_SESSION['status'] = $user['status'];
            $_SESSION['registration_date']= $user['registration_date'];
            $stmt = $conn->prepare("UPDATE users SET status = 'Online' WHERE id = ?");
            $stmt->bind_param("i", $user['id']); // Bind user ID as an integer
            $stmt->execute();
            


            // Optionally, generate a secure token (JWT or a random token for session authentication)
            $token = bin2hex(random_bytes(32));
            $_SESSION['token'] = $token;  // Store token in session

            // Or use a secure cookie to store the token
            setcookie('auth_token', $token, time() + 3600, '/', '', true, true);

            // Redirect to home page
            header("Location: second.php");
            

            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with this email.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link rel="stylesheet" href="">
    <script src="index.js"></script>
    <script src="logreg.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    
<style>
</style>
</head>
<body>
<a class="navbar-brand" id="Logo" href="index.php" >
    D&D Ultimate Tool
</a>
    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid">

        
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </nav>

<!-- Login/Register Form -->
<!-- Form container -->
<div class="container my-5" id="authForm">
    
    <!-- Form Title -->
    <h1 class="form-title mb-4" id="formTitle">Sign In</h1>
    
    <!-- Sign In Form (Visible by default) -->
    <div id="signIn" style="display: block;">
        <form method="post" action="./login.php" id="signInForm">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required autofocus id = "email"> 
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required >
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
        <form method="post" action="./register.php" id="signUpForm">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required >
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

    <style>
/* Import a medieval font */
@import url('https://fonts.googleapis.com/css2?family=IM+Fell+English+SC&display=swap');

body {
    background: url('https://www.transparenttextures.com/patterns/aged-paper.png');
    background-color: #3a1d0f; /* Dark brown for depth */
    color: #e4c590; /* Light gold for medieval feel */
    font-family: 'IM Fell English SC', serif;
    text-align: center;
}

/* Form Container - Parchment Style */
#authForm {
    max-width: 500px;
    margin: 0 auto;
    padding: 25px;
    background: url('https://www.transparenttextures.com/patterns/old-map.png'); /* Parchment texture */
    background-color: #c4a484; /* Parchment color */
    background-blend-mode: multiply; /* Darken the parchment */
    
    border: 6px solid #6b4226; /* Old brown parchment border */
    border-radius: 15px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(2px);
    
}

/* Input Fields - Old Scroll Look */
.input-group {
    margin-bottom: 15px;
    padding: 8px;
    border-radius: 10px;
    background: rgba(69, 37, 16, 0.7);
    border: 2px solid #8b5a2b;
}

.input-group-text {
    background: #8b5a2b;
    border: none;
    color:rgb(236, 120, 11);
}

.form-control {
    background: rgba(255, 240, 200, 0.9);
    border: 2px solid #6b4226;
    color: #3a1d0f;
    font-weight: bold;
    font-family: 'IM Fell English SC', serif;
}

.form-control:focus {
    background: rgba(255, 240, 200, 1);
    border-color: #d4af37; /* Gold outline when selected */
    outline: none;
    box-shadow: 0 0 8px #d4af37;
}

/* Submit Button - Wax Seal Style */
.btn-success {
    background: linear-gradient(to bottom, #a52a2a, #8b0000); /* Dark red wax effect */
    border: 3px solid #3a1d0f;
    color: #e4c590;
    font-family: 'IM Fell English SC', serif;
    font-size: 18px;
    padding: 10px;
    border-radius: 50px; /* Wax seal shape */
    transition: all 0.3s ease-in-out;
}

.btn-success:hover {
    background: linear-gradient(to bottom, #b22222, #600000);
    transform: scale(1.1);
    box-shadow: 0 0 10px rgba(218, 165, 32, 0.8);
}

/* Links - Old Scroll Hyperlink Style */
a {
    color:rgb(212, 86, 55);
    text-decoration: none;
}

a:hover {
    text-shadow: 0px 0px 5px #ffcc00;
}

/* Medieval Logo */
#Logo {
    font-size: 26px;
    font-weight: bold;
    background: linear-gradient(to bottom, #8b5a2b, #3a1d0f);
    padding: 15px 25px;
    border-radius: 15px;
    color: #e4c590;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
    border: 4px solid #6b4226;
}

.footer{
    background: linear-gradient(to bottom, #8b5a2b, #3a1d0f);
    color: #e4c590;
    font-size: 18px;
    font-weight: bold;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
    border-top: 4px solid #6b4226;
    /*Fix the footer at the bottom of the page*/
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    text-align: center;
    
}

    .recover {
        text-align: right;
        margin-top: -10px;
        margin-bottom: 10px;
    }

    .or {
        text-align: center;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .valtas {
        background-color: black;
        color: white;
        border: 1px solid white;
        border-radius: 25px;
        padding: 5px 10px;
    }

    .valtas:hover {
        background-color: white;
        color: black;
    }

    .links {
        margin-top: 10px;
    }

    .form-title {
        text-align: center;
        font-size: 2em;
    }

    .input-group {
        margin-bottom: 10px;
    }

    .input-group-text {
        background-color: black;
        color: white;
    }

    .input-group-text i {
        color: white;
    }

    .input-group input {
        border: 1px solid black;
    }

    .input-group input:focus {
        border: 1px solid black;
    }

    .btn {
        background-color: black;
        color: white;
        border: 1px solid white;
        border-radius: 25px;
        padding: 5px 10px;
    }

    .btn:hover {
        background-color: white;
        color: black;
    }

    .btn-link {
        color: black;
        text-decoration: none;
    }

    .btn-link:hover {
        color: rgb(255, 0, 0);
    }

    .container {
        max-width: 500px;
    }

    .mt-5 {
        margin-top: 50px;
    }

    .mb-3 {
        margin-bottom: 30px;
    }

    .w-100 {
        width: 100%;
    }

    .text-center {
        text-align: center;
    }

    .my-5 {
        margin-top: 50px;
        margin-bottom: 50px;
    }

    /* Background image */
    body {
        background-image: url('https://png.pngtree.com/thumb_back/fw800/background/20230519/pngtree-room-in-an-old-tavern-image_2571451.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        background-color: black;
        color: white;
    }
</style>

</body>
</html>