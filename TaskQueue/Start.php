<?php


require_once $argv[1];

require_once "TaskQueue.php";



$taskQueue = $container->Get("JPF\TaskQueue\TaskQueue");
$taskQueue->Start();
