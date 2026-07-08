<?php

// Часовой пояс
date_default_timezone_set('Asia/Almaty');

// Путь к queue_log
$QUEUE_LOG = '/var/log/asterisk/queue_log';

// Номер очереди
$QUEUE = '1';

// Операторы
$OPERATOR_START = 1001;
$OPERATOR_END   = 1009;

// Обновление страницы (сек)
$REFRESH = 5;

// Последние вызовы
$LAST_CALLS = 20;