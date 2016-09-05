<?php
namespace JPF\EntityGen;
abstract class Entity {

    var $fields = Array();
    var $entityType;
    var $deleted;

    /*
     * Following things can be passed to the constructor:
     *  - A record from the database (mysql_fetch_object)
     *  - An ID, which will make the record select (DEPRECATED)
     *  - Primary key(s)
     */

    function __construct($record) {
        $this->entityType = static::getClass();
        $values = get_object_vars($this);
        foreach ($values as $key => $value) {
            array_push($this->fields, $key);
        }

        if (is_object($record)) {
            foreach ($this->fields as $value) {
                if ($this->checkRealColumn($value)) {
                    $this->$value .= $record->$value;
                }
            }
        }
        if (func_num_args() == 1) {// only the ID is passed, DEPRECATED
            if (is_string($record)) {
                $obj = self::SingleSelect($record);
                if (isset($obj)) {
                    foreach ($this->fields as $value) {
                        if ($this->checkRealColumn($value)) {

                            $this->$value = $obj->$value;
                        }
                    }
                }
            }
        }
        /* if (is_string($record) && func_num_args() > 1) {
          $i=0;
          foreach ($this->primaryKeys as $pkey) {
          $this->$pkey = func_get_arg($i);
          $i++;
          }
          } */

        /*
         * If more than one column is passed, the values are set in the current fields
         * in order of coming in. Unreal columns cannot be pased, ID's can.
         */
        if (is_scalar($record) && func_num_args() > 1) {
            $i = 0;
            foreach ($this->fields as $key => $value) {
                if ($i < func_num_args()) {

                    if ($this->checkRealColumn($value) && is_string($value)) {
                        //echo "**$value <br>";
                        $this->$value = func_get_arg($i);
                    }
                }
                $i++;
            }
        }
    }

    /*
     * Get an array of objects from a table. This will create objects using the generated
     * entity classes.
     */

    static function Select($args = "", $order = NULL, $limitor = "") {
        $sqlstr = "Select * from `" . static::getClass() . "` $args";
        if (isset($order)) {
            $orderstr = $order->BuildOrder();
            $sqlstr = "select * from ($sqlstr) as t ORDER BY" . $orderstr;
        }
       
        $record = "";
        $sqlstr .= " " . $limitor;
        $sql = mysql_query($sqlstr) or die(mysql_error());
        $ObjectRows = Array();
        $i = 0;
        while ($record = mysql_fetch_object($sql)) {
            $classname = static::getClass();
            $newObject = new $classname($record);
            if (!(isset($record->deleted) && $record->deleted != 1)) {
                $ObjectRows[$i] = $newObject;
                $i += 1;
            }
        }

        return $ObjectRows;
    }

    /*
     * Select one object from the database using the ID field.
     */

    static function SingleSelect($id) {//@todo multiple primary keys
        $record = "";
        $class = static::getClass();
        $sql = mysql_query("Select * from `" . $class . "` where ID=$id") or die(mysql_error());

        $Object;
        $i = 0;
        while ($record = mysql_fetch_object($sql)) {

            $Object = new $class($record);
            return $Object;
        }
        return null;
    }

    /*
     * Delete current record from the database.
     */

    public function Delete() {
        $mysqli = SQL_Connector::getMysqliInstance();
        $sqlstr = "delete from $this->entityType where " . $this->getWhere();

        if (!$mysqli->query($sqlstr) && GlobalVars::getDebug()) {
            echo $mysqli->error;
        }
    }

    /*
     * Add the current object to the database (only useful after creation or to take copy's)
     */

    public function Add() {

        $mysqli = SQL_Connector::getMysqliInstance();
        $sqlstr = "insert into `" . $this->entityType . "`(" . $this->GetNonNullFields() . ")" . " VALUES(" . $this->GetValues() . ")";
        //echo $sqlstr;

        if (!$mysqli->query($sqlstr) && GlobalVars::getDebug()) {
            echo $mysqli->error;
        }
        if (property_exists(static::getClass(), "ID")) {
            $id = $mysqli->insert_id;
            $this->ID = $id;
        }
    }

