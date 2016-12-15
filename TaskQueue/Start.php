<?php
$options = getopt("",array("bootstrap::","dbHost::","dbUser::", "dbPass::", "dbName::"));

$_ENV["Db"]["Host"] = $options["dbHost"];
$_ENV["Db"]["User"] = $options["dbUser"];
$_ENV["Db"]["Pass"] = $options["dbPass"];
$_ENV["Db"]["Name"] = $options["dbName"];

require_once $options["bootstrap"];

require_once "TaskQueue.php";

$taskQueue = $container->Get("JPF\TaskQueue\TaskQueue");
$taskQueue->Start();
