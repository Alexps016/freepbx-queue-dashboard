<?php

require_once 'status.inc.php';
require_once 'queue.php';

header('Content-Type: application/json; charset=utf-8');

$data = getQueueData();

$statuses = getOperatorStatuses();

foreach ($data['operators'] as $ext => &$operator) {

    $operator['status'] = $statuses[$ext] ?? [
        'state' => 'Unknown',
        'color' => 'gray',
        'text'  => '⚪ Неизвестно'
    ];
}

unset($operator);

/*
 * Получаем статусы операторов
 */
$statusJson = shell_exec('php status.php');
$statuses = json_decode($statusJson, true);

foreach ($data['operators'] as $ext => &$operator) {

    $operator['status'] = $statuses[$ext] ?? [
        'state' => 'Unknown',
        'color' => 'gray',
        'text'  => '⚪ Неизвестно'
    ];

}

unset($operator);

echo json_encode(
    $data,
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);