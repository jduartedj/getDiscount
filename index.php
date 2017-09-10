<?php
ini_set('display_errors', '1');

// Main file handles the requstes named index.php for ease of use
include_once 'GetDiscount.class.php';

$gd = new GetDiscount();

If (!isset($_GET['orderJSON'])){

	//call NUSOAP library
	//require_once('nusoap/nusoap.php');
	require_once('nusoap/class.nusoap_base.php');
	require_once('nusoap/class.soap_val.php');
	require_once('nusoap/class.soap_parser.php');
	require_once('nusoap/class.soap_fault.php');
	
	require_once('nusoap/class.soap_transport_http.php');
	
	require_once('nusoap/class.xmlschema.php');
	require_once('nusoap/class.wsdl.php');
	
	require_once('nusoap/class.soap_server.php');
	
	
	$URL       = "urn:getDiscounts"; 
	$namespace = $URL . "wsdl";
	
	//using soap_server to create server object
	$server    = new soap_server;
	$server->configureWSDL('GetDiscount', $namespace);
	
	//register a function that works on server
	$server->register("getDiscount",
			array('order' => "xsd:String"),
			array('return' => "xsd:String"),
			false,
			false,
			false,
			false, 
			"Get the discount for a given order, it returns the original order with the discount added.");
	
	$server->register("HelloWorld",
			array(),
			array("result" => 'xsd:boolean'));

			
	// create the function
	function getDiscount($orderJSON)
	{
		
		if ($orderJSON == "") {
			return new soap_fault('Client', $namespace, 'No order received!');
		}
		return $gd->getDiscount($orderJSON);
		
	}
	
	function HelloWorld() {
		return true;
	}
	
	$server->soap_defencoding = 'UTF-8';
	$server->decode_utf8 = false;
	$server->encode_utf8 = true;
	
	// create HTTP listener
	$server->service("php://input");

}
else{
	
	if (trim($_REQUEST['orderJSON']) == "")
		die("No order received!");
	
	header('Content-Type: application/json');
	echo $gd->getDiscount($_REQUEST['orderJSON']);
	
}