<?php
class Products{
	
	private $products;

	function __construct($jsonFile = null){
		if ($jsonFile != null)
			$this->loadProducts($jsonFile);
	}
	
	private function loadProducts($jsonFile){
		
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
		
		return new Product();
	}

}

class Product{
	
	public $id,
	$description,
	$category,
	$price;
	
	function __construct(stdClass $prodInfo = null){
		
		if ($prodInfo != "") {
		
			//Product Init
			$this->id = $prodInfo->id;
			$this->description= $prodInfo->description;
			$this->category= $prodInfo->category;
			$this->price= $prodInfo->price;
			
		}
		
	}
}