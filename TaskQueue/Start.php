<?php
require_once "TaskQueue.php";

$taskQueue = $container->Get("JPF3\TaskQueue\TaskQueue");
$taskQueue->Start();
