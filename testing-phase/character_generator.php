<?php
require '../connect.php';

if (isset($_GET['type']) && isset($_GET['parentId'])) {
    $type = $_GET['type'];
    $parentId = intval($_GET['parentId']);

    if ($type === 'subclass_id') {
        $sql = "SELECT id, name FROM subclasses WHERE class_id = ?";
    } elseif ($type === 'subrace_id') {
        $sql = "SELECT id, name FROM subraces WHERE race_id = ?";
    } else {
        echo json_encode([]);
        exit;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $parentId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    $stmt->close();
}

$conn->close();
?>
