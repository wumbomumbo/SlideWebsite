<?php
require($_SERVER['DOCUMENT_ROOT'] ."/config/navbar.php");
if(!$LoggedIn){
    die(header("Location: /login/"));
}
?>
<title><?=Name;?> - home</title>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3 left-section-sticky">
                <div class="profile">
                    <div class="profile-info"><a class="highlightonlya" href="/user/?id=<?php echo $sessionloggedin; ?>"><?php echo getUsername($con, $sessionloggedin,true); ?></a></div>
                    <div class="user-tag"><a class="highlightonlya" href="/user/?id=<?php echo $sessionloggedin; ?>">@<?php echo getUserTag($con, $sessionloggedin,true); ?></a></div>
                    <img src="<?php echo getProfilePicture($con, $sessionloggedin); ?>"
                        alt="Profile Picture" class="profile-picture-large">
                </div>

                <div class="trending">
                    <h4>trending</h4>
                    <ul id="trending-list">

                    </ul>
                </div>

            </div>

            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="timeline">
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3 right-section-sticky">
                <div class="who-to-follow">
                    <h4>who to follow</h4>
                    <ul class="list-unstyled">
                        <li class="follow-suggestion">@JohnDoe</li>
                        <li class="follow-suggestion">@JaneDoe</li>
                    </ul>
                </div>
                <div class="additional-options">
                <ul class="list-unstyled horizontal-options">
                    <li>© <?php echo date("Y")." "; echo Name;?></li>
                    <li><a href="/terms-of-service/">terms</a></li>
                    <li><a href="/privacy-policy/">privacy</a></li>
                </ul>
            </div>
            </div>
        </div>
    </div>
</body>
<script src="/js/home.js"></script>
</html>