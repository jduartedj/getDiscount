<?php
include_once 'Products.class.php';

class Order{
	
	public $id,
			$customerId,
			$items,
			$discount,
			$total,
			$products,
			$customers,
			$categories;

	function __construct($jsonFile = null, Products $products, Customers $customers){
		
		$this->products = $products;
		$this->customers= $customers;
		
		if ($jsonFile != null){
			$this->loadOrder($jsonFile);
		}
		
	}
	
	private function loadOrder($jsonStr){
		
		$infoObj = json_decode($jsonStr); 
		
		$this->id = $infoObj->id;
		$this->customerId = $infoObj->{'customer-id'};
		$this->discount = 0.00;
		$this->total = $infoObj->total;
		$this->categories = [];
		
		foreach ($infoObj->items as $itemInfo){
			$new = new Item($itemInfo, $this->products);
			
			if (!$this->categoryExists($new->category)) {
				$this->categories[] = new Category($new->category);
			}
			
			foreach ($this->categories as $category){
				if ($category->id == $new->category){
					$category->items[] = $new;
					$category->quantity += $new->quantity;
				}
			}
				
			$this->items[] = $new;
			
		}
		
	}
	
	function getResult(){

		$result = array(
			"id" => $this->id,
			"customer-id" => $this->customerId,
			"items" => array(),
			"discount" => $this->discount,
			"total" => $this->total
		);
		
		foreach ($this->items as $item){
			$result["items"][] = array (
					"product-id" => $item->productId,
					"quantity" => $item->quantity,
					"unit-price" => $item->unitPrice,
					"discount" => $item->discount,
					"total" => $item->total
			);
		}
		
		return json_encode($result);
		
	}
	
	function calculate() {
		
		$total = 0.00;
		
		foreach($this->items as $item){			
			$total += $item->total = $item->quantity * $item->unitPrice - $item->discount;
			$item->total = round($item->total, 2);
		}
		
		$this->total = round($total - $this->discount, 2);
		
	}
	
	function categoryExists($id){
		
		foreach ($this->categories as $category){
			
			if ($category->id == $id){
				return true;
				
			}
		}
		
		return false;
	}
	
	function getCustomer(){
		
		foreach ($this->customers->getAll() as $customer){
			if ($customer->id == $this->customerId)
				return $customer;
		}
		
		return false;
	}
	
}

class Item{
	
	public	$productId,
			$quantity,
			$unitPrice,
			$discount,
			$total,
			$category;
	

	function __construct($itemInfo, Products $products){
		
		//Customer Init
			
		$this->productId = $itemInfo->{'product-id'};
		$this->quantity = $itemInfo->quantity;
		$this->unitPrice = $itemInfo->{'unit-price'};
		$this->discount = 0.00;
		$this->total= $itemInfo->total;
		
		$this->category = $products->getProductById($this->productId)->category;
	
		
	}
}

class Category{
	
	public	$id,
			$quantity,
			$items;
	
	function __construct($id){
		$this->id = $id;
		$this->quantity = 0.00;
		$this->items = [];

	}
		
}