<?php
// Pull in the NuSOAP code
require_once('../include/nusoap/nusoap.php');
// Create the client instance
$client = new soapclient('http://www.unseen.org.au/modules/xsoap/');
// Call the SOAP method
$rnd = rand(-100000, 100000000);
$result = $client->call('xoops_create_user', array('username' => 'test_acc', "password" => "test",  
						"user" => array('user_viewemail' => 1, 'uname' => 'test', 'url' => '', 'email' => 'simon@shower.geek.nz', 'actkey' => 'test2','pass' => '11111', 'timezone_offset' => 10,'user_mailok' => 1,	"passhash" => sha1((time()-$rnd).'test'.'11111'), "rand"=>$rnd, "time" => time(), 'xoops_url' => 'http://www.policybackup.com')));
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
