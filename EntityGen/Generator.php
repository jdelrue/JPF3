<?php
namespace JPF\EntityGen;



class Generator {
	private $dbScheme;
	public function __construct($dbScheme){
		$this->dbScheme = $dbScheme;

	}

	public function LoadEntitiesFromDbFile () {
		$entities = Array();
		$entityfile = "<?php\nnamespace JPF\EntityGen;\n";
		
		$currentClass = "";
		$first = true;
		$regex = "";
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $this->dbScheme) as $line){
			$matches = Array();
			if (preg_match("/^\s+`+(\w+)`+ \w+/", $line, $matches)) {
				$entityfile .= "\t var $" . $matches[1] . ";";
				$entityfile .= "\n";

			}
			if (preg_match("/^\s+PRIMARY KEY \((.+)\).*/", $line, $matches)) {
				$entityfile .= "\t var \$primaryKeys = array(" . str_replace("`", "\"", $matches[1]) . ");\n";
			}
			if (preg_match("/^.+AUTO_INCREMENT,$/", $line, $matches)) {
	
				if (preg_match("/^\s+`+(\w+)`+ \w+/", $line, $matches)) {
					$regex .= "\"" . $matches[1] . "\",";
					
				}
			}
			if (preg_match("/^CREATE TABLE `+(\w+)/i", $line, $matches)) {
				if ($first) {
					$first = false;
				} else { //finish last table
			
					$entityfile .= $this->AddEndOfClass($regex);
					$regex = "";
				}
				$entityfile .= "class " . $matches[1] . " extends Entity {";
				$entityfile .= "\n";
				$entities[$currentClass] = Array();
				$currentClass = $matches[1];
			}
		}
		$entityfile .= $this->AddEndOfClass($regex);
		$entityfile .= "\n?>";
		return $entityfile;
	}

	private function AddEndOfClass($regex){
					$entityfile = "";
					if($regex != ""){
				
					$regex = "\tvar \$auto_increment = array(".substr_replace($regex, "", -1).");\n";
									$entityfile .= $regex;
					}
		
					$entityfile .= "public static function getClass() { return __CLASS__; }\n";
					$entityfile .= "}\n";

					return $entityfile;
	}
};



	





?>