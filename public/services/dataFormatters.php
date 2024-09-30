<?php
declare(strict_types=1);

function formateRowFromDb($row) {
    return [
        'id' => $row['id'],
        'filename' => "{$row['id']}.{$row['extension']}",
        'originalFilename' => $row['original_name']
    ];
}

function formateDataFromDb($data) {
    return array_map('formateRowFromDb', $data);
}