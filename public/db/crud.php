<?php
declare(strict_types=1);

$pdo = new PDO('sqlite:' . __DIR__ . '/db.sqlite');

function getLastEntry() {
    try {
        global $pdo;
        $query = "SELECT * from playlist ORDER BY id DESC LIMIT 1";
        $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        
        return $result; // would be false with empty table
    } catch (Throwable $th) {
        // print_r($th);
        return json_encode($th);
    }
}

function addEntry($extension, $originalName) {
    try {
        global $pdo;
        $pdo->beginTransaction();

        $query = "INSERT INTO playlist (extension, original_name) values (?, ?)";
        $pdo->prepare($query)->execute([$extension, $originalName]);

        $lastInsertId = $pdo->lastInsertId();

        if ($lastInsertId > 0) {
            $pdo->commit();
        } else {
            $pdo->rollBack();
        }
        
        return (int) $lastInsertId;
    } catch (Throwable $th) {
        $pdo->rollBack();
        return json_encode($th);
    }
}
