<?
	
	include(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/inc/usercheck.php');
	
	function xoops_check_activation_xsd(){
		$xsd = array();
		$i=0;
		$xsd['request'][$i] = array("name" => "username", "type" => "string");
		$xsd['request'][$i++] = array("name" => "password", "type" => "string");	
		$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");		
		$xsd['request'][$i++]['items']['data'] = $data;
		$xsd['request'][$i++]['items']['objname'] = 'auth';
		
		$i=0;
		$xsd['response'][$i] = array("name" => "ERRNUM", "type" => "integer");
		$data = array();
			$data[] = array("name" => "uid", "type" => "integer");
			$data[] = array("name" => "uname", "type" => "string");		
			$data[] = array("name" => "email", "type" => "string");
			$data[] = array("name" => "user_from", "type" => "string");
			$data[] = array("name" => "name", "type" => "integer");
			$data[] = array("name" => "url", "type" => "string");		
			$data[] = array("name" => "user_icq", "type" => "string");
			$data[] = array("name" => "user_sig", "type" => "string");
			$data[] = array("name" => "user_viewemail", "type" => "integer");
			$data[] = array("name" => "user_aim", "type" => "string");		
			$data[] = array("name" => "user_yim", "type" => "string");
			$data[] = array("name" => "user_msnm", "type" => "string");
			$data[] = array("name" => "attachsig", "type" => "integer");
			$data[] = array("name" => "timezone_offset", "type" => "string");		
			$data[] = array("name" => "notify_method", "type" => "integer");
			$data[] = array("name" => "user_occ", "type" => "string");											
			$data[] = array("name" => "bio", "type" => "string");											
			$data[] = array("name" => "user_intrest", "type" => "string");	
			$data[] = array("name" => "user_mailok", "type" => "integer");																			
		$i++;
		$xsd['response'][$i]['items']['data'] = $data;
		$xsd['response'][$i]['items']['objname'] = 'RESULT';
		
		return $xsd;
	}
	
	function xoops_check_activation_wsdl(){
	
	}
	
	function xoops_check_activation_wsdl_service(){
	
	}
	
	function xoops_check_activation($username, $password, $user)
	{	

		global $xoopsModuleConfig, $xoopsConfig;
		if ($xoopsModuleConfig['site_user_auth']==1){
			if (!checkright(basename(__FILE__),$username,$password))
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
		}

		if ($auth['passhash']!=''){
			if ($auth['passhash']!=sha1(($auth['time']-$auth['rand']).$auth['username'].$auth['password']))
				return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		} else {
			return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		}
		
		foreach($user as $k => $l){
			${$k} = $l;
		}
		
		if (strlen(userCheck($uname, $email, $pass, $pass))==0){

			global $xoopsConfig, $xoopsConfigUser;
			$member_handler =& xoops_gethandler('member');
			$newuser =& $member_handler->createUser();
			$newuser->setVar('user_viewemail',$user_viewemail, true);
			$newuser->setVar('uname', $uname, true);
			$newuser->setVar('email', $email, true);
			if ($url != '') {
				$newuser->setVar('url', formatURL($url), true);
			}
			$newuser->setVar('user_avatar','blank.gif', true);
			$actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
			$newuser->setVar('actkey', $actkey, true);
			$newuser->setVar('pass', md5($pass), true);
			$newuser->setVar('timezone_offset', $timezone_offset, true);
			$newuser->setVar('user_regdate', time(), true);
			$newuser->setVar('uorder',$xoopsConfig['com_order'], true);
			$newuser->setVar('umode',$xoopsConfig['com_mode'], true);
			$newuser->setVar('user_mailok',$user_mailok, true);
			if ($xoopsConfigUser['activation_type'] == 1) {
				$newuser->setVar('level', 1, true);
			}
	
			if (!$member_handler->insertUser($newuser)) {
				$return = array('id' => 1, "text" => _US_REGISTERNG);
			}
			$newid = $newuser->getVar('uid');
			if (!$member_handler->addUserToGroup(XOOPS_GROUP_USERS, $newid)) {
				$return = array('id' => 1, "text" => _US_REGISTERNG);
			}
			if ($xoopsConfigUser['activation_type'] == 1) {
				$return = array('id' => 2,  "user" => $newuser);
			}
			// Sending notification email to user for self activation
			if ($xoopsConfigUser['activation_type'] == 0) {
				if (!function_exists('getMailer'))
					$xoopsMailer =& xoops_getMailer();
				if (!function_exists('xoops_getMailer'))
					$xoopsMailer =& getMailer();
				$xoopsMailer->useMail();
				$xoopsMailer->setTemplate('register.tpl');
				$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
				$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
				$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
				$xoopsMailer->setToUsers(new XoopsUser($newid));
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				$xoopsMailer->setFromName($xoopsConfig['sitename']);
				$xoopsMailer->setSubject(sprintf(_US_USERKEYFOR, $uname));
				if ( !$xoopsMailer->send() ) {
					$return = array('id' => 1, "text" => _US_YOURREGMAILNG);
				} else {
					$return = array('id' => 1, "text" => _US_YOURREGISTERED);
				}
			// Sending notification email to administrator for activation
			} elseif ($xoopsConfigUser['activation_type'] == 2) {
				$xoopsMailer =& xoops_getMailer();
				$xoopsMailer->useMail();
				$xoopsMailer->setTemplate('adminactivate.tpl');
				$xoopsMailer->assign('USERNAME', $uname);
				$xoopsMailer->assign('USEREMAIL', $email);
				$xoopsMailer->assign('USERACTLINK', XOOPS_URL.'/register.php?op=actv&id='.$newid.'&actkey='.$actkey);
				$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
				$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
				$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
				$member_handler =& xoops_gethandler('member');
				$xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['activation_group']));
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				$xoopsMailer->setFromName($xoopsConfig['sitename']);
				$xoopsMailer->setSubject(sprintf(_US_USERKEYFOR, $uname));
				if ( !$xoopsMailer->send() ) {
					$return = array('id' => 1, "text" => _US_YOURREGMAILNG);
				} else {
					$return = array('id' => 1, "text" => _US_YOURREGISTERED2);
				}
			}
			if ($xoopsConfigUser['new_user_notify'] == 1 && !empty($xoopsConfigUser['new_user_notify_group'])) {
				$xoopsMailer =& xoops_getMailer();
				$xoopsMailer->useMail();
				$member_handler =& xoops_gethandler('member');
				$xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['new_user_notify_group']));
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				$xoopsMailer->setFromName($xoopsConfig['sitename']);
				$xoopsMailer->setSubject(sprintf(_US_NEWUSERREGAT,$xoopsConfig['sitename']));
				$xoopsMailer->setBody(sprintf(_US_HASJUSTREG, $uname));
				$xoopsMailer->send();
			}
		
			return array("ERRNUM" => 1, "RESULT" => $return);
		
		} else {

			return array("ERRNUM" => 1, "RESULT" => array('id' => 1, "text" => userCheck($uname, $email, $pass, $pass));

		}				

	}

	
?>