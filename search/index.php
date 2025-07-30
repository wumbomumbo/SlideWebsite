<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/navbar.php");
if (!$LoggedIn) {
    die(header("Location: /login/"));
}
?>
<title><?=Name;?> - search</title>
<div class="container-fluid">
    <div class="row">

    <div class="col-xs-12 col-sm-6 col-md-3 left-section-sticky">
        <div class="search-sidebar">
            <h4>explore</h4>
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="active"><a href="#">popular</a></li>
                <li role="presentation"><a href="#">users</a></li>
            </ul>
        </div>

            <div class="trending">
                <h4>trending</h4>
                <ul id="trending-list">
                </ul>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6">
            <div class="timeline">
                <div class="searchident" hidden></div>
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
<script src="/js/search.js"></script>
</html>
