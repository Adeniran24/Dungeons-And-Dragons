<?php
require 'connect.php';

// Fetch data from tables to populate the dropdown menus
$levelQuery = "SELECT 'id', 'level' FROM spell_level"; // Ensure column names match the DB

$castingTimeQuery = "SELECT 'id', 'time' FROM casting_times"; // Ensure column names match the DB

$componentsQuery = "SELECT * FROM components";
$durationQuery = "SELECT * FROM durations";
$sourceQuery = "SELECT * FROM sources";
$spellTypesQuery = "SELECT * FROM spell_types";

// Execute queries
$levels = $conn->query($levelQuery);
$castingTimes = $conn->query($castingTimeQuery);
$components = $conn->query($componentsQuery);
$durations = $conn->query($durationQuery);
$sources = $conn->query($sourceQuery);
$spellTypes = $conn->query($spellTypesQuery);

// Check if the queries are returning any data, if not, display the error message.
if (!$levels) {
    die("Error executing query for levels: " . $conn->error);
}
if (!$castingTimes) {
    die("Error executing query for casting times: " . $conn->error);
}
if (!$components) {
    die("Error executing query for components: " . $conn->error);
}
if (!$durations) {
    die("Error executing query for durations: " . $conn->error);
}
if (!$sources) {
    die("Error executing query for sources: " . $conn->error);
}
if (!$spellTypes) {
    die("Error executing query for spell types: " . $conn->error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required fields exist
    if (isset($_POST['name'], $_POST['level_id'], $_POST['casting_time_id'], $_POST['component_id'], $_POST['duration_id'], $_POST['source_id'], $_POST['spell_type_id'], $_POST['range_id'], $_POST['components'], $_POST['duration'], $_POST['description'], $_POST['spell_list_id'])) {
        
        // Sanitize and retrieve the form values
        $name = $_POST['name'];
        $level_id = $_POST['level_id'];
        $casting_time_id = $_POST['casting_time_id'];
        $component_id = $_POST['component_id'];
        $duration_id = $_POST['duration_id'];
        $source_id = $_POST['source_id'];
        $spell_type_id = $_POST['spell_type_id'];
        $range_id = $_POST['range_id'];
        $components_text = $_POST['components'];
        $duration = $_POST['duration'];
        $description = $_POST['description'];
        $spell_list_id = $_POST['spell_list_id'];

        // Insert the new spell into the database
        $sql = "INSERT INTO spells (name, level_id, casting_time_id, component_id, duration_id, source_id, spell_type_id, range_id, components, duration, description, spell_list_id) 
                VALUES ('$name', '$level_id', '$casting_time_id', '$component_id', '$duration_id', '$source_id', '$spell_type_id', '$range_id', '$components_text', '$duration', '$description', '$spell_list_id')";

        if ($conn->query($sql) === TRUE) {
            echo "New spell added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Please fill in all required fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Spell</title>
</head>
<body>

<h2>Add a New Spell</h2>
<form method="POST" action="add_spell.php">

    <!-- Spell Name -->
    <label for="name">Spell Name:</label><br>
    <input type="text" id="name" name="name" required><br><br>

    <!-- Spell Level -->
    <label for="level_id">Level:</label><br>
    <select id="level_id" name="level_id" required>
        <?php 
        if ($levels->num_rows > 0) {
            while ($level = $levels->fetch_assoc()) { 
                echo "<option value='" . $level['id'] . "'>" . $level['level_name'] . "</option>";
            }
        } else {
            echo "<option>No levels available</option>";
        }
        ?>
    </select><br><br>

    <!-- Casting Time -->
    <label for="casting_time_id">Casting Time:</label><br>
    <select id="casting_time_id" name="casting_time_id" required>
        <?php 
        if ($castingTimes->num_rows > 0) {
            while ($castingTime = $castingTimes->fetch_assoc()) { 
                echo "<option value='" . $castingTime['id'] . "'>" . $castingTime['casting_time'] . "</option>";
            }
        } else {
            echo "<option>No casting times available</option>";
        }
        ?>
    </select><br><br>

    <!-- Components -->
    <label for="component_id">Component:</label><br>
    <select id="component_id" name="component_id" required>
        <?php 
        if ($components->num_rows > 0) {
            while ($component = $components->fetch_assoc()) { 
                echo "<option value='" . $component['id'] . "'>" . $component['component'] . "</option>";
            }
        } else {
            echo "<option>No components available</option>";
        }
        ?>
    </select><br><br>

    <!-- Duration -->
    <label for="duration_id">Duration:</label><br>
    <select id="duration_id" name="duration_id" required>
        <?php 
        if ($durations->num_rows > 0) {
            while ($duration = $durations->fetch_assoc()) { 
                echo "<option value='" . $duration['id'] . "'>" . $duration['duration'] . "</option>";
            }
        } else {
            echo "<option>No durations available</option>";
        }
        ?>
    </select><br><br>

    <!-- Source -->
    <label for="source_id">Source:</label><br>
    <select id="source_id" name="source_id" required>
        <?php 
        if ($sources->num_rows > 0) {
            while ($source = $sources->fetch_assoc()) { 
                echo "<option value='" . $source['id'] . "'>" . $source['source_name'] . "</option>";
            }
        } else {
            echo "<option>No sources available</option>";
        }
        ?>
    </select><br><br>

    <!-- Spell Type -->
    <label for="spell_type_id">Spell Type:</label><br>
    <select id="level_id" name="level_id" required>
    <?php 
    if ($levels->num_rows > 0) {
        while ($level = $levels->fetch_assoc()) { 
            echo "<option value='" . $level['id'] . "'>" . $level['level_name'] . "</option>";
        }
    } else {
        echo "<option>No levels available</option>";
    }
    ?>
</select><br><br>

    <!-- Other Fields -->
    <label for="range_id">Range:</label><br>
    <select id="range_id" name="range_id" required>
        <option value="0">Self</option>
        <option value="1">Touch (5 feet)</option>
        <option value="2">10 feet</option>
        <option value="3">15 feet</option>
        <option value="4">20 feet</option>
        <option value="5">30 feet</option>
        <option value="6">40 feet</option>
        <option value="7">60 feet</option>
        <option value="8">100 feet</option>
        <option value="9">120 feet</option>
        <option value="10">150 feet</option>
        <option value="11">200 feet</option>
        <option value="12">300 feet</option>
      
        
    </select><br><br>

    <label for="components">Components Text:</label><br>
    <input type="text" id="components" name="components" required><br><br>

    <label for="duration">Duration Text:</label><br>
    <input type="text" id="duration" name="duration" required><br><br>

    <label for="description">Description:</label><br>
    <textarea id="description" name="description" required></textarea><br><br>

    <label for="spell_list_id">Spell List (ID):</label><br>
    <input type="number" id="spell_list_id" name="spell_list_id" required><br><br>

    <input type="submit" value="Add Spell">

</form>

</body>
</html>
