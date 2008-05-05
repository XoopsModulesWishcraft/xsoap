<?

function tablesforpost_xsd(){
	$xsd = array();
	$xsd['request'][] = array("name" => "username", "type" => "string");
	$xsd['request'][] = array("name" => "password", "type" => "string");

	$xsd['response'][] = array("name" => "id", "type" => "integer");
	$xsd['response'][] = array("name" => "table", "type" => "string");

	return $xsd;	
}

function tablesforpost_wsdl(){

}

function tablesforpost_wsdl_service(){

}

// Define the method as a PHP function
function tablesforpost($var) {
	global $xoopsModuleConfig;
	if ($xoopsModuleConfig['site_user_auth']==1){
		if (!checkright(basename(__FILE__),$var['username'],$var['password']))
			return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
	}
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

?>