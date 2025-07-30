<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/navbar.php");
?>
<title><?=Name;?> - register</title>
<div class="container-fluid">
    <form class="form-signin">
        <h2 class="form-signin-heading">register</h2>
        <label for="inputDisplayName" class="sr-only">display name</label>
        <input type="text" id="inputDisplayName" class="form-control" placeholder="display name" required autofocus>
        <label for="inputUsername" class="sr-only">username</label>
        <input type="text" id="inputUsername" class="form-control" placeholder="username" required>
        <label for="inputEmail" class="sr-only">email address</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="email address" required>
        <label for="inputPassword" class="sr-only">password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="password" required>
        <label for="inputConfirmPassword" class="sr-only">confirm password</label>
        <input type="password" id="inputConfirmPassword" class="form-control" placeholder="confirm password" required>

        <p class="text-muted">already have an account? <a href="/login/">login here</a>.</p>

        <button class="btn btn-lg btn-primary btn-block" type="button" onclick="register()">register</button>
        <div id="errorBanner"></div>
    </form>
</div>

</body>
<script src="/js/register.js"></script>
<link rel="stylesheet" href="/css/loginandregister.css">
</html>
