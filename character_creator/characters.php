<?php
include 'connect.php';

$sql = "SELECT * FROM characters";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Character List</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>" . $row["name"] . " (" . $row["class"] . ")</strong><br>";
        echo "<a href='character_profile.php?id=" . $row["id"] . "'>View Character</a></p>";
    }
} else {
    echo "No characters found.";
}

$conn->close();
?>
