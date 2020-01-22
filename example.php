 <?php

require_once '/home/kokoro/Cache/Interfaces/CacheInterface.php';
require_once '/home/kokoro/Cache/Drivers/CacheRedis.php';

$values = new CacheRedis();
$values->set('test', 'Hello');
echo $values->get('test');
$values->remove('test', 'Hello');