<?php

namespace Warehouse\Infrastructure\Inventory;

use Warehouse\Domain\Service\InventoryService;
use Warehouse\Domain\Model\Inventory\StockUpdateStatus;
use Warehouse\Domain\Model\Inventory\StockUpdateRequest;
use Warehouse\Domain\Model\Inventory\StockUpdateResponse;
use Warehouse\Domain\Model\Inventory\StockLockRequest;
use Warehouse\Domain\Model\Inventory\StockLockResponse;
use Warehouse\Domain\Model\Inventory\StockQueryRequest;
use Warehouse\Domain\Model\Inventory\StockQueryResponse;
use Warehouse\Domain\Model\Inventory\ArticleStock;

use \Exception;

class LocalInventoryService implements InventoryService {
  private $stockStorage;

  public function __construct() {
    $this->stockStorage = array();
  }

  public function lockStock(StockLockRequest $lockRequest) {
    $success = true;
    foreach ($lockRequest->getStockLocks() as $stockLock) {
      $stockUnits = $this->getArticleStock($stockLock->getArticleId());
      if ($stockUnits < $stockLock->getUnits()) {
        $success = false;
        break;
      }
    }

    /* No concurrency model in the Local, that's something that can be handled
     * in the other implementations (Redis, SQL...) where better built-in
     * support for this is already done.
     */
    if ($success) {
      foreach ($lockRequest->getStockLocks() as $stockLock) {
        $this->stockStorage[$stockLock->getArticleId()] -= $stockLock->getUnits();
      }
    }

    return new StockLockResponse($success);
  }

  public function queryStock(StockQueryRequest $query) {
    $stockQueryResponse = new StockQueryResponse();
    foreach($query->getStockQueries() as $stockQuery) {
      $stockQueryResponse->addArticleStock(
        new ArticleStock(
          $stockQuery->getArticleId(),
          $this->getArticleStock($stockQuery->getArticleId()))
      );
    }
    return $stockQueryResponse;
  }

  public function updateStock(StockUpdateRequest $stockUpdateRequest) {
    $success = true;
    $response = new StockUpdateResponse();

    foreach($stockUpdateRequest->getStockUpdateList() as $stockUpdate) {
      try {
        if(!isset($this->stockStorage[$stockUpdate->getArticleId()])) {
          $this->stockStorage[$stockUpdate->getArticleId()] = $stockUpdate->getNumUnits();
        } else {
          $this->stockStorage[$stockUpdate->getArticleId()] += $stockUpdate->getNumUnits();
        }
        $response->addStockUpdateStatus(
          new StockUpdateStatus($stockUpdate, true)
        );
      } catch (\Exception $ex) {
        $response->addStockUpdateStatus(new StockUpdateStatus($stockUpdate, false));
        $success = false;
      }
    }

    $response->setSuccess($success);
    return $response;
  }

  public function getArticleStock($id) {
    return isset($this->stockStorage[$id]) ? $this->stockStorage[$id] : 0;
  }

}
