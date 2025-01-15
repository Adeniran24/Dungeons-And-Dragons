<?php
// Adatbázis kapcsolat betöltése
require 'connect.php';

// Ellenőrizzük, hogy a kaszt id át lett adva
if (isset($_GET['class_id'])) {
    $class_id = (int)$_GET['class_id'];
    
    // Alosztályok lekérdezése
    $query = "SELECT * FROM subclasses WHERE class_id = $class_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $subclasses = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($subclasses); // JSON formátumban visszaadjuk az eredményt
    } else {
        echo json_encode([]); // Ha nincs találat, üres listát küldünk vissza
    }
} else {
    echo json_encode([]); // Ha nincs class_id, üres listát küldünk
}
?>
