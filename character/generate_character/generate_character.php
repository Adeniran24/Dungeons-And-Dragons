<?php
// Adatbázis kapcsolat betöltése
require '../connect.php';
session_start(); // Session elindítása

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id'])) {
    header("Location: ../main/login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // A bejelentkezett felhasználó ID-ja

// Fajok lekérése
$racesQuery = "SELECT * FROM races";
$racesResult = mysqli_query($conn, $racesQuery);
if (!$racesResult) {
    die("Hiba a fajok lekérdezésekor: " . mysqli_error($conn));
}
$races = mysqli_fetch_all($racesResult, MYSQLI_ASSOC);

// Kasztok lekérése
$classesQuery = "SELECT * FROM classes";
$classesResult = mysqli_query($conn, $classesQuery);
if (!$classesResult) {
    die("Hiba a kasztok lekérdezésekor: " . mysqli_error($conn));
}
$classes = mysqli_fetch_all($classesResult, MYSQLI_ASSOC);

// Ha karakter már létezik a session-ban, akkor megjelenítjük
$character = isset($_SESSION['generated_character']) ? $_SESSION['generated_character'] : null;
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karakter Generálása</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            text-align: center;
            padding: 20px;
        }
        .character {
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            max-width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .stat-field {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Karakter Generáló</h1>

    <!-- Ha karakter létezik a session-ban, megjelenítjük -->
    <?php if ($character): ?>
        <div class="character">
            <h2><?php echo htmlspecialchars($character['name']); ?></h2>
            <p>Faj: <?php echo htmlspecialchars($character['race']); ?></p>
            <p>Kaszt: <?php echo htmlspecialchars($character['class']); ?></p>
            <p>Alrészfaj: <?php echo htmlspecialchars($character['subrace']); ?></p>
            <p>Alosztály: <?php echo htmlspecialchars($character['subclass']); ?></p>
        </div>
        <?php unset($_SESSION['generated_character']); ?>
    <?php endif; ?>

    <form method="POST" action="save_character.php">
        <label for="name">Karakter Neve:</label>
        <input type="text" id="name" name="name" value="<?php echo isset($character) ? htmlspecialchars($character['name']) : ''; ?>" placeholder="Add meg a karaktered nevét" required>
        <br><br>

        <label for="race">Faj:</label>
        <select id="race" name="race" required>
            <option value="">Válassz egy fajt</option>
            <?php foreach ($races as $race): ?>
                <option value="<?php echo $race['id']; ?>" <?php echo (isset($character) && $character['race'] == $race['id']) ? 'selected' : ''; ?>>
                    <?php echo $race['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="class">Kaszt:</label>
        <select id="class" name="class" required>
            <option value="">Válassz egy kasztot</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?php echo $class['id']; ?>" <?php echo (isset($character) && $character['class'] == $class['id']) ? 'selected' : ''; ?>>
                    <?php echo $class['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="subrace">Alrészfaj:</label>
        <select id="subrace" name="subrace">
            <option value="">Válassz egy alrészfajt</option>
            <!-- Alrészfajok betöltése -->
        </select>
        <br><br>

        <label for="subclass">Alosztály:</label>
        <select id="subclass" name="subclass">
            <option value="">Válassz egy alosztályt</option>
            <!-- Alosztályok betöltése -->
        </select>
        <br><br>

        <h3>Statok</h3>
        <div class="stat-field">
            <label for="str">Erő (STR):</label>
            <input type="number" id="str" name="str" value="10" min="1" max="20" required>
        </div>

        <div class="stat-field">
            <label for="dex">Dexteritás (DEX):</label>
            <input type="number" id="dex" name="dex" value="10" min="1" max="20" required>
        </div>

        <div class="stat-field">
            <label for="con">Konstrukt (CON):</label>
            <input type="number" id="con" name="con" value="10" min="1" max="20" required>
        </div>

        <div class="stat-field">
            <label for="int">Intelligencia (INT):</label>
            <input type="number" id="int" name="int" value="10" min="1" max="20" required>
        </div>

        <div class="stat-field">
            <label for="wis">Bölcsesség (WIS):</label>
            <input type="number" id="wis" name="wis" value="10" min="1" max="20" required>
        </div>

        <div class="stat-field">
            <label for="cha">Karizma (CHA):</label>
            <input type="number" id="cha" name="cha" value="10" min="1" max="20" required>
        </div>

        <br><br>
        <button type="submit">Karakter Generálása</button>
    </form>

<script>
    // Alrészfajok és alosztályok betöltése
    document.getElementById('race').addEventListener('change', loadSubraces);
    document.getElementById('class').addEventListener('change', loadSubclasses);

    function loadSubraces() {
        const raceId = document.getElementById('race').value;
        const subraceSelect = document.getElementById('subrace');

        if (raceId) {
            fetch(`get_subraces.php?race_id=${raceId}`)
                .then(response => response.json())
                .then(subraces => {
                    subraceSelect.innerHTML = '<option value="">Válassz egy alrészfajt</option>';
                    subraces.forEach(subrace => {
                        const option = document.createElement('option');
                        option.value = subrace.id;
                        option.textContent = subrace.name;
                        subraceSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Hiba történt alrészfajok betöltésekor:', error));
        } else {
            subraceSelect.innerHTML = '<option value="">Válassz egy alrészfajt</option>';
        }
    }

    function loadSubclasses() {
        const classId = document.getElementById('class').value;
        const subclassSelect = document.getElementById('subclass');

        if (classId) {
            fetch(`get_subclasses.php?class_id=${classId}`)
                .then(response => response.json())
                .then(subclasses => {
                    subclassSelect.innerHTML = '<option value="">Válassz egy alosztályt</option>';
                    subclasses.forEach(subclass => {
                        const option = document.createElement('option');
                        option.value = subclass.id;
                        option.textContent = subclass.name;
                        subclassSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Hiba történt alosztályok betöltésekor:', error));
        } else {
            subclassSelect.innerHTML = '<option value="">Válassz egy alosztályt</option>';
        }
    }
</script>

</body>
</html>
