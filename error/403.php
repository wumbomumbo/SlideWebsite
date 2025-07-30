<?php
require($_SERVER['DOCUMENT_ROOT'] ."/config/navbar.php");
?>
    <link href="/css/error.css" rel="stylesheet">
    <title><?=Name;?> - 403</title>
    <div class="container-fluid">
    <div class="error-container">
        <div class="error-well">
            <div class="error-heading">403</div>
            <div class="error-message">looks like this page is off limits.</div>
            <p class="home-link"><a href="/home/" class="btn btn-primary">go to home</a></p>
            <p><a onclick="history.back();" class="btn btn-primary">back</a></p>
        </div>
    </div>
    </div>
</body>
</html>