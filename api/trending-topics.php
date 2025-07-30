<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/include.php");
header("Content-Type: application/json");

try {
    // check cached result
    $stmt = $con->prepare("SELECT data, timestamp FROM cached_trends WHERE query_key = 'trending_posts' ORDER BY timestamp DESC LIMIT 1");
    $stmt->execute();
    $cachedResult = $stmt->fetch(PDO::FETCH_ASSOC);

    // check if the cached result is still valid (less than 1 hour old)
    if ($cachedResult && (time() - $cachedResult['timestamp'] < 3600)) {
        echo $cachedResult['data'];
        exit;
    }

    // fetch recent posts, within the last 24 hours
    $stmt = $con->prepare("SELECT text FROM posts WHERE timestamp >= UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY)");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // process posts to extract and count keywords, excluding common words
    $keywords = [];
    $ignoreList = [
        "and", "the", "is", "or", "i", "a", ",", ".", "!", "?", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0",
        "my", "its", "totally", "of", "oh", "in", "to", "with", "on", "for", "it", "this", "that", "by", "an", "be",
        "are", "from", "as", "at", "but", "not", "they", "you", "we", "his", "her", "their", "our", "one",
        "was", "were", "which", "have", "has", "will", "would", "can", "could", "there", "here", "about",
        "just", "so", "out", "up", "down", "if", "all", "any", "no", "been", "them", "who", "what", "when", "where", "how"
    ];

    foreach ($posts as $post) {
        $words = preg_split("/[\s,]+/", $post['text']);
        foreach ($words as $word) {
            $word = strtolower(trim($word, " \t\n\r\0\x0B.,!?"));
            if (!in_array($word, $ignoreList) && !empty($word)) {
                if (!isset($keywords[$word])) {
                    $keywords[$word] = ['name' => $word, 'mentionCount' => 1];
                } else {
                    $keywords[$word]['mentionCount']++;
                }
            }
        }
    }

    uasort($keywords, function ($a, $b) {
        return $b['mentionCount'] - $a['mentionCount'];
    });

    $result = array_values(array_slice($keywords, 0, 6, true));

    // save to cache
    $jsonResult = json_encode($result);
    $timestamp = time();
    $stmt = $con->prepare("REPLACE INTO cached_trends (query_key, data, timestamp) VALUES ('trending_posts', :jsonResult, :timestamp)");
    $stmt->bindParam(':jsonResult', $jsonResult, PDO::PARAM_STR);
    $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
    $stmt->execute();

    echo $jsonResult;
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}