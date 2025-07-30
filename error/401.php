<?php
require($_SERVER['DOCUMENT_ROOT'] ."/config/navbar.php");
?>
    <link href="/css/error.css" rel="stylesheet">
    <title><?=Name;?> - 401</title>
    <div class="container-fluid">
    <div class="error-container">
        <div class="error-well">
            <div class="error-heading">401</div>
            <div class="error-message">hmmm.. looks like you are not authorized to access this page.</div>
            <p class="home-link"><a href="/home/" class="btn btn-primary">go to home</a></p>
            <p><a onclick="history.back();" class="btn btn-primary">back</a></p>
        </div>
    </div>
    </div>
</body>
</html>