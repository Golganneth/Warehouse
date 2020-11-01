<?php

namespace Warehouse\Domain\Model\Inventory;

use Warehouse\Domain\Model\Inventory\StockUpdate;

class StockUpdateStatus {
  private $stockUpdate;
  private $success;

  public function __construct(StockUpdate $stockUpdate, bool $success) {
      $this->stockUpdate = $stockUpdate;
      $this->success = $success;
  }

  public function getStockUpdate() { return $this->stockUpdate; }
  public function getSuccess() { return $this->success; }
}
