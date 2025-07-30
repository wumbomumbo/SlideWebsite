<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/include.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    exit;
}

header("Content-Type: application/json");

if (!$LoggedIn) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit();
}

$userId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($userId === null) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid user ID.']));
}

try {

    if (!getFollowing($con, $sessionloggedin, $userId)) {
        die(json_encode(['success' => false, 'message' => 'You are not following this user.']));
    }

    $unfollow = unfollowUser($con, $sessionloggedin, $userId);

    if (!$unfollow) {
        die(json_encode(['success' => false, 'message' => 'Error unfollowing user.']));
    }

    echo json_encode(['success' => true, 'message' => 'Unfollowed user successfully.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error unfollowing user.']);
}
