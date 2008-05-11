<?php

global $xoopsModuleConfig,$xoopsModule;

if (!file_exists(XOOPS_ROOT_PATH.'/class/soap/xoopssoap.php')){
	foreach (get_loaded_extensions() as $ext){
		if ($ext=="soap"){
			$native=true;
		}
	}
	
	if ($native!=true) {
		define('SOAPLIB','NUSOAP');
		require_once('nusoap/nusoap.php');
	} else {
		define('SOAPLIB','INHERIT');
	}
} else {
	require_once (XOOPS_ROOT_PATH.'/class/soap/xoopssoap.php');
}

require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/class/class.functions.php');
require_once('common.php');

	switch(SOAPLIB){
	case "NUSOAP":
		if ($xoopsModuleConfig['wsdl']==1){
			if (!isset($_GET['wsdl'])) {
				$server = new soap_server("xsoap.wsdl");
			} else {
				$server = new soap_server($_GET['wsdl'].'.service.wsdl');
			}
		} else {
			$server = new soap_server();
		}
		break;
	case "INHERIT":
		if ($xoopsModuleConfig['wsdl']==1){
			if (!isset($_GET['wsdl'])) {
				$server = new SoapServer("xsoap.wsdl");
			} else {
				$server = new SoapServer($_GET['wsdl'].'.service.wsdl');
			}
		} else {
			$server = new SoapServer();
		}
		break;
	}
	

$funct = new FunctionsHandler($xoopsModuleConfig['wsdl']);

$FunctionDefine = array();
foreach($funct->GetServerExtensions() as $extension){
	global $xoopsDB;
	$sql = "SELECT count(*) rc FROM ".$xoopsDB->prefix('soap_plugins'). " where active = 1 and plugin_file = '".$extension."'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	if ($row['rc']==1){
		require_once(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'. $extension);
		$FunctionDefine[] = substr( $extension,0,strlen( $extension)-4);
	}	
}

$FunctionDefine = array_unique($FunctionDefine);

foreach($FunctionDefine as $function){
	if (function_exists($function)){
		switch(SOAPLIB){
		case "NUSOAP":
			$server->register($function,array(),array(),XOOP_URL."/modules/xsoap/wdsl/$function");
			break;
		case "INHERIT":
			$server->addFunction($function);
			break;
		}
	}
}
if (SOAPLIB!="NUSOAP")
	$server->handle();
?>