function loadPostById(postId) {
    fetch(`/api/posts?postid=${postId}`)
        .then(response => response.json())
        .then(post => {
            insertPostMetaTags(post);
            displayPost(post);
        })
        .catch(error => {
            console.error('Error fetching post:', error);
            showErrorBanner('failed to fetch the post. please try again later.');
        });
}

function displayPost(post) {
    const postContainer = document.querySelector('.post-container');
    const postElement = createPostElement(post);
    postContainer.appendChild(postElement);
}

document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');
    if (postId) {
        loadPostById(postId);
    } else {
        showErrorBanner('Post ID not provided.');
    }
});