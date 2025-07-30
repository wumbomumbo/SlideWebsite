<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/include.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        header("Content-Type: application/json");

        if(!$LoggedIn){
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Please login first.']);
            exit();
        }
        $postContent = isset($_POST['content']) ? $_POST['content'] : '';
        $postImage = isset($_FILES['image']) ? $_FILES['image'] : null;
        $postVideo = isset($_FILES['video']) ? $_FILES['video'] : null;

        if (empty(trim($postContent))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Please enter some text before posting.']);
            exit();
        }

        $imagePath = null;
        $videoPath = null;

        if ($postImage) {
            $imageFileName = generateFileName($sessionloggedin, $postImage['name']);
            $imagePath = "/images/post_images/" . $imageFileName;

            $imageData = file_get_contents($postImage['tmp_name']);
            $imageType = exif_imagetype($postImage['tmp_name']);

            if ($imageType === IMAGETYPE_GIF) {
                move_uploaded_file($postImage['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imagePath);
            } elseif ($imageType !== IMAGETYPE_PNG) {
                $image = imagecreatefromstring($imageData);
                $newImagePath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;

                if (imagecolortransparent($image) >= 0 || imageistruecolor($image)) {
                    imagealphablending($image, false);
                    imagesavealpha($image, true);

                    $backgroundColor = imagecolorallocatealpha($image, 0, 0, 0, 0);
                    imagefill($image, 0, 0, $backgroundColor);
                }

                imagepng($image, $newImagePath);
                imagedestroy($image);
            } else {
                move_uploaded_file($postImage['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imagePath);
            }
        }

        if ($postVideo) {
            $videoFileName = generateVideoFileName($sessionloggedin, $postVideo['name']);
            $videoPath = "/videos/post_videos/" . $videoFileName;

            $videoType = mime_content_type($postVideo['tmp_name']);
            if ($videoType !== 'video/mp4') {
                http_response_code(400);
                echo json_encode(['success' => false, 'reason' => 'NOTMP4']);
                exit();
            }

            move_uploaded_file($postVideo['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $videoPath);
        }

        $stmt = $con->prepare("INSERT INTO posts (poster, text, image, video, timestamp) VALUES (:poster, :text, :image, :video, :timestamp)");
        $stmt->bindParam(':text', $postContent);
        $stmt->bindValue(':poster', $sessionloggedin, PDO::PARAM_INT);
        $stmt->bindValue(':timestamp', time(), PDO::PARAM_INT);
        $stmt->bindValue(':image', $imagePath, PDO::PARAM_STR);
        $stmt->bindValue(':video', $videoPath, PDO::PARAM_STR);

        $stmt->execute();

        $postId = $con->lastInsertId();

        echo json_encode(['success' => true, 'postid' => $postId]);
        exit();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit();
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit();
}

function generateFileName($userId, $originalFileName)
{
    $timestamp = time();
    $hash = hash('sha256', $userId . $timestamp . $originalFileName);
    return "{$hash}_{$timestamp}_{$userId}.png";
}
function generateVideoFileName($userId, $originalFileName)
{
    $timestamp = time();
    $hash = hash('sha256', $userId . $timestamp . $originalFileName);
    return "{$hash}_{$timestamp}_{$userId}.mp4";
}
?>
