<?php

namespace Warehouse\Domain\Model\Inventory;

class StockQueryResponse {
  private $articleStockList;

  public function __construct() {
    $this->articleStockList = array();
  }

  public function addArticleStock(ArticleStock $articleStock) {
    array_push($this->articleStockList, $articleStock);
    return $this;
  }

  public function getArticleStocks() { return $this->articleStockList; }
}
