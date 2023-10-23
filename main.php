<?php
namespace store;

const MAX_CASHBOXES = 5;
const CLOSE_CASHBOX = 2; // Количество интераций до закрытия кассы
const MAX_GOODS = 10;
const MIN_WORK_HOUR = 10;
const MAX_WORK_HOUR = 22;
const MAX_CUSTOMERS = 5; // Максимальное количество покупателей на кассе
const INCREASE_OF_CUSTOMERS = 10; // Увеличение числа покупателей в час
const HOUR = 60;
CONST PAYMENT_TIME = 3; // Минуты
CONST CHECK_PRODUCT_TIME = 2; // Минуты
const MIDDLE_OF_DAY = 16;

require "store.class.php";
require "cashbox.class.php";
require "customer.class.php";

use store\Store;

$store = new Store();

$storeOpen = true;
while ($storeOpen) {
	$storeOpen = $store->work();
}