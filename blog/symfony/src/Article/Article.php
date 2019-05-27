<?php
/**
 * @copyright Copyright (c) 2019 Eduard Rudakan.
 * @author    Eduard Rudakan <rudiwork@ya.ru>
 * Project: blog
 * File: Article.php
 * Date: 25.05.19
 * Time: 14:34
 */
namespace App\Article;

use App\Interfaces\ArticleInterface;
use App\PDO\Pdo;

/**
 * Class Article
 * @package App\Article
 */
class Article implements ArticleInterface
{
    /**
     * Article constructor.
     * @param int $id
     * @throws \Exception
     */
    public function __construct(int $id)
    {
        $this->id = $id;
        $this->pdo = Pdo::getInstance();
        $sth = $this->pdo->prepare("SELECT * FROM news WHERE news_id=?");
        $sth->execute([$id]);
        foreach ($sth->fetchAll() AS $item) {
            $this->setTheme('');
            $this->setTitle('');
            $this->setText('');
            $this->setDate(new \DateTime());
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'theme' => $this->theme,
            'title' => $this->title,
            'text'  => $this->text,
            'date'  => $this->date
        ];
    }

    /**
     * @return int
     */
    public function save(): int
    {
        // TODO: Implement save() method.
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Article
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getTheme(): int
    {
        return $this->theme;
    }

    /**
     * @param int $theme
     * @return Article
     */
    public function setTheme(int $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Article
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Article
     * @throws \Exception
     */
    public function setDate(\DateTime $date = null): self
    {
        $this->date = $date?:(new \DateTime());
        return $this;
    }

    /**
     * @var \App\PDO\Pdo
     */
    protected $pdo;
    /**
     * @var int
     */
    protected $id;
    /**
     * @var int
     */
    protected $theme;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $text;
    /**
     * @var \DateTime
     */
    protected $date;
}