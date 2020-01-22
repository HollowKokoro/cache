 <?php

require_once 'CacheInterface.php';
require_once 'CacheRedis.php';

$values = new CacheFile('/home/kokoro/1.txt');
$values->set('test', 'Hello');
echo $values->get('test');
$values->remove('test', 'Hello');