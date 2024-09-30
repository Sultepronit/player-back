<?php
// echo random_bytes(10);
$bytes = random_bytes(5);
$str = strtoupper(bin2hex($bytes));

var_dump($str);

echo time();