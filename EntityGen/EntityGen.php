<?php
/*
* The Entity Generator generates a file that can be used to access the MySQL database
*
*/
namespace JPF\EntityGen;

require_once "Fetcher.php";
require_once "Generator.php";

class EntityGen {
	private $entities;
	public function __construct(){

		$db = new \mysqli('localhost', 'root', '221662', 'artag8local');

		$fetcher = new Fetcher($db);

		$scheme = $fetcher->Fetch("DB.SQL");

		$generator = new Generator($scheme);
		$this->entities = $generator->LoadEntitiesFromDbFile();
	}


	public function WriteToFile($fileName){
		$myFile = $fileName;
		$fh = fopen($myFile, 'w') or die("can't open file");
	
		fwrite($fh, $this->entities);

	}

}
$entityGen = new EntityGen();

$entityGen->WriteToFile("MySQL-Entities.php");

?>