<?

include 'admin_header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
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

$sql = "SELECT * FROM ".$xoopsDB->prefix('soap_tables')."";
$ret = $xoopsDB->queryF($sql);
?>
<table width="100%" border="0" cellspacing="0">
  <tr class="head">
    <td width="16%" valign="top"><div align="right">Select Table:</div></td>
    <td width="84%" valign="top"><form>
      <label>
        <select name="select" id="select" onchange="window.location=''+this.options[this.selectedIndex].value">
        <? while($row = $xoopsDB->fetchArray($ret)) { ?>
        <option value="index.php?op=fields&tbl_id=<? echo $row['tbl_id']; ?>" <? if ($tbl_id == $row['tbl_id']) { echo "selected"; }?>><? echo $row['tablename'];?></option>
        <? } ?>
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
  <tr class="<? echo $class; ?>">
    <td><input type="hidden" name="id[<? echo $field; ?>]" name="id[<? echo $field; ?>]" value="new">
    <input type="hidden" name="key[<? echo $field; ?>]" name="key[<? echo $field; ?>]" value="<? echo $key; ?>">
	<input type="hidden" name="string[<? echo $field; ?>]" name="string[<? echo $field; ?>]" value="<? echo $string; ?>">
	<input type="hidden" name="int[<? echo $field; ?>]" name="int[<? echo $field; ?>]" value="<? echo $int; ?>">
	<input type="hidden" name="float[<? echo $field; ?>]" name="float[<? echo $field; ?>]" value="<? echo $float; ?>">
    <input type="hidden" name="text[<? echo $field; ?>]" name="text[<? echo $field; ?>]" value="<? echo $text; ?>">
    <input type="hidden" name="other[<? echo $field; ?>]" name="other[<? echo $field; ?>]" value="<? echo $other; ?>">
    <input type="hidden" name="fieldname[<? echo $field; ?>]" name="fieldname[<? echo $field; ?>]" value="<? echo $fieldname; ?>">
	<strong><? echo $fieldname; ?></strong> (new)</td>
    <td align="center" valign="middle"><input <? if ($key==1) { echo 'disabled="disabled"'; } elseif ($tbldat['allowpost']==1) { echo 'checked'; } ?>  name="post[<? echo $field; ?>]" type="checkbox" id="post[<? echo $field; ?>]" value="1"></td>
    <td align="center" valign="middle"><input <? if ($tbldat['allowretrieve']==1) { echo 'checked'; } ?> name="retrieve[<? echo $field; ?>]" type="checkbox" id="retrieve[<? echo $field; ?>]" value="1"></td>
    <td align="center" valign="middle"><input <? if ($key==1) { echo 'disabled="disabled"'; } elseif ($tbldat['allowupdate']==1) { echo 'checked'; } ?> name="update[<? echo $field; ?>]" type="checkbox" id="update[<? echo $field; ?>]" value="1">
    <td align="center" valign="middle"><input  <? if ($tbldat['visible']==1) { echo 'checked'; } ?>  name="visible[<? echo $field; ?>]" type="checkbox" id="visible[<? echo $field; ?>]" value="1">
    <td align="center" valign="middle"><input <? if ($key==1) { echo 'disabled="disabled"'; } ?> name="crc[<? echo $field; ?>]" type="checkbox" id="crc[<? echo $field; ?>]" value="1">
    </td>
  </tr>
  
 <? } else { ?>
  
   <tr class="<? echo $class; ?>">
    <td><strong><input type="hidden" name="key[<? echo $field; ?>]" name="key[<? echo $field; ?>]" value="<? echo $key; ?>">
	<input type="hidden" name="string[<? echo $field; ?>]" name="string[<? echo $field; ?>]" value="<? echo $string; ?>">
	<input type="hidden" name="int[<? echo $field; ?>]" name="int[<? echo $field; ?>]" value="<? echo $int; ?>">
	<input type="hidden" name="float[<? echo $field; ?>]" name="float[<? echo $field; ?>]" value="<? echo $float; ?>">
    <input type="hidden" name="text[<? echo $field; ?>]" name="text[<? echo $field; ?>]" value="<? echo $text; ?>">
    <input type="hidden" name="other[<? echo $field; ?>]" name="other[<? echo $field; ?>]" value="<? echo $other; ?>"><input type="hidden" name="id[<? echo $field; ?>]" name="id[<? echo $field; ?>]" value="<? echo $tbldata['fld_id']; ?>"><input type="hidden" name="fieldname[<? echo $field; ?>]" name="fieldname[<? echo $field; ?>]" value="<? echo $fieldname; ?>"><? echo $fieldname; ?></strong></td>
    <td align="center" valign="middle"><input <? if ($key==1) { echo 'disabled="disabled"'; } ?> name="post[<? echo $field; ?>]" type="checkbox" id="post[<? echo $field; ?>]" value="1" <? if ($tbldata['allowpost']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="retrieve[<? echo $field; ?>]"  type="checkbox" id="retrieve[<? echo $field; ?>]" value="1"  <? if ($tbldata['allowretrieve']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input <? if ($key==1) { echo 'disabled="disabled"'; } ?> name="update[<? echo $field; ?>]"  type="checkbox" id="update[<? echo $field; ?>]" value="1"  <? if ($tbldata['allowupdate']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="visible[<? echo $field; ?>]" type="checkbox" id="visible[<? echo $field; ?>]" value="1"  <? if ($tbldata['visible']==1) { echo "checked"; } ?>>
    <td align="center" valign="middle"><input <? if ($key==1) { echo 'disabled="disabled"'; } ?> name="crc[<? echo $field; ?>]" type="checkbox" id="crc[<? echo $field; ?>]" value="1"  <? if ($tbldata['crc']==1) { echo "checked"; } ?>>
  </tr>
  
 <? } 
	} 
 ?>
</table>
<input type="hidden" name="tbl_id" value="<? echo $tbl_id; ?>">
<input type="hidden" name="op" value="savefields">
<input type="hidden" name="new" value="<? echo $new; ?>"><br/>
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
			$sql = "INSERT INTO ".$xoopsDB->prefix('soap_fields')." (tbl_id, fieldname, allowpost, allowretrieve, allowupdate, visible, `key`, `string`, `int`, `float`, `text`, `other`) VALUES ('$tbl_id','".addslashes($fieldname[$tt])."','".intval($post[$tt])."','".intval($retrieve[$tt])."','".intval($update[$tt])."','".intval($visible[$tt])."','".intval($key[$tt])."','".intval($string[$tt])."','".intval($int[$tt])."','".intval($float[$tt])."','".intval($text[$tt])."','".intval($other[$tt])."')";
			$ty=$xoopsDB->queryF($sql);
			break;
		default:
			$sql = "UPDATE ".$xoopsDB->prefix('soap_tables')." SET allowpost ='".intval($post[$tt])."', allowupdate ='".intval($update[$tt])."',allowretrieve = '".intval($retrieve[$tt])."', visible='".intval($visible[$tt])."',`key` ='".intval($key[$tt])."', `string` = '".intval($string[$tt])."', `int`='".intval($int[$tt])."',`float` ='".intval($float[$tt])."', `text` = '".intval($text[$tt])."', `other`='".intval($other[$tt])."' WHERE fld_id = ".$id[$tt]. " and tbl_id = ".$tbl_id;
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
			$sql = "INSERT INTO ".$xoopsDB->prefix('soap_tables')." (tablename, allowpost, allowretrieve, allowupdate, visible) VALUES ('".addslashes($tablename[$tt])."','".intval($post[$tt])."','".intval($retrieve[$tt])."','".intval($update[$tt])."','".intval($visible[$tt])."')";
			$ty=$xoopsDB->queryF($sql);
			break;
		default:
			$sql = "UPDATE ".$xoopsDB->prefix('soap_tables')." SET allowpost ='".intval($post[$tt])."', allowretrieve = '".intval($retrieve[$tt])."', allowupdate = '".intval($update[$tt])."', visible='".intval($visible[$tt])."' WHERE tbl_id = ".$id[$tt];
			$ty=$xoopsDB->queryF($sql);
		}
	
	}
	redirect_header("index.php?op=tables",2,"Database Updated");
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
  <tr class="<? echo $class; ?>">
    <td><strong><input type="hidden" name="id[<? echo $field; ?>]" name="id[<? echo $field; ?>]" value="new"><input type="hidden" name="tablename[<? echo $field; ?>]" name="tablename[<? echo $field; ?>]" value="<? echo strip_prefix($table); ?>"><? echo strip_prefix($table); ?></strong> (new)</td>
    <td align="center" valign="middle"><input name="post[<? echo $field; ?>]" type="checkbox" id="post[<? echo $field; ?>]" value="1"></td>
    <td align="center" valign="middle"><input name="retrieve[<? echo $field; ?>]" type="checkbox" id="retrieve[<? echo $field; ?>]" value="1"></td>
    <td align="center" valign="middle"><input name="update[<? echo $field; ?>]" type="checkbox" id="update[<? echo $field; ?>]" value="1">
    <td align="center" valign="middle"><input name="visible[<? echo $field; ?>]" type="checkbox" id="visible[<? echo $field; ?>]" value="1">
    </td>
  </tr>
  
 <? } else { ?>
  
   <tr class="<? echo $class; ?>">
    <td><strong><input type="hidden" name="id[<? echo $field; ?>]" name="id[<? echo $field; ?>]" value="<? echo $tbldata['tbl_id']; ?>"><input type="hidden" name="tablename[<? echo $field; ?>]" name="tablename[<? echo $field; ?>]" value="<? echo strip_prefix($table); ?>"><a href="index.php?op=fields&tbl_id=<? echo $tbldata['tbl_id']; ?>"><? echo strip_prefix($table); ?></a></strong></td>
    <td align="center" valign="middle"><input name="post[<? echo $field; ?>]" type="checkbox" id="post[<? echo $field; ?>]" value="1" <? if ($tbldata['allowpost']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="retrieve[<? echo $field; ?>]"  type="checkbox" id="retrieve[<? echo $field; ?>]" value="1"  <? if ($tbldata['allowretrieve']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="update[<? echo $field; ?>]"  type="checkbox" id="update[<? echo $field; ?>]" value="1"  <? if ($tbldata['allowupdate']==1) { echo "checked"; } ?>></td>
    <td align="center" valign="middle"><input name="visible[<? echo $field; ?>]" type="checkbox" id="visible[<? echo $field; ?>]" value="1"  <? if ($tbldata['visible']==1) { echo "checked"; } ?>>
  </tr>
  
 <? } 
 
 } ?>
</table>
<input type="hidden" name="op" value="savetables">
<input type="hidden" name="new" value="<? echo $new; ?>"><br/>
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

function adminmenu(){
	global $xoopsModule;

	echo "
		<table width='100%' cellspacing='0' cellpadding='0' border='0' class='outer'>\n
		<tr>\n
		<td style='font-size: 10px; text-align: left; color: #2F5376; padding: 2px 6px; line-height: 18px;'>\n
		<a href='../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule -> getVar('mid') . "'>Preferences</a> | \n
		<a href='../admin/index.php?op=tables'>Tables</a> | \n
		<a href='../admin/index.php?op=fields'>Fields</a> \n
		</td>\n
		</tr>\n
		</table><br />";
}
?>