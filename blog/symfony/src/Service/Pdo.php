<?php
/**
 * @copyright Copyright (c) 2019 Eduard Rudakan.
 * @author    Eduard Rudakan <rudiwork@ya.ru>
 * Project: blog
 * File: Pdo.php
 * Date: 24.05.19
 * Time: 16:45
 */
namespace App\Service;

use App\Help\Help;

/**
 * Class Pdo
 * @package App\Service
 */
class Pdo
{

    /**
     * @var Pdo|null
     */
    protected static $instance = null;
    /**
     * @var \PDO|null
     */
    protected static $pdo = null;

    /**
     * @return \PDO
     * @throws \Exception
     */
    public static function getInstance(): \PDO {
        if(self::$instance === null) {
            try{
                self::$pdo = new \PDO("mysql:host=db;dbname=test_news;", "root", "gdfgdsds3453dafg");
                self::$instance = new self;
            } catch (\PDOException $e) {
                throw new \Exception($e->getMessage());
            }
        }
        return self::$pdo;
    }

    // close
    final private function __construct() {}
    final private function __clone() {}
    final private function __sleep() {}
    final private function __wakeup() {}
}