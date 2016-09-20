<?php
namespace JPF\EntityGen;
class customer extends Entity {
	 var $ID;
	 var $Name;
	 var $userID;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}
class pendingcustomeruserinvite extends Entity {
	 var $ID;
	 var $hash;
	 var $CustomerId;
	 var $email;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}
class pendingresellerinvite extends Entity {
	 var $ID;
	 var $hash;
	 var $email;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}
class pendingsalesrepinvite extends Entity {
	 var $ID;
	 var $hash;
	 var $salesrepgroupID;
	 var $email;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}
class salesrepgroup extends Entity {
	 var $ID;
	 var $name;
	 var $reference;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}
class user extends Entity {
	 var $ID;
	 var $username;
	 var $email;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}
class usercustomerpermissions extends Entity {
	 var $ID;
	 var $UserID;
	 var $CustomerId;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}
class userresellerpermissions extends Entity {
	 var $ID;
	 var $userID;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}
class usersalesreppermission extends Entity {
	 var $ID;
	 var $userID;
	 var $salesrepgroupID;
	 var $primaryKeys = array("ID");
	var $auto_increment = array("ID");
public static function getClass() { return __CLASS__; }
}

?>