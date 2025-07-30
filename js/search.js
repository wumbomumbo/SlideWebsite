$(document).ready(function () {
    const searchParams = new URLSearchParams(window.location.search);
    const searchTerm = searchParams.get('srch-term');
    loadPosts(searchTerm);
    loadTrendingTopics();
});

window.addEventListener('scroll', function () {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
        loadPosts();
    }
});