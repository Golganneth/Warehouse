<?php

namespace Warehouse\Domain\Model\Inventory;

use Warehouse\Domain\Model\Inventory\StockUpdateStatus;

class StockUpdateResponse {
  private $stockUpdateList;
  private $success;

  public function __construct() {
    $this->stockUpdateList = array();
  }

  public function getStockUpdateList() { return $this->stockUpdateList; }
  public function getSuccess() { return $this->success; }
  public function isSuccess() { return $this->success; }

  public function setSuccess(bool $success) {
    $this->success = $success;
    return $this;
  }


  public function addStockUpdateStatus(StockUpdateStatus $stockUpdateStatus) {
    array_push($this->stockUpdateList, $stockUpdateStatus);
    return $this;
  }
}
