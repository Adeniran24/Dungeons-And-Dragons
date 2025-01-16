<?php
require 'connect.php';

// Fetch data for dropdowns
$levelQuery = "SELECT * FROM spell_level";
$levelResult = $conn->query($levelQuery);

$spellListQuery = "SELECT * FROM spell_list";
$spellListResult = $conn->query($spellListQuery);

$rangeQuery = "SELECT * FROM spell_range";
$rangeResult = $conn->query($rangeQuery);

$spellTypeQuery = "SELECT * FROM spell_types";
$spellTypeResult = $conn->query($spellTypeQuery);

// Fetching data for new fields: Casting Time, Components, and Durations
$castingTimeQuery = "SELECT * FROM casting_times";
$castingTimeResult = $conn->query($castingTimeQuery);

$componentQuery = "SELECT * FROM components";
$componentResult = $conn->query($componentQuery);

$durationQuery = "SELECT * FROM durations";
$durationResult = $conn->query($durationQuery);

// Fetch data for spell schools
$schoolQuery = "SELECT * FROM spell_schools";
$schoolResult = $conn->query($schoolQuery);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $level_id = $_POST['level_id'];
    $spell_list_id = $_POST['spell_list_id'];
    $range_id = $_POST['range_id'];
    $casting_time_id = $_POST['casting_time_id'];
    $component_id = $_POST['component_id'];
    $duration_id = $_POST['duration_id'];
    $source_id = $_POST['source_id'];
    $spell_type_id = $_POST['spell_type_id'];
    $school_id = $_POST['school_id'];  // New field for spell school

    $insertQuery = "INSERT INTO spells (name, description, level_id, spell_list_id, range_id, casting_time_id, component_id, duration_id, source_id, spell_type_id, school_id)
                    VALUES ('$name', '$description', '$level_id', '$spell_list_id', '$range_id', '$casting_time_id', '$component_id', '$duration_id', '$source_id', '$spell_type_id', '$school_id')";

    if ($conn->query($insertQuery) === TRUE) {
        echo "New spell added successfully!";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Spell</title>
    <script>
        function addSpellForm() {
            // Create a new form element
            var form = document.createElement('form');
            form.setAttribute('method', 'POST');
            form.setAttribute('action', 'add_spell.php');

            // Add the form fields (these can be replicated from the original form)
            var htmlContent = `
                <label for="name">Spell Name:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="description">Spell Description:</label>
                <textarea id="description" name="description" required></textarea><br><br>

                <label for="level_id">Spell Level:</label>
                <select name="level_id" id="level_id" required>
                    <option value="">Select Level</option>
                    <?php while ($row = $levelResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['level'] ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="spell_list_id">Spell List:</label>
                <select name="spell_list_id" id="spell_list_id" required>
                    <option value="">Select Spell List</option>
                    <?php while ($row = $spellListResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['spell_list_name'] ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="range_id">Range:</label>
                <select name="range_id" id="range_id" required>
                    <option value="">Select Range</option>
                    <?php while ($row = $rangeResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['range_description'] ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="casting_time_id">Casting Time:</label>
                <select name="casting_time_id" id="casting_time_id" required>
                    <option value="">Select Casting Time</option>
                    <?php while ($row = $castingTimeResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['time'] ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="component_id">Component:</label>
                <select name="component_id" id="component_id" required>
                    <option value="">Select Component</option>
                    <?php while ($row = $componentResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['component'] ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="duration_id">Duration:</label>
                <select name="duration_id" id="duration_id" required>
                    <option value="">Select Duration</option>
                    <?php while ($row = $durationResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['duration'] ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="source_id">Source:</label>
                <select name="source_id" id="source_id" required>
                    <option value="">Select Source</option>
                    <?php while ($row = $sourceResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['source_name'] ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="spell_type_id">Spell Type:</label>
                <select name="spell_type_id" id="spell_type_id" required>
                    <option value="">Select Spell Type</option>
                    <?php while ($row = $spellTypeResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['spell_type_name'] ?></option>
                    <?php } ?>
                </select><br><br>

                <label for="school_id">Spell School:</label>
                <select name="school_id" id="school_id" required>
                    <option value="">Select Spell School</option>
                    <?php while ($row = $schoolResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['school_name'] ?></option>
                    <?php } ?>
                </select><br><br>

                <input type="submit" value="Add Spell">
            `;

            form.innerHTML = htmlContent;

            // Append the new form to the body or a specific div
            document.getElementById('formsContainer').appendChild(form);
        }
    </script>
</head>
<body>

<h1>Add New Spell</h1>

<!-- Container to hold the forms -->
<div id="formsContainer"></div>

<!-- Button to add a new form -->
<button type="button" onclick="addSpellForm()">Add Spell Form</button>

</body>
</html>
