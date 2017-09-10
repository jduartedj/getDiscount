<?php
include_once 'DiscountActions.class.php';

class Discounts{
	
	private $discounts;

	function __construct($jsonFile = null){
		if ($jsonFile != null)
			$this->loadDiscounts($jsonFile);
	}
	
	private function loadDiscounts($jsonFile){
		
		$json = file_get_contents($jsonFile);
		
		$infoObj = json_decode($json); 
		
		foreach ($infoObj as $discInfo){
			$this->discounts[] = new Discount($discInfo);
		}
		
	}
	
	function getAll (){
		return $this->discounts;
		
	}
	
	function getDiscountsByScope($scope){
		$discounts = [];
		
		foreach($this->discounts as $discount){
			if ($discount->scope == $scope) $discounts[] = $discount;
		}
		
		
		return $discounts;
		
	}
	
	function apply(Order $order){
		
		// discounts should be aplied from bottom to top (hierarchically)
		// to avoid giving more discount than it should 
		
		
		foreach ($this->getDiscountsByScope("Item") as $discount)
			$discount->applyDiscount($order);

		foreach ($this->getDiscountsByScope("Category") as $discount)
			$discount->applyDiscount($order);
		
		foreach ($this->getDiscountsByScope("Order") as $discount)
			$discount->applyDiscount($order);
		
		foreach ($this->getDiscountsByScope("Customer") as $discount)
			$discount->applyDiscount($order);
	}

}

class Discount{
	
	public	$id,
			$scope,
			$scopeFilter,
			$rules;
	
	function __construct($discInfo){
		
		//Product Init
		$this->id = $discInfo->id;
		$this->scope= $discInfo->scope;
		$this->scopeFilter= $discInfo->{'scope-filter'};
		
		foreach($discInfo->rules as $rule){
			$this->rules[] = new Rule($rule);
		}
		
	}
	
	function validate($scopeObject){
		
		//if we don't have rules it is always valid
		$result = true;
		
		foreach ($this->rules as $rule){
			
			$result = $result && $rule->validate($scopeObject);
	
		}
		
		return $result;
		
	}
	
	function applyDiscount(Order $order){
		
		$scopeObj = [];
		
		switch ($this->scope){
			case "Customer":
				$scopeObj[] = $order->getCustomer();
				break;
			case "Order":
			//case "Total":
				$scopeObj[] = $order;
				break;
				
			case "Category":
			//case "Type":
				if ($this->scopeFilter== "*" || $order->categoryExists($this->scopeFilter)){
					
					if ($this->scopeFilter== "*"){
						$scopeObj = $order->categories;
					}
					else{
						foreach ($order->categories as $category)
							if ($category->id == $this->scopeFilter) $scopeObj[] = $category;
					}
					
				}
				break;
			
			//case "Article":
			//case "Product":
			case "Item":
			//case "Line":
				if ($this->scopeFilter== "*" || $order->itemExists($this->scopeFilter)){
					
					if ($this->scopeFilter== "*"){
						$scopeObj = $order->items;
					}
					else{
						$scopeObj[] =  $order->items->getItemById($this->scopeFilter);
					}
					
				}
				break;
			default:
				//wrong definition of Discount
				break;
		}
		
		foreach ($scopeObj as $object){
			
			if ($this->validate($object)){
		
				//We keep actions separated to keep discount configuration separe from development
				$action = new DiscountActions();
				
				$action->execute($this->id, $order, $object);
			}
		}
		
	}
}

class Rule{
	private	$field,
			$operator,
			$value,
			$container;
		
	function __construct(stdClass $rule){
		$this->field = $rule->field;
		$this->operator= $rule->operator;
		$this->value= $rule->value;
		//$this->container= $discount;
	
	}
	
	function validate($scopeObject){
	
		$result = false;
		
		switch ($this->operator){
			
			case "=":
			case "==":
				$result = $scopeObject->{$this->field} == $this->value;
				break;
			case ">=":
				$result = $scopeObject->{$this->field} >= $this->value;
				break;
			case "<=":
				$result = $scopeObject->{$this->field} <= $this->value;
				break;
			case ">":
				$result = $scopeObject->{$this->field} > $this->value;
				
				break;
			case "<":
				$result = $scopeObject->{$this->field} < $this->value;
				break;
			case "!=":
			case "<>":
				$result = $scopeObject->{$this->field} != $this->value;
				break;
			default:
				$result = false;
				break;
		}

	return 	$result;
	
	}
}