<?php

require_once 'config.php';

function getQueueData()
{
    global $QUEUE_LOG, $QUEUE, $OPERATOR_START, $OPERATOR_END, $LAST_CALLS;

    $result = [
        'summary' => [
            'total' => 0,
            'answered' => 0,
            'missed' => 0
        ],
        'operators' => [],
        'calls' => []
    ];

    // Инициализация операторов
    for ($i = $OPERATOR_START; $i <= $OPERATOR_END; $i++) {
        $result['operators'][$i] = [
            'answered' => 0,
            'missed' => 0
        ];
    }

    if (!file_exists($QUEUE_LOG)) {
        return $result;
    }

    $today = strtotime(date('Y-m-d'));

    $calls = [];

    $fp = fopen($QUEUE_LOG, "r");

    $today = strtotime(date('Y-m-d 00:00:00'));

    while (($line = fgets($fp)) !== false) {

        $line = trim($line);

        if ($line == '') {
            continue;
        }

        $p = explode('|', $line);

        $eventTime = (int)$p[0];

        if ($eventTime < $today) {
        continue;
        }

        if (count($p) < 5) {
            continue;
        }

        $time = (int)$p[0];

        if ($time < $today) {
            continue;
        }

        $unique = $p[1];
        $queue = $p[2];
        $agent = trim($p[3]);
        $event = trim($p[4]);

        if ($queue != $QUEUE) {
            continue;
        }

        if (!isset($calls[$unique])) {
            $calls[$unique] = [
                'time' => $time,
                'caller' => '',
                'answered' => false,
                'answered_by' => '',
                'talk' => 0,
                'abandoned' => false,
                'ringed' => []
            ];
        }

        switch ($event) {

            case 'ENTERQUEUE':

                $result['summary']['total']++;

                if (isset($p[6])) {
                    $calls[$unique]['caller'] = $p[6];
                }

                break;

case 'RINGNOANSWER':

    $ringTime = isset($p[5]) ? (int)$p[5] : 0;

    // Игнорируем очень короткие вызовы
    if ($ringTime >= 3000 && is_numeric($agent)) {
        $calls[$unique]['ringed'][$agent] = true;
    }

    break;

            case 'CONNECT':

                $calls[$unique]['answered'] = true;
                $calls[$unique]['answered_by'] = $agent;

                if (isset($result['operators'][$agent])) {
                    $result['operators'][$agent]['answered']++;
                }

                $result['summary']['answered']++;

                break;

            case 'COMPLETECALLER':
            case 'COMPLETEAGENT':

                if (isset($p[6])) {
                    $calls[$unique]['talk'] = (int)$p[6];
                }

                break;

            case 'ABANDON':
            case 'EXITWITHTIMEOUT':
            case 'EXITEMPTY':

                if (!$calls[$unique]['answered']) {
                    $calls[$unique]['abandoned'] = true;
                    $result['summary']['missed']++;
                }

                break;

        }

    }

    fclose($fp);

    // Подсчет пропущенных оператором
foreach ($calls as $call) {

    $alreadyCounted = [];

    foreach ($call['ringed'] as $agent => $dummy) {

        if ($agent == $call['answered_by']) {
            continue;
        }

        if (isset($alreadyCounted[$agent])) {
            continue;
        }

        if (isset($result['operators'][$agent])) {
            $result['operators'][$agent]['missed']++;
        }

        $alreadyCounted[$agent] = true;
    }
}

    // Последние вызовы
    uasort($calls, function ($a, $b) {
        return $b['time'] <=> $a['time'];
    });

    $i = 0;

    foreach ($calls as $call) {

        if ($i >= $LAST_CALLS) {
            break;
        }

        $result['calls'][] = [
            'time' => date('H:i:s', $call['time']),
            'caller' => $call['caller'],
            'operator' => $call['answered_by'],
            'duration' => $call['talk'],
            'status' => $call['answered'] ? 'ANSWERED' : 'MISSED'
        ];

        $i++;
    }

    return $result;
}