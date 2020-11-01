<?php

namespace Warehouse\Domain\Model\Inventory;

use Warehouse\Domain\Model\Inventory\StockUpdate;

class StockUpdateRequest {
  private $stockUpdateList;

  public function __construct() {
    $this->stockUpdateList = array();
  }

  public function addStockUpdate(StockUpdate $update) {
    array_push($this->stockUpdateList, $update);
    return $this;
  }

  public function getStockUpdateList() { return $this->stockUpdateList; }
}
