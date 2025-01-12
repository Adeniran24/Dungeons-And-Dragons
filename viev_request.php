<?php
session_start();
require_once 'connect.php';

$user_id = $_SESSION['user_id'];

// Get pending friend requests
$stmt = $conn->prepare("SELECT u.id, u.username, u.profile_picture, f.friend_id FROM friends f JOIN users u ON f.user_id = u.id WHERE f.friend_id = ? AND f.status = 'pending'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$requests = [];

while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
$stmt->close();
?>

<h3>Friend Requests</h3>

<?php if (empty($requests)): ?>
    <p>You have no pending friend requests.</p>
<?php else: ?>
    <ul>
        <?php foreach ($requests as $request): ?>
            <li>
                <img src="<?php echo htmlspecialchars($request['profile_picture']); ?>" alt="Profile Picture">
                <?php echo htmlspecialchars($request['username']); ?>
                <form action="respond_friend_request.php" method="POST">
                    <input type="hidden" name="friend_id" value="<?php echo $request['friend_id']; ?>" />
                    <button type="submit" name="action" value="accept">Accept</button>
                    <button type="submit" name="action" value="deny">Deny</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
