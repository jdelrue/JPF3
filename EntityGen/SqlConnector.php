<?php
/* Build Management System
 * Created on 19-feb-09
 * File to connect to Dbase system
 */
namespace JPF\EntityGen;

class SqlConnector {
    var $MySQLUser;
    var $MySQLPassword ;
    var $MySQLDatabase;
    var $MySQLHost;
    private static  $mysqli;
    private static $dbname;
    function  SqlConnector ($MySQLHost, $MySQLUser, $MySQLPassword, $MySQLDatabase)
    {
        $this->MySQLHost = $MySQLHost;
        $this->MySQLUser = $MySQLUser;
        $this->MySQLPassword = $MySQLPassword;
        $this->MySQLDatabase = $MySQLDatabase;
        self::$dbname = $MySQLDatabase;
     }
    function Connect(){
        //new way of connecting
        $mysqli = new mysqli($this->MySQLHost , $this->MySQLUser,   $this->MySQLPassword, $this->MySQLDatabase);
//this should be deleted soon
        $handle = mysql_connect($this->MySQLHost , $this->MySQLUser,   $this->MySQLPassword) 
            or die("Connection Failure to Database");
         mysql_select_db( $this->MySQLDatabase, $handle) or die("Cannot select database");
         self::$mysqli = $mysqli;
        return $mysqli;
    }
    public static function getMysqliInstance(){
        return self::$mysqli;
    }
    public static function getDBName(){
        return self::$dbname;
    }
}
?>