<?php

namespace Warehouse\Infrastructure\Persistence\InMemory;

use Warehouse\Domain\Model\ProductRepository;
use Warehouse\Domain\Model\Product;

class InMemoryProductRepository implements ProductRepository {
  private $storage;

  public function findById($id) {
    return $this->storage[$id] ? $this->storage[$id] : null;
  }

  public function store(Product $product) {
    $this->storage[$product->getId()] = $product;
    return $product;
  }

  public function search($whereClause = array(), $options = array()) {
    $searchResult = $this->storage;
    if (isset($whereClause['name'])) {
      $searchResult = array_filter(
        $searchResult,
        function($v, $k) use ($whereClause){
          return strpos($v->getName(), $whereClause['name']) !== false;
        }, ARRAY_FILTER_USE_BOTH);
    }
    return $searchResult;
  }

}
