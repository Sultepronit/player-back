<?php
declare(strict_types=1);

require_once './db/crud.php';

header('Access-Control-Allow-Origin: *');

if(!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$reqUri = $_SERVER['REQUEST_URI'] ?? '';
$path = str_replace('/player', '', $reqUri);

$audioDir = __DIR__ . '/audio';

if($method === 'GET' && $path === '/list') {
    $list = array_slice(scandir($audioDir), 2);
    header('Content-Type: application/json');
    echo json_encode($list);
    exit;
}

if ($method === 'GET' && str_starts_with($path, '/files')) {
    $filePath = str_replace('/files', $audioDir, $path);

    $filePath = str_replace('_', '.', $filePath);

    if (!file_exists($filePath)) {
        echo 'Nothing here!';
        exit;
    }

    $contentType = mime_content_type($filePath);
    // echo $contentType;
    header('Accept-Ranges: bytes');
    header("Content-Type: {$contentType}");
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);

    exit;
}

if($method === 'POST' && $path === '/upload') {
    if (!isset($_FILES['file'])) {
        echo 'What was that???';
        exit;
    }

    $file = $_FILES['file'];
    $fullPath = $file['full_path'];

    $extensionStart = strrpos($fullPath, '.');
    $originalName = substr($fullPath, 0, $extensionStart);
    $extension = strtolower(substr($fullPath, $extensionStart + 1));

    $previous = getLastEntry();
    if (isset($previous['id'])) {
        $id = $previous['id'] + 1;
    } else if ($previous === false) {
        $id = 1;
    } else {
        http_response_code(500);
        echo $previous;
        
        exit;
    }

    $fileName = "{$id}.{$extension}";
    $destination = __DIR__ . "/audio/{$fileName}";

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $lastInsertIdOrError = addEntry($extension, $originalName);
        if ($lastInsertIdOrError === $id) {
            echo "Saved \"{$fullPath}\" as \"{$fileName}\"";
        } else {
            http_response_code(500);
            echo "Error! Instead of last insert id ($id) get the: {$lastInsertIdOrError}";
        }
    } else {
        http_response_code(500);
        echo 'Failed to save the file!';
    }
}
