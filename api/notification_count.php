<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/include.php");

try {
    $stmt = $con->prepare("SELECT COUNT(*) AS notification_count FROM notifications WHERE user_id = :user_id AND is_read = 0");
    $stmt->bindValue(':user_id', $sessionloggedin, PDO::PARAM_INT);
    $stmt->execute();
    $notificationCountResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $notificationCount = $notificationCountResult['notification_count'];

    header("Content-Type: application/json");

    echo json_encode(['count' => $notificationCount]);
} catch (PDOException $e) {
    header("Content-Type: application/json");
    echo json_encode(['success' => false, 'message' => 'Something went wrong.']);
    http_response_code(500);
    die();
}
?>
