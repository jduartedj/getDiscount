<?php

include_once 'Customers.class.php';
include_once 'Products.class.php';

// *********** TODOs **************

//TODO:Service consuption interface: get + NuSoap via index.php
//TODO:LOAD Discounts
//TODO:Classes: Order + Result
//TODO:Create Discounts JSON: 2 levels
//TODO:Create actions CLASS + methods - RULE OF THUMB - Read $Order + Write $Result

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
			$this->products= new Products("data/products.json");
		}
		
		private function loadCustomers(){
			//load JSON file
			$this->customers = new Customers("data/customers.json");
		}
		
		private function loadDiscounts(){
			//load JSON file
		}
		
		
		function getDiscount($orderJSON){
			
			//load Order
			$this->order = new Order($orderJSON);
			
			//create result object to preserve order
			$this->result = new Result ($this->order, $this->discounts);
			
			return $this->result;
		}
		
		function __destruct(){
			
			// release all vars
			//unecessary overzeleous unsetting of vars
			/*unset($this->products,
					$this->customers,
					$this->discounts);*/
		
		}
}