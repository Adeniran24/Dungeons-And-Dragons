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
            header("Location: index.php");
            

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
        <form method="post" action="./login.php">
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
        <form method="post" action="./register.php">
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

    <style>
    /* Move the Logo to the left side */
#Logo {
    position: absolute;
    left: 10%; /* Adjust left value to make the logo sit comfortably within the navbar */
    transform: translateX(-50%);
    padding: 10px 20px;
    border-radius: 25px;
    font-family: 'Cinzel', serif;
    font-weight: bold;
    z-index: 1; /* Ensure the logo stays on top of the navbar */
    color:white; 
    background-color: rgb(155, 83, 57); 
    padding: 10px 20px; 
    border-radius: 25px; 
    font-family: 'Cinzel', serif; 
    font-weight: bold;
    opacity:0,2;
}

/* Modify the Navbar to cover the logo */
.navbar {
    background-color: rgb(155, 83, 57);    
    padding: 25px 50px; /* Increase padding for a wider navbar */
    border-radius: 25px;
    position: relative; /* Ensure it positions properly */

    backdrop-filter: blur(10px); /* Add a blur effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Add a shadow */
    opacity: 0.2;
}

/* Adjust the logo placement to avoid overlap */
.navbar .container-fluid {
    display: flex;
    justify-content: space-between; /* To spread out the elements */
    align-items: center; /* Ensure the items are vertically centered */
}

/* Make sure the navbar background covers the logo */
.navbar-toggler {
    z-index: 2; /* Ensure it’s above the logo */
}


    .footer {
        background-color: black;
        color: white;
        text-align: center;
        position: fixed;
        bottom: 0;
        width: 100%;
    }

    .footer a {
        color: white;
    }

    .footer a:hover {
        color: rgb(255, 0, 0);
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