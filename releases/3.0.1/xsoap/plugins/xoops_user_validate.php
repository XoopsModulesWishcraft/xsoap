<?
	include(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/inc/usercheck.php');	
	
	function xoops_user_validate_xsd(){
		$xsd = array();
		$i=0;
		$xsd['request'][$i] = array("name" => "username", "type" => "string");
		$xsd['request'][$i++] = array("name" => "password", "type" => "string");	
		$data = array();
			$data[] = array("name" => "uname", "type" => "string");
			$data[] = array("name" => "pass", "type" => "string");		
			$data[] = array("name" => "vpass", "type" => "string");
			$data[] = array("name" => "email", "type" => "string");		
		$xsd['request'][$i++]['items']['data'] = $data;
		$xsd['request'][$i++]['items']['objname'] = 'validate';
		
		$i=0;
		$xsd['response'][$i] = array("name" => "ERRNUM", "type" => "integer");
		$xsd['response'][$i] = array("name" => "RESULT", "type" => "string");
		
		return $xsd;
	}
	
	function xoops_user_validate_wsdl(){
	
	}
	
	function xoops_user_validate_wsdl_service(){
	
	}
	
	$ret= explode(" ",XOOPS_VERSION);
	$ver= explode(".",$ret[1]);
	
	if ($ret[0]>=2&&$ret[1]>=3){

		xoops_load("userUtility");

		function xoops_user_validate($username, $password, $validate)
		{	
	
			global $xoopsModuleConfig, $xoopsConfig;
			if ($xoopsModuleConfig['site_user_auth']==1){
				if (!checkright(basename(__FILE__),$username,$password))
					return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
	
			if ($validate['passhash']!=''){
				if ($validate['passhash']!=sha1(($validate['time']-$validate['rand']).$validate['uname'].$validate['pass']))
					return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
			} else {
				return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
			}
	
			return array('ERRNUM' => 1, 'RESULT' => XoopsUserUtility::validate($validate['uname'], $validate['email'], $validate['pass'], $validate['vpass']));
		}
	} else { // LEGACY SUPPORT
	
			function xoops_user_validate($username, $password, $validate)
		{	
	
			global $xoopsModuleConfig, $xoopsConfig;
			if ($xoopsModuleConfig['site_user_auth']==1){
				if (!checkright(basename(__FILE__),$username,$password))
					return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
			}
	
			if ($validate['passhash']!=''){
				if ($validate['passhash']!=sha1(($validate['time']-$validate['rand']).$validate['uname'].$validate['pass']))
					return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
			} else {
				return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
			}
	
			return array('ERRNUM' => 1, 'RESULT' => userCheck($validate['uname'], $validate['email'], $validate['pass'], $validate['vpass']));
		}
	}
	
	
?>