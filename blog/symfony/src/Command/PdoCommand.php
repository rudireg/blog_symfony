<?php
/**
 * @copyright Copyright (c) 2019 Eduard Rudakan.
 * @author    Eduard Rudakan <rudiwork@ya.ru>
 * Project: blog
 * File: PdoCommand.php
 * Date: 24.05.19
 * Time: 16:52
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PdoCommand extends Command
{
    protected $db;

    /**
     * PdoCommand constructor.
     * @param string|null $name
     * @throws \Exception
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);
        $this->db = \App\PDO\Pdo::getInstance();
    }

    protected function configure()
    {
        $this->setName('app:create_tables');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            // таблица themes
            $sql = "CREATE TABLE IF NOT EXISTS `themes` (
                    `theme_id` INT(11) NOT NULL AUTO_INCREMENT,
                    `theme_title` VARCHAR(255) NOT NULL,
                    PRIMARY KEY(`theme_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $this->db->query($sql);
            // init
            $sql = "INSERT INTO `themes` (`theme_id`, `theme_title`) VALUES
                    (1, 'Наука'),
                    (2, 'Спорт'),
                    (3, 'Интернет'),
                    (4, 'Авто'),
                    (5, 'Глямур'),
                    (6, 'Искусство')";
            $this->db->query($sql);

            // таблица news
            $sql = "CREATE TABLE IF NOT EXISTS `news` (
                `news_id` INT(11) NOT NULL AUTO_INCREMENT,
                `date` DATETIME NOT NULL DEFAULT now(),
                `theme_id` INT,
                `text` TEXT,
                `title` VARCHAR(255),
                PRIMARY KEY(`news_id`),
                KEY `news_date_idx` (`theme_id`, `date`),
                FOREIGN KEY (`theme_id`) REFERENCES themes(`theme_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->db->query($sql);

        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }




    }

}