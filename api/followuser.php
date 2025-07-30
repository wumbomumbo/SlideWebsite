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

    if (getFollowing($con, $sessionloggedin, $userId)) {
        die(json_encode(['success' => false, 'message' => 'You are already following this user.']));
    }

    $follow = followUser($con, $sessionloggedin, $userId);

    if (!$follow) {
        die(json_encode(['success' => false, 'message' => 'Error following user.']));
    }

    echo json_encode(['success' => true, 'message' => 'Followed user successfully.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error following user.']);
}
