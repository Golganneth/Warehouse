<?php

namespace Warehouse\Domain\Model\Inventory;

class ArticleStock {
  private $articleId;
  private $units;

  public function __construct($articleId, $units) {
    /* validate $articleId: positive integer */
    /* validate $quantity: integer */

    $this->articleId = $articleId;
    $this->units = $units;
  }

  public function getArticleId() { return $this->articleId; }
  public function getUnits() { return $this->units; }
}
