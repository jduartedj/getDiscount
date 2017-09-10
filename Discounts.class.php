<?php
include_once 'DiscountActions.class.php';

class Discounts{
	
	private $discounts;

	function __construct(string $jsonFile = ""){
		if ($jsonFile != "")
			$this->loadDiscounts($jsonFile);
	}
	
	private function loadDiscounts(string $jsonFile){
		
		$json = file_get_contents($jsonFile);
		
		$infoObj = json_decode($json); 
		
		foreach ($infoObj as $discInfo){
			$this->discounts[] = new Discount($discInfo);
		}

	}
	
	function getAll (){
		return $this->discounts;
		
	}
	
	function apply(Order $order){
		foreach ($this->discounts as $discount)
			$discount->applyDiscount($order);
	}

}

class Discount{
	
	public	$id,
			$scope,
			$scopeFilter,
			$rules;
	
	function __construct(Object $discInfo){
		
		//Product Init
		$this->id = $discInfo->id;
		$this->scope= $discInfo->scope;
		$this->scopeFilter= $discInfo->scopeFilter;
		
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
		
		$scopeObj = null;
		
		switch ($this->scope){
			case "Order":
			case "Total":
				$scopeObj[] = &$order;
				break;
				
			case "Category":
			case "Type":
				if ($discount->scopeFilter== "*" || $order->categoryExists($discount->scopeFilter)){
					
					if ($discount->scopeId == "*"){
						$scopeObj = &$order->categories;
					}
					else{
						foreach ($order->categories as &$category)
							if ($category->id == $discount->scopeFilter) $scopeObj[] = $category;
					}
					
				}
				break;
			
			case "Article":
			case "Product":
			case "Item":
			case "Line":
				if ($discount->scopeFilter== "*" || $order->itemExists($discount->scopeFilter)){
					
					if ($discount->scopeId == "*"){
						$scopeObj = $order->items->getAll();
					}
					else{
						$scopeObj[] =  $order->items->getItemById($discount->scopeFilter);
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
		
	function _construct(Object $rule){
		$this->field = $rule->field;
		$this->operator= $rule->operator;
		$this->value= $rule->value;
		$this->container= $discount;
	}
	
	function validate($scopeObject){
	
		$result = false;
		
		switch ($this->operator){
			
			case "=":
			case "==":
				$result = $scopeObject->$field == $this->value;
				break;
			case ">=":
				$result = $scopeObject->$field >= $this->value;
				break;
			case "<=":
				$result = $scopeObject->$field <= $this->value;
				break;
			case ">":
				$result = $scopeObject->$field > $this->value;
				break;
			case "<":
				$result = $scopeObject->$field < $this->value;
				break;
			case "!=":
			case "<>":
				$result = $scopeObject->$field != $this->value;
				break;
			default:
				$result = false;
				break;
		}

	return 	$result;
	
	}
}