<?php
require($_SERVER['DOCUMENT_ROOT'] ."/config/navbar.php");
?>
    <title><?=Name;?> - login</title>
    <div class="container-fluid">
    <form class="form-signin">
      <h2 class="form-signin-heading">login</h2>
      <label for="inputUsername" class="sr-only">username</label>
      <input type="text" id="inputUsername" class="form-control" placeholder="username" required autofocus>
      <label for="inputPassword" class="sr-only">password</label>
      <input type="password" id="inputPassword" class="form-control" placeholder="password" required>
      <p class="text-muted">don't have an account? <a href="/signup/">sign up here</a>.</p>
      <button class="btn btn-lg btn-primary btn-block" type="button" onclick="login()">sign in</button>
      <div id="errorBanner"></div>
    </form>
    </div>
</body>
<script src="/js/login.js"></script>
<link rel="stylesheet" href="/css/loginandregister.css">
</html>