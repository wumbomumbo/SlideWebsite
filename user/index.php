<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/navbar.php");
?>
<link rel="stylesheet" href="/css/profile.css">
<title><?= Name; ?> - profile</title>
<div class="container-fluid">
    <div class="row" id="profile-container">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <img id="profile-picture" class="profile-picture-xlarge" alt="profile picture">
                    <h5 class="mt-2"><a id="profile-name" class="highlightonlya" href="/user/?id=0"></a></h5>
                    <div class="user-tag">
                        <a id="profile-usertag" class="highlightonlya" href="/user/?id=0"></a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title">who to follow</h5>
                </div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                        <li class="follow-suggestion">@JohnDoe</li>
                        <li class="follow-suggestion">@JaneDoe</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title">user statistics</h5>
                </div>
                <div class="panel-body">
                    <div class="row text-center">
                        <div class="col-xs-6 col-md-3">
                            <h6>posts</h6>
                            <p id="total-posts" class="text-muted"></p>
                        </div>
                        <div class="col-xs-6 col-md-3">
                            <h6>followers</h6>
                            <p id="followers-count" class="text-muted"></p>
                        </div>
                        <div class="col-xs-6 col-md-3">
                            <h6>following</h6>
                            <p id="following-count" class="text-muted"></p>
                        </div>
                        <div class="col-xs-6 col-md-3">
                            <h6>likes</h6>
                            <p id="likes-count" class="text-muted"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title">timeline</h5>
                </div>
                <div class="panel-body">
                    <ul class="timeline list-group">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<script>
    const id = new URLSearchParams(window.location.search).get('id');
    
    document.addEventListener('DOMContentLoaded', function() {
        loadProfile(id);
        loadPosts('', id);
    });

    function loadProfile() {
        fetch('/api/userprofile?id=' + id)
            .then(response => response.json())
            .then(profile => {
                document.getElementById('profile-name').textContent = profile.name;
                document.getElementById('profile-name').href = '/user/?id=' + profile.userId;
                document.getElementById('profile-usertag').textContent = '@' + profile.userTag;
                document.getElementById('profile-usertag').href = '/user/?id=' + profile.userId;
                document.getElementById('profile-picture').src = profile.profilePictureUrl;
                document.getElementById('total-posts').textContent = profile.totalPosts;
                document.getElementById('followers-count').textContent = profile.followersCount;
                document.getElementById('following-count').textContent = profile.followingCount;
                document.getElementById('likes-count').textContent = profile.likesCount;
            })
            .catch(error => {
                console.error('Error loading profile:', error);
                showErrorBanner('sorry, something went wrong while loading your profile, please try again later.');
            });
    }
</script>

</html>
