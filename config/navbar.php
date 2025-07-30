<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/include.php");

// hacky??
// not quite sure, but it works, doesnt seem slow either.
if ($LoggedIn) {
    if ($_SERVER['REQUEST_URI'] == '/login/') {
        die(header("Location: /home/"));
    } elseif ($_SERVER['REQUEST_URI'] == '/signup/') {
        die(header("Location: /home/"));
    } elseif ($_SERVER['REQUEST_URI'] == '/') {
        die(header("Location: /home/"));
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- <?= Name; ?> CSS -->
    <link rel="stylesheet" href="/css/main.css">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- <?= Name; ?> JS -->
    <script src="/js/main.js"></script>

    <!-- Google Ads JS -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3368583339839243"
        crossorigin="anonymous"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap"
        rel="stylesheet">
</head>

<body <?php
if (isset($sessionloggedin)) {
    if (getDarkMode($con, $sessionloggedin)) {
        echo "class='dark-mode'";
    }
} ?>>
    <?php
    if (isset($sessionloggedin)) {
        if (getFirstTime($con, $sessionloggedin)) {
            echo '
        <div class="cover">
            <img src="/images/logo.svg" id="icon">
        </div>

        <div class="modal fade" id="workInProgressModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">work in progress</h4>
                    </div>
                    <div class="modal-body">
                        <p>this site is a work in progress. bugs and data loss may occur. nothing is set in stone as of now.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="/css/welcome.css">

        <script type="text/javascript">

        document.body.classList.add("nomarginandpadding");

        setTimeout(() => {
            $("#workInProgressModal").modal("show");
            let element = document.querySelector(".cover");
            if (element) {
                element.style.display = "none";
                document.body.classList.remove("nomarginandpadding");
            }
        }, 3900);

        </script>';

            hasSeenWelcomeScreen($con, $sessionloggedin);
        }
    }
    ?>
    <div id="success-banner" class="success-banner"></div>
    <div id="error-banner" class="error-banner"></div>
    <nav class="navbar navbar-default navbar-fixed">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/">
                    <img class="navbar-brand navbar-brand-centered" alt="<?= Name; ?> Logo" src="/images/logo.svg">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php
                    if ($LoggedIn) {
                        $homeClass = $notificationsClass = $messagesClass = $meClass = '';

                        $currentURI = $_SERVER['REQUEST_URI'];

                        if ($currentURI == '/home/') {
                            $homeClass = ' class="active"';
                        } elseif ($currentURI == '/notifications/') {
                            $notificationsClass = ' class="active"';
                        } elseif ($currentURI == '/messages/') {
                            $messagesClass = ' class="active"';
                        } elseif (strpos($currentURI, '/user/') !== false) {
                            $meClass = ' class="active"';
                        }

                        echo '
        <li' . $homeClass . '><a href="/home/"><span class="glyphicon glyphicon-home"></span> home</a></li>
        <li' . $notificationsClass . '><a href="/notifications/"><span class="glyphicon glyphicon-bell"></span> notifications<span id="notificationCount" class="badge"></span></a></li>
        <li' . $messagesClass . '><a href="/messages/"><span class="glyphicon glyphicon-envelope"></span> messages</a></li>
        <li' . $meClass . '><a href="/user/?id=' . $sessionloggedin . '"><span class="glyphicon glyphicon-user"></span> me</a></li>
    ';
                    }
                    ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if ($LoggedIn) {
                        echo '
                    <li>
                        <form class="navbar-form" role="search" action="/search/">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="search" name="srch-term"
                                    id="srch-term">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit"><i
                                            class="glyphicon glyphicon-search"></i></button>
                                </div>
                            </div>

                    </li>
                    ';
                    }
                    ?>
                    <?php if ($LoggedIn) {
                        echo '
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle profile-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true"
                            aria-expanded="false">
                            <img src="' . getProfilePicture($con, $sessionloggedin) . '"
                                alt="profile picture" class="profile-picture">
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/user/?id=' . $sessionloggedin . '">profile</a></li>
                            <li><a href="/settings/">settings</a></li>
                            <li><a href="/bookmarks/">bookmarks</a></li>
                            <li role="separator" class="divider"></li>
                            <li id="logout-link"><a>logout</a></li>
                        </ul>
                    </li>
                    <li class="post-li"><button type="button" onclick="postRedirect()" class="post-button">post</button></li>
                    ';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="modal fade" id="adBlockModal" tabindex="-1" role="dialog" aria-labelledby="adBlockModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="adBlockModalLabel">please turn off your adblocker</h4>
                </div>
                <div class="modal-body">
                    ad revenue helps fund our website. please consider turning off your ad blocker, or whitelist our site.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fetchNotificationCount() {
            fetch('/api/notification_count')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch notification count');
                    }
                    return response.json();
                })
                .then(data => {
                    const notificationCount = document.getElementById('notificationCount');
                    if (notificationCount) {
                        if (data.count !== 0) {
                            notificationCount.textContent = data.count;
                            notificationCount.style.display = 'inline-block';
                        } else {
                            notificationCount.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching notification count:', error);
                });
        }

        fetchNotificationCount();

        setInterval(fetchNotificationCount, 10000);
    </script>