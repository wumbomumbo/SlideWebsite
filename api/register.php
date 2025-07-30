<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/include.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['displayname']) && isset($data['username']) && isset($data['password']) && isset($data['email'])) {
        $displayname = $data['displayname'];
        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];

        $stmt = $con->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingUser) {
            // user does not exist, so lets make it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertStmt = $con->prepare("INSERT INTO users (displayname, username, password, email) VALUES (:displayname, :username, :password, :email)");
            $insertStmt->bindParam(':displayname', $displayname, PDO::PARAM_STR);
            $insertStmt->bindParam(':username', $username, PDO::PARAM_STR);
            $insertStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $insertStmt->execute();

            $userId = $con->lastInsertId();
            $_SESSION['UserId'] = $userId;

            echo json_encode(['success' => true, 'message' => 'Registration successful.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Username or email already in use.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Username, password, and email are required.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
