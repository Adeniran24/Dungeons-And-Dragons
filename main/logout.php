<?php
session_start();

// Destroy session data and remove session variables


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    
    // Include the database connection
    require_once './connect.php'; // Make sure this is correctly set up
    
    // Prepare the SQL statement to update the status to "Offline"
    $stmt = $conn->prepare("UPDATE users SET status = 'Offline' WHERE id = ?");
    $stmt->bind_param("i", $user_id); // Bind the user ID as an integer

    // Execute the statement
    if ($stmt->execute()) {
        // Status updated successfully
        echo "User status updated to offline.";
    } else {
        // Error occurred while updating the status
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close(); // Close the statement
    session_unset();
    session_destroy();
    
    

// Remove the token cookie by setting its expiration time to a past time
if (isset($_COOKIE['auth_token'])) {
    setcookie('auth_token', '', time() - 3600, '/', '', true, true);
}

// Redirect the user to the login page or homepage
header("Location: login.php");  // Change 'login.php' to the appropriate page
exit();
}
?>
