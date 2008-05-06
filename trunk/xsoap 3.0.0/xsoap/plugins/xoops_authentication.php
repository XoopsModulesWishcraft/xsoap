<?
	
	function xoops_authentication_xsd(){
		$xsd = array();
		$i=0;
		$xsd['request'][$i] = array("name" => "username", "type" => "string");
		$xsd['request'][$i++] = array("name" => "password", "type" => "string");	
		$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");		
		$xsd['request'][$i++]['items'] = $data;
		
		$i=0;
		$xsd['response'][$i] = array("name" => "ERRNUM", "type" => "integer");
		$data = array();
			$data[] = array("name" => "uid", "type" => "integer");
			$data[] = array("name" => "uname", "type" => "string");		
			$data[] = array("name" => "email", "type" => "string");
			$data[] = array("name" => "name", "type" => "string");		
		$xsd['response'][$i++]['items'] = $data;
		
		return $xsd;
	}
	
	function xoops_authentication_wsdl(){
	
	}
	
	function xoops_authentication_wsdl_service(){
	
	}

	function xoops_authentication($username, $password, $auth)
	{	

		global $xoopsModuleConfig;
		if ($xoopsModuleConfig['site_user_auth']==1){
			if (!checkright(basename(__FILE__),$var['username'],$var['password']))
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
		}

		if ($auth['passhash']!=''){
			if ($auth['passhash']!=sha1(($auth['time']-$auth['rand']).$auth['username'].$auth['password']))
				return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		} else {
			return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		}

		include_once XOOPS_ROOT_PATH.'/class/auth/authfactory.php';
		include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/auth.php';
		$xoopsAuth =& XoopsAuthFactory::getAuthConnection($myts->addSlashes($auth['username']));
		$user = $xoopsAuth->authenticate($myts->addSlashes($auth['username']), $myts->addSlashes($auth['password']));
		
		if(is_object($user))
			$row =array("uid" => $user->getVar('uid'),"uname" => $user->getVar('uname'),"email" => $user->getVar('email'),
						"location" => $user->getVar('location'),"name" => $user->getVar('name'));
						
		$rt=array('XoopsUserObj' => $row);
		if ($rowb['rc']>0){
			if (!empty($rt)){
				return array("ERRNUM" => 1, "RESULT" => $rt);
			} else {
				return array("ERRNUM" => 3, "ERRTXT" => _ERR_FUNCTION_FAIL);
			}				
		} else {
			return array("ERRNUM" => 3, "ERRTXT" => _ERR_FUNCTION_FAIL);
		}

			
//		$rt=$vogoo_items->visitor_get_recommended_items($cat, $filter);


	}

?>