<?php
namespace JPF\EntityGen;
class customer extends Entity {
	 var $ID;
	 var $Name;
	 var $userID;
	 var $reference;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

class customerRepository extends Repository{
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
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

class pendingcustomeruserinviteRepository extends Repository{
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
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

class pendingresellerinviteRepository extends Repository{
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
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

class pendingsalesrepinviteRepository extends Repository{
	function __construct() {
		 parent::__construct("pendingsalesrepinvite");
	}
}
class salesrepgroup extends Entity {
	 var $ID;
	 var $name;
	 var $reference;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

class salesrepgroupRepository extends Repository{
	function __construct() {
		 parent::__construct("salesrepgroup");
	}
}
class user extends Entity {
	 var $ID;
	 var $username;
	 var $email;
	 var $firstname;
	 var $lastname;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

class userRepository extends Repository{
	function __construct() {
		 parent::__construct("user");
	}
}
class usercustomerpermissions extends Entity {
	 var $ID;
	 var $UserID;
	 var $CustomerId;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

class usercustomerpermissionsRepository extends Repository{
	function __construct() {
		 parent::__construct("usercustomerpermissions");
	}
}
class userresellerpermissions extends Entity {
	 var $ID;
	 var $userID;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

class userresellerpermissionsRepository extends Repository{
	function __construct() {
		 parent::__construct("userresellerpermissions");
	}
}
class usersalesreppermission extends Entity {
	 var $ID;
	 var $userID;
	 var $salesrepgroupID;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

class usersalesreppermissionRepository extends Repository{
	function __construct() {
		 parent::__construct("usersalesreppermission");
	}
}
class vdctemplate extends Entity {
	 var $ID;
	 var $name;
	 var $VdcTemplate;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

class vdctemplateRepository extends Repository{
	function __construct() {
		 parent::__construct("vdctemplate");
	}
}
