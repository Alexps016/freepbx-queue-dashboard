<?php

require_once 'queue.php';

header('Content-Type: application/json; charset=utf-8');

echo json_encode(
    getQueueData(),
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);