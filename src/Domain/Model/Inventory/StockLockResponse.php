<?php

namespace Warehouse\Domain\Model\Inventory;

class StockLockResponse {
  private $success;

  public function __construct($success) {
    $this->success = $success;
  }

  public function isSuccess() { return $this->success; }
}
