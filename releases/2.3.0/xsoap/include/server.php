<?php

global $xoopsModuleConfig,$xoopsModule;

require_once('nusoap/nusoap.php');
require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/class/class.functions.php');
require_once('common.php');

$server = new soap_server($xoopsModuleConfig['wsdl']);

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
		$server->register($function);
	}
}
?>