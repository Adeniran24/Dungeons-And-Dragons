<?php
// Adatbázis kapcsolat betöltése
require 'connect.php';
session_start(); // Session elindítása

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // A bejelentkezett felhasználó ID-ja

// Ha karakter generálás történik, akkor adatok mentése
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ellenőrizzük, hogy a felhasználó minden mezőt kitöltött
    if (!empty($_POST['name']) && !empty($_POST['race']) && !empty($_POST['class'])) {
        // Adatok kinyerése
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $race = mysqli_real_escape_string($conn, $_POST['race']);
        $class = mysqli_real_escape_string($conn, $_POST['class']);
        // Ha van alrészfaj, akkor azt is beállítjuk, ha nincs, NULL értéket adunk
        $subrace = !empty($_POST['subrace']) ? mysqli_real_escape_string($conn, $_POST['subrace']) : NULL;
        // Ha van alosztály, akkor azt is beállítjuk, ha nincs, NULL értéket adunk
        $subclass = !empty($_POST['subclass']) ? mysqli_real_escape_string($conn, $_POST['subclass']) : NULL;

        // Karakter mentése az adatbázisba
        $sql = "INSERT INTO characters (name, race_id, class_id, subrace_id, subclass_id, user_id) 
                VALUES ('$name', '$race', '$class', '$subrace', '$subclass', '$user_id')";

        if (mysqli_query($conn, $sql)) {
            // Ha sikerült menteni, session-ban tároljuk a karakter adatait
            $_SESSION['generated_character'] = [
                'name' => $name,
                'race' => $race,
                'class' => $class,
                'subrace' => $subrace,
                'subclass' => $subclass
            ];
            echo "Karakter sikeresen hozzáadva!";
            header ("Location: character.php");
        } else {
            echo "Hiba történt a karakter hozzáadásakor: " . mysqli_error($conn);
        }
    } else {
        echo "Minden mezőt ki kell tölteni!";
    }
}
?>
