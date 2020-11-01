<?php

namespace Warehouse\Domain\Model\Inventory;

class StockQuery {
  private $articleId;

  public function __construct(int $articleId) {
    $this->articleId = $articleId;
  }

  public function getArticleId() { return $this->articleId; }
}
