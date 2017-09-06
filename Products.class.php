<?php
class Products{
	
	private $products;

	function __construct($jsonFile = ""){
		
		$this->loadProducts($jsonFile);
	}
	
	private function loadProducts($jsonFile){
		
		$json = file_get_contents($jsonFile);
		
		$infoObj = json_decode($json); 
		
		foreach ($infoObj as $prodInfo){
			$this->products[] = new Customer($prodInfo);
		}

	}
	
	function getAll (){
		return $this->products;
		
	}
	
	/*function getProductById($id){
		//TODO
	}*/

}

class Product{
	
	public $id,
	$description,
	$category,
	$price;
	
	function __construct($prodInfo){
		
		//Product Init
		$this->id = $prodInfo->id;
		$this->description= $prodInfo->description;
		$this->category= $prodInfo->category;
		$this->price= $prodInfo->price;
		
	}
}