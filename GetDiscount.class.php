<?php
// TODOs

// LOADs
// Aux methods
// Aux Objects
// Create Discounts JSON
// Create set*Discount functions - RULE OF THUMB - Read $Order + Write $Result

class GetDiscount{
	

		private $products,
				$customers,
				$discounts,
				$order,
				$result;
		
		
		function __construct(){
			
			//load JSON data
			loadProduct();
			loadCustomers();
			loadDiscounts();
		}
		
		private function loadProduct(){
			//load JSON file
			
			
		}
		
		private function loadCustomers(){
			//load JSON file
		}
		
		private function loadDiscounts(){
			//load JSON file
		}
		
		private function loadCategories(){
			//load order with Categories
			foreach($line in $this->order->lines){
				$line.category = $this->products->get($line->product-id);
			}
		}
		private function categoryExists($catID) {
			//TODO
			
		}
		private function calcDiscounts($result){
			
			$categories = new Categories();
			$lines = [];
			
			//iterate through all discount lines
			foreach($discount in $this->discounts){
				switch ($discount->scope) {
					case "Global":
						setGlobalDiscount();
					break;
					case "Category":
						if ($discount->scopeId == "*" || categoryInOrder($discount->scopeId)){
							//create category object(s) (total, quantity, lines)
							if ($discount->scopeId == "*"){
								foreach($line in $this->order->lines){
									addToCategory($line, $categories);
								}
							}
							else{
								addToCategory($this->order->lines->getByCategories($discount->scopeId), $categories);
							}
							
							//Loop through categories
							foreach($category in $categories){
								setCategoryDiscount($category);
							}
						}
						
					break;
					case "Line":
						if ($discount->scopeId == "*" || productInOrder($discount->scopeId)){
							
							if ($discount->scopeId == "*"){
								foreach($line in $this->order->lines){
									if ($discount->scopeId == "*" || $line->product-id == $discount->scopeId){
										$lines[] = $line;
									}
								}
							}

							//Loop through lines
							foreach($line in $lines){
								setLineDiscount($category);
							}
						}
					break;
				
					default:
						//Misconfigured discount
					break;
				}
			}
		}
		
		function getDiscount($order){
			
			//load Order
			$this->order = $order;
			
			//create result object (duplicate)
			$this->result = $order;
			
			//load product categories into original order
			loadCategories();
			
			//calculate Discounts
			calcDiscounts();
			
			return $this->result;
		}
		
		function __destruct(){
			
			// release all vars
			unset($products);
			unset($customers);
			unset($discounts);
		
		}
}