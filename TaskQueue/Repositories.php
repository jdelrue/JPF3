<?php
namespace JPF\TaskQueue\Repositories;
use JPF\EntityGen\Entity;
use JPF\EntityGen\Repository;

class TaskQueue extends Entity {
	 var $ID;
	 var $Date;
	 var $Task;
	 var $Params;
	 var $Retries;
	 var $MaxRetries;
	 var $Done;
	 var $LastError;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface TaskQueueRepositoryInterface{

	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null); 
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
       

}

class TaskQueueRepository extends Repository implements TaskQueueRepositoryInterface{
	function __construct() {
		 parent::__construct(__NAMESPACE__."\TaskQueue");
	}
}