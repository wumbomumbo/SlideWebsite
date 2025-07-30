<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/include.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    exit;
}

header("Content-Type: application/json");

if(!$LoggedIn){
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit();
}

$postId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($postId === null) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid post ID.']));
}

try {

    $stmt = $con->prepare("SELECT COUNT(*) FROM likes WHERE postid = :postId AND userid = :userId");
    $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
    $stmt->bindValue(':userId', $sessionloggedin, PDO::PARAM_INT);
    $stmt->execute();
    $likeCount = $stmt->fetchColumn();

    if ($likeCount > 0) {
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => 'You have already liked this post.']));
    }

    $stmt = $con->prepare("UPDATE posts SET likes = likes + 1 WHERE id = :postId");
    $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $con->prepare("INSERT INTO likes (postid, userid, timestamp) VALUES (:postId, :userId, :timestamp)");
    $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
    $stmt->bindValue(':userId', $sessionloggedin, PDO::PARAM_INT);
    $stmt->bindValue(':timestamp', time(), PDO::PARAM_INT);
    $stmt->execute();

    $posterId = getPosterId($con, $postId);

    addNotificationWithType($con, $posterId, "liked_post", $sessionloggedin, $postId, getPostText($con, $postId));

    echo json_encode(['success' => true, 'message' => 'Post liked successfully.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error liking post.']);
}
