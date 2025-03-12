<?php
// Include connect.php for database connection
require_once '../../connect.php';


// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if user submitted an edit form
if (isset($_POST['edit_note'])) {
    $note_id = $_POST['note_id'];
    $note_content = $_POST['note_content'];

    // Write the updated content back to the .txt file
    file_put_contents("../../game_documents/txt/note_$note_id.txt", $note_content);

    echo "Note updated successfully!";
}

// Get list of notes in the txt directory
$note_files = scandir("../../game_documents/txt/");
$notes = array_diff($note_files, array('.', '..'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Documents - Notes</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Game Documents - Notes</h1>
        
        <h2>Create New Note</h2>
        <form action="game_documents.php" method="POST">
            <textarea name="new_note" placeholder="Write your new note here..." required></textarea>
            <button type="submit" name="create_note">Create Note</button>
        </form>

        <h2>Existing Notes</h2>
        <ul>
            <?php
            // Display existing notes
            foreach ($notes as $note) {
                // Extract note ID (assuming note files are named "note_1.txt", "note_2.txt", etc.)
                $note_id = basename($note, ".txt");

                // Read the note content from the file
                $note_content = file_get_contents("../../game_documents/txt/$note");

                echo "<li>
                        <a href=\"#\" data-note-id=\"$note_id\" class=\"edit-note-link\">$note</a>
                        <form method=\"POST\" class=\"edit-note-form\" id=\"edit-form-$note_id\" style=\"display:none;\">
                            <textarea name=\"note_content\" required>$note_content</textarea>
                            <input type=\"hidden\" name=\"note_id\" value=\"$note_id\">
                            <button type=\"submit\" name=\"edit_note\">Save Changes</button>
                        </form>
                    </li>";
            }
            ?>
        </ul>
    </div>

    <script src="../../js/script.js"></script>
</body>
</html>
