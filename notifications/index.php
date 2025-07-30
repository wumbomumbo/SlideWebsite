<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/navbar.php");
if (!$LoggedIn) {
    die(header("Location: /login/"));
}
?>
<title><?= Name; ?> - notifications</title>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 notificationbox">
            <ul class="list-group" id="notificationList">
                <!-- Notifications will be dynamically added here -->
            </ul>
        </div>
    </div>
</div>
</body>
<link rel="stylesheet" href="/css/notificationpage.css">
<script src="/js/notificationspage.js"></script>
</html>