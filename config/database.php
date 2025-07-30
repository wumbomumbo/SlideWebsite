<?php
$host = 'localhost';
$dbname = 'social';
$user = 'root';
$password = 'password';

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $con->setAttribute(PDO::ATTR_PERSISTENT, true);
} catch(PDOException $e) {
    header("Location: /maintenance/");
    echo "Something went wrong, but don't fret — it's not your fault. Let's give it another shot.";
    exit();
}
?>
