<?php
declare(strict_types=1);

require_once '/home/kokoro/Cache/Interfaces/CacheInterface.php';
require_once '/home/kokoro/Cache/Drivers/CacheRedisFsocket.php';
require_once "/home/kokoro/Cache/Interfaces/ValueInterface.php";
require_once "/home/kokoro/Cache/Values/ValueNotFound.php";
require_once "/home/kokoro/Cache/Values/ValueFound.php";

$values = new CacheRedisFsocket("127.0.0.1", 6379, 0);
$values->set("test", "Hello");
$x = $values->get("test");
if (isset($x))
{
    $x;
} else{
    return null;
};
$values->remove("test");