<?php
session_start();
require_once 'connect.php';

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    // Search for users by username, ensuring we don't show the logged-in user
    $stmt = $conn->prepare("SELECT id, username, profile_picture FROM users WHERE username LIKE ? AND id != ?");
    $search_term = '%' . $search . '%';
    $stmt->bind_param("si", $search_term, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt->close();
}
?>

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
