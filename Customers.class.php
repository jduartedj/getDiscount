<?php
class Customers{
	
	private $customers;

	function __construct(string $jsonFile = ""){
		if ($jsonFile != "")
			$this->loadCustomers($jsonFile);
	}
	
	private function loadCustomers(string $jsonFile){
		
		$json = file_get_contents($jsonFile);
		
		$infoObj = json_decode($json); 
		
		foreach ($infoObj as $custInfo){
			$this->customers[] = new Customer($custInfo);
		}

	}
	
	function getAll (){
		return $this->customers;
		
	}
	
	/*function getCustomerById($id){
		//TODO
	}*/

}

class Customer{
	
	public $id,
	$name,
	$since,
	$revenue;
	

	function __construct($custInfo){
		
		//Customer Init
		$this->id = $custInfo->id;
		$this->name= $custInfo->name;
		$this->since= $custInfo->since;
		$this->revenue= $custInfo->revenue;
		
	}
}