    public function Edit() {
        $mysqli = SQL_Connector::getMysqliInstance();
        $sqlstr = "update " . $this->entityType . " SET " . $this->GetUpdateList() . " WHERE " . $this->getWhere();

        if (!$mysqli->query($sqlstr) && GlobalVars::getDebug()) {
            echo $mysqli->error;
        }
    }

    private function GetFields() {
        $list = "";
        foreach ($this->fields as $key => $value) {
            if ($this->checkRealColumn($value)) {
                $list .= $value . ",";
            }
        }
        $list = substr_replace($list, "", -1);
        return $list;
    }
    public function GetRawObject(){
        
        $object = new stdClass();
        foreach ($this->fields as $key => $value) {
            if ($this->checkRealColumn($value)) {
                
              $object->$value = $this->$value;
            }
        }
        return $object;
    
    }
    private function GetNonNullFields() {
        $values = get_object_vars($this);
        $list = "";
        foreach ($values as $key => $value) {
            if (isset($this->$key)) {
                if ($this->checkRealColumn($key) && !in_array($key, $this->auto_increment)) {
                    $list .= $key . ",";
                }
            }
        }
        $list = substr_replace($list, "", -1);
        return $list;
    }

    private function GetValues() {
        $values = get_object_vars($this);
        $list = "";
        $i = 0;
        foreach ($values as $key => $value) {
            if ($this->checkRealColumn($key) && !in_array($key, $this->auto_increment)) {
                if (isset($value)) {
                    $list .= "'" . $value . "',";
                }
            }
            $i++;
        }
        $list = substr_replace($list, "", -1);
        return $list;
    }

    private function GetUpdateList() {
        $values = get_object_vars($this);
        $list = "";
        foreach ($values as $key => $value) {
            if ($this->checkRealColumn($key)) {
                $list .= $key . "='" . $value . "',";
            }
        }
        $list = substr_replace($list, "", -1);
        return $list;
    }

    /*
     * Check if column is a database column or just a helper field of the Entity class.
     */

    private function checkRealColumn($field) {

        return ($field != "fields" && $field != "entityType" && $field != "deleted" && $field != "primaryKeys" && $field != "auto_increment" );
    }

    /*
     * Find out which of the records is this specific one with the where string
     */

    private function getWhere() {

        $where = "";
        foreach ($this->primaryKeys as $pkey) {
            $where .= $pkey . "=" . $this->$pkey . " AND ";
        }
        $where = substr($where, 0, -4);

        return $where;
    }

    /*
     * The values can be used with a colon as seperator to find values
     * in linked tables. 
     * Example:
     *  songID:albumID:artistID:name
     * This function will loop trough the colons, selecting the right 
     * value at the end.
     * A single select is executed for each relation
     */

    public function GetLinkedValue($dbname) {

        $arr = explode(":", $dbname);

        if (count($arr) == 1) {
            if (isset($this->$dbname)) {
                return $this->$dbname;
            }
        }
        $identifStr = substr($arr[0], -2);
        $first = substr($arr[0], 0, 1);
        $tableName = strtoupper($first) . substr(str_replace($identifStr, "", $arr[0]), 1);
        $ID = $this->$arr[0];
        $var = new $tableName("'" . $ID . "'");
        $currentvalue = $var;

        for ($i = 1; $i < count($arr)-1 ; $i++) {
            $identifStr = substr($arr[$i], -2);
            $first = substr($arr[$i], 0, 1);
            $tableName = strtoupper($first) . substr(str_replace($identifStr, "", $arr[$i]), 1);
            $ID = $currentvalue->$arr[$i];
            $var = new $tableName("'" . $ID . "'");
            $currentvalue = $var;
            if (isset($var->$arr[count($arr) - 1])) {
                $value = $var->$arr[count($arr) - 1];
            }
        }
        
        if (isset($value)) {

            return $value;
        } else {
            return "";
        }
    }

}

?>