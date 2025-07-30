$(document).ready(function () {
    loadPosts();
    loadTrendingTopics();
});

window.addEventListener('scroll', function () {
    if (!allPostsLoaded && window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
        loadPosts();
    }
});