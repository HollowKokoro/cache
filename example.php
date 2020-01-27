<?php

require_once '/home/kokoro/Cache/Interfaces/CacheInterface.php';
require_once '/home/kokoro/Cache/Drivers/CacheRedisFsocket.php';

$values = new CacheRedis('127.0.0.1', 6379);
$values->set('test', 'Hello');
echo $values->get('test');
//$values->remove('test');