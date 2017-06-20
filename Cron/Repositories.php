<?php
namespace JPF\Cron\Repositories;
use JPF\EntityGen\Entity;
use JPF\EntityGen\Repository;

class Cron extends Entity {
	 var $ID;
	 var $Date;
	 var $Task;
	 var $Params;
	 var $NextExcIn;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface CronRepositoryInterface{

	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null); 
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
       

}

class CronRepository extends Repository implements CronRepositoryInterface{
	function __construct() {
		 parent::__construct(__NAMESPACE__."\\Cron");
	}
}