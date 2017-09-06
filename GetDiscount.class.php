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
			$this->loadProduct();
			$this->loadCustomers();
			$this->loadDiscounts();
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
			foreach($this->order->lines as $line){
				$line->category = $this->products->get($line->product-id);
			}
		}
		private function categoryExists($catID) {
			//TODO
			
		}
		private function calcDiscounts($result){
			
			$categories = new Categories();
			$lines = [];
			
			//iterate through all discount lines
			foreach($this->discounts as $discount){
				switch ($discount->scope) {
					case "Global":
						$this->setGlobalDiscount();
					break;
					case "Category":
						if ($discount->scopeId == "*" || categoryInOrder($discount->scopeId)){
							//create category object(s) (total, quantity, lines)
							if ($discount->scopeId == "*"){
								foreach($this->order->lines as $line){
									$this->addToCategory($line, $categories);
								}
							}
							else{
								$this->addToCategory($this->order->lines->getByCategories($discount->scopeId), $categories);
							}
							
							//Loop through categories
							foreach($categories as $category){
								$this->setCategoryDiscount($category);
							}
						}
						
					break;
					case "Line":
						if ($discount->scopeId == "*" || productInOrder($discount->scopeId)){
							
							if ($discount->scopeId == "*"){
								foreach($this->order->lines as $line){
									if ($discount->scopeId == "*" || $line->product-id == $discount->scopeId){
										$lines[] = $line;
									}
								}
							}

							//Loop through lines
							foreach($lines as $line){
								$this->setLineDiscount($category);
							}
							
						}
					break;
				
					default:
						//Misconfigured discount
					break;
				}
			}
			
			//unecessary overzeleous unsetting of vars
			unset($categories);
			unset($lines);
		}
		
		function getDiscount($order){
			
			//load Order
			$this->order = $order;
			
			//create result object (duplicate)
			$this->result = $order;
			
			//load product categories into original order
			$this->loadCategories();
			
			//calculate Discounts
			$this->calcDiscounts();
			
			return $this->result;
		}
		
		function __destruct(){
			
			// release all vars
			//unecessary overzeleous unsetting of vars
			unset($this->products);
			unset($this->customers);
			unset($this->discounts);
		
		}
}