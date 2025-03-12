<?php
include 'connect.php';

if (isset($_GET["race_id"])) {
    $race_id = intval($_GET["race_id"]); // Ensure it's an integer
    $sql = "SELECT id, name FROM subraces WHERE race_id = $race_id";
    $result = $conn->query($sql);

    $subraces = [];
    while ($row = $result->fetch_assoc()) {
        $subraces[] = $row;
    }

    echo json_encode($subraces);
} else {
    echo json_encode([]); // Return an empty array if no race_id is given
}

$conn->close();
?>
