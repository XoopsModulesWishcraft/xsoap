<?
function viewsforretrieve_xsd(){
	$xsd = array();
	$xsd['request'][$i] = array("name" => "", "type" => "");
	$i++;
}

function viewsforretrieve_wsdl(){

}

function viewsforretrieve_wsdl_service(){

}
// Define the method as a PHP function
function viewsforretrieve($var) {
	global $xoopsModuleConfig;
	if ($xoopsModuleConfig['site_user_auth']==1){
		if (!checkright(basename(__FILE__),$var['username'],$var['password']))
			return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
	}
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')." WHERE allowretrieve = 1 and visible = 1 and view = 1";
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