<?php
namespace store;

class Customer {

	private array $goods = [];
	private array $preferences = [];

	public function __construct() {
		$this->generateGoods();
	}

	private function generateGoods() {
		$goods = [];
		$countOfGoods = rand(1, MAX_GOODS);
		for ($i = 1; $i <= $countOfGoods; $i++){
			$goods[] = "Product $i";
		}
		$this->goods =  $goods;
	}

	public function getCountOfGoods() {
		return count($this->goods);
	}

	public function chooseProduct() {
		
	}

}