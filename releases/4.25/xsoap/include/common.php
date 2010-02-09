<?php
function validateuser($username, $password){
	global $xoopsDB;
	$sql = "select * from ".$xoopsDB->prefix('users'). " WHERE uname = '$username' and pass = md5('$password')";
	$ret = $xoopsDB->query($sql);
	if (!$xoopsDB->getRowsNum($ret)) {
		return false;
	} else {
		return true;
	}
}

function user_uid($username, $password){
	global $xoopsDB;
	$sql = "select uid from ".$xoopsDB->prefix('users'). " WHERE uname = '$username' and pass = md5('$password')";
	$ret = $xoopsDB->query($sql);
	if (!$xoopsDB->getRowsNum($ret)) {
		return false;
	} else {
		$row = $xoopsDB->fetchArray($ret);
		return $row['uid'];
	}
}

function validate($tbl_id, $data, $function){
	global $xoopsDB;
	$sql = "select * from ".$xoopsDB->prefix('soap_tables'). " WHERE tablename = '".get_tablename($tbl_id)."' and $function = 1";
	$ret = $xoopsDB->query($sql);
	$pass=true;
	if (!$xoopsDB->getRowsNum($ret)) {
		$pass=false;	
	} else {
		foreach($data as $row){
			$sql = "select * from ".$xoopsDB->prefix('soap_fields'). " WHERE tbl_id = '$tbl_id' and $function = 1 and fieldname = '".$row['field']."'";
			$ret = $xoopsDB->query($sql);
			if (!$xoopsDB->getRowsNum($ret)&&!is_fieldkey($row['field'],$tbl_id)) {
				$pass=false;
			}
		}
	}
	
	return $pass;
}

function checkright($function_file, $username, $password){
	$uid = user_uid($username,$password);
	if ($uid <> 0){
		global $xoopsDB, $xoopsModule;
		$rUser = new XoopsUser($uid);
		$gperm_handler =& xoops_gethandler('groupperm');
		$groups = is_object($rUser) ? $rUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
		$sql = "select plugin_id from ".$xoopsDB->prefix('soap_plugins')." where plugin_file = '".addslashes($function_file)."'";
		$ret = $xoopsDB->queryF($sql);
		$row = $xoopsDB->fetchArray($ret);
		$item_id = $row['plugin_id'];
		$modid = $xoopsModule->getVar('mid');
		return $gperm_handler->checkRight('plugin_call',$item_id,$groups, $modid);
	} else {
		global $xoopsDB, $xoopsModule;
		$gperm_handler =& xoops_gethandler('groupperm');
		$groups = array(XOOPS_GROUP_ANONYMOUS);
		$sql = "select plugin_id from ".$xoopsDB->prefix('soap_plugins')." where plugin_file = '".addslashes($function_file)."'";
		$ret = $xoopsDB->queryF($sql);
		$row = $xoopsDB->fetchArray($ret);
		$item_id = $row['plugin_id'];
		$modid = $xoopsModule->getVar('mid');
		return $gperm_handler->checkRight('plugin_call',$item_id,$groups, $modid);
	}
}

function get_tableid($tablename){
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')." WHERE tablename = '$tablename'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	return $row['tbl_id'];
}

function get_tablename($tableid){
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')." WHERE tbl_id = '$tableid'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	return $row['tablename'];
}

function get_fieldname($fld_id, $tbl_id){
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_fields')." WHERE tbl_id = '$tbl_id' and fld_id = '$fld_id'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	return $row['fieldname'];

}

function is_fieldkey($fieldname, $tbl_id){
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_fields')." WHERE tbl_id = '$tbl_id' and fieldname = '$fieldname' and `key` = 1";
	//echo $sql."\n";
	$ret = $xoopsDB->query($sql);
	if (!$xoopsDB->getRowsNum($ret)){
		return false;
	} else {
		return true;
	}

}
?>