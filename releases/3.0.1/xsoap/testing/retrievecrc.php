<?php
// Pull in the NuSOAP code
require_once('../include/nusoap/nusoap.php');
// Create the client instance
$client = new soapclient('http://www.unseen.org.au/modules/xsoap/');
// Call the SOAP method
$result = $client->call('retrievecrc', array("xsoap" => array('username' => 'test_acc', "password" => "test", "tablename" => "soap_test")));
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
