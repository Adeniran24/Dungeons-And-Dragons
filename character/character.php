<?php
session_start(); // Start the session

// Check if the user is logged in by verifying session variables
if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
    // If the user is not logged in, redirect to login page
    header("Location: ../main/login.php");
    exit();
} else {
    // The user is logged in, you can use the session variables
    $is_logged_in = true;
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Optional: verify token if using cookie for added security
    if (isset($_COOKIE['auth_token']) && $_COOKIE['auth_token'] !== $_SESSION['token']) {
        // Invalidate session if the token does not match
        session_unset();
        session_destroy();
        header("Location: ../main/login.php");
        exit();
    }
}

// Now you can use $user_id, $username, and other session variables
?>

<?php
    include '../connect.php';

    //ne töröld ki mert elrontódik a css

    //Ha nincs bejelentkezve, akkor a login.php oldalra irányítjuk



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



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Website</title>
    <link rel="stylesheet" href="../main/index.css">
    <script src="../main/index.js"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>
<body>

<<nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container-fluid">
        <a class="navbar-brand" href="../main/index.php" style="color: rgb(255, 0, 0); background-color: black; padding: 10px 20px; border-radius: 25px; font-family: 'Cinzel', serif; font-weight: bold;">
    D&D Ultimate Tool
</a>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../character/character.php">Characters</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../wiki/wiki.php">Wiki</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../dmtool/dmTools.php">DM Tools</a>
              </li>

            </ul>
            <form class="d-flex">
                <?php if ($is_logged_in): ?>
                <!-- If the user is logged in, the profile button with their username and image will be shown -->
                <a style="display: block; color:yellow;" id="Logged" href="../profile/profil.php" >
                    <!-- Display the user's profile image -->
                    <img class="profKep" id="profkep" 
                        src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? './defaults/profile_picture.jpg'); ?>" alt="Profile Image">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <?php else: ?>
                    <!-- Ha nincs bejelentkezve, akkor a Login/Register gomb jelenik meg -->
                    <a style="display: block;" id="LogReg" class="btn btn-outline-warning" href="../main/login.php">Login/Register</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>
<div class="row" style="margin-bottom:5%">
            <!-- Character List -->
            <div>
                <h3>Your Characters</h3>
                <?php if (empty($characters)): ?>
                <?php else: ?>
                    <ul>
                        <?php foreach ($characters as $character): ?>
                            <div class="col-md-3">
                                <h4><?php echo htmlspecialchars($character['name']); ?></h4>
                                <p>Class: <?php echo htmlspecialchars($character['class']); ?></p>
                                <p>Race: <?php echo htmlspecialchars($character['race']); ?></p>
                                <p>Level: <?php echo htmlspecialchars($character['level']); ?></p>
                                <a href="view_character.php?id=<?php echo htmlspecialchars($character['id']); ?>">View Details</a>
                            </div>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php
        include 'connect.php';
        // Fetch the user's characters
        $characters = [];
        $stmt = $conn->prepare("
            SELECT 
                characters.id, 
                characters.name, 
                characters.image, 
                races.name AS race, 
                classes.name AS class 
            FROM characters
            LEFT JOIN races ON characters.race_id = races.id
            LEFT JOIN classes ON characters.class_id = classes.id
            WHERE characters.user_id = ?
        ");

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "
            <div class='character-card col-md-3'>
                <a href='character_details.php?id={$row['id']}'>
                    <img src='{$row['image']}' alt='{$row['name']}' class='character-image'>
                </a>
                <div class='character-info'>
                    <a href='character_details.php?id={$row['id']}' class='character-name'>{$row['name']}</a>
                    <p class='character-race'>Race: {$row['race']}</p>
                    <p class='character-class'>Class: {$row['class']}</p>
                </div>
            </div>";
        }
        ?>

        <div class="col-md-3" id="plus">
        <a href="generate_character.php"><button class="plus-button"></button><h3>Create Character</h3></a>
        </div>
        <style>
            #plus {
                border: 5px solid #ddd; 
                padding: auto;
                border-radius: 5%;;
                margin-bottom: 6%;
            }
            .plus-button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin-top: 15%;
            width: 15%;
            height: 25%;
            background-color: #007bff; /* Blue background */
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 20px;
            cursor: pointer;
            position: relative;
            }

            .plus-button::before {
            content: '';
            position: absolute;
            width: 30%;
            height: 5%;
            background-color: white; /* White color for the plus sign */
            }

            .plus-button::after {
            content: '';
            position: absolute;
            width: 5%;
            height: 30%;
            background-color: white; /* White color for the plus sign */
            }
        </style>


        <!-- Character List -->
         <style>
            .character-list{
                font-family: 'Roboto', Arial, sans-serif;
                background-color:rgba(26, 26, 29, 0.75);
                color: #f5f5f5;
                margin: 0;
                padding: 0;
                line-height: 1.6;
                display: flex;
            align-items: center; /* Center content horizontally */
            justify-content: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            max-width: 50%;
            margin: 20px auto;
            margin-bottom: 5%;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
            }

            .character-card img.character-image {
                width: 100%;
                height: 200px;
                object-fit: cover;
            }

            .character-info {
                padding: 15px;
                color: #f5f5f5;
            }

            .character-info .character-name {
                font-size: 1.2rem;
                font-weight: bold;
                color: #f05454;
                text-decoration: none;
            }

            .character-info .character-name:hover {
                text-decoration: underline;
            }

            .character-info .character-race,
            .character-info .character-class {
                font-size: 0.9rem;
                color: #a5a5a5;
                margin: 5px 0;
            }
        </style>
    </div>
    


<footer class="footer mt-auto py-3 ">
      <div class="container">
        <span class="">Đ&Đ Ultimate Tools</span>
      </div>
    </footer>
</body>
</html>