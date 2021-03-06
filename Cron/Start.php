<?php
$options = getopt("",array("bootstrap::","dbHost::","dbUser::", "dbPass::", "dbName::", "config::"));

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
if(isset($options["config"])){    
    $_ENV["Config"] = $options["config"];
}

require_once $options["bootstrap"];
require_once "Cron.php";

$cron = $container->Get("JPF\Cron\Cron");
$cron->Start();