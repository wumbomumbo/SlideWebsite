<?php
require($_SERVER['DOCUMENT_ROOT'] ."/config/include.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['username']) && isset($data['password'])) {

        $username = $data['username'];
        $password = $data['password'];

        $stmt = $con->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['UserId'] = $user['id'];
            echo json_encode(['success' => true, 'message' => 'Login successful.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>