<?php

include_once 'Customers.class.php';
include_once 'Products.class.php';
include_once 'Discounts.class.php';
include_once 'Order.class.php';


// *********** TODOs **************

//TODO: actions Class
//TODO:Service consuption interface: get + NuSoap via index.php



class GetDiscount{

		private $products,
				$customers,
				$discounts,
				$order;
		
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
			$this->discounts = new Discounts("data/discounts.json");
		}
		
		
		function getDiscount($orderJSON){
			
			//load Order
			$this->order = new Order($orderJSON);
			
			//apply discounts
			$this->discounts->apply($this->order);
			
			//return result
			return $this->order->getResult();

		}
		
		function __destruct(){
			
			// release all vars
			/*unecessary overzeleous unsetting of vars
				unset($this->products,
					$this->customers,
					$this->discounts,
					etc.);*/
		
		}
}