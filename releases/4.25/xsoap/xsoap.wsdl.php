<?php
include('../../mainfile.php');

if (!defined('wsdl_local_compile')&&$_GET['funcname']!=''&&file_exists(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'.$_GET['funcname'].'.php')){
	$func=$_GET['funcname'];
	
	require(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'.$func.'.php');
	$funcwsdl = $func."_wsdl";
	if (function_exists($funcwsdl)){

		$wsdl = $funcwsdl();
		
		header ('Content-Type:text/xml; charset=utf-8');
		echo '<?xml version="1.0"?>'.chr(13);
	
		echo '<definitions name="'.$func.'" targetNamespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/definitions" xmlns:tns="'.XOOPS_URL.'/modules/xsoap/'.$func.'/definitions" xmlns:xsd1="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns="http://schemas.xmlsoap.org/wsdl/soap/">'.chr(13);
		
		if ($_GET['local']!=1){
			echo chr(9).'<import namespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas" location="'.XOOPS_URL.'/modules/xsoap/xsoap.xsd.php?funcname='.$func.'"/>'.chr(13);			
			echo chr(9).'<types>'.chr(13);
			echo chr(9).chr(9).'<schema targetNamespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas" xmlns="http://www.w3.org/2000/10/XMLSchema">';
			echo get_xsd($func, chr(9));
			echo chr(9).chr(9).'</schema>';
			echo chr(9).'</types>';
	
		} else {
			echo chr(9).'<import namespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas" location="'.XOOPS_URL.'/modules/xsoap/'.$func.'.xsd"/>'.chr(13);
			echo chr(9).chr(9).'<schema targetNamespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas xmlns="http://www.w3.org/2000/10/XMLSchema">';
			echo get_xsd($func, chr(9));
			echo chr(9).chr(9).'</schema>';
			echo chr(9).'</types>';
		}

		echo get_xsd($func, chr(9));
		echo chr(9).'<message name="'.$func.'Input">'.chr(13);
		echo chr(9).chr(9).'<part name="body" element="xsd1:'.$func.'Request"/>'.chr(13);				
		echo chr(9).'</message>'.chr(13);				
		echo chr(9).'<message name="'.$func.'Output">'.chr(13);
		echo chr(9).chr(9).'<part name="body" element="xsd1:'.$func.'"/>'.chr(13);				
		echo chr(9).'</message>'.chr(13);				

		echo chr(9).'<portType name="'.$func.'PortType">'.chr(13);
		echo chr(9).chr(9).'<operation name="'.$func.'">'.chr(13);
		echo chr(9).chr(9).chr(9).'<input message="tns:'.$func.'Input"/>'.chr(13);				
		echo chr(9).chr(9).chr(9).'<output message="tns:'.$func.'Output"/>'.chr(13);							
		echo chr(9).chr(9).'</operation>'.chr(13);				
		echo chr(9).'</portType>'.chr(13);				
		echo '</definitions>'.chr(13);						
		exit;
	}
} else {
	
	function get_wsdl($func){
	
		$funcwsdl = $func."_wsdl";
		
		if (function_exists($funcwsdl)){
	
			$wsdl = $funcwsdl();
			
			echo '<?xml version="1.0"?>'.chr(13);
		
			echo '<definitions name="'.$func.'" targetNamespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/definitions" xmlns:tns="'.XOOPS_URL.'/modules/xsoap/'.$func.'/definitions" xmlns:xsd1="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns="http://schemas.xmlsoap.org/wsdl/soap/">'.chr(13);
			
		
			if ($_GET['local']!=1){
				echo chr(9).'<import namespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas" location="'.XOOPS_URL.'/modules/xsoap/xsoap.xsd.php?funcname='.$func.'"/>'.chr(13);
				echo chr(9).'<types>'.chr(13);
				echo chr(9).chr(9).'<schema targetNamespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas xmlns="http://www.w3.org/2000/10/XMLSchema">';
				echo get_xsd($func, chr(9));
				echo chr(9).chr(9).'</schema>';
				echo chr(9).'</types>';
		
			} else {
				echo chr(9).'<import namespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas" location="'.XOOPS_URL.'/modules/xsoap/'.$func.'.xsd"/>'.chr(13);
				echo chr(9).chr(9).'<schema targetNamespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas xmlns="http://www.w3.org/2000/10/XMLSchema">';
				echo get_xsd($func, chr(9));
				echo chr(9).chr(9).'</schema>';
				echo chr(9).'</types>';
			}
			
			echo chr(9).'<message name="'.$func.'Input">'.chr(13);
			echo chr(9).chr(9).'<part name="body" element="xsd1:'.$func.'Request"/>'.chr(13);				
			echo chr(9).'</message>'.chr(13);				
			echo chr(9).'<message name="'.$func.'Output">'.chr(13);
			echo chr(9).chr(9).'<part name="body" element="xsd1:'.$func.'"/>'.chr(13);				
			echo chr(9).'</message>'.chr(13);				
	
			echo chr(9).'<portType name="'.$func.'PortType">'.chr(13);
			echo chr(9).chr(9).'<operation name="'.$func.'">'.chr(13);
			echo chr(9).chr(9).chr(9).'<input message="tns:'.$func.'Input"/>'.chr(13);				
			echo chr(9).chr(9).chr(9).'<output message="tns:'.$func.'Output"/>'.chr(13);							
			echo chr(9).chr(9).'</operation>'.chr(13);				
			echo chr(9).'</portType>'.chr(13);				
			echo '</definitions>'.chr(13);		
		
			return $rst;
		}
	}
}

?>