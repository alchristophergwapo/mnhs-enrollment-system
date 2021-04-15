<?php
require_once(dirname(dirname(__DIR__)) . '/vendor/autoload.php');
require_once(__DIR__ . '/socket/socket.php');

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Socket()
        )
    ),
    6001
);
$server->run();
