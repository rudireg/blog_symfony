<?php
/**
 * @copyright Copyright (c) 2019 Eduard Rudakan.
 * @author    Eduard Rudakan <rudiwork@ya.ru>
 * Project: blog
 * File: ArticleCollectionInterface.php
 * Date: 25.05.19
 * Time: 14:59
 */
namespace App\Interfaces;

interface ArticleCollectionInterface
{
    /**
     * @param ArticleInterface $article
     * @return ArticleCollectionInterface
     */
    public function append(ArticleInterface $article): self;

    /**
     * @return ArticleInterface
     */
    public function shift():? ArticleInterface;

    /**
     * @return \ArrayObject
     */
    public function getAll(): \ArrayObject;
}