<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"] ?? '';  
    $class = $_POST["class"] ?? '';  
    $subclass = $_POST["subclass"] ?? '';  
    $race = $_POST["race"] ?? '';  
    $subrace = $_POST["subrace"] ?? '';  
    $level = $_POST["level"] ?? 1;  
    $background = $_POST["background"] ?? '';  
    $alignment = $_POST["alignment"] ?? '';  

    $strength = $_POST["strength"] ?? 10;
    $dexterity = $_POST["dexterity"] ?? 10;
    $constitution = $_POST["constitution"] ?? 10;
    $intelligence = $_POST["intelligence"] ?? 10;
    $wisdom = $_POST["wisdom"] ?? 10;
    $charisma = $_POST["charisma"] ?? 10;

    $ac = 10 + floor($dexterity / 2);
    $initiative = floor($dexterity / 2);
    $speed = 30;
    $hit_points = ($constitution * $level);
    $inventory = $_POST["inventory"] ?? '';

    $sql = "INSERT INTO characters (name, class, subclass, race, subrace, level, background, alignment,
    strength, dexterity, constitution, intelligence, wisdom, charisma, ac, initiative, speed,
    hit_points, inventory) 
    VALUES ('$name', '$class', '$subclass', '$race', '$subrace', '$level', '$background', '$alignment',
    '$strength', '$dexterity', '$constitution', '$intelligence', '$wisdom', '$charisma', 
    '$ac', '$initiative', '$speed', '$hit_points', '$inventory')";

    if ($conn->query($sql) === TRUE) {
        echo "Character created successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
