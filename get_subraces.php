<?php
// Adatbázis kapcsolat betöltése
require 'connect.php';

// Ellenőrizzük, hogy a faj id át lett adva
if (isset($_GET['race_id'])) {
    $race_id = (int)$_GET['race_id'];
    
    // Alrészfajok lekérdezése
    $query = "SELECT * FROM subraces WHERE race_id = $race_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $subraces = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($subraces); // JSON formátumban visszaadjuk az eredményt
    } else {
        echo json_encode([]); // Ha nincs találat, üres listát küldünk vissza
    }
} else {
    echo json_encode([]); // Ha nincs race_id, üres listát küldünk
}
?>
