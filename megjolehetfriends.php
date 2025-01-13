<?php
session_start();
require_once 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$profile_picture = $_SESSION['profile_picture'] ?? './defaults/profile_picture.jpg';

// Fetch all friends
$friends = [];
$stmt = $conn->prepare("SELECT u.id, u.username, u.profile_picture, u.status FROM friends f JOIN users u ON f.friend_id = u.id WHERE f.user_id = ? AND f.status = 'accepted'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $friends[] = $row;
}
$stmt->close();

// Fetch all pending friend requests
$requests = [];
$stmt = $conn->prepare("SELECT u.id, u.username, u.profile_picture FROM friends f JOIN users u ON f.user_id = u.id WHERE f.friend_id = ? AND f.status = 'pending'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">D&D Ultimate Tool</a>
    </div>
</nav>

<div class="container mt-4">
    <h1>Friends</h1>

    <!-- Search Users -->
    <div class="card mb-4">
        <div class="card-header">
            Search for Users
        </div>
        <div class="card-body">
            <form action="search_friends.php" method="GET">
                <input type="text" name="search" placeholder="Search for users" class="form-control mb-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <!-- Friend Requests -->
    <div class="card mb-4">
        <div class="card-header">
            Friend Requests
        </div>
        <div class="card-body">
            <?php if (empty($requests)): ?>
                <p>No pending friend requests.</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($requests as $request): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <img src="<?php echo htmlspecialchars($request['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                <?php echo htmlspecialchars($request['username']); ?>
                            </div>
                            <div>
                                <form action="respond_friend_request.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="friend_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                </form>
                                <form action="respond_friend_request.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="friend_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" name="action" value="deny" class="btn btn-danger btn-sm">Deny</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Friends List -->
    <div class="card mb-4">
        <div class="card-header">
            Your Friends
        </div>
        <div class="card-body">
            <?php if (empty($friends)): ?>
                <p>You have no friends yet. Search for users to add as friends!</p>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($friends as $friend): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <img src="<?php echo htmlspecialchars($friend['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                <?php echo htmlspecialchars($friend['username']); ?>
                            </div>
                            <span class="badge bg-<?php echo ($friend['status'] === 'Online') ? 'success' : 'secondary'; ?>">
                                <?php echo htmlspecialchars($friend['status']); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<footer class="footer mt-auto py-3 bg-dark text-white">
    <div class="container text-center">
        D&D Ultimate Tool © 2025
    </div>
</footer>
</body>
</html>
