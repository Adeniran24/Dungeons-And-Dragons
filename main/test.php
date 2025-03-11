<?php
include '../session_token.php';
include '../db_connection.php'; // Include database connection

$sql = "SELECT 
            spells.name, 
            spell_range.description AS range, 
            spells.description, 
            spell_level.level AS level, 
            casting_times.description AS casting_time, 
            components.description AS components, 
            durations.description AS duration, 
            sources.name AS source, 
            spell_types.description AS spell_type 
        FROM spells 
        INNER JOIN spell_range ON spells.range_id = spell_range.id 
        INNER JOIN spell_level ON spells.level_id = spell_level.id 
        INNER JOIN casting_times ON spells.casting_time_id = casting_times.id 
        INNER JOIN components ON spells.component_id = components.id 
        INNER JOIN durations ON spells.duration_id = durations.id 
        INNER JOIN sources ON spells.source_id = sources.id 
        INNER JOIN spell_types ON spells.spell_type_id = spell_types.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Spells</title>
    <link rel="stylesheet" href="index.css">
    <script src="index.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark ">
    <div class="container-fluid">
        <a class="navbar-brand" href="../main/index.php">D&D Ultimate Tool</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../character/character.php">Characters</a></li>
                <li class="nav-item"><a class="nav-link" href="../wiki/wiki.php">Wiki</a></li>
                <li class="nav-item"><a class="nav-link" href="../dmtool/dmTools.php">DM Tools</a></li>
            </ul>
            <form class="d-flex">
                <?php if ($is_logged_in): ?>
                <a id="Logged" href="../profile/profil.php">
                    <img class="profKep" id="profkep" src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? '../defaults/profile_picture.jpg']); ?>" alt="Profile Image">
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <?php else: ?>
                <a id="LogReg" class="btn btn-outline-warning" href="../main/login.php">Login/Register</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4">Spell List</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Range</th>
                <th>Description</th>
                <th>Level</th>
                <th>Casting Time</th>
                <th>Components</th>
                <th>Duration</th>
                <th>Source</th>
                <th>Spell Type</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['range']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['level']); ?></td>
                        <td><?php echo htmlspecialchars($row['casting_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['components']); ?></td>
                        <td><?php echo htmlspecialchars($row['duration']); ?></td>
                        <td><?php echo htmlspecialchars($row['source']); ?></td>
                        <td><?php echo htmlspecialchars($row['spell_type']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9">No spells found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<footer class="footer mt-auto py-3">
    <div class="container">
        <span>D&D Ultimate Tools</span>
    </div>
</footer>

</body>
</html>

<?php
$conn->close();
?>
