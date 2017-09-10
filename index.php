<?php
// Main file handles the requstes named index.php for ease of use
include_once 'GetDiscount.class.php';

$gd = new GetDiscount();

If (!isset($_GET['orderJSON'])){

	//call NUSOAP library
	require_once('nusoap/class.nusoap_base.php');
	require_once('nusoap/class.soap_server.php');
	require_once('nusoap/class.soap_val.php');
	require_once('nusoap/class.soap_parser.php');
	require_once('nusoap/class.soap_fault.php');
	
	$URL       = "http://localhost/";
	$namespace = $URL . '?wsdl';
	//using soap_server to create server object
	$server    = new soap_server;
	$server->configureWSDL('GetDiscount', $namespace);
	
	//register a function that works on server
	$server->register('getDiscount');
	
	
	
	// create the function
	function getDiscount($orderJSON)
	{
		if (!$orderJSON) {
			return new soap_fault('Client', '', 'No order sent!');
		}
		
		return $gd->getDiscount($orderJSON);
	
	}
	
	// create HTTP listener
	$server->service($HTTP_RAW_POST_DATA);
	exit();
}
else{
	
	if (trim($_GET['orderJSON']) == "")
		die("No order sent!");
	
	header('Content-Type: application/json');
	echo $gd->getDiscount($_GET['orderJSON']);
	
}

?>