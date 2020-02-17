<?php
declare(strict_types=1);

require_once '/home/kokoro/Cache/Interfaces/CacheInterface.php';
require_once '/home/kokoro/Cache/Drivers/CacheRedisFsocket.php';
require_once "/home/kokoro/Cache/Interfaces/ValueInterface.php";
require_once "/home/kokoro/Cache/Values/ValueNotFound.php";
require_once "/home/kokoro/Cache/Values/ValueFound.php";

$values = new CacheRedisFsocket("127.0.0.1", 6379, 0);
$values->set("test", "Hello");
$values->get("test");
$values->remove("test");
$value1 = new ValueNotFound();
$value2 = new ValueFound('sdsdsd');
$value1->isFound();
$value1->getValue();
$value2->isFound();
$value2->getValue();