<?php
include '../session_token.php';
include '../connect.php';
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsXl5i72J4jAhS9VAX6wW8HOeKw8wS3TQ4n6J5G" crossorigin="anonymous"></script>

    <style>
        /* Full-screen background setup */
        body {
            background-image: url('https://i.imgur.com/zCw5x4L.jpg'); /* Dark fantasy background */
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cinzel', serif;
            color: white;
            overflow: hidden;
        }

        /* Container for choice cards */
        .choice-container {
            display: flex;
            justify-content: space-between;
            width: 90%;  /* Space from borders */
            max-width: 1200px;
        }

        /* Card styling */
        .choice-card {
            width: 45%;  /* Slightly smaller */
            height: 80vh;  /* Gives space from top and bottom */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            cursor: pointer;
            background-size: cover;
            background-position: center;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        /* Player (left) */
        .player {
            background-image: url('https://wallpaperaccess.com/full/117905.jpg');
            border: 2px solid rgba(255, 255, 255, 0.2);
            
        }

        /* Dungeon Master (right) */
        .dm {
            background-image: url('https://static0.gamerantimages.com/wordpress/wp-content/uploads/2020/01/DD-Dragon-Vs.-Party.jpg');
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        /* Title */
        .choice-card h1 {
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 10px black;
        }

        /* Description */
        .choice-card p {
            font-size: 1.1rem;
            text-shadow: 2px 2px 10px black;
            max-width: 80%;
        }

        /* Button styling */
        .btn-custom {
            margin-top: 20px;
            font-size: 1.1rem;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            background: linear-gradient(45deg, #b71c1c, #7b1fa2);
            color: white;
            font-weight: bold;
            transition: 0.3s ease-in-out;
            text-decoration: none;
            ;
        }

        .btn-custom:hover {
            background: linear-gradient(45deg, #d32f2f, #9c27b0);
            transform: scale(1.1);
        }

        /* Hover effects */
        .choice-card:hover {
            transform: scale(1.03);
            box-shadow: 0px 0px 25px rgba(255, 0, 0, 0.6);
        }

        body {
            background-image: url("https://th.bing.com/th/id/OIP.hTe-U3ud_3QHRInLVV1HnQHaEK?rs=1&pid=ImgDetMain");
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            /*Tolja le a képet felül*/
            align-items: center;
            justify-content: center;
            font-family: 'Cinzel', serif;
            color: white;
            overflow: hidden;

        }



    </style>
</head>
<body>

<!-- Choice Container -->
<div class="choice-container">
    <!-- Player Side -->
    <div class="choice-card player" onclick="window.location.href='../character/character.php'">
        <h1>I am an Adventurer</h1>
        <p>Seeking the hardest dungeons, the most dangerous monsters, and the greatest treasures. I am ready for any challenge!</p>
        <a href="../character/character.php" class="btn btn-custom">Let's Go!</a>
    </div>

    <!-- DM Side -->
    <div class="choice-card dm" onclick="window.location.href='../dmtool/dmTools.php'">
        <h1>I am a God</h1>
        <p>My followers await my guidance through dark dungeons and fierce battles. Together, we shall conquer all!</p>
        <a href="../dmtool/dmTools.php" class="btn btn-custom">Let's Go!</a>
    </div>
</div>

</body>
</html>
