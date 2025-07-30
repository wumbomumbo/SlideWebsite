let page = 1;
let loadingPosts = false;
allPostsLoaded = false;

function showErrorBanner(message) {
    const errorBanner = document.getElementById('error-banner');
    errorBanner.textContent = message;
    errorBanner.style.display = 'block';

    setTimeout(() => {
        errorBanner.classList.add('slideUp');

        setTimeout(() => {
            errorBanner.style.display = 'none';
            errorBanner.classList.remove('slideUp');
        }, 500);
    }, 3000);
}

function showSuccessBanner(message) {
    const errorBanner = document.getElementById('success-banner');
    errorBanner.textContent = message;
    errorBanner.style.display = 'block';

    setTimeout(() => {
        errorBanner.classList.add('slideUp');

        setTimeout(() => {
            errorBanner.style.display = 'none';
            errorBanner.classList.remove('slideUp');
        }, 500);
    }, 3000);
}

function postRedirect() {
    window.location.href = "/post/";
}

function formatCount(count) {
    if (count < 1000) {
        return count.toString();
    } else if (count < 1000000) {
        return (count / 1000).toFixed(1) + 'K';
    } else if (count < 1000000000) {
        return (count / 1000000).toFixed(1) + 'M';
    } else {
        return (count / 1000000000).toFixed(1) + 'B';
    }
}

function formatPostDate(date) {
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);

    if (diffInSeconds < 60) {
        return `${diffInSeconds} seconds ago`;
    } else if (diffInSeconds < 3600) {
        const diffInMinutes = Math.floor(diffInSeconds / 60);
        return `${diffInMinutes} minutes ago`;
    } else if (diffInSeconds < 86400) {
        const diffInHours = Math.floor(diffInSeconds / 3600);
        return `${diffInHours} hours ago`;
    } else if (diffInSeconds < 2592000) {
        const diffInDays = Math.floor(diffInSeconds / 86400);
        return `${diffInDays} days ago`;
    } else {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString(undefined, options);
    }
}
document.addEventListener('DOMContentLoaded', function () {
    var logoutLink = document.getElementById('logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function () {
            fetch('/logout/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success === true) {
                        window.location.href = '/';
                    } else {
                        showErrorBanner('something went wrong on our end while logging out. please try again later.');
                    }
                })
                .catch(error => {
                    showErrorBanner('something went wrong on our end while logging out. please try again later.');
                });
        });
    }
});
function loadTrendingTopics() {
    showLoadingIndicator('trending');
    fetch('/api/trending-topics')
        .then(response => response.json())
        .then(trendingTopics => {
            hideLoadingIndicator('trending');
            displayTrendingTopics(trendingTopics);
        })
        .catch(error => {
            hideLoadingIndicator('trending');
            console.error('Error fetching trending topics:', error);
            showErrorBanner('failed to fetch trending topics. please try again later.');
        });
}

function displayTrendingTopics(trendingTopics) {
    const trendingList = document.getElementById('trending-list');

    trendingList.innerHTML = '';

    if (trendingTopics.length === 0) {
        const noTrendingMessage = document.createElement('div');
        noTrendingMessage.classList.add('no-trending-message');
        noTrendingMessage.textContent = "nothing seems to be trending right now, let's change that.";
        trendingList.appendChild(noTrendingMessage);
        return;
    }

    trendingTopics.forEach(topic => {
        const listItem = document.createElement('li');
        listItem.classList.add('trending-item');


        const link = document.createElement('a');
        link.href = `/search/?srch-term=${encodeURIComponent(topic.name)}`;
        link.textContent = `${escapeString(topic.name)} (${formatCount(topic.mentionCount)} mentions)`;
        link.classList.add('highlightonlya');

        listItem.appendChild(link);

        trendingList.appendChild(listItem);
    });
}


function loadPosts(searchTerm = '', userid = 0) {
    if (loadingPosts || allPostsLoaded) {
        return;
    }

    loadingPosts = true;

    const url = searchTerm ? `/api/posts?page=${page}&searchquery=${encodeURIComponent(searchTerm)}` : `/api/posts?page=${page}` + (userid ? `&userid=${userid}` : '');

    fetch(url)
        .then(response => response.json())
        .then(posts => {
            displayPosts(posts);
            loadingPosts = false;

            if (posts.length === 0) {
                allPostsLoaded = true;
            }
        })
        .catch(error => {
            showErrorBanner('failed to fetch posts. please try again later.');
            console.error('Error fetching posts:', error);
            loadingPosts = false;
        });
}

function displayPosts(posts) {
    const timeline = document.querySelector('.timeline');
    var searching = document.querySelector('.searchident') !== null;

    if (posts.length === 0) {
        const noPostsMessage = document.createElement('div');
        noPostsMessage.classList.add('no-posts-message');
        if (searching) {
            noPostsMessage.textContent = "nothing here, try the buttons on the left.";
        } else {
            noPostsMessage.textContent = "you've reached the end of the timeline.";
        }
        timeline.appendChild(noPostsMessage);
        return;
    }

    const noPostsMessage = timeline.querySelector('.no-posts-message');
    if (noPostsMessage) {
        noPostsMessage.remove();
    }

    posts.forEach(post => {
        const postElement = createPostElement(post);
        timeline.appendChild(postElement);
    });

    page++;
}

