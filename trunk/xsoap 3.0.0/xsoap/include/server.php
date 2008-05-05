<?php

global $xoopsModuleConfig,$xoopsModule;

if (!define("SOAP_1_2")&&!define("SOAP_1_1")) {
	define('SOAPLIB','NUSOAP');
	require_once('nusoap/nusoap.php');
} else {
	define('SOAPLIB','INHERIT');
}

require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/class/class.functions.php');
require_once('common.php');

if ($xoopsModuleConfig['wsdl']==1){
	if (!isset($_GET['wsdl'])) {
		$server = new soap_server(XOOPS_URL."/modules/xsoap/xsoap.wsdl.services.php");
	} else {
		$server = new soap_server(XOOPS_URL."/modules/xsoap/xsoap.wsdl.service.php?funcname=".$_GET['wsdl']);
	}
} else {
	$server = new soap_server();
}


$funct = new FunctionsHandler($xoopsModuleConfig['wsdl']);

$FunctionDefine = array();
global $xoopsDB;
$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_plugins'). " where active = 1";
$ret = $xoopsDB->queryF($sql);

while($row = $xoopsDB->fetchArray($ret)){

	require_once(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'.$row['plugin_file']);
	$FunctionDefine[] = $row['plugin_name'];

	
}
$FunctionDefine = array_unique($FunctionDefine);

foreach($FunctionDefine as $function){
	if (function_exists($function)){
		switch(SOAPLIB){
		case "NUSOAP":
			$server->register($function);
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