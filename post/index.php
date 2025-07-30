<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/navbar.php");

if (!$LoggedIn) {
    die(header("Location: /login/"));
}

$stringArray = array("what's on your mind?", "what's occuring?", "what's up?", "what's new?");
$randomTitle = $stringArray[array_rand($stringArray)];

?>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $randomTitle; ?></h3>
                </div>
                <div class="panel-body">
                    <div>
                        <div class="form-group">
                            <p class="text-muted small pull-left">
                                characters remaining:
                                <span id="charCount" class="char-count">255</span>
                            </p>
                            <textarea class="form-control post-text-area" id="post_content" placeholder="compose your post..." rows="3" maxlength="255"></textarea>
                        </div>
                        <div class="form-group text-left">
                            <label for="post_image" class="upload-icon">
                                <i class="fa fa-2x fa-image"></i>
                            </label>
                            <input type="file" id="post_image" accept="image/png, image/jpeg, image/gif" style="display: none;">
                            
                            <label for="post_video" class="upload-icon">
                                <i class="fa fa-2x fa-video-camera"></i>
                            </label>
                            <input type="file" id="post_video" accept="video/mp4" style="display: none;">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="submitPost()">post</button>
                        <div class="loader" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/post.js"></script>
<link rel="stylesheet" href="/css/post.css">
</body>
</html>
