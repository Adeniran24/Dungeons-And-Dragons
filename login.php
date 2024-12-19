<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<nav>
        <ul>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="characters.php">Characters</a></li>
            <li><a href="wiki.php">Wiki</a></li>
            <li><a href="dm_tools.php">DM Tools</a></li>
            <li><a href="index.php">Main Site</a></li>
        </ul>
    </nav>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

    <?php
    session_start();
    include 'connect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        echo $password;
        // Database connection
        

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
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
</body>
</html>