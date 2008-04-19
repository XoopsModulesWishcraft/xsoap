<?

// Define the method as a PHP function
function tableschemer($var) {
	global $xoopsModuleConfig;
	if ($xoopsModuleConfig['site_user_auth']==1){
		if (!checkright(basename(__FILE__),$var['username'],$var['password']))
			return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
	}
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

?>