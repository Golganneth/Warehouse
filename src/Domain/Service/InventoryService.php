<?php

namespace Warehouse\Domain\Service;

use Warehouse\Domain\Model\Inventory\StockUpdateRequest;
use Warehouse\Domain\Model\Inventory\StockLockRequest;
use Warehouse\Domain\Model\Inventory\StockQueryRequest;

interface InventoryService {
  public function lockStock(StockLockRequest $lockRequest);
  public function queryStock(StockQueryRequest $query);
  public function updateStock(StockUpdateRequest $stockUpdate);
  public function getArticleStock($articleId);
}
