<?php
$options = getopt("",array("bootstrap::","dbHost::","dbUser::", "dbPass::", "dbName::", "task:", "params:", "maxRetries::", "date::"));

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

$task = json_decode($options["task"]);

$params = json_decode($options["params"]);

$maxRetries = 5;
$date = null;
if(isset($options["maxRetries"])){
    $maxRetries = $options["dbPass"];
}
if(isset($options["date"])){
    $date = $options["date"];
}


require_once $options["bootstrap"];
require_once "TaskQueue.php";

$taskQueue = $container->Get("JPF\TaskQueue\TaskQueue");
$taskQueue->Add($task, $params, $maxRetries, $date);
