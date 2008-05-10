<?php
// Pull in the NuSOAP code
require_once('../include/nusoap/nusoap.php');
// Create the client instance
$client = new soapclient('http://www.unseen.org.au/modules/xsoap/');
// Call the SOAP method
$rnd = rand(-100000, 100000000);
$result = $client->call('xoops_authentication', array('username' => 'test_acc', "password" => "test",  
						"auth" => array('username' => 'test_acc', 'password' => 'test',"time" => time(), 
						"passhash" => sha1((time()-$rnd).'test_acc'.'test'), "rand"=>$rnd)));
// Display the result
print_r($result);
// Display the request and response
echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
// Display the debug messages
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

?>
