<?php

// Auth
session_start();

// Constants

define("SiteURL", "https://slidesocial.xyz");
define("Name", "slide");

// Settings

$Production = false;
$Debug = true;
$TimeZone = "Europe/London";
$WWWEnabled = false;

// Default Variables

$LoggedIn = false;
$FirstTime = false;

// Code for above settings

if (!$WWWEnabled) {
    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
        $redirect_url = 'http://' . substr($_SERVER['HTTP_HOST'], 4) . $_SERVER['REQUEST_URI'];
        die(header('Location: ' . $redirect_url, true, 301));
    }
}

$sessionloggedin = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : null;

if (!empty($sessionloggedin)) {
    $LoggedIn = true;
}

date_default_timezone_set($TimeZone);

if ($Debug) {
    error_reporting(-1);
    ini_set('display_errors', 'On');
} else {
    error_reporting(0);
    ini_set('display_errors', 'Off');
}

// Functions

function getProfilePicture($con, $id)
{

    $stmt = $con->prepare("SELECT profilepicture FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($user['profilepicture'])) {
        return "/images/defaultprofile.png";
    } else {
        return "/images/profile_pictures/" . $user['profilepicture'] . ".png";
    }
}

function getProfileLink($id)
{
    return "/user/?id=" . $id;
}

function getUserTag($con, $id, $xssprevent = false)
{

    $stmt = $con->prepare("SELECT username FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($xssprevent) {
        return htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8');
    } else {
        return $user['username'];
    }
}

function getUsername($con, $id, $xssprevent = false)
{

    $stmt = $con->prepare("SELECT displayname FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($xssprevent) {
        return htmlspecialchars($user['displayname'], ENT_QUOTES, 'UTF-8');
    } else {
        return $user['displayname'];
    }
}


function getPostText($con, $id)
{

    $stmt = $con->prepare("SELECT text FROM posts WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (is_array($post)) {
        return $post['text'];
    } else {
        return "";
    }
}

function getPosterId($con, $id)
{

    $stmt = $con->prepare("SELECT poster FROM posts WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (is_array($post)) {
        return $post['poster'];
    }
}


function doesPostExist($con, $id)
{

    $stmt = $con->prepare("SELECT id FROM posts WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (is_array($post)) {
        return true;
    } else {
        return false;
    }
}

function getFollowing($con, $follower, $following)
{
    $stmt = $con->prepare("SELECT * FROM followers WHERE followerid = :follower AND followingid = :following");
    $stmt->bindParam(':follower', $follower, PDO::PARAM_INT);
    $stmt->bindParam(':following', $following, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (is_array($user)) {
        return true;
    } else {
        return false;
    }
}

function followUser($con, $follower, $following)
{
    try {

        $time = time();

        $stmt = $con->prepare("SELECT * FROM followers WHERE followerid = :follower AND followingid = :following");
        $stmt->bindParam(':follower', $follower, PDO::PARAM_INT);
        $stmt->bindParam(':following', $following, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($user)) {
            $stmt = $con->prepare("DELETE FROM followers WHERE followerid = :follower AND followingid = :following");
            $stmt->bindParam(':follower', $follower, PDO::PARAM_INT);
            $stmt->bindParam(':following', $following, PDO::PARAM_INT);
            $stmt->execute();
        }

        $stmt = $con->prepare("INSERT INTO followers (followerid, followingid, timestamp) VALUES (:follower, :following, :timestamp)");
        $stmt->bindParam(':follower', $follower, PDO::PARAM_INT);
        $stmt->bindParam(':following', $following, PDO::PARAM_INT);
        $stmt->bindParam(':timestamp', $time, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        return false;
    }
}

function unfollowUser($con, $follower, $following)
{
    try {
        $stmt = $con->prepare("DELETE FROM followers WHERE followerid = :follower AND followingid = :following");
        $stmt->bindParam(':follower', $follower, PDO::PARAM_INT);
        $stmt->bindParam(':following', $following, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function getFirstTime($con, $id)
{

    $stmt = $con->prepare("SELECT welcomescreen FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (is_array($user)) {
        return $user['welcomescreen'];
    }
}

function getDarkMode($con, $id)
{

    $stmt = $con->prepare("SELECT darkmode FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (is_array($user)) {
        return $user['darkmode'];
    }
}

function hasSeenWelcomeScreen($con, $id)
{
    $stmt = $con->prepare("UPDATE users SET welcomescreen = 0 WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

function addNotificationWithType($con, $userId, $type, $senderId, $postid = null, $message = null)
{
    $notificationAction = '';
    $link = '';

    switch ($type) {
        case 'liked_post':
            $notificationAction = 'liked your post.';
            $link = '/posts/?id=' . $postid;
            break;
        case 'liked_reply':
            $notificationAction = 'liked your reply.';
            $link = '/posts/?id=' . $postid;
            break;
        case 'replied_post':
            $notificationAction = 'replied to your post.';
            $link = '/posts/?id=' . $postid;
            break;
        case 'replied_reply':
            $notificationAction = 'replied to your reply.';
            $link = '/posts/?id=' . $postid;
            break;
        case 'reposted_post':
            $notificationAction = 'reposted your post.';
            $link = '/posts/?id=' . $postid;
            break;
        case 'reposted_reply':
            $notificationAction = 'reposted your reply.';
            $link = '/posts/?id=' . $postid;
            break;
        case 'sent_message':
            $notificationAction = 'sent you a message.';
            $link = '/messages/';
            break;
        case 'followed':
            $notificationAction = 'followed you.';
            $link = '/profile/?id=' . $senderId;
            break;
        default:
            return; // do nothing for unknown types, a fallback i suppose would be nice..
    }

    $notificationMessage = $message !== null ? $message : $notificationAction;

    $notiftime = time();

    $stmt = $con->prepare("INSERT INTO notifications (user_id, sender, message, action, link, created_at) VALUES (:user_id, :sender, :message, :action, :link, :created_at)");
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':sender', $senderId, PDO::PARAM_INT);
    $stmt->bindValue(':message', $notificationMessage, PDO::PARAM_STR);
    $stmt->bindValue(':action', $notificationAction, PDO::PARAM_STR);
    $stmt->bindValue(':link', $link, PDO::PARAM_STR);
    $stmt->bindValue(':created_at', $notiftime, PDO::PARAM_INT);
    $stmt->execute();
}

function getTotalPosts($con, $id)
{
    $stmt = $con->prepare("SELECT COUNT(*) FROM posts WHERE poster = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if (is_array($post)) {
        return $post['COUNT(*)'];
    }
    return 0;
}

function getTotalFollowers($con, $id)
{
    $stmt = $con->prepare("SELECT COUNT(*) FROM followers WHERE followingid = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if (is_array($post)) {
        return $post['COUNT(*)'];
    }
    return 0;
}

function getTotalFollowing($con, $id)
{
    $stmt = $con->prepare("SELECT COUNT(*) FROM followers WHERE followerid = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if (is_array($post)) {
        return $post['COUNT(*)'];
    }
    return 0;
}

function getTotalLikes($con, $id)
{
    $stmt = $con->prepare("SELECT COUNT(*) FROM likes WHERE userid = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if (is_array($post)) {
        return $post['COUNT(*)'];
    }
    return 0;
}