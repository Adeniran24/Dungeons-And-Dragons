<?php
include 'connect.php';

$sql = "SELECT id, name FROM classes";
$result = $conn->query($sql);

$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}
echo json_encode($classes);

$conn->close();
?>
