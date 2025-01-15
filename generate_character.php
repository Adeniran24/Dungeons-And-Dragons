<?php
require "connect.php";
// Fetch races
$races = $pdo->query("SELECT id, name FROM races")->fetchAll(PDO::FETCH_ASSOC);

// Fetch subraces
$subraces = $pdo->query("SELECT id, name, race_id FROM subraces")->fetchAll(PDO::FETCH_ASSOC);

// Fetch classes
$classes = $pdo->query("SELECT id, name FROM classes")->fetchAll(PDO::FETCH_ASSOC);

// Fetch subclasses
$subclasses = $pdo->query("SELECT id, name, class_id FROM subclasses")->fetchAll(PDO::FETCH_ASSOC);

// Function to get subraces for a given race
function getSubracesForRace($raceId, $subraces) {
    return array_filter($subraces, fn($subrace) => $subrace['race_id'] == $raceId);
}

// Function to get subclasses for a given class
function getSubclassesForClass($classId, $subclasses) {
    return array_filter($subclasses, fn($subclass) => $subclass['class_id'] == $classId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $characterName = $_POST['name'];
    $raceId = (int)$_POST['race'];
    $subraceId = isset($_POST['subrace']) ? (int)$_POST['subrace'] : null;
    $classId = (int)$_POST['class'];
    $subclassId = isset($_POST['subclass']) ? (int)$_POST['subclass'] : null;

    // Validate subrace belongs to race
    if ($subraceId) {
        $validSubraces = getSubracesForRace($raceId, $subraces);
        $validSubraceIds = array_column($validSubraces, 'id');
        if (!in_array($subraceId, $validSubraceIds)) {
            die("Invalid subrace selected for the chosen race.");
        }
    }

    // Validate subclass belongs to class
    if ($subclassId) {
        $validSubclasses = getSubclassesForClass($classId, $subclasses);
        $validSubclassIds = array_column($validSubclasses, 'id');
        if (!in_array($subclassId, $validSubclassIds)) {
            die("Invalid subclass selected for the chosen class.");
        }
    }

    // Insert character into the database
    $stmt = $pdo->prepare(
        "INSERT INTO characters (name, race_id, subrace_id, class_id, subclass_id) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([$characterName, $raceId, $subraceId, $classId, $subclassId]);

    echo "Character successfully created!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Character</title>
    <script>
        function updateSubraces() {
            const raceId = document.getElementById('race').value;
            const subraceSelect = document.getElementById('subrace');
            const subraces = <?php echo json_encode($subraces); ?>;

            subraceSelect.innerHTML = '<option value="">None</option>';
            subraces.forEach(subrace => {
                if (subrace.race_id == raceId) {
                    subraceSelect.innerHTML += `<option value="${subrace.id}">${subrace.name}</option>`;
                }
            });
        }

        function updateSubclasses() {
            const classId = document.getElementById('class').value;
            const subclassSelect = document.getElementById('subclass');
            const subclasses = <?php echo json_encode($subclasses); ?>;

            subclassSelect.innerHTML = '<option value="">None</option>';
            subclasses.forEach(subclass => {
                if (subclass.class_id == classId) {
                    subclassSelect.innerHTML += `<option value="${subclass.id}">${subclass.name}</option>`;
                }
            });
        }
    </script>
</head>
<body>
    <h1>Generate a DnD Character</h1>
    <form method="POST">
        <label for="name">Character Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="race">Race:</label>
        <select id="race" name="race" onchange="updateSubraces()" required>
            <option value="">Select a Race</option>
            <?php foreach ($races as $race): ?>
                <option value="<?php echo $race['id']; ?>"><?php echo $race['name']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="subrace">Subrace:</label>
        <select id="subrace" name="subrace">
            <option value="">None</option>
        </select><br>

        <label for="class">Class:</label>
        <select id="class" name="class" onchange="updateSubclasses()" required>
            <option value="">Select a Class</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="subclass">Subclass:</label>
        <select id="subclass" name="subclass">
            <option value="">None</option>
        </select><br>

        <button type="submit">Generate Character</button>
    </form>
</body>
</html>
