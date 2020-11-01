<?php

use PHPUnit\Framework\TestCase;

use Warehouse\Application\ProductService;
use Warehouse\Infrastructure\Inventory\LocalInventoryService;
use Warehouse\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use Warehouse\Domain\Model\Inventory\StockUpdateRequest;
use Warehouse\Domain\Model\Inventory\StockUpdate;
use Warehouse\Domain\Model\Product;


class RemoveProductTest extends TestCase {
  private $productRepository;
  private $inventoryService;

  public function setUp() : void {
    $this->productRepository = new InMemoryProductRepository();
    $this->inventoryService = new LocalInventoryService();

    $stockUpdateRequest = new StockUpdateRequest();
    $stockUpdateRequest->addStockUpdate(new StockUpdate(1, 11));
    $stockUpdateRequest->addStockUpdate(new StockUpdate(2, 4));
    $stockUpdateRequest->addStockUpdate(new StockUpdate(3, 20));
    $this->inventoryService->updateStock($stockUpdateRequest);

    $this->productRepository->store(
      new Product(1, 'Test name', array(1 => 5, 2 => 2, 3 => 4))
    );
  }

  public function testRemoveProduct_ArticlesAreAvailable_ReturnTrue() {
    $sut = new ProductService(
      $this->productRepository,
      $this->inventoryService
    );
    $quantity = 2;
    $productId = 1;

    $old_stock = array();
    $product = $this->productRepository->findById($productId);
    foreach($product->getProductArticles() as $articleId => $units) {
      $old_stock[$articleId] = $this->inventoryService->getArticleStock($articleId);
    }

    $response = $sut->removeProduct($productId, $quantity);
    $this->assertTrue($response);

    foreach($product->getProductArticles() as $articleId => $units) {
      $this->assertEquals(
        $this->inventoryService->getArticleStock($articleId),
        $old_stock[$articleId] - ($units * $quantity)
      );
    }
  }

  public function testRemoveProduct_ArticlesAreNotAvailable_ReturnFalse() {
    $sut = new ProductService(
      $this->productRepository,
      $this->inventoryService
    );

    $response = $sut->removeProduct(1, 3);
    $this->assertFalse($response);
  }
}
