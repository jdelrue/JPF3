<?php
namespace JPF\EntityGen;
class customer extends Entity {
	 var $ID;
	 var $Name;
	 var $userID;
	 var $reference;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface customerRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class customerRepository extends Repository implements customerRepositoryInterface {
	function __construct() {
		 parent::__construct("customer");
	}
}
class pendingcustomeruserinvite extends Entity {
	 var $ID;
	 var $hash;
	 var $CustomerId;
	 var $email;
	 var $firstname;
	 var $lastname;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface pendingcustomeruserinviteRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class pendingcustomeruserinviteRepository extends Repository implements pendingcustomeruserinviteRepositoryInterface {
	function __construct() {
		 parent::__construct("pendingcustomeruserinvite");
	}
}
class pendingresellerinvite extends Entity {
	 var $ID;
	 var $hash;
	 var $email;
	 var $firstname;
	 var $lastname;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface pendingresellerinviteRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class pendingresellerinviteRepository extends Repository implements pendingresellerinviteRepositoryInterface {
	function __construct() {
		 parent::__construct("pendingresellerinvite");
	}
}
class pendingsalesrepinvite extends Entity {
	 var $ID;
	 var $hash;
	 var $salesrepgroupID;
	 var $email;
	 var $firstname;
	 var $lastname;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface pendingsalesrepinviteRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class pendingsalesrepinviteRepository extends Repository implements pendingsalesrepinviteRepositoryInterface {
	function __construct() {
		 parent::__construct("pendingsalesrepinvite");
	}
}
class salesrepgroup extends Entity {
	 var $ID;
	 var $name;
	 var $reference;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface salesrepgroupRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class salesrepgroupRepository extends Repository implements salesrepgroupRepositoryInterface {
	function __construct() {
		 parent::__construct("salesrepgroup");
	}
}
class taskqueue extends Entity {
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

interface taskqueueRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class taskqueueRepository extends Repository implements taskqueueRepositoryInterface {
	function __construct() {
		 parent::__construct("taskqueue");
	}
}
class user extends Entity {
	 var $ID;
	 var $username;
	 var $email;
	 var $firstname;
	 var $lastname;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface userRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class userRepository extends Repository implements userRepositoryInterface {
	function __construct() {
		 parent::__construct("user");
	}
}
class usercustomerpermissions extends Entity {
	 var $ID;
	 var $UserID;
	 var $CustomerId;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface usercustomerpermissionsRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class usercustomerpermissionsRepository extends Repository implements usercustomerpermissionsRepositoryInterface {
	function __construct() {
		 parent::__construct("usercustomerpermissions");
	}
}
class userresellerpermissions extends Entity {
	 var $ID;
	 var $userID;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface userresellerpermissionsRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class userresellerpermissionsRepository extends Repository implements userresellerpermissionsRepositoryInterface {
	function __construct() {
		 parent::__construct("userresellerpermissions");
	}
}
class usersalesreppermission extends Entity {
	 var $ID;
	 var $userID;
	 var $salesrepgroupID;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface usersalesreppermissionRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class usersalesreppermissionRepository extends Repository implements usersalesreppermissionRepositoryInterface {
	function __construct() {
		 parent::__construct("usersalesreppermission");
	}
}
class vdctemplate extends Entity {
	 var $ID;
	 var $name;
	 var $VdcTemplate;
	 protected $primaryKeys = array("ID");
	protected $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

interface vdctemplateRepositoryInterface {
	public function FindOne($filter = array());
	public function Find($filter = array(), $limit = null);
	public function PutMany($array);
	public function Put($object);
	public function Update($object);
}

class vdctemplateRepository extends Repository implements vdctemplateRepositoryInterface {
	function __construct() {
		 parent::__construct("vdctemplate");
	}
}
