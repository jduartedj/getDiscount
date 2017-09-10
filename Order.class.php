<?php
include_once 'Products.class.php';

class Order{
	
	public $id,
			$customerId,
			$items,
			$discount,
			$total,
			$products,
			$categories;

	function __construct(string $jsonFile = "", Products $products){
		
		$this->products = $products;
		
		if ($jsonFile != ""){
			$this->loadOrder($jsonFile);
		}
		
		
			
		
	}
	
	private function loadOrder(string $jsonStr){
		
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
			
			foreach ($this->categories as $category)
				if ($category->id == $new->category) $category->items[] = &$new;
				
			$this->items[] = &$new;
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
		
		foreach($this->items as &$item){			
			$total += $item->total = $item->quantity * $item->unitPrice - $item->discount; 
		}
		
		$this->total = $total - $this->discount;
		
	}
	
	function categoryExists(string $id){
		
		foreach ($this->categories as $category){
			if ($category->id == $id)
				return true;
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
	
	/*"product-id": "B102",
      "quantity": "10",
      "unit-price": "4.99",
      "total": "49.90"
      */

	function __construct($itemInfo, Products $products){
		
		//Customer Init
		$this->productId= $itemInfo->{'product-id'};
		$this->quantity= $itemInfo->quantity;
		$this->unitPrice= $itemInfo->{'unit-price'};
		$this->discount = 0.00;
		$this->total= $itemInfo->total;
		
		$this->category = $products->getProductById($this->productId)->category;
		
		
	}
}

class Category{
	
	public	$id,
			$items;
	
	function __constuct(string $id){
		
		$this->id = $id;
		
	}
		
}