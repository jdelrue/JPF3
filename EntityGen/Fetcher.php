<?php
namespace JPF\EntityGen;

class Fetcher {

    private $db;

    public function __construct($db){
        $this->db = $db;

    }

    public function Fetch($fileToWriteTo){

       
        $stringData =  $this->GetStructure();
        return $stringData;
    }
    private function GetStructure(){

        $tables = $this->GetTables();
       
        $sql = "";
        foreach($tables as $table){
            $create = $this->GetCreateStatement($table);
            $sql .= $create;
        }

        return  $sql;
    }
    private function GetCreateStatement($table)
    {
      $res = mysqli_query($this->db,"SHOW CREATE TABLE `$table`");
      $create = "\n";
      while($cRow = mysqli_fetch_array($res))
      {
        $create .= $cRow[1];
      }

      return $create;
    }
    private function GetTables()
    {
      $tableList = array();
      $res = mysqli_query($this->db,"SHOW TABLES");
      while($cRow = mysqli_fetch_array($res))
      {
        $tableList[] = $cRow[0];
      }
      return $tableList;
    }

}


?>