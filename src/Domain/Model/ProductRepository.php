<?php

namespace Warehouse\Domain\Model;

interface ProductRepository {
  public function findById($id);
  public function store(Product $product);
  public function search($whereClause = array(), $options = array());
}
