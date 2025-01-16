<?php
require 'connect.php';
// SQL lekérdezés az összes varázslat lekérésére
$sql = "SELECT s.id, s.name, s.level, s.casting_time, r.range_description, s.components, s.duration, s.description, l.spell_list_name, s.source 
        FROM spells s
        JOIN spell_range r ON s.range_id = r.id
        JOIN spell_list l ON s.spell_list_id = l.id";
$result = $conn->query($sql);

// Ellenőrizzük, hogy van-e találat
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Name</th>
                <th>Level</th>
                <th>Casting Time</th>
                <th>Range</th>
                <th>Components</th>
                <th>Duration</th>
                <th>Description</th>
                <th>Spell List</th>
                <th>Source</th>
            </tr>";
    
    // Kiírjuk a találatokat
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["name"] . "</td>
                <td>" . $row["level"] . "</td>
                <td>" . $row["casting_time"] . "</td>
                <td>" . $row["range_description"] . "</td>
                <td>" . $row["components"] . "</td>
                <td>" . $row["duration"] . "</td>
                <td>" . $row["description"] . "</td>
                <td>" . $row["spell_list_name"] . "</td>
                <td>" . $row["source"] . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No spells found";
}

$conn->close();
?>
