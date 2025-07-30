<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/include.php");

try {
    $userid = isset($_GET['id']) ? intval($_GET['id']) : null;

    header("Content-Type: application/json");
    die(json_encode([
        'success' => true,
        'name' => getUsername($con, $userid),
        'userTag' => getUserTag($con, $userid),
        'userId' => $userid,
        'profilePictureUrl' => getProfilePicture($con, $userid),
        'totalPosts' => getTotalPosts($con, $userid),
        'followersCount' => getTotalFollowers($con, $userid),
        'followingCount' => getTotalFollowing($con, $userid),
        'likesCount' => getTotalLikes($con, $userid)
    ]));
} catch (PDOException $e) {
    header("Content-Type: application/json");
    echo json_encode(['success' => false, 'message' => 'Something went wrong.']);
    http_response_code(500);
    die();
}
?>
