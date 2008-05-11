<?
	
	function xoops_network_disclaimer_xsd(){
		$xsd = array();
		$i=0;
		$xsd['request'][$i] = array("name" => "username", "type" => "string");
		$xsd['request'][$i++] = array("name" => "password", "type" => "string");	

		
		$i=0;
		$xsd['response'][$i] = array("name" => "ERRNUM", "type" => "integer");
		$xsd['response'][$i] = array("name" => "RESULT", "type" => "string");
		
		
		return $xsd;
	}
	
	function xoops_network_disclaimer_wsdl(){
	
	}
	
	function xoops_network_disclaimer_wsdl_service(){
	
	}
	
	function xoops_network_disclaimer($username, $password)
	{	

		global $xoopsModuleConfig, $xoopsConfig;
		if ($xoopsModuleConfig['site_user_auth']==1){
			if (!checkright(basename(__FILE__),$username,$password))
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
		}

		$config_handler =& xoops_gethandler('config');
		$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

		return array("ERRNUM" => 1, "RESULT" => $xoopsConfigUser['reg_disclaimer']);

	}

?>