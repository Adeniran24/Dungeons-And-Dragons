<?php
// Include the database connection file
include 'db_connection.php';

// Function to fetch options from a table
function fetchOptions($conn, $table) {
    $query = "SELECT id, name FROM $table";
    $result = mysqli_query($conn, $query);
    $options = '';

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='{$row['id']}'>{$row['name']}</option>";
        }
    } else {
        $options .= "<option value=''>Error loading options</option>";
    }

    return $options;
}

// Fetch data for dropdowns
$levels = fetchOptions($conn, 'spell_levels');
$castingTimes = fetchOptions($conn, 'casting_times');
$components = fetchOptions($conn, 'components');
$durations = fetchOptions($conn, 'durations');
$sources = fetchOptions($conn, 'sources');
$spellTypes = fetchOptions($conn, 'spell_types');
?>
