<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/include.php");

if (isset($_GET['postid'])) {
    try {
        $postId = intval($_GET['postid']);
        $posterId = isset($_GET['userid']) ? intval($_GET['userid']) : null;

        $stmt = $con->prepare("SELECT p.*, 
            (SELECT COUNT(*) FROM likes WHERE postid = p.id AND userid = :userId) AS hasLiked 
            FROM posts p 
            WHERE p.id = :postId");
        if ($LoggedIn) {
            $stmt->bindValue(':userId', $sessionloggedin, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':userId', 0, PDO::PARAM_INT);
        }
        $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();

        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            $formattedPost = [
                'id' => $post['id'],
                'userid' => $post['poster'],
                'username' => getUsername($con, $post['poster']),
                'usertag' => getUserTag($con, $post['poster']),
                'userpicture' => getProfilePicture($con, $post['poster']),
                'text' => $post['text'],
                'image' => $post['image'],
                'video' => $post['video'],
                'likes' => $post['likes'],
                'reposts' => $post['reposts'],
                'timestamp' => $post['timestamp'],
                'hasLiked' => $post['hasLiked'] > 0,
                'areCreator' => $post['poster'] == $sessionloggedin,
                'areFollowing' => getFollowing($con, $sessionloggedin, $post['poster'])
            ];

            header("Content-Type: application/json");
            echo json_encode($formattedPost);
        } else {
            header("Content-Type: application/json");
            echo json_encode(['success' => false, 'message' => 'Post not found.']);
            http_response_code(404);
            die();
        }
    } catch (PDOException $e) {
        header("Content-Type: application/json");
        echo json_encode(['success' => false, 'message' => 'Something went wrong.']);
        http_response_code(500);
        die();
    }
} else {
    try {
        header("Cache-Control: no-cache, must-revalidate");

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $postsPerPage = 10;
        $offset = ($page - 1) * $postsPerPage;

        $searchTerm = isset($_GET['searchquery']) ? $_GET['searchquery'] : '';
        $posterId = isset($_GET['userid']) ? intval($_GET['userid']) : null;

        if ($posterId !== null) {
            $stmt = $con->prepare("SELECT p.*, 
                (SELECT COUNT(*) FROM likes WHERE postid = p.id AND userid = :userId) AS hasLiked 
                FROM posts p 
                WHERE p.poster = :posterId
                ORDER BY id DESC 
                LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':posterId', $posterId, PDO::PARAM_INT);
        } else {
            $orderClause = $searchTerm ? "ORDER BY likes DESC, reposts DESC, id DESC" : "ORDER BY id DESC";
            $stmt = $con->prepare("SELECT p.*, 
                (SELECT COUNT(*) FROM likes WHERE postid = p.id AND userid = :userId) AS hasLiked 
                FROM posts p 
                WHERE p.text LIKE :searchTerm $orderClause 
                LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        }

        if ($LoggedIn) {
            $stmt->bindValue(':userId', $sessionloggedin, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':userId', 0, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formattedPosts = [];

        foreach ($posts as $post) {
            $formattedPosts[] = [
                'id' => $post['id'],
                'userid' => $post['poster'],
                'username' => getUsername($con, $post['poster']),
                'usertag' => getUserTag($con, $post['poster']),
                'userpicture' => getProfilePicture($con, $post['poster']),
                'text' => $post['text'],
                'image' => $post['image'],
                'video' => $post['video'],
                'likes' => $post['likes'],
                'reposts' => $post['reposts'],
                'timestamp' => $post['timestamp'],
                'hasLiked' => $post['hasLiked'] > 0,
                'areCreator' => $post['poster'] == $sessionloggedin,
                'areFollowing' => getFollowing($con, $sessionloggedin, $post['poster'])
            ];
        }

        header("Content-Type: application/json");
        echo json_encode($formattedPosts);
    } catch (PDOException $e) {
        header("Content-Type: application/json");
        echo json_encode(['success' => false, 'message' => 'Something went wrong.']);
        http_response_code(500);
        die();
    }
}
