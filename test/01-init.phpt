--TEST--
JsonDatabase: Initialization
--FILE--
<?php
require dirname(__FILE__).'/../../../core/init.php';
$db = new \JsonDb\JsonDatabase(DIR_CORE);
echo get_class($db), "\n";
?>
--EXPECT--
JsonDb\JsonDatabase

