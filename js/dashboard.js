function viewNote(noteId) {
    fetch('view_note.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'note_id=' + noteId
    })
    .then(response => response.json())
    .then(data => {
        // Remove 'selected' class from all notes
        const notes = document.querySelectorAll('.note');
        notes.forEach(note => {
            note.classList.remove('selected');
        });

        // Highlight the selected note
        const selectedNote = document.getElementById(`note-${noteId}`);
        selectedNote.classList.add('selected');

        // Update the top-container with the note details
        document.getElementById('top-container').innerHTML = `<h1>${data.title}</h1><p>${data.content}</p>`;
    })
    .catch(error => console.error('Error:', error));
}
document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-button');
    const form = document.getElementById('note-form');
    const titleInput = document.getElementById('title');
    const contentTextarea = document.getElementById('content');
    const noteIdInput = document.getElementById('note-id');
    const noteButton = document.getElementById('note-button');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const noteId = this.getAttribute('data-id');
            const noteTitle = this.getAttribute('data-title');
            const noteContent = this.getAttribute('data-content');

            noteIdInput.value = noteId;
            titleInput.value = noteTitle;
            contentTextarea.value = noteContent;
            noteButton.innerHTML = '<i class="fas fa-circle-plus"></i> Update Note';

            // Change the form action to update_note.php if required
            form.action = 'update_note.php';
        });
    });

    // Check for the success query parameter and display an alert if present
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        alert('Note updated successfully!');
        // Remove the success query parameter from the URL
        const newUrl = window.location.href.split('?')[0];
        window.history.replaceState({}, document.title, newUrl);
    }
});