function showLoadingIndicator(section) {
    const loaderElement = document.createElement('span');
    loaderElement.classList.add('loader');

    const trendingList = document.getElementById('trending-list');
    trendingList.appendChild(loaderElement);
}

function hideLoadingIndicator(section) {
    const loaderElement = document.getElementById('trending-list').querySelector('.loader');
    if (loaderElement) {
        loaderElement.remove();
    }
}

function escapeString(str) {
    return String(str).replace(/[^\w. ]/gi, function (c) {
        return '&#' + c.charCodeAt(0) + ';';
    });
}

function createPostElement(post) {
    const postDiv = document.createElement('div');
    postDiv.classList.add('post');
    postDiv.dataset.id = post.id;
    postDiv.dataset.reposts = post.reposts;

    const postDate = new Date(post.timestamp * 1000);
    const formattedDate = formatPostDate(postDate);

    postDiv.innerHTML = `
        <div class="post-container">
            <div class="post-body">
                <div class="post-header">
                    <img src="${post.userpicture}" alt="avatar" class="post-avatar">
                    <div>
                        <div class="post-username"><a class="highlightonlya" href="/user/?id=${post.userid}">${escapeString(post.username)}</a></div>
                        <div class="post-usertag"><a class="highlightonlya" href="/user/?id=${post.userid}">@${escapeString(post.usertag)}</a></div>
                        <div class="post-date">${formattedDate}</div>
                    </div>
                </div>
                <p class="no-margin">${escapeString(post.text)}</p>
                ${post.image ? `<img src="${post.image}" alt="image" class="post-image">` : ''}
                ${post.video ? `<video src="${post.video}" controls class="post-video"></video>` : ''}
                <div class="post-actions">
                    <div class="post-action heart-post-action">
                        <span class="fa fa-heart${post.hasLiked ? '' : '-o'} icon post-action-icons ${post.hasLiked ? 'liked' : ''}" onclick="toggleLike(${post.id})"></span>
                        <span class="like-count ${post.hasLiked ? 'redtext' : ''}" data-likes="${post.likes}">${formatCount(post.likes)}</span>
                    </div>
                    <div class="post-action repost-post-action">
                        <span class="fa fa-repeat icon post-action-icons ${post.repost ? 'reposted' : ''}" onclick="toggleRepost(${post.id})"></span>
                        <span class="repost-count" data-reposts="${post.reposts}">${formatCount(post.reposts)}</span>
                    </div>
                    <div class="post-action dropdown-post-action">
                        <div class="dropdown">
                            <span class="fa fa-bars icon post-action-icons dropdown-toggle" data-toggle="dropdown"></span>
                            <ul class="dropdown-menu">
                                <li><a onclick="copyLink(${post.id})">copy link</a></li>
                                ${navigator.share ? '<li><a onclick="sharePost(' + post.id + ')">share post</a></li>' : ''}
                                ${post.areCreator ? '<li><a>delete post</a></li>' : ''}
                                ${post.areCreator ? '<li><a>edit post</a></li>' : ''}
                                <li role="separator" class="divider"></li>
                                ${post.areFollowing ? '<li><a onclick="unfollowUser(' + post.userid + ')">unfollow @' + escapeString(post.usertag) + '</a></li>' : ''}
                                ${!post.areFollowing && !post.areCreator ? '<li><a onclick="followUser(' + post.userid + ')">follow @' + escapeString(post.usertag) + '</a></li>' : ''}
                                ${!post.areCreator ? '<li><a>block @' + escapeString(post.usertag) + '</a></li>' : ''}
                                ${!post.areCreator ? '<li><a>mute @' + escapeString(post.usertag) + '</a></li>' : ''}
                                ${!post.areCreator ? '<li role="separator" class="divider"></li>' : ''}
                                <li><a>bookmark</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    return postDiv;
}

function insertPostMetaTags(post) {
    const metaTags = document.createElement('meta');
    metaTags.name = 'description';
    metaTags.content = escapeString(post.text);
    document.head.appendChild(metaTags);

    const usertagMetaTags = document.createElement('meta');
    usertagMetaTags.name = 'og:username';
    usertagMetaTags.content = escapeString(post.usertag);
    document.head.appendChild(usertagMetaTags);

    const titleMetaTags = document.createElement('meta');
    titleMetaTags.name = 'og:title';
    titleMetaTags.content = escapeString(post.username);
    document.head.appendChild(titleMetaTags);

    const urlMetaTags = document.createElement('meta');
    urlMetaTags.name = 'og:url';
    urlMetaTags.content = window.location.href;
    document.head.appendChild(urlMetaTags);

    if (post.image) {
        const imageMetaTags = document.createElement('meta');
        imageMetaTags.name = 'og:image';
        imageMetaTags.content = post.image;
        document.head.appendChild(imageMetaTags);
    }

    if (post.video) {
        const videoMetaTags = document.createElement('meta');
        videoMetaTags.name = 'og:video';
        videoMetaTags.content = post.video;
        document.head.appendChild(videoMetaTags);
    }
}

function toggleLike(postId) {
    const likeCountElement = document.querySelector(`.post[data-id="${postId}"] .like-count`);
    const likeIcon = document.querySelector(`.post[data-id="${postId}"] .fa-heart, .post[data-id="${postId}"] .fa-heart-o`);

    if (likeIcon.classList.contains('fa-heart-o')) {
        likePost(postId);
        likeIcon.classList.remove('fa-heart-o');
        likeIcon.classList.add('fa-heart');
        likeIcon.classList.add('liked');
        likeCountElement.classList.add('redtext');
        let likeCount = parseInt(likeCountElement.dataset.likes, 10);
        likeCount++;
        likeCountElement.textContent = formatCount(likeCount);
        likeCountElement.dataset.likes = likeCount;
    } else {
        // Unlike the post
        unlikePost(postId);
        likeIcon.classList.remove('fa-heart');
        likeIcon.classList.remove('liked');
        likeIcon.classList.add('fa-heart-o');
        likeCountElement.classList.remove('redtext');
        let likeCount = parseInt(likeCountElement.dataset.likes, 10);
        likeCount--;
        likeCountElement.textContent = formatCount(likeCount);
        likeCountElement.dataset.likes = likeCount;
    }
}

function likePost(postId) {
    fetch(`/api/likepost?id=${postId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success === false) {
                throw new Error('Like post request failed');
            }
        })
        .catch(error => {
            console.error('Error liking post:', error);
            showErrorBanner('failed to like post. please try again later.');
        });
}

