<?php

namespace Warehouse\Application;

use Warehouse\Domain\Model\ProductRepository;
use Warehouse\Domain\Service\InventoryService;
use Warehouse\Domain\Model\Inventory\StockLockRequest;
use Warehouse\Domain\Model\Inventory\StockLock;
use Warehouse\Domain\Model\Inventory\StockQueryRequest;
use Warehouse\Domain\Model\Inventory\StockQuery;
use Warehouse\Domain\Model\Product;
use \Excpetion;

class ProductService {
  private $productRepository;
  private $inventoryService;

  public function __construct(
    ProductRepository $productRepository,
    InventoryService $inventoryService
  ) {
    $this->productRepository = $productRepository;
    $this->inventoryService = $inventoryService;
  }

  /**
  * Registers a new product in the system
  *
  * @param int      $id       Product unique identifier
  * @param string   $name     Product name
  * @param array    $articles List of pairs ArticleId, Units defining the composition of the product
  */
  public function registerProduct($id, $name, $articles) {
    $product = $this->productRepository->findById($id);
    if($product) {
      throw new Exception('Product ' . $id . ' already exist');
    }

    $product = new Product($id, $name, $articles);

    $this->productRepository->store($product);

    return $product;
  }

  /**
  * Updates the number of units of one article that forms the product
  *
  * @param int $productId   Product unique identifier
  * @param int $articleId   Article unique identifier
  * @param int $units       Number of article units required by the product
  */
  public function updateProductArticle($productId, $articleId, $units) {
    $product = $this->productRepository->findById($productId);
    $product->setArticleUnits($articleId, $units);
    $this->productRepository->store($product);

    return $product;
  }

  /**
  * Performs a product search and returens the matching information and the number
  * of available units for those products
  *
  * @param $searchParams  array    Hash containing clauses to filter the products
  * @param $searchOptions array    Hash with query modifiers, like offset and limit
  */
  public function searchProducts($searchParams = array(), $searchOptions = array()) {
    $productList = $this->productRepository->search($searchParams, $searchOptions);

    $items = array();
    $toRequest = array();

    foreach($productList as $product) {
      $items[$product->getId()] = array(
        'product' => $product,
        'units' => 0
      );

      $articleIds = array_keys($product->getProductArticles());
      foreach($articleIds as $articleId) {
        $toRequest[$articleId] = 1;
      }
    }

    if (count($toRequest)) {
      $stockQueryRequest = new StockQueryRequest();
      foreach($toRequest as $articleId => $flag) {
        $stockQueryRequest->addStockQuery(new StockQuery($articleId));
      }
      $stockResponse = $this->inventoryService->queryStock($stockQueryRequest);
      $stockMap = array();
      foreach($stockResponse->getArticleStocks() as $articleStock) {
        $stockMap[$articleStock->getArticleId()] = $articleStock;
      }

      foreach ($productList as $product) {
        $productUnits = $product->calculateStock($stockMap);
        $items[$product->getId()]['units'] = $productUnits;
      }
    }

    return array(
      'total' => count($items),
      'items' => $items
    );
  }

  /**
  * Removes a product (the articles composing it) from the inventory
  *
  * @param $productId int   Product unique identifier
  * @param $quantity int    Number of products to remove
  */
  public function removeProduct($productId, $quantity) {
    $product = $this->productRepository->findById($productId);
    if (!$product) {
      throw new Exception('Product ' . $id . ' does not exist');
    }

    $stockLockRequest = new StockLockRequest();

    foreach($product->getProductArticles() as $articleId => $units) {
      $stockLockRequest->addStockLock(
        new StockLock(
          $articleId,
          $units * $quantity
        )
      );
    }

    $stockLockResult = $this->inventoryService->lockStock($stockLockRequest);
    return $stockLockResult->isSuccess();
  }
}
