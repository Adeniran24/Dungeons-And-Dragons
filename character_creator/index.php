<!DOCTYPE html>
<html>
<head>
    <title>D&D Character Creator</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Create Your DnD Character</h2>
    <form action="create_character.php" method="post">
        <label>Name:</label>
        <input type="text" name="name" required><br>

        <label>Class:</label>
        <select name="class" id="class-dropdown" required>
            <option value="">Select Class</option>
        </select><br>

        <label>Subclass:</label>
        <select name="subclass" id="subclass-dropdown">
            <option value="">Select Subclass</option>
        </select><br>

        <label>Level:</label>
        <input type="number" name="level" value="1" required><br>

        <label>Race:</label>
        <select name="race" id="race-dropdown" required>
            <option value="">Select Race</option>
        </select><br>

        <label>Subrace:</label>
        <select name="subrace" id="subrace-dropdown">
            <option value="">Select Subrace</option>
        </select><br>

        <label>Background:</label>
        <input type="text" name="background"><br>

        <label>Alignment:</label>
        <input type="text" name="alignment"><br>

        <label>Strength:</label>
        <input type="number" name="strength" value="10" required><br>

        <label>Dexterity:</label>
        <input type="number" name="dexterity" value="10" required><br>

        <label>Constitution:</label>
        <input type="number" name="constitution" value="10" required><br>

        <label>Intelligence:</label>
        <input type="number" name="intelligence" value="10" required><br>

        <label>Wisdom:</label>
        <input type="number" name="wisdom" value="10" required><br>

        <label>Charisma:</label>
        <input type="number" name="charisma" value="10" required><br>

        <label>Inventory:</label>
        <textarea name="inventory"></textarea><br>

        <input type="submit" value="Create Character">
    </form>

    <script>
        $(document).ready(function() {
    // Load Classes
    $.getJSON("fetch_classes.php", function(data) {
        $.each(data, function(index, value) {
            $("#class-dropdown").append(`<option value="${value.id}">${value.name}</option>`);
        });
    });

    // Load Races
    $.getJSON("fetch_races.php", function(data) {
        $.each(data, function(index, value) {
            $("#race-dropdown").append(`<option value="${value.id}">${value.name}</option>`);
        });
    });

    // Load Subclasses when Class is selected
    $("#class-dropdown").change(function() {
        var class_id = $(this).val();
        $("#subclass-dropdown").html('<option value="">Select Subclass</option>');

        if (class_id) {
            $.getJSON("fetch_subclasses.php?class_id=" + class_id, function(data) {
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#subclass-dropdown").append(`<option value="${value.id}">${value.name}</option>`);
                    });
                } else {
                    $("#subclass-dropdown").html('<option value="">No subclasses available</option>');
                }
            }).fail(function() {
                alert("Error loading subclasses. Check fetch_subclasses.php!");
            });
        }
    });

    // Load Subraces when Race is selected
    $("#race-dropdown").change(function() {
        var race_id = $(this).val();
        $("#subrace-dropdown").html('<option value="">Select Subrace</option>');

        if (race_id) {
            $.getJSON("fetch_subraces.php?race_id=" + race_id, function(data) {
                if (data.length > 0) {
                    $.each(data, function(index, value) {
                        $("#subrace-dropdown").append(`<option value="${value.id}">${value.name}</option>`);
                    });
                } else {
                    $("#subrace-dropdown").html('<option value="">No subraces available</option>');
                }
            }).fail(function() {
                alert("Error loading subraces. Check fetch_subraces.php!");
            });
        }
    });
});
    </script>
</body>
</html>
