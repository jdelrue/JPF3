<?php
namespace JPF\EntityGen;

use  JPF\EntityGen\Entity;

abstract class Repository {

    private $tableName;

    public function __construct($tableName) {
      $this->tableName = $tableName;
    }


    public function FindOne($filter = array()){
        $arr = $this->Find($filter, 1);
        if(isset($arr[0])){
            return $arr[0];
        }
        return null;
    }
    private function isRegex($str0) {
        $regex = "/^\/[\s\S]+\/$/";
        return preg_match($regex, $str0);
    }
    public function Find($filter = array(), $limit = null){
        $class = "JPF\\EntityGen\\".$this->tableName;

        $object = new $class(null);
        $fields = $object->GetFields();
        $object = $object->GetRawObject();
        $mysqli = SqlConnector::getMysqliInstance();
        
        $filterStr = "";
        if(count($filter) != 0){
            $filterStr .= " WHERE ";
        }
        $first = true;
        foreach($filter as $key => &$value){


            if($first){
                if($this->isRegex($value)){
                    $filterStr .= $key." REGEXP ? ";
                    $first = false; 
                    $value = substr($value,1,-1);
                }else{
                    $filterStr .= $key."=? ";
                    $first = false; 
                }
            }else{
                if($this->isRegex($value)){
                    $filterStr .= "AND ". $key." REGEXP ? ";
                    $value = substr($value,1,-1); //remove trailing and beginning /
                }else{
                    $filterStr .= "AND ". $key."=? ";
                }

            }
        }
        $limitStr = "";
        if(isset($limit)){
            $limitStr = " LIMIT ".$limit;
        }
        $query = "SELECT $fields FROM ".$this->tableName." ".$filterStr." ".$limitStr;
        $result = Array();
        if ($stmt = $mysqli->prepare($query)) {
            $arrBp = array();
            if(count($filter) != 0){
                    $arrBp[0] = "";
            
                
                foreach($filter as $key => $valuebp){
                    $arrBp[0] .= $this->GetType($valuebp);
                    $arrBp[$key] = &$filter[$key];
                }
                call_user_func_array(array($stmt, 'bind_param'), $arrBp);
            }
             $stmt->execute();
             if(isset($stmt->error) || $stmt->error == ""){
                 return array(null, $stmt->error);
             }
            $arr = array();
            foreach($object as $key => $value){

                 $arr[$key] = &$object->$key;
                
            }
        
            call_user_func_array(array($stmt, 'bind_result'), $arr);
            /* fetch values */
            while ($stmt->fetch()) {
                
                array_push($result, new $class( (object)$arr));
              
            }
            $stmt->close();
        }

        return $result;

    }

