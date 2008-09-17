<?php
/**
 * $Id: admin_header.php v 1.13 06 july 2004 Catwolf Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
 error_reporting(E_ALL);
include '../../../mainfile.php';
include '../../../include/cp_header.php';
include '../include/functions.php';

include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
	
if (is_object($xoopsUser)) {
    $xoopsModule = XoopsModule::getByDirname("xsoap");
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . "/", 3, _NOPERM);
        exit();
    } 
} else {
    redirect_header(XOOPS_URL . "/", 1, _NOPERM);
    exit();
}
$myts = &MyTextSanitizer::getInstance();
error_reporting(E_ALL);
function adminmenu(){
	global $xoopsModule;

	echo "
		<table width='100%' cellspacing='0' cellpadding='0' border='0' class='outer'>\n
		<tr>\n
		<td style='font-size: 10px; text-align: left; color: #2F5376; padding: 2px 6px; line-height: 18px;'>\n
		<a href='../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule -> getVar('mid') . "'>Preferences</a> | \n
		<a href='../admin/index.php?op=tables'>Tables</a> | \n
		<a href='../admin/index.php?op=fields'>Fields</a> | \n
		<a href='../admin/index.php?op=views'>Views</a> | \n
		<a href='../admin/index.php?op=plugins'>Plugin's</a> \n
		</td>\n
		</tr>\n
		</table><br />";
}
?>