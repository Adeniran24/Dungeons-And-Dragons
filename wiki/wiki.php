<?php
include '../session_token.php';
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

    <style>
        .brownish-bg {
            background-color: #8B4513; /* Brownish background color */
            color: white; /* White text for better contrast */
        }
        .brownish-btn {
            background-color: #A0522D; /* Brownish button color */
            border-color: #8B4513; /* Darker brown border */
            color: white; /* White text */
            margin: 5px; /* Add some spacing between buttons */
        }
        .brownish-btn:hover {
            background-color: #8B4513; /* Darker brown on hover */
            border-color: #A0522D;
        }
        .button-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap; /* Allow wrapping if the screen is too small */
            gap: 10px; /* Add spacing between buttons */
            padding: 20px; /* Add some padding around the buttons */
        }
    </style>
</head>
<body class="brownish-bg">

<nav class="navbar navbar-expand-lg navbar-dark">
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
                <a style="display: block; color:yellow;" id="Logged" href="../profile/profil.php">
                    <img class="profKep" id="profkep" src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? '../defaults/profile_picture.jpg'); ?>" alt="Profile Image">
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <?php else: ?>
                <a style="display: block;" id="LogReg" class="btn btn-outline-warning" href="../main/login.php">Login/Register</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="button-row">
        <a href="wikiSubPages/spells/spells.php" class="btn brownish-btn">Spells</a>
        <a href="races.php" class="btn brownish-btn">Races</a>
        <a href="backgrounds.php" class="btn brownish-btn">Backgrounds</a>
        <a href="heroic_chronicle.php" class="btn brownish-btn">Heroic Chronicle</a>
        <a href="classes.php" class="btn brownish-btn">Classes</a>
        <a href="items.php" class="btn brownish-btn">Items</a>
        <a href="feats.php" class="btn brownish-btn">Feats</a>
        <a href="radial_feats.php" class="btn brownish-btn">Radial Feats</a>
        <a href="miscellaneous.php" class="btn brownish-btn">Miscellaneous</a>
        <a href="homebrew.php" class="btn brownish-btn">Homebrew</a>
        <a href="ua.php" class="btn brownish-btn">UA</a>
    </div>
</div>

<footer class="footer mt-auto py-3 brownish-bg">
    <div class="container">
        <span>D&D Ultimate Tools</span>
    </div>
</footer>

</body>
</html>