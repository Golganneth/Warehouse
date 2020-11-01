<?php

namespace Warehouse\Domain\Model\Inventory;

class StockLockRequest {
  private $stockLockList;

  public function __construct() {
    $this->stockLockList = array();
  }

  public function addStockLock(StockLock $stockLock) {
    array_push($this->stockLockList, $stockLock);
    return $this;
  }

  public function getStockLocks() { return $this->stockLockList; }

}
