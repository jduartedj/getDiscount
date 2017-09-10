<?php
class Products{
	
	private $products;

	function __construct(string $jsonFile = ""){
		if ($jsonFile != "")
			$this->loadProducts($jsonFile);
	}
	
	private function loadProducts(string $jsonFile){
		
		$json = file_get_contents($jsonFile);
		
		$infoObj = json_decode($json); 
		
		foreach ($infoObj as $prodInfo){
			$this->products[] = new Product($prodInfo);
		}
	}
	
	function getAll (){
		return $this->products;
		
	}
	
	function getProductById($id){
		foreach($this->products as $product){
			if ($product->id == $id) 
				return $product;
		}
		
		return null;
	}

}

class Product{
	
	public $id,
	$description,
	$category,
	$price;
	
	function __construct(stdClass $prodInfo){
		
		//Product Init
		$this->id = $prodInfo->id;
		$this->description= $prodInfo->description;
		$this->category= $prodInfo->category;
		$this->price= $prodInfo->price;
		
	}
}