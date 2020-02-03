<?php

require_once '/home/kokoro/Cache/Interfaces/CacheInterface.php';
require_once '/home/kokoro/Cache/Drivers/CacheRedisFsocket.php';

$values = new CacheRedisFsocket("127.0.0.1", 6379);
$values->set("test", "Hello");
$values->get("test");
$values->remove("test");