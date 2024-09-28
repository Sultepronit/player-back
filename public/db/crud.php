<?php
declare(strict_types=1);

$pdo = new PDO('sqlite:' . __DIR__ . '/db.sqlite');

function addEntry($extension, $originalName) {
    global $pdo;
    $query = "INSERT INTO playlist (extension, original_name) values (?, ?)";
    $pdo->prepare($query)->execute([$extension, $originalName]);

    $query = "SELECT * from playlist ORDER BY id DESC LIMIT 1";
    $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
    
    return $result;
}
