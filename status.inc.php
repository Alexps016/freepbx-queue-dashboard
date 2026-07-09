<?php

function getOperatorStatuses()
{
    $output = shell_exec('asterisk -rx "core show hints"');

    $statuses = [];

    $lines = explode("\n", $output);

    foreach ($lines as $line) {

        if (preg_match('/^(100[1-9])@ext-local.*State:([A-Za-z]+)/', trim($line), $m)) {

            $ext   = $m[1];
            $state = $m[2];

            switch ($state) {

                case 'Idle':
                    $color = 'green';
                    $text = '🟢 Свободен';
                    break;

                case 'InUse':
                    $color = 'red';
                    $text = '🔴 Разговаривает';
                    break;

                case 'Ringing':
                    $color = 'yellow';
                    $text = '🟡 Звонит';
                    break;

                case 'Unavailable':
                    $color = 'gray';
                    $text = '⚪ Не зарегистрирован';
                    break;

                default:
                    $color = 'blue';
                    $text = $state;
            }

            $statuses[$ext] = [
                'state' => $state,
                'color' => $color,
                'text'  => $text
            ];
        }
    }

    ksort($statuses, SORT_NUMERIC);

    return $statuses;
}