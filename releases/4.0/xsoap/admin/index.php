<?

include 'admin_header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
error_reporting(E_ALL);
global $xoopsDB;

  if (isset($HTTP_GET_VARS)) {
    foreach ($HTTP_GET_VARS as $k => $v) {
      $$k = $v;
    }
  }

  if (isset($HTTP_POST_VARS)) {
    foreach ($HTTP_POST_VARS as $k => $v) {
      $$k = $v;
    }
  }
  
switch ($op){

case "fields":

if (!$tbl_id)
	$tbl_id=1;
	
xoops_cp_header();
adminmenu();

$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')." where view = '0'";
$ret = $xoopsDB->queryF($sql);
?>
<table width="100%" border="0" cellspacing="0">
  <tr class="head">
    <td width="16%" valign="top"><div align="right">Select Table:</div></td>
    <td width="84%" valign="top"><form>
      <label>
        <select name="select" id="select" onchange="window.location=''+this.options[this.selectedIndex].value">
        <?php while($row = $xoopsDB->fetchArray($ret)) { ?>
        <option value="index.php?op=fields&tbl_id=<?php echo $row['tbl_id']; ?>" <?php if ($tbl_id == $row['tbl_id']) { echo "selected"; }?>><?php echo $row['tablename'];?></option>
        <?php } ?>
        </select>
        </label>
    </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
 <?
 $sql = "SHOW FIELDS FROM ".$xoopsDB->prefix(get_tablename($tbl_id));
$ret = $xoopsDB->queryF($sql);

?>
</p>
<form action='index.php' method='post'>
<table width="100%" border="0" cellspacing="0">
  <tr class="head">
    <td width="56%">Field Name</td>
    <td width="8%" align="center" valign="middle">Allow Post</td>
    <td width="9%" align="center" valign="middle">Allow Retrieve</td>
    <td width="9%" align="center" valign="middle">Allow Update</td>
    <td width="9%" align="center" valign="middle">Visible</td>
    <td width="9%" align="center" valign="middle">Use for CRC</td>
  </tr>
<?
	$field=0;
	$tbldat = get_tableconfig(get_tablename($tbl_id));
	
	while(list($fieldname, $type, $null, $keytype, $tmp, $tmp) = $xoopsDB->fetchRow($ret)){
	 $field++;
		if ($class == 'odd'){
			$class= 'even';
		} else {
			$class = 'odd';
		}

		$int = 0;
		$string = 0;
		$float = 0;
		$text = 0;
		$other = 0;		
		$key = 0;
		if (strpos(' '.$type,'int')>0){
			$int = 1;
		} elseif (strpos(' '.$type,'char')>0){
			$string = 1;
		} elseif (strpos(' '.$type,'float')>0){
			$float = 1;
		} elseif (strpos(' '.$type,'text')>0){
			$text = 1;
		} else {
			$other = 1;
		}
		
		if ($keytype == "PRI"){
			$key = 1;
		}
		$tbldata = get_fieldconfig($fieldname, $tbl_id);
	if (!isset($tbldata)){
		$new++;
?>
  <tr class="<?php echo $class; ?>">
    <td><input type="hidden" name="id[<?php echo $field; ?>]" name="id[<?php echo $field; ?>]" value="new">
    <input type="hidden" name="key[<?php echo $field; ?>]" name="key[<?php echo $field; ?>]" value="<?php echo $key; ?>">
	<input type="hidden" name="string[<?php echo $field; ?>]" name="string[<?php echo $field; ?>]" value="<?php echo $string; ?>">
	<input type="hidden" name="int[<?php echo $field; ?>]" name="int[<?php echo $field; ?>]" value="<?php echo $int; ?>">
	<input type="hidden" name="float[<?php echo $field; ?>]" name="float[<?php echo $field; ?>]" value="<?php echo $float; ?>">
    <input type="hidden" name="text[<?php echo $field; ?>]" name="text[<?php echo $field; ?>]" value="<?php echo $text; ?>">
    <input type="hidden" name="other[<?php echo $field; ?>]" name="other[<?php echo $field; ?>]" value="<?php echo $other; ?>">
    <input type="hidden" name="fieldname[<?php echo $field; ?>]" name="fieldname[<?php echo $field; ?>]" value="<?php echo $fieldname; ?>">
	<strong><?php echo $fieldname; ?></strong> (new)</td>
    <td align="center" valign="middle"><input <?php if ($key==1) { echo 'disabled="disabled"'; } elseif ($tbldat['allowpost']==1) { echo 'checked'; } ?>  name="post[<?php echo $field; ?>]" type="checkbox" id="post[<?php echo $field; ?>]" value="1"></td>
    <td align="center" valign="middle"><input <?php if ($tbldat['allowretrieve']==1) { echo 'checked'; } ?> name="retrieve[<?php echo $field; ?>]" type="checkbox" id="retrieve[<?php echo $field; ?>]" value="1"></td>
    <td align="center" valign="middle"><input <?php if ($key==1) { echo 'disabled="disabled"'; } elseif ($tbldat['allowupdate']==1) { echo 'checked'; } ?> name="update[<?php echo $field; ?>]" type="checkbox" id="update[<?php echo $field; ?>]" value="1">
    <td align="center" valign="middle"><input  <?php if ($tbldat['visible']==1) { echo 'checked'; } ?>  name="visible[<?php echo $field; ?>]" type="checkbox" id="visible[<?php echo $field; ?>]" value="1">
    <td align="center" valign="middle"><input <?php if ($key==1) { echo 'disabled="disabled"'; } ?> name="crc[<?php echo $field; ?>]" type="checkbox" id="crc[<?php echo $field; ?>]" value="1">
    </td>
  </tr>
  
 <?php } else { ?>
  
   <tr class="<?php echo $class; ?>">
    <td><strong><input type="hidden" name="key[<?php echo $field; ?>]" name="key[<?php echo $field; ?>]" value="<?php echo $key; ?>">
	<input type="hidden" name="string[<?php echo $field; ?>]" name="string[<?php echo $field; ?>]" value="<?php echo $string; ?>">
	<input type="hidden" name="int[<?php echo $field; ?>]" name="int[<?php echo $field; ?>]" value="<?php echo $int; ?>">
	<input type="hidden" name="float[<?php echo $field; ?>]" name="float[<?php echo $field; ?>]" value="<?php echo $float; ?>">
    <input type="hidden" name="text[<?php echo $field; ?>]" name="text[<?php echo $field; ?>]" value="<?php echo $text; ?>">
    <input type="hidden" name="other[<?php echo $field; ?>]" name="other[<?php echo $field; ?>]" value="<?php echo $other; ?>"><input type="hidden" name="id[<?php echo $field; ?>]" name="id[<?php echo $field; ?>]" value="<?php echo $tbldata['fld_id']; ?>"><input type="hidden" name="fieldname[<?php echo $field; ?>]" name="fieldname[<?php echo $field; ?>]" value="<?php echo $fieldname; ?>"><?php echo $fieldname; ?></strong></td>
    <td align="center" valign="middle"><input <?php if ($key==1) { echo 'disabled="disabled"'; } ?> name="post[<?php echo $field; ?>]" type="checkbox" id="post[<?php echo $field; ?>]" value="1" <?php if ($tbldata['allowpost']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="retrieve[<?php echo $field; ?>]"  type="checkbox" id="retrieve[<?php echo $field; ?>]" value="1"  <?php if ($tbldata['allowretrieve']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input <?php if ($key==1) { echo 'disabled="disabled"'; } ?> name="update[<?php echo $field; ?>]"  type="checkbox" id="update[<?php echo $field; ?>]" value="1"  <?php if ($tbldata['allowupdate']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="visible[<?php echo $field; ?>]" type="checkbox" id="visible[<?php echo $field; ?>]" value="1"  <?php if ($tbldata['visible']==1) { echo "checked"; } ?>>
    <td align="center" valign="middle"><input <?php if ($key==1) { echo 'disabled="disabled"'; } ?> name="crc[<?php echo $field; ?>]" type="checkbox" id="crc[<?php echo $field; ?>]" value="1"  <?php if ($tbldata['crc']==1) { echo "checked"; } ?>>
  </tr>
  
 <?php } 
	} 
 ?>
</table>
<input type="hidden" name="tbl_id" value="<?php echo $tbl_id; ?>">
<input type="hidden" name="op" value="savefields">
<input type="hidden" name="new" value="<?php echo $new; ?>"><br/>
<center><input type="submit" name="submit" value="Submit!"></center>
</form>
<?
	xoops_cp_footer();
	break;
case "savefields":

	foreach ($id as $f){
		$tt++;	
		switch ($f){
		case "new":
			$sql = "INSERT INTO ".$xoopsDB->prefix('soap_fields')." (tbl_id, fieldname, allowpost, allowretrieve, allowupdate, visible, `key`, `string`, `int`, `float`, `text`, `other`, `crc`) VALUES ('$tbl_id','".addslashes($fieldname[$tt])."','".intval($post[$tt])."','".intval($retrieve[$tt])."','".intval($update[$tt])."','".intval($visible[$tt])."','".intval($key[$tt])."','".intval($string[$tt])."','".intval($int[$tt])."','".intval($float[$tt])."','".intval($text[$tt])."','".intval($other[$tt])."','".intval($crc[$tt])."')";
			$ty=$xoopsDB->queryF($sql);
			break;
		default:
			$sql = "UPDATE ".$xoopsDB->prefix('soap_fields')." SET allowpost ='".intval($post[$tt])."', allowupdate ='".intval($update[$tt])."',allowretrieve = '".intval($retrieve[$tt])."', visible='".intval($visible[$tt])."',`key` ='".intval($key[$tt])."', `string` = '".intval($string[$tt])."', `int`='".intval($int[$tt])."',`float` ='".intval($float[$tt])."', `text` = '".intval($text[$tt])."', `other`='".intval($other[$tt])."', crc = '".intval($crc[$tt])."' WHERE fld_id = ".$id[$tt]. " and tbl_id = ".$tbl_id;
			$ty=$xoopsDB->queryF($sql);
		}
	
	}
	redirect_header("index.php?op=fields&tbl_id=".$tbl_id,2,"Database Updated");
	break;
	
case "savetables":

	foreach ($id as $f){
		$tt++;	
		switch ($f){
		case "new":
			$sql = "INSERT INTO ".$xoopsDB->prefix('soap_tables')." (tablename, allowpost, allowretrieve, allowupdate, visible, view) VALUES ('".addslashes($tablename[$tt])."','".intval($post[$tt])."','".intval($retrieve[$tt])."','".intval($update[$tt])."','".intval($visible[$tt])."','0')";
			$ty=$xoopsDB->queryF($sql);
			break;
		default:
			$sql = "UPDATE ".$xoopsDB->prefix('soap_tables')." SET allowpost ='".intval($post[$tt])."', allowretrieve = '".intval($retrieve[$tt])."', allowupdate = '".intval($update[$tt])."', visible='".intval($visible[$tt])."' WHERE tbl_id = ".$id[$tt];
			$ty=$xoopsDB->queryF($sql);
		}
	
	}
	redirect_header("index.php?op=tables",2,"Database Updated");
	break;

case "saveviews":

	foreach ($id as $f){
		$tt++;	
		switch ($f){
		case "new":
			$sql = "INSERT INTO ".$xoopsDB->prefix('soap_tables')." (tablename, allowpost, allowretrieve, allowupdate, visible, view) VALUES ('".addslashes($tablename[$tt])."','".intval($post[$tt])."','".intval($retrieve[$tt])."','".intval($update[$tt])."','".intval($visible[$tt])."','1')";
			$ty=$xoopsDB->queryF($sql);
			break;
		default:
			$sql = "UPDATE ".$xoopsDB->prefix('soap_tables')." SET allowpost ='".intval($post[$tt])."', allowretrieve = '".intval($retrieve[$tt])."', allowupdate = '".intval($update[$tt])."', visible='".intval($visible[$tt])."' WHERE tbl_id = ".$id[$tt];
			$ty=$xoopsDB->queryF($sql);
		}
	
	}
	redirect_header("index.php?op=views",2,"Database Updated");
	break;
	
case "views":

$sql = "SHOW VIEWS FROM ".XOOPS_DB_NAME."";
$ret = $xoopsDB->queryF($sql);

xoops_cp_header();
adminmenu();
?>
</p>
<form action='index.php' method='post'>
<table width="100%" border="0" cellspacing="0">
  <tr class="head">
    <td width="65%">View Name</td>
    <td width="9%" align="center" valign="middle">Allow Retrieve</td>
    <td width="9%" align="center" valign="middle">Visible</td>
  </tr>
<?
	$field=0;
	while(list($table) = $xoopsDB->fetchRow($ret)){
	 $field++;
		if ($class == 'odd'){
			$class= 'even';
		} else {
			$class = 'odd';
		}
		$tbldata = get_tableconfig($table);
	if (!isset($tbldata)){
		$new++;
?>
  <tr class="<?php echo $class; ?>">
    <td><strong><input type="hidden" name="id[<?php echo $field; ?>]" name="id[<?php echo $field; ?>]" value="new"><input type="hidden" name="viewname[<?php echo $field; ?>]" name="viewname[<?php echo $field; ?>]" value="<?php echo $table; ?>"><?php echo $table; ?></strong> (new)</td>
    <td align="center" valign="middle"><input name="retrieve[<?php echo $field; ?>]" type="checkbox" id="retrieve[<?php echo $field; ?>]" value="1"></td>
    <td align="center" valign="middle"><input name="visible[<?php echo $field; ?>]" type="checkbox" id="visible[<?php echo $field; ?>]" value="1">
    </td>
  </tr>
  
 <?php } else { ?>
  
   <tr class="<?php echo $class; ?>">
    <td><strong><input type="hidden" name="id[<?php echo $field; ?>]" name="id[<?php echo $field; ?>]" value="<?php echo $tbldata['tbl_id']; ?>"><input type="hidden" name="viewname[<?php echo $field; ?>]" name="viewname[<?php echo $field; ?>]" value="<?php echo strip_prefix($table); ?>"><a href="index.php?op=fields&tbl_id=<?php echo $tbldata['tbl_id']; ?>"><?php echo strip_prefix($table); ?></a></strong></td>
    <td align="center" valign="middle"><input name="retrieve[<?php echo $field; ?>]"  type="checkbox" id="retrieve[<?php echo $field; ?>]" value="1"  <?php if ($tbldata['allowretrieve']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="visible[<?php echo $field; ?>]" type="checkbox" id="visible[<?php echo $field; ?>]" value="1"  <?php if ($tbldata['visible']==1) { echo "checked"; } ?>>
  </tr>
  
 <?php } 
 
 } ?>
</table>
<input type="hidden" name="op" value="saveviews">
<input type="hidden" name="new" value="<?php echo $new; ?>"><br/>
<center><input type="submit" name="submit" value="Submit!"></center>
</form>
<?
	xoops_cp_footer();
	break;	
case "saveplugins":
	

	foreach ($id as $f){
		$tt++;	
		switch ($f){
		case "new":
			$sql = "INSERT INTO ".$xoopsDB->prefix('soap_plugins')." (plugin_name, plugin_file, active) VALUES ('".addslashes($functionname[$tt])."','".addslashes($filename[$tt])."','".intval($active[$tt])."')";
			$ty=$xoopsDB->queryF($sql);
			break;
		default:
			$sql = "UPDATE ".$xoopsDB->prefix('soap_plugins')." SET active ='".intval($active[$tt])."' WHERE plugin_id = ".$id[$tt];
			$ty=$xoopsDB->queryF($sql);
		}
	
	}
	if (!compile_wsdl()){
		redirect_header("index.php?op=plugins",2,"Database Updated - Error Compiling WSDL");
	} else {
		redirect_header("index.php?op=plugins",2,"Database Updated - Complete Compile of WSDL");
	}
	break;

case "plugins":
error_reporting(E_ALL);
global $xoopsModuleConfig;
require_once('../class/class.functions.php');
$funct = new FunctionsHandler($xoopsModuleConfig['wsdl']);

$FunctionDefine = array();
foreach($funct->GetServerExtensions() as $extension){
	$phpcode= file_get_contents(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'.$extension);
	ob_start();
	$r=eval("?>".$phpcode."<?");
	$result = ob_get_contents();
	ob_end_clean();
	if (strpos(' '.$result,"Parse")==0){
		$FunctionDefine[] = $extension;
	}
	
}

xoops_cp_header();
adminmenu();
?>
</p>
<form action='index.php' method='post'>
<table width="100%" border="0" cellspacing="0">
  <tr class="head">
    <td width="41%">Function Name</td>
    <td width="51%">File</td>
    <td width="8%"><div align="center">Active</div></td>
  </tr>
<?php 
	$field=0;
	foreach($FunctionDefine as $func) { 
		$field++;
		if ($class == 'odd'){
			$class= 'even';
		} else {
			$class = 'odd';
		}
		$functdata = get_functionconfig($func);
		if (!isset($functdata)){
			$new++;
?>
  <tr class="<?php echo $class; ?>">
    <td><strong><input type="hidden" name="id[<?php echo $field; ?>]" name="id[<?php echo $field; ?>]" value="new"><input type="hidden" name="functionname[<?php echo $field; ?>]" name="functionname[<?php echo $field; ?>]" value="<?php echo str_replace('.php','',$func); ?>"><?php echo str_replace('.php','',$func); ?></strong> (new)</td>
    <td align="left" valign="middle"><input type="hidden" name="filename[<?php echo $field; ?>]" name="filename[<?php echo $field; ?>]" value="<?php echo $func; ?>"><strong><?php echo $func; ?></strong></td>
    <td align="center" valign="middle"><input name="active[<?php echo $field; ?>]" type="checkbox" id="active[<?php echo $field; ?>]" value="1">
    </td>
  </tr>
  
 <?php } else { ?>
  
   <tr class="<?php echo $class; ?>">
    <td><strong><input type="hidden" name="id[<?php echo $field; ?>]" name="id[<?php echo $field; ?>]" value="<?php echo $functdata['plugin_id']; ?>"><input type="hidden" name="functionname[<?php echo $field; ?>]" name="functionname[<?php echo $field; ?>]" value="<?php echo str_replace('.php','',$func); ?>"><?php echo str_replace('.php','',$func); ?></strong></td>
    <td align="left" valign="middle"><input type="hidden" name="filename[<?php echo $field; ?>]" name="filename[<?php echo $field; ?>]" value="<?php echo $func; ?>"><strong><?php echo $func; ?></strong></td>
    <td align="center" valign="middle"><input name="active[<?php echo $field; ?>]" type="checkbox" id="active[<?php echo $field; ?>]" value="1"  <?php if ($functdata['active']==1) { echo "checked"; } ?>>
  </tr>
  
 <?php } 
} ?>
</table> 
<input type="hidden" name="op" value="saveplugins">
<input type="hidden" name="new" value="<?php echo $new; ?>"><br/>
<center><input type="submit" name="submit" value="Submit!"></center>
</form>
<?
	xoops_cp_footer();
	break;
default:

$sql = "SHOW TABLES FROM ".XOOPS_DB_NAME." LIKE '".XOOPS_DB_PREFIX."\_%'";
$ret = $xoopsDB->queryF($sql);

xoops_cp_header();
adminmenu();
?>
</p>
<form action='index.php' method='post'>
<table width="100%" border="0" cellspacing="0">
  <tr class="head">
    <td width="65%">Table Name</td>
    <td width="8%" align="center" valign="middle">Allow Post</td>
    <td width="9%" align="center" valign="middle">Allow Retrieve</td>
    <td width="9%" align="center" valign="middle">Allow Update</td>
    <td width="9%" align="center" valign="middle">Visible</td>
  </tr>
<?
	$field=0;
	while(list($table) = $xoopsDB->fetchRow($ret)){
	 $field++;
		if ($class == 'odd'){
			$class= 'even';
		} else {
			$class = 'odd';
		}
		$tbldata = get_tableconfig($table);
	if (!isset($tbldata)){
		$new++;
?>
  <tr class="<?php echo $class; ?>">
    <td><strong><input type="hidden" name="id[<?php echo $field; ?>]" name="id[<?php echo $field; ?>]" value="new"><input type="hidden" name="tablename[<?php echo $field; ?>]" name="tablename[<?php echo $field; ?>]" value="<?php echo strip_prefix($table); ?>"><?php echo strip_prefix($table); ?></strong> (new)</td>
    <td align="center" valign="middle"><input name="post[<?php echo $field; ?>]" type="checkbox" id="post[<?php echo $field; ?>]" value="1"></td>
    <td align="center" valign="middle"><input name="retrieve[<?php echo $field; ?>]" type="checkbox" id="retrieve[<?php echo $field; ?>]" value="1"></td>
    <td align="center" valign="middle"><input name="update[<?php echo $field; ?>]" type="checkbox" id="update[<?php echo $field; ?>]" value="1">
    <td align="center" valign="middle"><input name="visible[<?php echo $field; ?>]" type="checkbox" id="visible[<?php echo $field; ?>]" value="1">
    </td>
  </tr>
  
 <?php } else { ?>
  
   <tr class="<?php echo $class; ?>">
    <td><strong><input type="hidden" name="id[<?php echo $field; ?>]" name="id[<?php echo $field; ?>]" value="<?php echo $tbldata['tbl_id']; ?>"><input type="hidden" name="tablename[<?php echo $field; ?>]" name="tablename[<?php echo $field; ?>]" value="<?php echo strip_prefix($table); ?>"><a href="index.php?op=fields&tbl_id=<?php echo $tbldata['tbl_id']; ?>"><?php echo strip_prefix($table); ?></a></strong></td>
    <td align="center" valign="middle"><input name="post[<?php echo $field; ?>]" type="checkbox" id="post[<?php echo $field; ?>]" value="1" <?php if ($tbldata['allowpost']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="retrieve[<?php echo $field; ?>]"  type="checkbox" id="retrieve[<?php echo $field; ?>]" value="1"  <?php if ($tbldata['allowretrieve']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="update[<?php echo $field; ?>]"  type="checkbox" id="update[<?php echo $field; ?>]" value="1"  <?php if ($tbldata['allowupdate']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="visible[<?php echo $field; ?>]" type="checkbox" id="visible[<?php echo $field; ?>]" value="1"  <?php if ($tbldata['visible']==1) { echo "checked"; } ?>>
  </tr>
  
 <?php } 
 
 } ?>
</table>
<input type="hidden" name="op" value="savetables">
<input type="hidden" name="new" value="<?php echo $new; ?>"><br/>
<center><input type="submit" name="submit" value="Submit!"></center>
</form>
<?
	xoops_cp_footer();
	
}

function strip_prefix($raw_tablename){
	return str_replace(XOOPS_DB_PREFIX."_",'',$raw_tablename);
}

function get_tableconfig($raw_tablename){
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')." WHERE tablename = '".strip_prefix($raw_tablename)."'";
	$ret = $xoopsDB->query($sql);
	if ($xoopsDB->getRowsNum($ret)){
		return $xoopsDB->fetchArray($ret);
	} else {

	}
}
function get_functionconfig($plugin_filename){
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_plugins')." WHERE plugin_file = '".addslashes($plugin_filename)."'";
	$ret = $xoopsDB->query($sql);
	if ($xoopsDB->getRowsNum($ret)){
		return $xoopsDB->fetchArray($ret);
	} else {

	}
}

function get_fieldconfig($raw_fieldname, $tbl_id){
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_fields')." WHERE fieldname = '$raw_fieldname' and tbl_id = $tbl_id";
	$ret = $xoopsDB->query($sql);
	if ($xoopsDB->getRowsNum($ret)){
		return $xoopsDB->fetchArray($ret);
	} else {

	}
}
function get_tableid($tablename){
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')." WHERE tablename = '$tablename'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	return $row['tbl_id'];
}

function get_tablename($tableid){
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')." WHERE tbl_id = '$tableid'";
	$ret = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($ret);
	return $row['tablename'];
}

function compile_wsdl(){
	define('wsdl_local_compile',true);
	require_once('../xsoap.xsd.php');
	require_once('../xsoap.wsdl.services.php');
	require_once('../xsoap.wsdl.service.php');
	require_once('../xsoap.wsdl.php');
	
	global $xoopsDB;
	ini_set('allow_url_fopen',true);
	$FunctionDefine = array();
	$funct = new FunctionsHandler($xoopsModuleConfig['wsdl']);
	foreach($funct->GetServerExtensions() as $extension){
		global $xoopsDB;
		$sql = "SELECT count(*) rc FROM ".$xoopsDB->prefix('soap_plugins'). " where active = 1 and plugin_file = '".$extension."'";
		$ret = $xoopsDB->query($sql);
		$row = $xoopsDB->fetchArray($ret);
		//echo $extension.$row['rc']."<br>";
		if ($row['rc']==1){
			$FunctionDefine = substr( $extension,0,strlen( $extension)-4);
			require_once(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'. $extension);
			
			WSDL_dump_File(get_xsd($FunctionDefine),XOOPS_ROOT_PATH."/modules/xsoap/".$FunctionDefine.".xsd");
			
			WSDL_dump_File(get_wsdl($FunctionDefine),XOOPS_ROOT_PATH."/modules/xsoap/".$FunctionDefine.".wsdl");
				
			WSDL_dump_File(get_wsdl_service($FunctionDefine),XOOPS_ROOT_PATH."/modules/xsoap/".$FunctionDefine.".service.wsdl");
		}
	}
	
	WSDL_dump_File(get_wsdl_services(),XOOPS_ROOT_PATH."/modules/xsoap/xsoap.wsdl");
	
	return filesize(XOOPS_ROOT_PATH."/modules/xsoap/xsoap.wsdl");
}

function WSDL_dump_File($URLXML_DATA, $dest){
    if(!empty($URLXML_DATA)){
		$fout = fopen($dest, 'w');
		fwrite($fout,$URLXML_DATA);
		fclose($fout);
    }
}
?>