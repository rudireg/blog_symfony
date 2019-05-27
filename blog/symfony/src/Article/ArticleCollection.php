<?php
/**
 * @copyright Copyright (c) 2019 Eduard Rudakan.
 * @author    Eduard Rudakan <rudiwork@ya.ru>
 * Project: blog
 * File: ArticleCollection.php
 * Date: 25.05.19
 * Time: 15:39
 */
namespace App\Article;

use App\Interfaces\ArticleCollectionInterface;
use App\Interfaces\ArticleInterface;

/**
 * Class ArticleCollection
 * @package App\Article
 */
class ArticleCollection implements ArticleCollectionInterface
{
    /**
     * ArticleCollection constructor.
     */
    public function __construct() {
        $this->arrayObject = new \ArrayObject([]);
    }

    /**
     * Добавить статью в начало стека
     * @param ArticleInterface $article
     * @return ArticleCollection
     */
    public function append(ArticleInterface $article): ArticleCollectionInterface
    {
        $this->arrayObject->append($article);
        return $this;
    }

    /**
     * Вернуть первую статью из стека и удалить из стека
     * @return ArticleInterface|null
     */
    public function shift():? ArticleInterface
    {
        $article = $this->arrayObject->offsetGet(0);
        if (null !== $article) {
            $this->arrayObject->offsetUnset(0);
        }
        return $article;
    }

    /**
     * @return \ArrayObject
     */
    public function getAll(): \ArrayObject
    {
        return $this->arrayObject;
    }

    /**
     * @var \ArrayObject
     */
   protected $arrayObject;
}
