<?php
/**
 * @copyright Copyright (c) 2019 Eduard Rudakan.
 * @author    Eduard Rudakan <rudiwork@ya.ru>
 * Project: blog
 * File: ArticleInterface.php
 * Date: 25.05.19
 * Time: 14:59
 */

namespace App\Interfaces;


interface ArticleInterface
{
    /**
     * Сериализовать объект в массив
     * @return array
     */
    public function toArray(): array;

    /**
     * Сохранить объект в БД
     * @return int
     */
    public function save(): int;
}