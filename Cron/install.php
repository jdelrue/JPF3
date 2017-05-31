<?php
    require_once "../EntityGen/SqlConnector.php";


    $mysqli = SqlConnector::getMysqliInstance();

    $mysqli->query("CREATE TABLE `Cron`(
                    `ID` int NOT NULL AUTO_INCREMENT,
                    `Date` DATETIME NOT NULL,
                    `Task` VARCHAR(255) NOT NULL,
                    `Params` VARCHAR(1024) NOT NULL,
                    `NextExcIn` int NOT NULL,
                    PRIMARY KEY (`ID`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
