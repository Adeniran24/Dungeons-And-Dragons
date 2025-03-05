<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Profile of <?php echo $_SESSION['username']; ?></h2>
    <p>Email: <?php echo $_SESSION['email']; ?></p>
    <a href="main.php">Back to Main</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>
