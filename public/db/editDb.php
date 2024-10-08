<?php
declare(strict_types=1);

require_once 'db-env.php'; // $dbName = 'db.sqlite';

$pdo = new PDO('sqlite:' . __DIR__ . '/' . $dbName);

// $dropTable = "DROP TABLE IF EXISTS playlist";
// $pdo->exec($dropTable);

// $createTable = "CREATE TABLE IF NOT EXISTS playlist (
//     id INTEGER PRIMARY KEY,
//     extension TEXT,
//     original_name TEXT
// )";
// echo $pdo->exec($createTable);

// $renameColumn = "ALTER TABLE playlist RENAME column file_name TO extension";
// echo $pdo->exec($renameColumn);

// $addColumn = "ALTER TABLE playlist ADD description TEXT";
$addColumn = "ALTER TABLE playlist ADD rating INTEGER DEFAULT 90";
echo $pdo->exec($addColumn);

echo PHP_EOL;