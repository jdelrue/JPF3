<?php
/*
* The Entity Generator generates a file that can be used to access the MySQL database
*
*/
namespace JPF\EntityGen;

require_once "Fetcher.php";
require_once "Generator.php";

class EntityGen {
	public function __construct(){

		$db = new \mysqli('localhost', 'root', '', 'test');

		$fetcher = new Fetcher($db);

		$scheme = $fetcher->Fetch("DB.SQL");

		$generator = new Generator($scheme);
		$generator->LoadEntitiesFromDbFile();
	}


	public function WriteToFile($fileName){
		$myFile = $fileName;
		$fh = fopen($myFile, 'w') or die("can't open file");
		//echo $stringData;
		fwrite($fh, $entityfile);
		echo " Done !";
	}

}
$entityGen = new EntityGen();
$entityGen->WriteToFile("MySQL-Entities.php");

?>