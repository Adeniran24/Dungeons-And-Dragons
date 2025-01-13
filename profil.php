<?php
session_start(); // Start the session

// Check if the user is logged in by verifying session variables
if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    // If the user is not logged in, redirect to login page
    header("Location: login.php");
    exit();
} else {
    // The user is logged in, you can use the session variables
    $is_logged_in = true;
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];    
    $profile_picture = $_SESSION['profile_picture'] ?? './defaults/profile_picture.jpg';

    // Optional: verify token if using cookie for added security
    if (isset($_COOKIE['auth_token']) && $_COOKIE['auth_token'] !== $_SESSION['token']) {
        // Invalidate session if the token does not match
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Fetch the list of friends from the database
require_once 'connect.php'; // Include your database connection

$friends = [];
if ($is_logged_in) {
    $stmt = $conn->prepare("SELECT u.id, u.username, u.profile_picture, u.status 
                            FROM friends f
                            JOIN users u ON f.friend_id = u.id
                            WHERE f.user_id = ? AND f.status = 'Friend';");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $friends[] = $row; // Save each friend's data
    }
    $stmt->close();
}

// Fetch the user's characters
$characters = [];
$stmt = $conn->prepare("
    SELECT 
        characters.id, 
        characters.name, 
        characters.image, 
        races.name AS race, 
        classes.name AS class 
    FROM characters
    LEFT JOIN races ON characters.race_id = races.id
    LEFT JOIN classes ON characters.class_id = classes.id
    WHERE characters.user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "
    <div class='character-card'>
        <a href='character_details.php?id={$row['id']}'>
            <img src='{$row['image']}' alt='{$row['name']}' class='character-image'>
        </a>
        <div class='character-info'>
            <a href='character_details.php?id={$row['id']}' class='character-name'>{$row['name']}</a>
            <p class='character-race'>Race: {$row['race']}</p>
            <p class='character-class'>Class: {$row['class']}</p>
        </div>
    </div>";
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - D&D Website</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="profil.css">  
    <script src="index.js"></script>
    <script src="profil.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
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
                    <a class="nav-link active" aria-current="page" href="dmTools.php">DM Tools</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container mt-5">
    <!-- Profile Box -->
    <div class="profile-box">
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-pic">
        <h2 class="username"><?php echo htmlspecialchars($username); ?></h2>
        <p class="registration-date">Joined on: <?php echo $_SESSION['registration_date']; ?></p>
        <button class="change-pic-btn" onclick="openModal()">Change Picture</button>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Modal Window -->
    <div id="pictureModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Select a Profile Picture</h2>
            <div class="image-options">
                <?php
                $directory = './defaults/';
                $images = glob($directory . "*.jpg"); // Adjust extension as needed
                foreach ($images as $image) {
                    echo '<img src="' . htmlspecialchars($image) . '" alt="Default Profile" class="default-pic" onclick="selectImage(\'' . htmlspecialchars($image) . '\')">';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Friend List -->
    <div class="friend-list">
        <h3>Your Friends</h3>
        <ul class="friends">
            <?php foreach ($friends as $friend): ?>
                <li class="friend">
                    <img src="<?php echo htmlspecialchars($friend['profile_picture'] ?? './defaults/profile_picture.jpg'); ?>" alt="Profile Picture" class="friend-pic">
                    <div class="friend-info">
                        <span class="friend-name"><?php echo htmlspecialchars($friend['username']); ?></span>
                        <span class="friend-status <?php echo ($friend['status'] === 'Online') ? 'online' : 'offline'; ?>">
                            <?php echo htmlspecialchars($friend['status']); ?>
                        </span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Character List -->
    <div class="character-list">
        <h3>Your Characters</h3>
        <?php if (empty($characters)): ?>
            <p>You have no characters yet. <a href="create_character.php">Create one now</a>.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($characters as $character): ?>
                    <li>
                        <h4><?php echo htmlspecialchars($character['name']); ?></h4>
                        <p>Class: <?php echo htmlspecialchars($character['class']); ?></p>
                        <p>Race: <?php echo htmlspecialchars($character['race']); ?></p>
                        <p>Level: <?php echo htmlspecialchars($character['level']); ?></p>
                        <a href="view_character.php?id=<?php echo htmlspecialchars($character['id']); ?>">View Details</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
<style>
   dy {
    font-family: 'Roboto', Arial, sans-serif;
    background-color: #1a1a1d;
    color: #f5f5f5;
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.navbar {
    background-color: #2d2d34;
    border-bottom: 2px solid #f05454;
}

.navbar-brand {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    color: #f05454;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
}

.profile-box {
    background-color: #2d2d34;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.profile-pic {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid #f05454;
    margin-bottom: 10px;
    object-fit: cover;
}

.username {
    font-size: 1.5rem;
    font-weight: bold;
    color: #f05454;
}

.registration-date {
    font-size: 0.9rem;
    color: #a5a5a5;
}

.change-pic-btn, .logout-btn {
    display: inline-block;
    padding: 10px 20px;
    margin: 10px 5px;
    background-color: #f05454;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s;
}

.change-pic-btn:hover, .logout-btn:hover {
    background-color: #d43f3f;
}

.friend-list, .character-list {
    background-color: #2d2d34;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.friend-list h3, .character-list h3 {
    color: #f05454;
    margin-bottom: 20px;
}

.friend {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.friend-pic {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
    object-fit: cover;
}

.friend-info {
    color: #f5f5f5;
}

.friend-name {
    font-size: 1rem;
    font-weight: bold;
}

.friend-status {
    font-size: 0.9rem;
    color: #a5a5a5;
}

.friend-status.online {
    color: #4caf50;
}

.friend-status.offline {
    color: #e53935;
}

.character-card {
    background-color: #2d2d34;
    border: 1px solid #f05454;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    width: 280px;
    margin: 15px;
    text-align: center;
    display: inline-block;
    vertical-align: top;
}

.character-card img.character-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.character-info {
    padding: 15px;
    color: #f5f5f5;
}

.character-info .character-name {
    font-size: 1.2rem;
    font-weight: bold;
    color: #f05454;
    text-decoration: none;
}

.character-info .character-name:hover {
    text-decoration: underline;
}

.character-info .character-race,
.character-info .character-class {
    font-size: 0.9rem;
    color: #a5a5a5;
    margin: 5px 0;
}

footer {
    background-color: #2d2d34;
    color: #f5f5f5;
    text-align: center;
    padding: 10px;
    border-top: 2px solid #f05454;
    margin-top: 20px;
}

/* Fix navbar positioning */
.navbar {
    position: sticky; /* Keep the navbar at the top while scrolling */
    top: 0;
    z-index: 1030; /* Ensure it stays above all other content */
    background-color: #000; /* Black background for contrast */
    padding: 10px;
}

/* Push the content below the navbar */
.container {
    margin-top: 80px; /* Adjust based on the navbar height */
}

/* Fix overlapping modal or z-index issues */
.modal {
    z-index: 1050; /* Ensure modals appear above the navbar */
}

/* Character list styling */
.character-list {
    display: flex;
    flex-wrap: wrap; /* Ensure cards wrap to the next row */
    gap: 20px;
    justify-content: center;
    margin-top: 20px;
}

/* Friend list styling */
.friend-list {
    margin-top: 20px;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.friend-list ul {
    list-style: none;
    padding: 0;
}

.friend-list .friend {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.friend-list .friend img.friend-pic {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 10px;
}

.friend-list .friend-info {
    display: flex;
    flex-direction: column;
}

.friend-list .friend-name {
    font-weight: bold;
}

.friend-list .friend-status {
    font-size: 12px;
    color: #666;
}

.friend-list .friend-status.online {
    color: green;
}

.friend-list .friend-status.offline {
    color: red;
}


</style>

    </style>
<footer class="footer mt-auto py-3">
    <div class="container">
        <span class="">D&D Ultimate Tools</span>
    </div>
</footer>

</body>
</html>
