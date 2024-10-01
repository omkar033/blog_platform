tinymce.init({
    selector: '#content', // Initialize TinyMCE for the content area
    setup: function(editor) {
        // Set up content change listener to detect changes
        editor.on('keyup change', function() {
            clearTimeout(editor.autoSaveTimer); // Clear any existing timers
            editor.autoSaveTimer = setTimeout(autoSaveDraft, 30000); // Autosave after 30 seconds of inactivity
        });
    }
});

// Function to perform auto-save operation
function autoSaveDraft() {
    var title = document.querySelector('input[name="title"]').value; // Get the title input value
    var content = tinymce.get('content').getContent(); // Get TinyMCE content

    if (!title || !content) {
        console.log('Title or content is empty, not saving draft.');
        return; // Don't save if either title or content is empty
    }

    // Send the AJAX request to autosave.php
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "autosave.php", true); // Set up the request to autosave.php
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Create a callback to process the response
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) { // Check if the request is complete
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    console.log('Draft saved successfully!');
                    displaySaveStatus('Draft saved successfully!');
                } else {
                    console.log('Error: ' + response.message);
                    displaySaveStatus('Error saving draft: ' + response.message);
                }
            } else {
                console.error('Server error during auto-save: ' + xhr.status);
                displaySaveStatus('Server error: Unable to save draft.');
            }
        }
    };

    // Send the data to the server
    xhr.send("title=" + encodeURIComponent(title) + "&content=" + encodeURIComponent(content));
}

// Function to display save status in the UI
function displaySaveStatus(message) {
    var statusElement = document.getElementById('autosave-status');
    if (statusElement) {
        statusElement.textContent = message; // Set the message
        statusElement.style.display = 'block'; // Make the status message visible
        setTimeout(function() {
            statusElement.style.display = 'none'; // Hide after 5 seconds
        }, 5000); // Hide the message after 5 seconds
    }
}

// Optionally: Trigger auto-save immediately when loading the page
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(autoSaveDraft, 30000); // Auto-save 30 seconds after page load
});
