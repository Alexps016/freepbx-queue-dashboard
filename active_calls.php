<?php

function getActiveCalls()
{
    $output = shell_exec('asterisk -rx "core show channels concise"');

    $calls = [];

    foreach (explode("\n", trim($output)) as $line) {

        if (empty($line)) {
            continue;
        }

        $p = explode('!', $line);


        if (count($p) < 14) {
            continue;
        }

        $channel  = $p[0];
        $caller   = $p[7];
        $duration = (int)$p[11];
        $linkedid = $p[12];

        // Входящий канал (номер клиента)
        if (strpos($channel, 'PJSIP/cisco-') === 0) {

            $calls[$linkedid]['caller'] = $caller;

            }

        // Канал оператора
        if (preg_match('/Local\/(100[1-9])@from-queue/', $channel, $m)) {

           $calls[$linkedid]['operator'] = $m[1];
           $calls[$linkedid]['duration'] = $duration;

            }

    }

    $result = [];

    foreach ($calls as $call) {

        if (
            isset($call['operator']) &&
            isset($call['caller'])
        ) {

            $result[] = [
                'operator' => $call['operator'],
                'caller'   => $call['caller'],
                'duration' => $call['duration']
            ];

        }

    }

    return $result;
}