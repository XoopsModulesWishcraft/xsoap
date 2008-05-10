<?
include('../../mainfile.php');

class elements {
	function output($arry,$step){
		if (isset($arry['items'])&&!isset($arry['name'])){
			echo $step.'<element name="'.$arry['items']['objname'].'">'.chr(13);
			echo $step.chr(9).'<complexType>'.chr(13);
			echo $step.chr(9).chr(9).'<all>'.chr(13);				
			foreach ($arry['items']['data'] as $buffer){
				$this->output($buffer,$step.$step);
			}
			echo $step.chr(9).chr(9).'</all>'.chr(13);				
			echo $step.chr(9).'</complexType>'.chr(13);	
			echo $step.'</element>'.chr(13);					
		} else {
			
			echo $step.'<element name="'.$arry['name'].'" type="'.$arry['type'].'"/>'.chr(13);
		}
	}
}

$ele = new elements();

if ($_GET['funcname']!=''&&file_exists(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'.$_GET['funcname'].'.php')){
	$func=$_GET['funcname'];
	
	require(XOOPS_ROOT_PATH.'/modules/xsoap/plugins/'.$func.'.php');
	$funcxsd = $func."_xsd";
	if (function_exists($funcxsd)){
		$xsd = $funcxsd();
		
		header ('Content-Type:text/xml; charset=utf-8');
		echo '<?xml version="1.0"?>'.chr(13);
		echo '<schema targetNamespace="'.XOOPS_URL.'/modules/xsoap/'.$func.'/schemas" xmlns="http://www.w3.org/2000/10/XMLSchema">'.chr(13);
		echo chr(9).'<element name="'.$func.'Request">'.chr(13);
		echo chr(9).chr(9).'<complexType>'.chr(13);
		echo chr(9).chr(9).chr(9).'<all>'.chr(13);				
		foreach ($xsd['request'] as $request){
			echo $ele->output($request, chr(9).chr(9).chr(9).chr(9));	
		}	
		echo chr(9).chr(9).chr(9).'</all>'.chr(13);				
		echo chr(9).chr(9).'</complexType>'.chr(13);
		echo chr(9).'</element>'.chr(13);
		
		echo chr(9).'<element name="'.$func.'">'.chr(13);
		echo chr(9).chr(9).'<complexType>'.chr(13);
		echo chr(9).chr(9).chr(9).'<all>'.chr(13);				
		foreach ($xsd['response'] as $response){
			echo $ele->output($response, chr(9).chr(9).chr(9).chr(9));	
		}	
		echo chr(9).chr(9).chr(9).'</all>'.chr(13);				
		echo chr(9).chr(9).'</complexType>'.chr(13);
		echo chr(9).'</element>'.chr(13);
		echo '</schema>';
		
		exit;
	}
}

?>