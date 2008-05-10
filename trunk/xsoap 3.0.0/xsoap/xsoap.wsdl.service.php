<?

include('../../mainfile.php');

if ($_GET['funcname']!=''&&file_exists(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'.$_GET['funcname'].'.php')){
	$function=$_GET['funcname'];
	
	require(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'.$function.'.php');
	$funcwsdl = $function."_wsdl_service";
	if (function_exists($funcwsdl)){

		$wsdl = $funcwsdl();

		header ('Content-Type:text/xml; charset=utf-8');
		echo '<?xml version="1.0"?>'.chr(13);
		
		echo '<definitions name="'.$function.'" targetNamespace="'.XOOPS_URL.'/modules/xsoap/'.$function.'/service" xmlns:tns="'.XOOPS_URL.'/modules/xsoap/'.$function.'/service" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:defs="'.XOOPS_URL.'/modules/xsoap/'.$function.'/definitions"  xmlns="http://schemas.xmlsoap.org/wsdl/">'.chr(13);
		
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
		
		echo chr(9).'<service name="'.$function.'Service">'.chr(13);
		echo chr(9).chr(9).'<documentation>'.$wsdl['documentation'].'</documentation>'.chr(13);				
		echo chr(9).chr(9).'<port name="'.$function.'Port" binding="tns:'.$function.'Binding">'.chr(13);				
		echo chr(9).chr(9).chr(9).'<soap:address location="'.XOOPS_URL.'/modules/xsoap/'.$function.'"/>'.chr(13);
		echo chr(9).chr(9).'</port>'.chr(13);
		echo chr(9).'</service>'.chr(13);			
		echo '</definitions>'.chr(13);						

		
	}
}

?>