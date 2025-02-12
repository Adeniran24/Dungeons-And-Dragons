<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D Character Sheet</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleSection(id) {
            var section = document.getElementById(id);
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }

        async function submitForm(event) {
            event.preventDefault();
            const formData = new FormData(document.getElementById('characterForm'));
            const data = Object.fromEntries(formData.entries());
            
            try {
                let response = await fetch('/submit-character', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                let result = await response.json();
                alert('Character saved successfully!');
            } catch (error) {
                alert('Error saving character.');
                console.error(error);
            }
        }
    </script>
</head>
<body>
    <form id="characterForm" class="character-sheet" onsubmit="submitForm(event)">
        <section class="header">
            <label>Name: <input type="text" name="character_name" required></label>
            <label>Class: <input type="text" name="class"></label>
            <label>Background: <input type="text" name="background"></label>
            <label>Race: <input type="text" name="race"></label>
            <label>Alignment: <input type="text" name="alignment"></label>
            <label>Level: <input type="number" name="level" min="1"></label>
        </section>

        <section class="combat-stats" style="background-color:lightgreen;">
            <label>Current HP: <input type="number" name="current_hp" min="0"></label>
            <label>Max HP: <input type="number" name="max_hp" min="1"></label>
            <label>Temp HP: <input type="number" name="temp_hp" min="0"></label>
            <label>AC: <input type="number" name="ac" min="0"></label>
            <label>Speed: <input type="number" name="speed" min="0"></label>
            <label>Initiative: <input type="number" name="initiative"></label>
        </section>
        
        <section class="stats" style="background-color:lightblue;">
            <label>Strength: <input type="number" name="strength" min="1"></label>
            <label>Dexterity: <input type="number" name="dexterity" min="1"></label>
            <label>Constitution: <input type="number" name="constitution" min="1"></label>
            <label>Intelligence: <input type="number" name="intelligence" min="1"></label>
            <label>Wisdom: <input type="number" name="wisdom" min="1"></label>
            <label>Charisma: <input type="number" name="charisma" min="1"></label>
        </section>

        
        
        <section class="spells">
            <h3 onclick="toggleSection('cantrips')">Cantrips ▼</h3>
            <div id="cantrips" class="spell-section">
                <label>Spell Name: <input type="text" name="cantrip_name"></label>
                <label>Range: <input type="text" name="cantrip_range"></label>
                <label>Duration: <input type="text" name="cantrip_duration"></label>
            </div>
            <h3 onclick="toggleSection('level1')">1st Level ▼</h3>
            <div id="level1" class="spell-section" style="display:none;">
                <label>Spell Name: <input type="text" name="spell1_name"></label>
                <label>Slots: <input type="number" name="spell1_slots" min="0"></label>
                <label>Duration: <input type="text" name="spell1_duration"></label>
            </div>
        </section>
        
        <button type="submit">Save Character</button>
    </form>
</body>
</html>

<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    padding: 20px;
}

.character-sheet {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 800px;
    margin: auto;
}

section {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.spells h3 {
    cursor: pointer;
    background: #333;
    color: white;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
}

.spell-section {
    background: #eee;
    padding: 10px;
    border-radius: 5px;
    margin-top: 5px;
}

</style>