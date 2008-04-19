<?

require_once('nusoap/nusoap.php');

$server = new soap_server($xoopsModuleConfig['wsdl']);



$server->register('tablesforpost');
$server->register('tablesforretrieve');
$server->register('tablesforupdate');
$server->register('tableschemer');
$server->register('post');
$server->register('retrieve');
$server->register('update');
$server->register('retrievekeys');
$server->register('retrievecrc');

// Define the method as a PHP function
function tablesforpost($var) {
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')." WHERE allowpost = 1 and visible = 1";
	$ret = $xoopsDB->query($sql);
	$rtn = array();
	while ($row = $xoopsDB->fetchArray($ret)){
		$t++;
		$rtn[$t] = array( 'id' => $row['tbl_id'],
						'table' => $row['tablename']);
	}

	global $xoopsModuleConfig;
	if ($xoopsModuleConfig['site_user_auth']==1){
		if (!validateuser($var['username'],$var['password']))
			return false;
	}		
	return $rtn;

}

function tablesforupdate($var) {
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')." WHERE allowupdate = 1 and visible = 1";
	$ret = $xoopsDB->query($sql);
	$rtn = array();
	while ($row = $xoopsDB->fetchArray($ret)){
		$t++;
		$rtn[$t] = array( 'id' => $row['tbl_id'],
						'table' => $row['tablename']);
	}

	global $xoopsModuleConfig;
	if ($xoopsModuleConfig['site_user_auth']==1){
		if (!validateuser($var['username'],$var['password']))
			return false;
	}		
	return $rtn;

}

