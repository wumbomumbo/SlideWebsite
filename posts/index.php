<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/navbar.php");
$postid = isset($_GET['id']) ? intval($_GET['id']) : null;
if ($postid === null) {
    die(header("Location: /home/"));
} else {
    if (doesPostExist($con, $postid) === false) {
        die(header("Location: /home/"));
    }
}
?>
<title><?= Name; ?> - post</title>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 post-container-box">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="post-container">
                        <!-- Post content will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<link rel="stylesheet" href="/css/posts.css">
<script src="/js/posts.js"></script>
</html>