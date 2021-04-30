<?php

include_once('../../../load.php');

echo get_option('ffa', '0');
/*
use TochkaIntegrationStomp{
    StompClient,
    Publisher
};

//указываем реквизиты для установки подключения к брокеру-сообщений по протоколу Stomp
$stompClient = new StompClient('wws://youinrolltinod.com:15673', 'xaticont', 'tester322');

//создаем объект, который умеет отправлять сообщения
$publisher = new Publisher($stompClient);

// формируем сообщение на отправку:
$requestContent = <<<REQ
<?xml version="1.0" encoding="UTF-8"?>
<request sender="test" timestamp="2016-12-07T07:27:26.503+00:00">
 <data code="1234567" />
</request>
REQ;

// Указываем необходимые заголовки
$headers = [
    'field' => 'test data'
];

// отправляем сообщение в очередь
$publisher->send('q.test.queue', $requestContent, $headers, uniqid());
*/


