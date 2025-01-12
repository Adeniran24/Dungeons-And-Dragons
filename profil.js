// Description: JavaScript file for the profile page.
    // Open the modal
    function openModal() {
        document.getElementById('pictureModal').style.display = 'block';
    }

    // Close the modal
    function closeModal() {
        document.getElementById('pictureModal').style.display = 'none';
    }

    // Select an image and set it as the profile picture
    function selectImage(imagePath) {
        if (confirm('Are you sure you want to select this picture?')) {
            // Use AJAX to save the selected picture to the session or database
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'save_profile_picture.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (this.status === 200) {
                    // Refresh the page to reflect the new profile picture
                    location.reload();
                }
            };
            xhr.send('profile_picture=' + encodeURIComponent(imagePath));
        }
    }
