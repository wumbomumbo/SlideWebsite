<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/include.php");

try {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    $latestNotificationId = isset($_GET['latest_id']) ? intval($_GET['latest_id']) : PHP_INT_MAX;

    $stmt = $con->prepare("SELECT * FROM notifications WHERE user_id = :user_id AND id > :latest_id ORDER BY created_at DESC");
    $stmt->bindValue(':user_id', $sessionloggedin, PDO::PARAM_INT);
    $stmt->bindValue(':latest_id', $latestNotificationId, PDO::PARAM_INT);
    $stmt->execute();

    $newNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $newNotificationCount = count($newNotifications);

    if ($newNotificationCount === 0) {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $notificationsPerPage = 10;
        $offset = ($page - 1) * $notificationsPerPage;

        $stmt = $con->prepare("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':user_id', $sessionloggedin, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $notificationsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $notifications = $newNotifications;
    }

    $formattedNotifications = [];

    foreach ($notifications as $notification) {
        $formattedNotifications[] = [
            'id' => $notification['id'],
            'sender' => getUsername($con, $notification['sender']),
            'senderLink' => getProfileLink($notification['sender']),
            'profileImg' => getProfilePicture($con, $notification['sender']),
            'message' => $notification['message'],
            'action' => " ".$notification['action'],
            'link' => $notification['link'],
            'created_at' => $notification['created_at']
        ];
    }

    header("Content-Type: application/json");
    echo json_encode(['notifications' => $formattedNotifications, 'new_notification_count' => $newNotificationCount]);
} catch (PDOException $e) {
    header("Content-Type: application/json");
    echo json_encode(['success' => false, 'message' => 'Something went wrong.']);
    http_response_code(500);
    die();
}
?>
