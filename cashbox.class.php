<?php
namespace store;

class Cashbox {

	private bool $state = false;
	private array $customersQueue = [];

	public function open() {
		$this->state = true;
	}

	public function close() {
		$this->state = false;
	}

	public function isClose() {
		return !$this->state;
	}

	public function addCustomer(&$customer) {
		if (!$this->state) {
			return $this->state;
		}
		if (count($this->customersQueue) < MAX_CUSTOMERS) {
			$this->customersQueue[] = $customer;
		}
	}

	public function countOfCustomers() {
		if (!$this->state) {
			return $this->state;
		}
		return count($this->customersQueue);
	}

	public function serveCustomers() {
		if (!$this->state) {
			return $this->state;
		}
		$hour = HOUR;
		$removeCustomers = 0;
		foreach ($this->customersQueue as $customer) {
			$customerServeTime = $customer->getCountOfGoods() * CHECK_PRODUCT_TIME + PAYMENT_TIME;
			$hour -= $customerServeTime;
			if ($hour <= 0) {
				break;
			}
			$removeCustomers++;
		}
		$this->removeCustomers($removeCustomers);
		return $removeCustomers;
	}

	private function removeCustomers($count) {
		$this->customersQueue = array_slice($this->customersQueue, $count);
	}

}