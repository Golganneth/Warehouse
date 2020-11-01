<?php

use PHPUnit\Framework\TestCase;

use Warehouse\Infrastructure\Persistence\InMemory\InMemoryArticleRepository;
use Warehouse\Infrastructure\Inventory\LocalInventoryService;
use Warehouse\Domain\Model\Article;

final class UpdateArticleStockTest extends TestCase {
  private $articleRepository;
  private $inventoryService;

  protected function setUp() : void {
    $this->articleRepository = new InMemoryArticleRepository();
    $this->articleRepository->store(new Article(1, "leg"));
    $this->articleRepository->store(new Article(2, "screw"));
    $this->articleRepository->store(new Article(3, "seat"));

    $this->inventoryService = new LocalInventoryService();
  }

  public function testUpdateArticleStock_ArticleNotExists_ThrowsException() {
    $sut = new Warehouse\Application\ArticleService(
      $this->articleRepository,
      $this->inventoryService
    );

    $this->expectException(Exception::class);
    $sut->updateArticleStock(4, 5);
  }

  public function testUpdateArticleStock_NegativeStock_ThrowsException() {
    $sut = new Warehouse\Application\ArticleService(
      $this->articleRepository,
      $this->inventoryService
    );

    $this->expectException(Exception::class);
    $sut->updateArticleStock(1, -3);

  }

  /*
  public function testUpdateArticleStock_InventoryServiceFails_ThrowsException() {
    $sut = new Warehouse\Application\ArticleService(
      $this->articleRepository,
      $this->inventoryService
    );

    $this->expectException(Exception::class);
    $sut->updateArticleStock(1, 3);
  }
  */

  public function testUpdateArticleStock_UpdateWorksWell_ReturnsTrue() {
    $sut = new Warehouse\Application\ArticleService(
      $this->articleRepository,
      $this->inventoryService
    );

    $response = $sut->updateArticleStock(1, 3);
    $this->assertTrue($response);
    $this->assertEquals(
      $this->inventoryService->getArticleStock(1),
      3
    );
  }
}
