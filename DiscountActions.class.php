<?php
include_once 'Order.class.php';

class DiscountActions{
	
	function __contruct(){
		//init whatever necessary
	}
	
	function execute($id, Order $order, $scopeObj){
		
		switch ($id){
			
			case"1":
				$order->discount = round(0.1 * $order->total, 2);
				
				break;
			case"2":
				
				//1 for every 5
				$qtyFree = floor($scopeObj->quantity / 5);
				
				//add offer line
				$new = clone $scopeObj;
				
				$new->unitPrice = 0.00;
				$new->quantity = $qtyFree;
				$new->discount = 0.00;
				
				$order->items[] = $new;
				break;
			case"3":
				
				$min = INF;
				$minItem = null;
				
				// get cheapest item
				foreach($scopeObj->items as $item){
					if ($item->total < $min){
						$min = $item->total;
						$minItem = $item;
					}
				}
				
				$minItem->discount = round(0.2 * $minItem->total, 2);
				
				break;
			default:
				//discount doesn't exist
				break;			
		}
		
		$order->calculate();
		
	}
	
}