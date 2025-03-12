<?php
// Start the session (optional)
session_start();

// Connect to the database (adjust credentials as needed)
require_once 'connect.php';


// Check if a race_id is provided in the query string
if (isset($_GET['race_id'])) {
    $raceId = (int) $_GET['race_id'];

    // Query to get subraces for the given race_id
    $stmt = $pdo->prepare("SELECT id, name FROM subraces WHERE race_id = :race_id");
    $stmt->execute(['race_id' => $raceId]);
    
    // Fetch results
    $subraces = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Send the results as JSON
    echo json_encode($subraces);
    exit;
}

// If race_id is not set, send a generic response (optional)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Character Creation</title>
    <script>
        // Function to update subraces based on the selected race
        function updateSubraceOptions() {
            var raceId = document.getElementById('race').value;
            var subraceSelect = document.getElementById('subrace');
            subraceSelect.innerHTML = '<option value="">None</option>'; // Clear previous options

            if (raceId === "") return; // Do nothing if no race is selected

            // Fetch subraces based on the selected race_id
            fetch('generate_character.php?race_id=' + raceId)
                .then(response => response.json()) // Ensure the response is parsed as JSON
                .then(data => {
                    // Check if data is valid
                    if (Array.isArray(data)) {
                        data.forEach(subrace => {
                            var option = document.createElement('option');
                            option.value = subrace.id;
                            option.textContent = subrace.name;
                            subraceSelect.appendChild(option);
                        });
                    } else {
                        console.error('Invalid data format:', data);
                    }
                })
                .catch(error => console.error('Error fetching subraces:', error)); // Handle any errors
        }
    </script>
</head>
<body>
    <h1>Create a Character</h1>

    <form method="POST">
        <label for="name">Character Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="race">Race:</label>
        <select id="race" name="race" onchange="updateSubraceOptions()" required>
            <option value="">Select Race</option>
            <!-- Assuming you have fetched these races from the database -->
            <option value="1">High Elf</option>
            <option value="2">Wood Elf</option>
        </select><br>

        <label for="subrace">Subrace:</label>
        <select id="subrace" name="subrace" required>
            <option value="">None</option>
            <!-- Subraces will be dynamically populated based on selected race -->
        </select><br>

        <label for="class">Class:</label>
        <select id="class" name="class" required>
            <option value="1">Wizard</option>
            <option value="2">Rogue</option>
        </select><br>

        <label for="subclass">Subclass:</label>
        <select id="subclass" name="subclass" required>
            <option value="1">Sorcerer</option>
        </select><br>

        <label for="strength">Strength:</label>
        <input type="number" id="strength" name="strength" required><br>

        <label for="dexterity">Dexterity:</label>
        <input type="number" id="dexterity" name="dexterity" required><br>

        <label for="constitution">Constitution:</label>
        <input type="number" id="constitution" name="constitution" required><br>

        <label for="intelligence">Intelligence:</label>
        <input type="number" id="intelligence" name="intelligence" required><br>

        <label for="wisdom">Wisdom:</label>
        <input type="number" id="wisdom" name="wisdom" required><br>

        <label for="charisma">Charisma:</label>
        <input type="number" id="charisma" name="charisma" required><br>

        <input type="submit" value="Create Character">
    </form>
</body>
</html>
