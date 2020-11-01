<?php

namespace Warehouse\Domain\Model;

use Warehouse\Domain\Model\Article;

interface ArticleRepository {
  public function store(Article $article);
  public function findById($id);
}
