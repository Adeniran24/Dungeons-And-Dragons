<?php
session_start();
require 'connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    http_response_code(403);
    echo "Unauthorized access.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['profile_picture'])) {
        // Sanitize the profile picture input
        $profile_picture = filter_var($_POST['profile_picture'], FILTER_SANITIZE_STRING);

        // Validate that the file exists and is in the correct directory
        if (file_exists($profile_picture) && strpos(realpath($profile_picture), realpath('./defaults/')) === 0) {
            // Update the session with the new profile picture
            $_SESSION['profile_picture'] = $profile_picture;

            // Update the database with the new profile picture
            $user_id = $_SESSION['user_id'];
            $sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $profile_picture, $user_id);

            if ($stmt->execute()) {
                echo "Profile picture updated successfully.";
            } else {
                echo "Database update failed.";
            }

            $stmt->close();
        } else {
            http_response_code(400);
            echo "Invalid profile picture.";
        }
    } else {
        http_response_code(400);
        echo "No profile picture provided.";
    }
} else {
    http_response_code(405);
    echo "Method not allowed.";
}
?>
