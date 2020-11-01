<?php

namespace Warehouse\Infrastructure\Persistence\InMemory;

use Warehouse\Domain\Model\ArticleRepository;
use Warehouse\Domain\Model\Article;

class InMemoryArticleRepository implements ArticleRepository {
  private $storage;

  public function __construct() {
    $this->storage = array();
  }

  public function store(Article $article) {
    $this->storage[$article->getId()] = $article;
    return $article;
  }

  public function findById($id) {
    return  isset($this->storage[$id]) ? : null;
  }
}