// Define the method as a PHP function
function tablesforretrieve($var) {
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')." WHERE allowretrieve = 1 and visible = 1";
	$ret = $xoopsDB->query($sql);
	$rtn = array();
	while ($row = $xoopsDB->fetchArray($ret)){
		$t++;
		$rtn[$t] = array( 'id' => $row['tbl_id'],
						'table' => $row['tablename']);
	}

	global $xoopsModuleConfig;
	if ($xoopsModuleConfig['site_user_auth']==1){
		if (!validateuser($var['username'],$var['password']))
			return false;
	}
	return $rtn;

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
// Define the method as a PHP function
function tableschemer($var) {
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_fields')." WHERE visible = 1 ";
	if ($var['post']=1){
		$sql .= "and allowpost = 1 ";
	} elseif ($var['retrieve']=1) {
		$sql .= "and allowretrieve = 1 ";
	} elseif ($var['update']=1) {
		$sql .= "and allowupdate = 1 ";
	}
	if (strlen($var['tablename'])>0) {
		$sql .= "and tbl_id = ".get_tableid($var['tablename']);		
	} elseif ($var['id']>0) {
		$sql .= "and tbl_id = ".$var['id'];
	} else {
		return array('ErrNum'=> 2, "ErrDesc" => 'Table Name or Table ID not specified');
	}
	
	$ret = $xoopsDB->query($sql);
	$rtn = array();
	while ($row = $xoopsDB->fetchArray($ret)){
		$rtn[] = array( 'table_id' => $row['tbl_id'],
						'field' => $row['fieldname'],
						'allowpost'=> $row['allowpost'],
						'allowretrieve'=> $row['allowretrieve'],
						'allowupdate'=> $row['allowupdate'],
						'string'=> $row['string'],
						'int'=> $row['int'],
						'float'=> $row['float'],
						'text'=> $row['text'],
						'key'=> $row['key'],
						'other'=> $row['other']);
	}

	global $xoopsModuleConfig;
	if ($xoopsModuleConfig['site_user_auth']==1){
		if (!validateuser($var['username'],$var['password']))
			return false;
	}		
	return $rtn;
	

}

// Define the method as a PHP function
function post($var) {
	global $xoopsDB;
	if (strlen($var['tablename'])>0) {
		$tbl_id = get_tableid($var['tablename']);
	} elseif ($var['id']>0) {
		$tbl_id = $var['id'];
	} else {
		return array('ErrNum'=> 2, "ErrDesc" => 'Table Name or Table ID not specified');
	}

	if (!validate($tbl_id,$var['data'], "allowpost")){
		return array('ErrNum'=> 1, "ErrDesc" => 'Not all fields are allowed posting');
	} else {
		$sql = "INSERT INTO ".$xoopsDB->prefix(get_tablename($tbl_id));
		foreach ($var['data'] as $data){
			$sql_b .= "`". $data['field']."`,";
			$sql_c .= "'". addslashes($data['value'])."',";
		}
		global $xoopsModuleConfig;
		if ($xoopsModuleConfig['site_user_auth']==1){
			if (!validateuser($var['username'],$var['password']))
				return false;
		}
//		echo $sql." (".substr($sql_b,0,strlen($str_b)-1).") VALUES (".substr($sql_c,0,strlen($str_c)-1).")";
		$rt = $xoopsDB->queryF($sql." (".substr($sql_b,0,strlen($str_b)-1).") VALUES (".substr($sql_c,0,strlen($str_c)-1).")");
		return array("insert_id" => $xoopsDB->getInsertId($rt));
	}

}

// Define the method as a PHP function
function retrieve($var) {
	global $xoopsDB;
	if (strlen($var['tablename'])>0) {
		$tbl_id = get_tableid($var['tablename']);
	} elseif ($var['id']>0) {
		$tbl_id = $var['id'];
	} else {
		return array('ErrNum'=> 2, "ErrDesc" => 'Table Name or Table ID not specified');
	}

	if (!validate($tbl_id,$var['data'], "allowretrieve")){
		return array('ErrNum'=> 4, "ErrDesc" => 'Not all fields are allowed retrieve');
	} else {
		$sql = "SELECT ";
		foreach ($var['data'] as $data){
			if ($data['field']=='*')
				return array('ErrNum'=> 7, "ErrDesc" => 'Wildcard not accepted');				
				
			$sql_b .= "`". $data['field']."`,";
		}
		if (strlen($var['clause'])>0){
			$sql_c .= 'WHERE '.$var['clause'] ."";
		}

		global $xoopsModuleConfig;
		if ($xoopsModuleConfig['site_user_auth']==1){
			if (!validateuser($var['username'],$var['password']))
				return false;
		}
		//echo $sql." ".substr($sql_b,0,strlen($str_b)-1)." FROM ".$xoopsDB->prefix(get_tablename($tbl_id))." ".$sql_c;
		
		$rt = $xoopsDB->queryf($sql." ".substr($sql_b,0,strlen($str_b)-1)." FROM ".$xoopsDB->prefix(get_tablename($tbl_id))." ".$sql_c);

		if (!$xoopsDB->getRowsNum($rt)){
			return array('ErrNum'=> 3, "ErrDesc" => 'No Records Returned from Query');
		} else {
			$rtn = array();
			while($row = $xoopsDB->fetchArray($rt)){
				$rdata = array();
				foreach ($var['data'] as $data){
					$rdata[] = array("fieldname"=> $data['field'], "value"=>$row[$data['field']]);
				}
				$rtn[] = $rdata;
			}
		
		}

		return array("total_records" => $xoopsDB->getRowsNum($rt), "data" => $rtn);
	
	}

}

// Define the method as a PHP function
function update($var) {
	global $xoopsDB;
	if (strlen($var['tablename'])>0) {
		$tbl_id = get_tableid($var['tablename']);
	} elseif ($var['id']>0) {
		$tbl_id = $var['id'];
	} else {
		return array('ErrNum'=> 2, "ErrDesc" => 'Table Name or Table ID not specified');
	}

	if (!validate($tbl_id,$var['data'], "allowupdate")){
		return array('ErrNum'=> 5, "ErrDesc" => 'Not all fields are allowed update');
	} else {
		$sql = "UPDATE ".$xoopsDB->prefix(get_tablename($tbl_id)).' SET ';
		foreach ($var['data'] as $data){
			if (!is_fieldkey($data['field'], $tbl_id)){
				$sql_b .= "`". $data['field']."` = '". addslashes($data['value'])."',";
			} else {
				if (strpos(' '.$data['value'],'%')>0||strpos(' '.$data['value'],'_')>0)
					return array('ErrNum'=> 7, "ErrDesc" => 'Wildcard not accepted');				
				$sql_c .= " WHERE `". $data['field']."` = '". addslashes($data['value'])."'";
			}
		}
		if (strlen($sql_c)==0)
			return array('ErrNum'=> 6, "ErrDesc" => 'No primary key set');

		global $xoopsModuleConfig;
		if ($xoopsModuleConfig['site_user_auth']==1){
			if (!validateuser($var['username'],$var['password']))
				return false;
		}
		return $xoopsDB->queryF($sql.substr($sql_b,0,strlen($sql_b)-1).$sql_c);
	}

}

// Define the method as a PHP function
function retrievekeys($var) {
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_fields')." WHERE `key` = 1 and visible = 1 ";
	if (strlen($var['tablename'])>0) {
		$sql .= "and tbl_id = ".get_tableid($var['tablename']);		
		$tbl_id = get_tableid($var['tablename']);
	} elseif ($var['id']>0) {
		$sql .= "and tbl_id = ".$var['id'];
		$tbl_id = $var['id'];
	} else {
		return array('ErrNum'=> 2, "ErrDesc" => 'Table Name or Table ID not specified');
	}

	$ret = $xoopsDB->query($sql);
	$sql = "SELECT ";
	$tmp = array();
	while ($row = $xoopsDB->fetchArray($ret)){
		$sql .= '`'.$row['fieldname'].'`';
		$tmp[] = $row['fieldname'];
		$t++;
		if ($t<$xoopsDB->getRowsNum($ret)){
 			$sql .= ', ';
		}
	}
	if (strlen($var['tablename'])>0) {
		$sql .= ' FROM '.$xoopsDB->prefix($var['tablename']);		
	} elseif ($var['id']>0) {
		$sql .= ' FROM '.$xoopsDB->prefix(get_tablename($var['id']));
	}
	if ($var['clause']==1){
		$sql .= ' WHERE `'.get_fieldname($var['fieldid'], $tbl_id).'` '.$var['clause'];
	}

	$ret = $xoopsDB->query($sql);
	$rtn = array();

	while ($row = $xoopsDB->fetchArray($ret)){
		$id++;
		$tmp_b = array();
		foreach ($tmp as $result){
			$tmp_b[] = array("field" => $result, "value" => $row[$result]);
		}
		$rtn[] = array( 'id' => $id,
						'data'=> $tmp_b);
		
	}

	global $xoopsModuleConfig;
	if ($xoopsModuleConfig['site_user_auth']==1){
		if (!validateuser($var['username'],$var['password']))
			return false;
	}
	return $rtn;
	
}

// Define the method as a PHP function
function retrievecrc($var) {
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_fields')." WHERE `crc` = 1 ";
	if (strlen($var['tablename'])>0) {
		$sql .= "and tbl_id = ".get_tableid($var['tablename']);		
		$tbl_id = get_tableid($var['tablename']);
	} elseif ($var['id']>0) {
		$sql .= "and tbl_id = ".$var['id'];
		$tbl_id = $var['id'];
	} else {
		return array('ErrNum'=> 2, "ErrDesc" => 'Table Name or Table ID not specified');
	}

	$ret = $xoopsDB->query($sql);
	$sql = "SELECT ";
	$tmp = array();
	while ($row = $xoopsDB->fetchArray($ret)){
		$sql .= '`'.$row['fieldname'].'`';
		$tmp[] = $row['fieldname'];
		$t++;
		if ($t<$xoopsDB->getRowsNum($ret)){
 			$sql .= ', ';
		}
	}
	if (strlen($var['tablename'])>0) {
		$sql .= ' FROM '.$xoopsDB->prefix($var['tablename']);		
	} elseif ($var['id']>0) {
		$sql .= ' FROM '.$xoopsDB->prefix(get_tablename($var['id']));
	}
	if ($var['clause']==1){
		$sql .= ' WHERE `'.get_fieldname($var['fieldid'], $tbl_id).'` '.$var['clause'];
	}

	$ret = $xoopsDB->query($sql);
	$rtn = array();

	while ($row = $xoopsDB->fetchArray($ret)){
		$id++;
		$tmp_b = array();
		$crc='';
		foreach ($tmp as $result){
			$tmp_b[] = array("field" => $result, "crc" => md5($row[$result]));
			$crc = md5($crc.$row[$result]);
		}
		$rtn[] = array( 'id' => $id,
						'crc' => $crc,
						'data'=> $tmp_b);
	
	}

	global $xoopsModuleConfig;
	if ($xoopsModuleConfig['site_user_auth']==1){
		if (!validateuser($var['username'],$var['password']))
			return false;
	}
	return $rtn;
	
}

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