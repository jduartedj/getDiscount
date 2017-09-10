<?php
include_once 'Order.class.php';

class DiscountActions{
	
	function __contruct(){
		//init whatever necessary
	}
	
	function execute(string $id, Order $order, $scopeObj){
		
		switch ($id){
			
			case"1":
				$order->discount = 0.1 * $this->total;
				
				break;
			case"2":
				$new =  new Item($scopeObj, $order->products);
				
				$new->unitPrice = 0.00;
				$new->quantity = 1;
				$new->discount = 0.00;
				
				$order->items[] = $new;
				break;
			case"3":
				
				$min = INF;
				$minItem = null;
				
				foreach($scopeObj->items as &$item){
					if ($item->total < $min){
						$min = $item->total;
						$minItem = $item;
					}
				}
				
				$minItem->discount = 0.2 * $minItem->total;
				
				break;
			default:
				//discount doesn't exist
				break;			
		}
		
		$order->calculate();
		
	}
	
}