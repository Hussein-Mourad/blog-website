function handleAddReaction(button) {
    const postId = button.getAttribute('data-post-id');
    const reactionType = button.getAttribute('data-reaction-type');
    console.log(postId, reactionType);

    fetch('./forms/reactions/handleCreateReaction.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify("test")
    })
    .then(response => response.json())
    .then(data => {
        console.log('Server Response:', data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
