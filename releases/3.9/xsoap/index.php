<?
include ('../../mainfile.php');
error_reporting(E_ALL);
require ('include/server.php');

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';

if ($HTTP_RAW_POST_DATA=='') {
	header('Location: '.XOOPS_URL);
	exit;
} else {
	$server->service($HTTP_RAW_POST_DATA);
	exit;
}


?>