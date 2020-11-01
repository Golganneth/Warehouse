<?php

namespace Warehouse\Domain\Model\Inventory;

class StockUpdate {
    private $articleId;
    private $numUnits;

    public function __construct(int $articleId, int $numUnits) {
      $this->articleId = $articleId;
      $this->numUnits = $numUnits;
    }

    public function getArticleId() { return $this->articleId; }
    public function getNumUnits() { return $this->numUnits; }
}
