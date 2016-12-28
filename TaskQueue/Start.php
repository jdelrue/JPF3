<?php
$options = getopt("",array("bootstrap::","dbHost::","dbUser::", "dbPass::", "dbName::"));

if(isset($options["dbHost"])){
    $_ENV["Db"]["Host"] = $options["dbHost"];
}
if(isset($options["dbUser"])){
    $_ENV["Db"]["User"] = $options["dbUser"];
}
if(isset($options["dbPass"])){
    $_ENV["Db"]["Pass"] = $options["dbPass"];
}
if(isset($options["dbName"])){
    $_ENV["Db"]["Name"] = $options["dbName"];
}

require_once $options["bootstrap"];

require_once "TaskQueue.php";

$taskQueue = $container->Get("JPF\TaskQueue\TaskQueue");
$taskQueue->Start();
