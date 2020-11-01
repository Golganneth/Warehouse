<?php

namespace Warehouse\Domain\Model\Inventory;

class StockLock {
  private $articleId;
  private $units;

  public function __construct($articleId, $units) {
    $this->articleId = $articleId;
    $this->units = $units;
  }

  public function getArticleId() { return $this->articleId; }
  public function getUnits() { return $this->units; }

}
