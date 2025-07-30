<?php
require($_SERVER['DOCUMENT_ROOT'] ."/config/navbar.php");
?>    
    <link href="/css/error.css" rel="stylesheet">
    <title><?=Name;?> - 504</title>
    <div class="container-fluid">
    <div class="error-container">
        <div class="error-well">
            <div class="error-heading">504</div>
            <div class="error-message">sorry, the server timed out giving a response, please try again later.</div>
            <p class="home-link"><a href="/home/" class="btn btn-primary">go to home</a></p>
            <p><a onclick="history.back();" class="btn btn-primary">back</a></p>
        </div>
    </div>
    </div>
</body>
</html>