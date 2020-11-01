<?php

namespace Warehouse\Domain\Model\Inventory;

use Warehouse\Domain\Model\Inventory\StockQuery;

class StockQueryRequest {
  private $stockQueryList;

  public function __construct() {
    $this->stockQueryList = array();
  }

  public function addStockQuery(StockQuery $stockQuery) {
    array_push($this->stockQueryList, $stockQuery);
    return $this;
  }

  public function getStockQueries() { return $this->stockQueryList; }

}
