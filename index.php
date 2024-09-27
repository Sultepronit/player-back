<?php
declare(strict_types=1);

header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = str_replace(' ', '_', $file['name']);
        $destination = __DIR__ . "/audio-files/{$fileName}";
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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $fileName = 'Elisa_-_Ebullient_Future.MP3';
    // $fileName = '瀧川ありさ_『さよならのゆくえ』MUSIC_VIDEO(full_ver.).mkv';
    $file = __DIR__ . "/audio-files/{$fileName}";
    $contentType = mime_content_type($file);
    // echo $contentType;
    header('Content-Description: File Transfer');
    // header('Content-Type: application/octet-stream');
    header("Content-Type: {$contentType}");
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
}