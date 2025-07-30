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
    exit;
}

$theme = isset($_POST['theme']) ? intval($_POST['theme']) : null;

if ($theme === null) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid Theme.']));
}

try {

    if($theme === 1){
    $stmt = $con->prepare("UPDATE users SET darkmode = 0 WHERE id = :userId");
    $stmt->bindValue(':userId', $sessionloggedin, PDO::PARAM_INT);
    $stmt->execute();
    }elseif($theme === 2){
    $stmt = $con->prepare("UPDATE users SET darkmode = 1 WHERE id = :userId");
    $stmt->bindValue(':userId', $sessionloggedin, PDO::PARAM_INT);
    $stmt->execute();
    }else{
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => 'Theme does not exist.']));
    }

    echo json_encode(['success' => true, 'message' => 'Theme changed successfully.']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error changing theme.']);
}
