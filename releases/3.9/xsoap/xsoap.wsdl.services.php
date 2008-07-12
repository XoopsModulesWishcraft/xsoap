<?

include('../../mainfile.php');
require_once(XOOPS_ROOT_PATH.'/modules/xsoap/class/class.functions.php');

if (!defined('wsdl_local_compile')){

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

	header ('Content-Type:text/xml; charset=utf-8');
	echo '<?xml version="1.0"?>'.chr(13);
	
	echo '<definitions name="xsoap" targetNamespace="'.XOOPS_URL.'/modules/xsoap/xsoap/service" xmlns:tns="'.XOOPS_URL.'/modules/xsoap/xsoap/service" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:defs="'.XOOPS_URL.'/modules/xsoap/xsoap/definitions"  xmlns="http://schemas.xmlsoap.org/wsdl/">'.chr(13);
	
	
	foreach($FunctionDefine as $function){
		$funcwsdl = $function."_wsdl_service";
		if (function_exists($funcwsdl)){
	
			$wsdl = $funcwsdl();
	
			if ($_GET['local']!=1){
				echo chr(9).'<import namespace="'.XOOPS_URL.'/modules/xsoap/'.$function.'/schemas" location="'.XOOPS_URL.'/modules/xsoap/xsoap.wsdl.php?funcname='.$function.'"/>'.chr(13);
			} else {
				echo chr(9).'<import namespace="'.XOOPS_URL.'/modules/xsoap/'.$function.'/schemas" location="'.XOOPS_URL.'/modules/xsoap/'.$function.'.wsdl"/>'.chr(13);
			}
			echo chr(9).'<binding name="'.$function.'SoapBinding" type="defs:'.$function.'PortType">'.chr(13);
			echo chr(9).chr(9).'<soap:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>'.chr(13);				
			echo chr(9).chr(9).'<operation name="'.$function.'">'.chr(13);				
			echo chr(9).chr(9).chr(9).'<soap:operation soapAction="'.XOOPS_URL.'/modules/xsoap/'.$function.'"/>'.chr(13);				
			echo chr(9).chr(9).chr(9).'<input>'.chr(13);
			echo chr(9).chr(9).chr(9).chr(9).'<soap:body use="literal"/>'.chr(13);
			echo chr(9).chr(9).chr(9).'</input>'.chr(13);			
			echo chr(9).chr(9).chr(9).'<output>'.chr(13);
			echo chr(9).chr(9).chr(9).chr(9).'<soap:body use="literal"/>'.chr(13);
			echo chr(9).chr(9).chr(9).'</output>'.chr(13);			
			echo chr(9).chr(9).'</operation>'.chr(13);				
			echo chr(9).'</binding>'.chr(13);				
			
			echo chr(9).'<service name="'.$function.'">'.chr(13);
			echo chr(9).chr(9).'<documentation>'.$wsdl['documentation'].'</documentation>'.chr(13);				
			echo chr(9).chr(9).'<port name="'.$function.'Port" binding="tns:'.$function.'Binding">'.chr(13);				
			echo chr(9).chr(9).chr(9).'<soap:address location="'.XOOPS_URL.'/modules/xsoap/'.$function.'"/>'.chr(13);
			echo chr(9).chr(9).'</port>'.chr(13);
			echo chr(9).'</service>'.chr(13);			
			
		}
	}
	echo '</definitions>'.chr(13);						
} else {

	function get_wsdl_services(){

		$rst .=  '<?xml version="1.0"?>'.chr(13);
		
		$rst .=  '<definitions name="xsoap" targetNamespace="'.XOOPS_URL.'/modules/xsoap/xsoap/service" xmlns:tns="'.XOOPS_URL.'/modules/xsoap/xsoap/service" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:defs="'.XOOPS_URL.'/modules/xsoap/xsoap/definitions"  xmlns="http://schemas.xmlsoap.org/wsdl/">'.chr(13);
		
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
			$funcwsdl = $function."_wsdl_service";
			if (function_exists($funcwsdl)){
		
				$wsdl = $funcwsdl();
		
				$rst .=  chr(9).'<import namespace="'.XOOPS_URL.'/modules/xsoap/'.$function.'/schemas" location="'.XOOPS_URL.'/modules/xsoap/'.$function.'.wsdl"/>'.chr(13);
				
				$rst .=  chr(9).'<binding name="'.$function.'SoapBinding" type="defs:'.$function.'PortType">'.chr(13);
				$rst .=  chr(9).chr(9).'<soap:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>'.chr(13);				
				$rst .=  chr(9).chr(9).'<operation name="'.$function.'">'.chr(13);				
				$rst .=  chr(9).chr(9).chr(9).'<soap:operation soapAction="'.XOOPS_URL.'/modules/xsoap/'.$function.'"/>'.chr(13);				
				$rst .=  chr(9).chr(9).chr(9).'<input>'.chr(13);
				$rst .=  chr(9).chr(9).chr(9).chr(9).'<soap:body use="literal"/>'.chr(13);
				$rst .=  chr(9).chr(9).chr(9).'</input>'.chr(13);			
				$rst .=  chr(9).chr(9).chr(9).'<output>'.chr(13);
				$rst .=  chr(9).chr(9).chr(9).chr(9).'<soap:body use="literal"/>'.chr(13);
				$rst .=  chr(9).chr(9).chr(9).'</output>'.chr(13);			
				$rst .=  chr(9).chr(9).'</operation>'.chr(13);				
				$rst .=  chr(9).'</binding>'.chr(13);				
				
				$rst .=  chr(9).'<service name="'.$function.'">'.chr(13);
				$rst .=  chr(9).chr(9).'<documentation>'.$wsdl['documentation'].'</documentation>'.chr(13);				
				$rst .=  chr(9).chr(9).'<port name="'.$function.'Port" binding="tns:'.$function.'Binding">'.chr(13);				
				$rst .=  chr(9).chr(9).chr(9).'<soap:address location="'.XOOPS_URL.'/modules/xsoap/'.$function.'"/>'.chr(13);
				$rst .=  chr(9).chr(9).'</port>'.chr(13);
				$rst .=  chr(9).'</service>'.chr(13);			
				
			}
		}
		$rst .=  '</definitions>'.chr(13);						
		return $rst;
	}
}
?>