<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/navbar.php");
if (!$LoggedIn) {
    die(header("Location: /login/"));
}
?>
<title>
    <?= Name; ?> - settings
</title>
<div class="container-fluid">
    <ul class="nav nav-tabs setting-tabs">
        <li class="active"><a href="#profile-settings" data-toggle="tab">profile settings</a></li>
        <li><a href="#Password" data-toggle="tab">password and email</a></li>
        <li><a href="#themes" data-toggle="tab">themes</a></li>
        <li><a href="#delete-account" data-toggle="tab">delete account</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="profile-settings">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h2>profile settings</h2>
                    <div class="form-group">
                        <label for="firstName">display name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="enter display name"
                            autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="userTag">user tag</label>
                        <input type="text" class="form-control" id="userTag"
                            placeholder="enter user tag (e.g., @username)" autocomplete="off">
                    </div>
                    <hr>
                    <button type="button" class="btn btn-primary" id="saveProfileSettings">save changes</button>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="Password">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h2>password and email</h2>
                    <div class="form-group">
                        <label for="email">email address</label>
                        <input type="email" class="form-control" id="email" placeholder="enter email"
                            autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="password">password</label>
                        <input type="password" class="form-control" id="password" placeholder="password"
                            autocomplete="off">
                    </div>
                    <hr>
                    <button type="button" class="btn btn-primary" id="savePasswordEmail">save changes</button>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="themes">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h2>themes</h2>
                    <div class="form-group">
                        <label>select theme:</label>
                        <?php
                        if(getDarkMode($con, $sessionloggedin)){
                            $selecteddark = "checked";
                            $selectedlight = "";
                        }else{
                            $selecteddark = "";
                            $selectedlight = "checked";
                        }
                        ?>
                        <div class="radio">
                            <label><input type="radio" name="theme" value=1 <?=$selectedlight;?>>light mode</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="theme" value=2 <?=$selecteddark;?>>dark mode</label>
                        </div>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-primary" id="saveTheme">save changes</button>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="delete-account">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h2>delete account</h2>
                    <p>deleting your account is irreversible. all your data will be lost.</p>
                    <button type="button" class="btn btn-danger" id="deleteAccountBtn" data-toggle="modal"
                        data-target="#confirmDeleteModal">delete my account</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="confirmDeleteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">confirm account deletion</h4>
            </div>
            <div class="modal-body">
                <p>are you sure you want to delete your account?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">delete</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/css/settings.css">
<script src="/js/settings.js"></script>
</body>
</html>