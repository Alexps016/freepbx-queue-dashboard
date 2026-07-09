<?php

require 'status.inc.php';

header('Content-Type: application/json; charset=utf-8');

echo json_encode(
    getOperatorStatuses(),
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);