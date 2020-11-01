<?php

use PHPUnit\Framework\TestCase;

use Warehouse\Application\ProductService;
use Warehouse\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use Warehouse\Infrastructure\Inventory\LocalInventoryService;
use Warehouse\Domain\Model\Inventory\StockUpdateRequest;
use Warehouse\Domain\Model\Inventory\StockUpdate;
use Warehouse\Domain\Model\Product;

class SearchProductTest extends TestCase {
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

    $this->productRepository->store(
      new Product(2, 'Test product', array(1 => 2, 3 => 5))
    );
  }
  public function testSearchProduct_SearchByName_ReturnsProducts() {
      $sut = new ProductService(
        $this->productRepository,
        $this->inventoryService
      );
      $searchResult = $sut->searchProducts(array('name' => 'Test' ));

      $this->assertEquals(2, $searchResult['total']);
      $this->assertEquals(2, $searchResult['items'][1]['units']);
      $this->assertEquals(4, $searchResult['items'][2]['units']);
  }
}
