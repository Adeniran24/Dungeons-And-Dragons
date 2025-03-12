<?php
include 'connect.php';

if (!isset($_GET["id"])) {
    die("No character ID provided.");
}

$character_id = $_GET["id"];
$sql = "SELECT characters.*, classes.name AS class_name, races.name AS race_name
        FROM characters
        LEFT JOIN classes ON characters.class = classes.id
        LEFT JOIN races ON characters.race = races.id
        WHERE characters.id = $character_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Character not found.");
}

$character = $result->fetch_assoc();

// Function to calculate ability modifier
function abilityModifier($score) {
    return floor(($score - 10) / 2);
}

// Calculate proficiency bonus based on level
function proficiencyBonus($level) {
    return floor((($level - 1) / 4) + 2);
}

$proficiency_bonus = proficiencyBonus($character["level"]); // Add this line to initialize $proficiency_bonus

// Calculate Saving Throws (apply proficiency if the class has it)
$saving_throws = [
    "Strength" => abilityModifier($character["strength"]),
    "Dexterity" => abilityModifier($character["dexterity"]),
    "Constitution" => abilityModifier($character["constitution"]),
    "Intelligence" => abilityModifier($character["intelligence"]),
    "Wisdom" => abilityModifier($character["wisdom"]),
    "Charisma" => abilityModifier($character["charisma"])
];

$saving_throw_proficiencies = ["Strength", "Dexterity"]; // TEMP FIX, should come from the class
foreach ($saving_throw_proficiencies as $save) {
    $saving_throws[$save] += $proficiency_bonus;
}

$sql = "SELECT characters.*, classes.name AS class_name, classes.saving_throws, races.name AS race_name
        FROM characters
        LEFT JOIN classes ON characters.class = classes.id
        LEFT JOIN races ON characters.race = races.id
        WHERE characters.id = $character_id";
$result = $conn->query($sql);
$character = $result->fetch_assoc();

$saving_throw_proficiencies = explode(", ", $character["saving_throws"]); // Convert to array

foreach ($saving_throw_proficiencies as $save) {
    if (isset($saving_throws[$save])) {
        $saving_throws[$save] += $proficiency_bonus;
    }
}

$skill_proficiencies = "Perception, Stealth, Arcana";


/// Skill list with corresponding ability score
$skill_ability_mapping = [
    "Acrobatics" => "dexterity",
    "Animal Handling" => "wisdom",
    "Arcana" => "intelligence",
    "Athletics" => "strength",
    "Deception" => "charisma",
    "History" => "intelligence",
    "Insight" => "wisdom",
    "Intimidation" => "charisma",
    "Investigation" => "intelligence",
    "Medicine" => "wisdom",
    "Nature" => "intelligence",
    "Perception" => "wisdom",
    "Performance" => "charisma",
    "Persuasion" => "charisma",
    "Religion" => "intelligence",
    "Sleight of Hand" => "dexterity",
    "Stealth" => "dexterity",
    "Survival" => "wisdom"
];

// Sample proficient skills (Replace with actual skills from the database)
$proficient_skills = explode(", ", $character["skill_proficiencies"]);
$skills = [
    "Acrobatics" => abilityModifier($character["dexterity"]),
    "Animal Handling" => abilityModifier($character["wisdom"]),
    "Arcana" => abilityModifier($character["intelligence"]),
    "Athletics" => abilityModifier($character["strength"]),
    "Deception" => abilityModifier($character["charisma"]),
    "History" => abilityModifier($character["intelligence"]),
    "Insight" => abilityModifier($character["wisdom"]),
    "Intimidation" => abilityModifier($character["charisma"]),
    "Investigation" => abilityModifier($character["intelligence"]),
    "Medicine" => abilityModifier($character["wisdom"]),
    "Nature" => abilityModifier($character["intelligence"]),
    "Perception" => abilityModifier($character["wisdom"]),
    "Performance" => abilityModifier($character["charisma"]),
    "Persuasion" => abilityModifier($character["charisma"]),
    "Religion" => abilityModifier($character["intelligence"]),
    "Sleight of Hand" => abilityModifier($character["dexterity"]),
    "Stealth" => abilityModifier($character["dexterity"]),
    "Survival" => abilityModifier($character["wisdom"])
];















foreach ($skills as $skill => $value) {
    if (in_array($skill, $proficient_skills)) {
        $skills[$skill] += $proficiency_bonus;
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($character["name"]); ?>'s Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($character["name"]); ?>'s Character Sheet</h1>
    
    <h2>Basic Information</h2>
    <p><strong>Class:</strong> <?php echo htmlspecialchars($character["class_name"]); ?></p>
<p><strong>Race:</strong> <?php echo htmlspecialchars($character["race_name"]); ?></p>

    <p><strong>Level:</strong> <?php echo htmlspecialchars($character["level"]); ?></p>
    <p><strong>Background:</strong> <?php echo htmlspecialchars($character["background"]); ?></p>
    <p><strong>Alignment:</strong> <?php echo htmlspecialchars($character["alignment"]); ?></p>

    <h2>Ability Scores & Modifiers</h2>
    <ul>
        <?php foreach (["Strength", "Dexterity", "Constitution", "Intelligence", "Wisdom", "Charisma"] as $ability) { ?>
            <li><strong><?php echo $ability; ?>:</strong> <?php echo $character[strtolower($ability)]; ?>
                (Modifier: <?php echo abilityModifier($character[strtolower($ability)]); ?>)
            </li>
        <?php } ?>
    </ul>

    <h2>Saving Throws</h2>
    <ul>
        <?php foreach ($saving_throws as $save => $value) { ?>
            <li><strong><?php echo $save; ?>:</strong> <?php echo $value; ?></li>
        <?php } ?>
    </ul>

    <h2>Skills</h2>
<ul>
    <?php foreach ($skills as $skill => $value) { ?>
        <li><strong><?php echo $skill; ?>:</strong> <?php echo $value; ?></li>
    <?php } ?>
</ul>


    <h2>Combat Stats</h2>
    <p><strong>Armor Class (AC):</strong> <?php echo $character["ac"]; ?></p>
    <p><strong>Initiative:</strong> <?php echo abilityModifier($character["dexterity"]); ?></p>
    <p><strong>Speed:</strong> <?php echo $character["speed"]; ?> ft</p>
    <p><strong>Hit Points:</strong> <?php echo $character["hit_points"]; ?></p>

    <h2>Weapons & Attacks</h2>
    <p>Coming Soon...</p>

    <h2>Inventory</h2>
    <p><?php echo nl2br(htmlspecialchars($character["inventory"])); ?></p>
</body>
</html>
