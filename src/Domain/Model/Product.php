<?php

namespace Warehouse\Domain\Model;

class Product {
  private $id;
  private $name;
  private $productArticles;

  public function __construct($id, $name, array $articles) {
    /* validate $id: positive integer */
    /* validate $name: not-empty string */
    /* product composition is not an empty list and each element is an articleId, units pair */
    $this->id = $id;
    $this->name = $name;

    foreach($articles as $articleId => $units) {
      $this->productArticles[$articleId] = $units;
    }
  }

  public function getId() { return $this->id; }
  public function getName() { return $this->name; }
  public function getProductArticles() { return $this->productArticles; }

  public function setArticleUnits($articleId, $units) {
    if ($units <= 0) {
      throw new \Exception("Cannot set 0 or less units for an article");
    }
    $this->productArticles[$articleId] = $units;
    return $this;
  }

  public function getArticleUnits($articleId) {
    return isset($this->productArticles[$articleId]) ?
      $this->productArticles[$articleId] : 0;
  }

  public function calculateStock($articleStock) {
    $productStock = null;

    foreach ($this->productArticles as $articleId => $units) {
      $newProductStock = intval(floor($articleStock[$articleId]->getUnits() / $units));
      if(is_null($productStock) || $productStock > $newProductStock) {
        $productStock = $newProductStock;
      }
    }

    return $productStock;
  }
}
