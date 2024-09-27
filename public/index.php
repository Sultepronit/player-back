<?php
declare(strict_types=1);

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = str_replace(' ', '_', $file['name']);
        $destination = __DIR__ . "/audio/{$fileName}";
        print_r($file);
        echo PHP_EOL;
        print_r($destination);
        echo PHP_EOL;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            echo 'Success!';
        } else {
            echo 'Failed!';
        }
    }
    exit;
}
