<?php
include 'connect.php';

if (isset($_GET["class_id"])) {
    $class_id = intval($_GET["class_id"]); // Ensure it's an integer
    $sql = "SELECT id, name FROM subclasses WHERE class_id = $class_id";
    $result = $conn->query($sql);

    $subclasses = [];
    while ($row = $result->fetch_assoc()) {
        $subclasses[] = $row;
    }

    echo json_encode($subclasses);
} else {
    echo json_encode([]); // Return an empty array if no class_id is given
}

$conn->close();
?>
