<?php
session_start(); // Start the session

// Check if the user is logged in by verifying session variables






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

    // Store the profile image URL in session (assume profile picture is already set in the session)
    $profil_img['profile_picture'] = $_SESSION['profile_picture']; 
    
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
    $stmt = $conn->prepare("SELECT u.id, u.username, u.profile_picture, u.status    FROM friends f    JOIN users u ON f.friend_id = u.id    WHERE f.user_id = ? AND f.status = 'Friend';");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $friends[] = $row; // Save each friend's data
    }
    $stmt->close();
}



// Now you can use $user_id, $username, and other session variables
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Website</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="profil.css">  
    <script src="index.js"></script>
    <script src="profil.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid">
        <a class="navbar-brand" href="index.php" style="color: rgb(255, 0, 0); background-color: black; padding: 10px 20px; border-radius: 25px; font-family: 'Cinzel', serif; font-weight: bold;">
    D&D Ultimate Tool
</a>          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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


</div>

<!-- Profile Box -->
<!-- Profile Box -->
<div class="profile-box">
    <img src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? './defaults/profile_picture.jpg'); ?>" alt="Profile Picture" class="profile-pic">
    <h2 class="username"><?php echo $username; ?></h2>
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
            // Fetch all images from the ./defaults directory
            
            $directory = './defaults/';
            $images = glob($directory . "*.jpg"); // Adjust extension as needed (e.g., .png, .jpeg)

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
                    <span class="friend-status <?php echo ($friend['status'] === 'Online') ? 'Online' : 'Offline'; ?>">
                        <?php echo htmlspecialchars($friend['status']); ?>
                    </span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <!-- HTML for search results -->
<form action="search_friends.php" method="GET">
    <input type="text" name="search" placeholder="Search for friends" />
    <button type="submit">Search</button>
</form>


<?php if (isset($users)): ?>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                <?php echo htmlspecialchars($user['username']); ?>
                <form action="add_friend.php" method="POST">
                    <input type="hidden" name="friend_id" value="<?php echo $user['id']; ?>" />
                    <button type="submit">Add Friend</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>


    <h3>Friend Requests</h3>
    <?php

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id']; // Logged-in receiver's ID

$stmt = $conn->prepare("
    SELECT fr.id, u.username, u.profile_picture 
    FROM friend_requests fr 
    JOIN users u ON fr.sender_id = u.id 
    WHERE fr.receiver_id = ? AND fr.status = 'pending'
");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();
$friend_requests = [];
while ($row = $result->fetch_assoc()) {
    $friend_requests[] = $row;
}
$stmt->close();
?>

<?php if (empty($friend_requests)): ?>
    <p>You have no pending friend requests.</p>
<?php else: ?>
    <div class="friend-requests-container">
        <?php foreach ($friend_requests as $request): ?>
            <div class="friend-request">
                <img id="profkep" src="<?php echo htmlspecialchars($request['profile_picture']); ?>" alt="Profile Picture" class="request-profile-pic">
                <p class="request-username"><?php echo htmlspecialchars($request['username']); ?></p>
                <form action="respond_friend_request.php" method="POST">
                    <input type="hidden" name="friend_request_id" value="<?php echo $request['id']; ?>">
                    <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                    <button type="submit" name="action" value="deny" class="btn btn-danger">Deny</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

<style>.friend-status.online {
    color: green; /* Green color for available (online) friends */
}

.friend-status.offline {
    color: red; /* Red color for offline friends */
}
</style>

<style>
.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
    text-align: center;
    position: relative;
}
</style>






        <!-- Character List -->
<div class="character-list row">
    <?php
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
    <div class='character-card col-md-3'>
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
?>
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

        <style>
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
        </style>
</div>

</div>

      <footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>
</body>
</html>