<?php
include 'connect.php';

// Ha a formot elküldik
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $race = $_POST['race'];
    $strength = $_POST['strength'];
    $dexterity = $_POST['dexterity'];
    $constitution = $_POST['constitution'];
    $intelligence = $_POST['intelligence'];
    $wisdom = $_POST['wisdom'];
    $charisma = $_POST['charisma'];
    $level = $_POST['level'];
    $class = $_POST['class'];

    // Az SQL lekérdezés a karakter hozzáadásához
    $sql = "INSERT INTO characters (Name, Race, Strength, Dexterity, Constitution, Intelligence, Wisdom, Charisma, Level, Class)
            VALUES ('$name', '$race', $strength, $dexterity, $constitution, $intelligence, $wisdom, $charisma, $level, '$class')";

    if ($conn->query($sql) === TRUE) {
        echo "New character created successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>


<?php
session_start(); // Szesszió indítása

// Ellenőrizd, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['token']))
{
    header ("Location:login.php");
}

?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Website</title>
    <link rel="stylesheet" href="index.css">
    <script src="index.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid">
        <a class="navbar-brand" href="index.php" style="color: rgb(255, 0, 0); background-color: black; padding: 10px 20px; border-radius: 25px; font-family: 'Cinzel', serif; font-weight: bold;">
    D&D Ultimate Tool
</a>          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="character.php">Characters</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="wiki.php">Wiki</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="dmTools.php">DM Tools</a>
              </li>
              
            </ul>
            <form class="d-flex">
                <?php if ($is_logged_in): ?>
                    <!-- Ha be van jelentkezve a felhasználó, a profil gomb jelenik meg -->
                    <a style="display: block;" id="Logged" href="profil.php">
                        <img class="profKep" id="profkep" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQmVq-OmHL5H_5P8b1k306pFddOe3049-il2A&s" alt=""> Profil
                    </a>
                <?php else: ?>
                    <!-- Ha nincs bejelentkezve, akkor a Login/Register gomb jelenik meg -->
                    <a style="display: block;" id="LogReg" class="btn btn-outline-warning" href="login.php">Login/Register</a>
                <?php endif; ?>
            </form>
          </div>
        </div>
      </nav>

      <h2>Új karakter létrehozása</h2>
    <form method="POST" action="">
        <label for="name">Név:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="race">Faj:</label><br>
        <input type="text" id="race" name="race" required><br><br>

        <label for="strength">Erő:</label><br>
        <input type="number" id="strength" name="strength" required><br><br>

        <label for="dexterity">Ügyesség:</label><br>
        <input type="number" id="dexterity" name="dexterity" required><br><br>

        <label for="constitution">Kitartás:</label><br>
        <input type="number" id="constitution" name="constitution" required><br><br>

        <label for="intelligence">Intelligencia:</label><br>
        <input type="number" id="intelligence" name="intelligence" required><br><br>

        <label for="wisdom">Bölcsesség:</label><br>
        <input type="number" id="wisdom" name="wisdom" required><br><br>

        <label for="charisma">Karizma:</label><br>
        <input type="number" id="charisma" name="charisma" required><br><br>

        <label for="level">Szint:</label><br>
        <input type="number" id="level" name="level" required><br><br>

        <label for="class">Osztály:</label><br>
        <input type="text" id="class" name="class" required><br><br>

        <button type="submit">Karakter hozzáadása</button>
    </form>


    


<footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>
</body>
</html>