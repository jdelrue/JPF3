<?php
    require_once "../EntityGen/SqlConnector.php";


    $mysqli = SqlConnector::getMysqliInstance();

    $mysqli->query("CREATE TABLE `TaskQueue`(
                    `ID` int NOT NULL AUTO_INCREMENT,
                    `Date` DATETIME NOT NULL,
                    `Task` VARCHAR(255) NOT NULL,
                    `Params` VARCHAR(1024) NOT NULL,
                    `Retries` int NOT NULL,
                    `MaxRetries` int NOT NULL,
                    `Done` boolean NOT NULL,
                    `LastError` NULL,
                    PRIMARY KEY (`ID`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
