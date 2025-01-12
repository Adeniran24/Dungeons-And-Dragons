<?php
session_start();

// Destroy session data and remove session variables
session_unset();
session_destroy();

// Remove the token cookie by setting its expiration time to a past time
if (isset($_COOKIE['auth_token'])) {
    setcookie('auth_token', '', time() - 3600, '/', '', true, true);
}

// Redirect the user to the login page or homepage
header("Location: login.php");  // Change 'login.php' to the appropriate page
exit();
?>
