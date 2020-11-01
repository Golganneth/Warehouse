<?php

use PHPUnit\Framework\TestCase;

use Warehouse\Application\ProductService;
use Warehouse\Infrastructure\Persistence\InMemory\InMemoryProductRepository;
use Warehouse\Infrastructure\Inventory\LocalInventoryService;

class RegisterProductTest extends TestCase {
  private $productRepository;
  private $inventoryService;

  protected function setUp() : void {
      $this->productRepository = new InMemoryProductRepository();
      $this->inventoryService = new LocalInventoryService();
  }

  public function testRegisterProduct_RequestOK_ReturnProduct() {
    $sut = new ProductService(
      $this->productRepository,
      $this->inventoryService
    );

    $id = 1;
    $name = 'Test Product';
    $articles = array(
      1 => 5,
      58 => 10,
      99 => 2
    );

    $product = $sut->registerProduct($id, $name, $articles);
    $this->assertEquals($id, $product->getId());
    $this->assertEquals($name, $product->getName());

    foreach($articles as $article) {
      $this->assertEquals(
        $product->getArticleUnits($article['id']),
        $article['units']
      );
    }
  }
}
