<?php
namespace Warehouse\Application;

use Warehouse\Domain\Model\ArticleRepository;
use Warehouse\Domain\Service\InventoryService;
use Warehouse\Domain\Model\Article;
use Warehouse\Domain\Model\Inventory\StockUpdateRequest;
use Warehouse\Domain\Model\Inventory\StockUpdate;

use \Exception;

class ArticleService {
  private $articleRepository;
  private $inventoryService;

  public function __construct(
      ArticleRepository $articleRepository,
      InventoryService $inventoryService
  )
  {
    $this->articleRepository = $articleRepository;
    $this->inventoryService = $inventoryService;
  }

  /**
  * Registers a new article in the system
  *
  * @param $id    int     Article unique id
  * @param $name  string  Article name
  */
  public function registerArticle(int $id, string $name) {
    $article = $this->articleRepository->findById($id);
    if ($article && $article->name != $name) {
      throw new \Exception('Already exists a product with id' . $id);
    } else if (!$article) {
      $article = new Article($id, $name);
      $this->articleRepository->store($article);
    }

    return $article;
  }

  /**
  * Increase the stock of the given article by $num_units
  *
  * @param $id        int   Article ID to update
  * @param $new_units int   Number of units to increase the stock of the article
  */
  public function updateArticleStock(int $id, int $newUnits) {
    if ($newUnits <= 0) {
      throw new Exception('Units must be greater than 0');
    }

    $article = $this->articleRepository->findById($id);
    if(!$article) {
      throw new \Exception('Article ' . $id . ' not found');
    }
    $stockUpdate = new StockUpdate($id, $newUnits);

    $stockUpdateRequest = new StockUpdateRequest();
    $stockUpdateRequest->addStockUpdate(
      $stockUpdate
    );

    $updateResponse = $this->inventoryService->updateStock($stockUpdateRequest);
    if (!$updateResponse->isSuccess()) {
      throw new \Exception('Error while updating the stock for article id '
      . $article_id);
    }

    return true;
  }
}
