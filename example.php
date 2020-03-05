<?php
declare(strict_types=1);

require_once "./Interfaces/CacheInterface.php";
require_once "./Drivers/CacheFile.php";
//require_once "./Drivers/CacheRedisFsocket.php";
require_once "./Interfaces/ValueInterface.php";
require_once "./Values/ValueNotFound.php";
require_once "./Values/ValueFound.php";

//$cache = new CacheRedisFsocket("127.0.0.1", 6379, 0);
$cache = new CacheFile();
$cache->set("test", "Hello", 546);
print_r($cache->get("test"));