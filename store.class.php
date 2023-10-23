<?php
namespace store;

class Store {

	private bool $state = false;
	private int $currentHour = MIN_WORK_HOUR;
	private array $cashboxes = [];
	private $currentCashbox = null;
	private int $maxCustomers = 0;
	private array $cashboxWaiting = [];

	public function work() {
		echo "\n-----------------------------------------\n";
		if ($this->currentHour <= MAX_WORK_HOUR) {
			if (!$this->state) {
				$this->state = true;
				$this->openCashbox();
				echo "Магазин открылся\n";
			}
		} else {
			$this->state = false;
			$this->cashboxes[] = [];
			return false;
		}
		$customers = $this->generateCustomer();
		foreach ($customers as $customer) {
			$this->findCurrentCashbox();
			$this->currentCashbox->addCustomer($customer);
		}
		$this->showState();
		$this->closeEmptyCashboxes();
		$this->serveCustomers();
		$this->currentHour++;
		return true;
	}

	private function openCashbox() {
		foreach ($this->cashboxes as $key => $cashbox) {
			if ($cashbox->isClose()) {
				$cashbox->open();
				$this->cashboxWaiting[$key] = 0;
				return $key;
			}
		}
		if (count($this->cashboxes) < MAX_CASHBOXES) {
			$cashbox = new Cashbox();
			$cashbox->open();
			$this->cashboxes[] = $cashbox;
			$this->cashboxWaiting[] = 0;
			return count($this->cashboxes) - 1;
		}
		return null;
	}

	private function generateCustomer() {
		$customers = [];
		if ($this->currentHour <= MIDDLE_OF_DAY) {
			$this->maxCustomers = $this->maxCustomers + INCREASE_OF_CUSTOMERS;
		} else if ($this->maxCustomers >= INCREASE_OF_CUSTOMERS) {
			$this->maxCustomers = $this->maxCustomers - INCREASE_OF_CUSTOMERS;
		}
		$customersArrived = rand(1, $this->maxCustomers);
		for ($i = 1; $i <= $customersArrived; $i++) {
			$customers[] = new Customer();
		}
		echo "Пришло покупателей: " . count($customers) . "\n";
		return $customers;
	}

	private function findCurrentCashbox() {
		$minCustomersInCashbox = MAX_CUSTOMERS;
		$cashboxKey = null;
		foreach ($this->cashboxes as $key => $cashbox) {
			$currentCashboxCount = $cashbox->countOfCustomers();
			if ($currentCashboxCount === 0) {
				$cashboxKey = $key;
				break;
			}
			if ($currentCashboxCount < $minCustomersInCashbox) {
				$minCustomersInCashbox = $currentCashboxCount;
				$cashboxKey = $key;
			}
		}
		if ($cashboxKey === null) {
			$cashboxKey = $this->openCashbox();
		}
		if ($cashboxKey !== null) {
			$this->currentCashbox = $this->cashboxes[$cashboxKey];
		} else {
			echo "Все кассы заняты. Покупатели уходят.\n";
		}
	}

	private function serveCustomers() {
		foreach ($this->cashboxes as $key => $cashbox) {
			$count = $cashbox->serveCustomers();
			echo "На кассе $key обслужено покуателей: $count\n";
		}
	}

	private function closeEmptyCashboxes() {
		foreach ($this->cashboxes as $key => $cashbox) {
			if ($cashbox->countOfCustomers() === 0) {
				$this->cashboxWaiting[$key]++;
			} else if ($this->cashboxWaiting[$key] !== 0) {
				$this->cashboxWaiting[$key] = 0;
			}
		}
		foreach ($this->cashboxWaiting as $key => $cashboxWaitingState) {
			if ($cashboxWaitingState === CLOSE_CASHBOX) {
				$this->cashboxes[$key]->close();
			}
		}
	}

	private function showState() {
		echo "Время: $this->currentHour:00\n";
		$cashboxCount = count($this->cashboxes);
		echo "Количество открытых касс: $cashboxCount\n";
		foreach ($this->cashboxes as $key => $cashbox) {
			$customersCount = $cashbox->countOfCustomers();
			echo "На кассе $key - $customersCount человек\n";
		}
	}

}