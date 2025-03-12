<?php
include 'connect.php';

$sql = "SELECT id, name FROM races";
$result = $conn->query($sql);

$races = [];
while ($row = $result->fetch_assoc()) {
    $races[] = $row;
}
echo json_encode($races);

$conn->close();
?>
