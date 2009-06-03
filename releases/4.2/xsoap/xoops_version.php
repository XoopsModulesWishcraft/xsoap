<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 xoops.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //


$modversion['name']		    = 'x-Soap';
$modversion['version']		= 4.2;
$modversion['releasedate'] = "Wed: 3 Sept 2008";
$modversion['status'] = "Final";
$modversion['author'] = "Chronolabs Australia";
$modversion['credits'] = "Simon Roberts";
$modversion['teammembers'] = "Wishcraft";
$modversion['license'] = "GNU see LICENSE";
$modversion['official'] = 0;
$modversion['description']	= 'SOAP Server to exchange XML SQL Queries with other services.';
$modversion['help']		    = "";
$modversion['image']		= "images/xsoap_slogo.png";
$modversion['dirname']		= 'xsoap';

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['author_realname'] = "Simon Roberts";
$modversion['author_website_url'] = "http://www.chronolabs.org.au";
$modversion['author_website_name'] = "Chronolabs International";
$modversion['author_email'] = "simon@chronolabs.org.au";
$modversion['demo_site_url'] = "";
$modversion['demo_site_name'] = "";
$modversion['support_site_url'] = "http://www.chronolabs.org.au/forums/x-Soap/0,10,0,0,100,0,DESC,0";
$modversion['support_site_name'] = "x-Soap";
$modversion['submit_bug'] = "http://www.chronolabs.org.au/forums/x-Soap/0,10,0,0,100,0,DESC,0";
$modversion['submit_feature'] = "http://www.chronolabs.org.au/forums/x-Soap/0,10,0,0,100,0,DESC,0";
$modversion['usenet_group'] = "sci.chronolabs";
$modversion['maillist_announcements'] = "";
$modversion['maillist_bugs'] = "";
$modversion['maillist_features'] = "";

// Tables created by sql file (without prefix!)
$modversion['tables'][0]	= 'soap_tables';
$modversion['tables'][1]	= 'soap_fields';
$modversion['tables'][2]	= 'soap_plugins';

// Admin things
$modversion['hasAdmin']		= 1;
$modversion['adminindex']	= "admin/index.php";
$modversion['adminmenu']	= "admin/menu.php";

// Search
$modversion['hasSearch'] = 0;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "content_search";

// Menu
$modversion['hasMain'] = 1;

// Smarty
$modversion['use_smarty'] = 1;

$modversion['config'][1]['name'] = 'wsdl';
$modversion['config'][1]['title'] = '_XS_WDSL';
$modversion['config'][1]['description'] = '_XS_WDSLDESC';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 0;

$modversion['config'][2]['name'] = 'site_user_auth';
$modversion['config'][2]['title'] = '_XS_USERAUTH';
$modversion['config'][2]['description'] = '_XS_USERAUTHDESC';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 1;
?>
