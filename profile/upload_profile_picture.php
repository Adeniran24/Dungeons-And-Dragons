<?php
session_start();
require '../connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    http_response_code(403);
    echo "Unauthorized access.";
    exit();
}

// Check if the file is uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];

    // Validate the file type and size
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $max_size = 5 * 1024 * 1024;  // 5 MB
    $upload_dir = '../uploads/';

    if (!in_array($file['type'], $allowed_types)) {
        echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
        exit();
    }

    if ($file['size'] > $max_size) {
        echo "File is too large. Maximum file size is 5MB.";
        exit();
    }

    // Generate a unique name for the uploaded file
    $file_name = uniqid('profile_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $target_path = $upload_dir . $file_name;

    // Move the file to the uploads directory
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Update session with the new profile picture path
        $_SESSION['profile_picture'] = $target_path;

        // Update the profile picture in the database
        $user_id = $_SESSION['user_id'];
        $sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $target_path, $user_id);

        if ($stmt->execute()) {
            echo "Profile picture uploaded successfully.";
        } else {
            echo "Database update failed.";
        }

        $stmt->close();
    } else {
        echo "File upload failed.";
    }
} else {
    echo "No file uploaded.";
}
?>

<!-- 
ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT './defaults/profile_picture.jpg';
-->
