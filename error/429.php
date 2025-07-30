<?php
require($_SERVER['DOCUMENT_ROOT'] ."/config/navbar.php");
?>
    <link href="/css/error.css" rel="stylesheet">
    <div class="container-fluid">
    <div class="error-container">
        <div class="error-well">
            <div class="error-heading">429</div>
            <div class="error-message">woah, calm down. please wait a few minutes then try again.</div>
            <p class="home-link"><a href="/home/" class="btn btn-primary">go to home</a></p>
            <p><a onclick="history.back();" class="btn btn-primary">back</a></p>
        </div>
    </div>
    </div>
</body>
</html>