function unlikePost(postId) {
    fetch(`/api/unlikepost?id=${postId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success === false) {
                throw new Error('Unlike post request failed');
            }
            console.log('Unliked post:', data);
        })
        .catch(error => {
            console.error('Error unliking post:', error);
            showErrorBanner('failed to unlike post. please try again later.');
        });
}

function toggleRepost(postId) {
    const repostCountElement = document.querySelector(`.post[data-id="${postId}"] .repost-count`);
    const repostIcon = document.querySelector(`.post[data-id="${postId}"] .fa-repeat`);

    if (repostIcon.classList.contains('reposted')) {
        // Do nothing
    } else {
        repostPost(postId);
        repostIcon.classList.add('reposted');
        repostCountElement.style.color = '#00b700';
        let repostCount = parseInt(repostCountElement.dataset.reposts, 10);
        repostCount++;
        repostCountElement.textContent = formatCount(repostCount);

        repostCountElement.dataset.reposts = repostCount;
    }
}

function repostPost(postId) {
    fetch(`/api/posts/${postId}/repost`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => console.log('Reposted post:', data))
        .catch(error => {
            console.error('Error reposting post:', error);
            showErrorBanner('failed to repost. please try again later.');
        });
}

function copyLink(postId) {
    const link = window.location.origin + '/post/?id=' + postId;
    navigator.clipboard.writeText(link);
}

function sharePost(postId) {
    try {
        navigator.share({ url: window.location.origin + '/post/?id=' + postId });
    } catch (error) {
        console.error('Error sharing post:', error);
        showErrorBanner('something went wrong while trying to share. please try again later.');
    }
}

function followUser(userId) {
    fetch(`/api/followuser?id=${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success === false) {
                throw new Error('Follow user request failed');
            }
            console.log('Followed user:', data);
        })
        .catch(error => {
            console.error('Error following user:', error);
            showErrorBanner('failed to follow user. please try again later.');
        });
}

function unfollowUser(userId) {
    fetch(`/api/unfollowuser?id=${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success === false) {
                throw new Error('Unfollow user request failed');
            }
            console.log('Unfollowed user:', data);
        })
        .catch(error => {
            console.error('Error unfollowing user:', error);
            showErrorBanner('failed to unfollow user. please try again later.');
        });
}

function checkAdBlocker() {
    if (typeof window.google_ad_modifications === "undefined") {
        if (!getAdblockCookie("adBlockModalShown")) {
            $('#adBlockModal').modal('show');
            setAdblockCookie("adBlockModalShown", "true", 1);
        }
    }
}

function setAdblockCookie(cookieName, cookieValue, expirationDays) {
    var d = new Date();
    d.setTime(d.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
}

function getAdblockCookie(cookieName) {
    var name = cookieName + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var cookieArray = decodedCookie.split(';');
    for (var i = 0; i < cookieArray.length; i++) {
        var cookie = cookieArray[i];
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) == 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    return "";
}

window.onload = checkAdBlocker;