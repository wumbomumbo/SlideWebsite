<?php
require($_SERVER['DOCUMENT_ROOT'] ."/config/navbar.php");
?>
    <link href="/css/error.css" rel="stylesheet">
    <title><?=Name;?> - 500</title>
    <div class="container-fluid">
    <div class="error-container">
        <div class="error-well">
            <div class="error-heading">500</div>
            <div class="error-message">sorry, this page is down. don't worry, we're working on it.</div>
            <p class="home-link"><a href="/home/" class="btn btn-primary">go to home</a></p>
            <p><a onclick="history.back();" class="btn btn-primary">back</a></p>
        </div>
    </div>
    </div>
</body>
</html>