<?

	include(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/inc/usercheck.php');
	include(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/inc/authcheck.php');	
	include(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/inc/siteinfocheck.php');		
	if (!class_exists("XoopsMailer"))
	{
		include(XOOPS_ROOT_PATH.'/class/xoopsmailer.php');
	}

	
	function xoops_lostpassword_xsd(){
			$xsd = array();
		$i=0;
		$xsd['request'][$i] = array("name" => "username", "type" => "string");
		$xsd['request'][$i++] = array("name" => "password", "type" => "string");	
		$data = array();

			$data[] = array("name" => "email", "type" => "string");		
			$data[] = array("name" => "code", "type" => "string");
			$data_b = array();
				$data_b[] = array("name" => "sitename", "type" => "string");
				$data_b[] = array("name" => "adminmail", "type" => "string");
				$data_b[] = array("name" => "xoops_url", "type" => "string");
				$data_b[] = array("name" => "ip", "type" => "string");
			$data[] = array("items" => array("data" => $data_b, "objname" => "siteinfo"));					
		$xsd['request'][$i++]['items']['data'] = $data;
		$xsd['request'][$i]['items']['objname'] = 'lost';
		
		$i=0;
		$xsd['response'][$i] = array("name" => "ERRNUM", "type" => "integer");
		$data = array();
			$data[] = array("name" => "text", "type" => "string");
		$i++;
		$xsd['response'][$i]['items']['data'] = $data;
		$xsd['response'][$i]['items']['objname'] = 'RESULT';
	}
	
	function xoops_lostpassword_wsdl(){
	
	}
	
	function xoops_lostpassword_wsdl_service(){
	
	}
	
	function xoops_lostpassword($username, $password, $lost)
	{	

		global $xoopsModuleConfig, $xoopsConfig;
		if ($xoopsModuleConfig['site_user_auth']==1){
			if (!checkright(basename(__FILE__),$username,$password))
				return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
		}

		if ($lost['passhash']!=''){
			if ($lost['passhash']!=sha1(($lost['time']-$lost['rand']).$lost['email'].$lost['code']))
				return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		} else {
			return array("ERRNUM" => 4, "ERRTXT" => 'No Passhash');
		}
		
		foreach($lost as $k => $l){
			${$k} = $l;
		}
		

		$myts =& MyTextSanitizer::getInstance();

		$member_handler =& xoops_gethandler('member');
		$getuser =& $member_handler->getUsers(new Criteria('email', $myts->addSlashes($email)));

		include_once XOOPS_ROOT_PATH.'/class/auth/authfactory.php';
		include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/auth.php';
		$xoopsAuth =& XoopsAuthFactory::getAuthConnection($myts->addSlashes($getuser[0]->getVar("uname")));

		if (check_auth_class($xoopsAuth)==true){
			
			return $xoopsAuth->lost_password($email, $code, $siteinfo);
			
		} else {
			$myts =& MyTextSanitizer::getInstance();
			$member_handler =& xoops_gethandler('member');
			$getuser =& $member_handler->getUsers(new Criteria('email', $myts->addSlashes($email)));

			if (empty($getuser)) {
				return _US_SORRYNOTFOUND;
			} else {
				$areyou = substr($getuser[0]->getVar("pass"), 0, 5);
				if ($code != '' && $areyou == $code) {
					$newpass = xoops_makepass();
					$xoopsMailer =& getMailer();
					$xoopsMailer->useMail();
					$xoopsMailer->setTemplate("lostpass2.tpl");
					$xoopsMailer->assign("SITENAME", $siteinfo['sitename']);
					$xoopsMailer->assign("ADMINMAIL", $siteinfo['adminmail']);
					$xoopsMailer->assign("SITEURL", $siteinfo['xoops_url']."/");
					$xoopsMailer->assign("IP", $siteinfo['ip']);
					$xoopsMailer->assign("NEWPWD", $newpass);
					$xoopsMailer->setToUsers($getuser[0]);
					$xoopsMailer->setFromEmail($siteinfo['adminmail']);
					$xoopsMailer->setFromName($siteinfo['sitename']);
					$xoopsMailer->setSubject(sprintf(_US_NEWPWDREQ,$siteinfo['xoops_url']));
					if ( !$xoopsMailer->send() ) {
						return $xoopsMailer->getErrors();
					}

					// Next step: add the new password to the database
					$sql = sprintf("UPDATE %s SET pass = '%s' WHERE uid = %u", $xoopsDB->prefix("users"), md5($newpass), $getuser[0]->getVar('uid'));
					if ( !$xoopsDB->queryF($sql) ) {
						return _US_MAILPWDNG;
						exit();
					}
					return sprintf(_US_PWDMAILED,$getuser[0]->getVar("uname"));
				// If no Code, send it
				} else {
					$xoopsMailer =& getMailer();
					$xoopsMailer->useMail();
					$xoopsMailer->setTemplate("lostpass1.tpl");
					$xoopsMailer->assign("SITENAME", $siteinfo['sitename']);
					$xoopsMailer->assign("ADMINMAIL", $siteinfo['adminmail']);
					$xoopsMailer->assign("SITEURL", $siteinfo['xoops_url']."/");
					$xoopsMailer->assign("IP", $siteinfo['ip']);
					$xoopsMailer->assign("NEWPWD_LINK", $siteinfo['xoops_url']."/lostpass.php?email=".$email."&code=".$areyou);
					$xoopsMailer->setToUsers($getuser[0]);
					$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
					$xoopsMailer->setFromName($xoopsConfig['sitename']);
					$xoopsMailer->setSubject(sprintf(_US_NEWPWDREQ,$xoopsConfig['sitename']));
					if ( !$xoopsMailer->send() ) {
						return $xoopsMailer->getErrors();
					}
					return "<h4>". sprintf(_US_CONFMAIL,$getuser[0]->getVar("uname"))."</h4>";
				}
			}				
		}
	}

?>