    public function PutMany($array){
        if(is_array($array)){
            foreach($array as $item){
                $this->Put($item);
            }
            return array(true, null);
        }
        return array("Error: not an array", null); //@todo error class
    }
    public function Put($object){
        $class = "JPF\\EntityGen\\".$this->tableName;
        $fields = $object->GetFields();
      
        $mysqli = SqlConnector::getMysqliInstance();

        $valuesStr = "";

        $first = true;
        $arrParam = array();

         foreach($object as $key => $value){
         
             if($this->checkRealColumn($key)){
                 $arrParam[$key] = $value;
                if($first){
                    $first = false;
                    $valuesStr .= "?";
                }else{
                    $valuesStr .= ", ?";
                }
             }
                
        }
        $query = "INSERT into ".$this->tableName."(".$fields.") VALUES(".$valuesStr.")";
        $result = Array();
        if ($stmt = $mysqli->prepare($query)) {
            $arrBp = array();

            $arrBp[0] = ""; // keep types here
            $objectAsArray = (array)$object;
            foreach($arrParam as $key => $value){
                $arrBp[0] .= $this->GetType($value);
                $arrBp[$key] = &$arrParam[$key];
            }
            call_user_func_array(array($stmt, 'bind_param'), $arrBp);
             $stmt->execute();
             if(isset($stmt->error) && $stmt->error == ""){
                 return array(null, $stmt->error);
             }
            $stmt->close();
        }
        return array($object, null);

    }



public function Update($object){
        $mysqli = SqlConnector::getMysqliInstance();
        $valuesStr = "";
        $first = true;
        $arrParam = array();

        foreach($object as $key => $value){

             $arrParam[$key] = $value;
            if($first){
                $first = false;
                $valuesStr .= $key."=?";
            }else{
                $valuesStr .= ",".$key."=?";
            }
        }
        $keys = $object->GetKeys();
        $where = " WHERE ";
        $first = true;
        foreach($keys as $key){
             if($first){
                  $where .= $key."=".$object->$key;
             }else{
                 $where .= "AND ".$key."=".$object->$key;
             }
           
        }
        $query = "UPDATE ".$this->tableName." SET ".$valuesStr." ".$where;
        $stmt = $mysqli->prepare($query);
        $result = Array();
        if ($stmt = $mysqli->prepare($query)) {

            $arrBp = array();
            $arrBp[0] = ""; // keep types here
            $objectAsArray = (array)$object;
            foreach($arrParam as $key => $value){
                $arrBp[0] .= $this->GetType($value);
                $arrBp[$key] = &$arrParam[$key];
            }
            call_user_func_array(array($stmt, 'bind_param'), $arrBp);
             $stmt->execute();
             if(isset($stmt->error) || $stmt->error == ""){
                 return array(null, $stmt->error);
             }
            $stmt->close();
        }
        return array($object, null);

    
    }

    function Delete($filter = array()){
        $class = "JPF\\EntityGen\\".$this->tableName;

        $object = new $class(null);
        $fields = $object->GetFields();
        $object = $object->GetRawObject();
        $mysqli = SqlConnector::getMysqliInstance();
        
        /* check connection */
        $filterStr = "";
        if(count($filter) != 0){
            $filterStr .= " WHERE ";
        }
        $first = true;
        foreach($filter as $key => $value){
            if($first){
            $filterStr .= $key."=? ";
            $first = false; 
            }else{
                $filterStr .= "AND ". $key."=? ";
            }
        }
        $query = "DELETE FROM ".$this->tableName." ".$filterStr;
        $result = Array();
        if ($stmt = $mysqli->prepare($query)) {
            $arrBp = array();
            if(count($filter) != 0){
                    $arrBp[0] = "";
            
                
                foreach($filter as $key => $value){
                    $arrBp[0] .= $this->GetType($value);
                    $arrBp[$key] = &$filter[$key];
                }
                print_r($arrBp);
                call_user_func_array(array($stmt, 'bind_param'), $arrBp);
            }
            $stmt->execute();
             if(isset($stmt->error) || $stmt->error == ""){
                 return array(null, $stmt->error);
             }
            $stmt->close();
        }

        return array(true, null);

    }
    
    private function GetType($var){
        $type = gettype($var);
        if($type == "string"){
            return "s";
        }else if ($type == "double"){
            return "d";
        }else if ($type == "integer"){
            return "i";
        }
        return "s";

    }
    private function checkRealColumn($field) {

        return ($field != "fields" && $field != "entityType" && $field != "deleted" && $field != "primaryKeys" && $field != "auto_increment" );
    }
}

/*
some example/test code

class UserRepository extends Repository{

    function __construct() {
         parent::__construct("User");
    }

}

$userrep = new UserRepository();

$objects = $userrep->FindOne(); // array("username" => "testname", "email" => "testmail@gmail.com")

$object= $objects[0];
$object->username = "robbydemoustiez";
 $userrep->Update($object);


/*
$arrtest = array("or", "username" => "testname", "email" => "testmail@gmail.com");
print_r($arrtest);*/

/*
$user = new user(0, "username1", "somemeail" , "somefirstname", "somelastname");

$userrep->Put($user);
$userrep->Delete(array("username" => "username1"));
*/