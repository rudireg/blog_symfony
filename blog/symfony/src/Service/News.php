<?php
/**
 * @copyright Copyright (c) 2019 Eduard Rudakan.
 * @author    Eduard Rudakan <rudiwork@ya.ru>
 * Project: blog
 * File: News.php
 * Date: 26.05.19
 * Time: 12:09
 */
namespace App\Service;


use App\Help\Help;

class News
{
    /**
     * News constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->pdo = Pdo::getInstance();
    }

    /**
     * Получить кол-во новостей по годам и месяцам
     * @return array
     */
    public function getYearMonthCount(): array
    {
        $sth = $this->pdo->query("
                                    SELECT 
                                        DATE_FORMAT(date, '%Y') AS yy, 
                                        DATE_FORMAT(date, '%m') AS mm, 
                                        COUNT(*) AS cnt
                                    FROM news
                                    GROUP BY yy, mm
                                    ORDER BY yy DESC, mm DESC", \PDO::FETCH_ASSOC);
        $rv = $sth->fetchAll();
        $result = [];
        foreach ($rv AS $item) {
            $array[] = date("F", strtotime("+{$item['mm']} months"));
            $result[$item['yy']][$item['mm']] = [
                'russ_month' => Help::monthToRus($item['mm']),
                'count'      => (int)$item['cnt']
            ];
        }
        return $result;
    }

    /**
     * Получить кол-во новостей по темам
     * @return array
     */
    public function getThemeCount(): array
    {
        $sth = $this->pdo->query("
                                    SELECT
                                           t.theme_id,
                                           COUNT(*) AS cnt,
                                           t.theme_title
                                    FROM news n
                                    INNER JOIN themes t on n.theme_id = t.theme_id
                                    GROUP BY n.theme_id
                                    ORDER BY t.theme_id ASC", \PDO::FETCH_ASSOC);
        return $sth->fetchAll();
    }

    /**
     * Выборка статей, или выборка одной статьи
     * @param int|null $year - год
     * @param int|null $month - месяц
     * @param int|null $id - ID новости для детального отображения
     * @param int|null $themeId - ID темы, при фильрации по теме
     * @param int|null $page - текущий номер страницы (для пагинации)
     * @return array
     */
    public function getNews(?int $year, ?int $month, ?int $id, ?int $themeId, ?int $page): array
    {
        $page = $page? :1;
        $offset = ($page -1) * self::LIMIT;
        $where = $text = '';
        $text = ", CONCAT(LEFT(n.text, 256), ' ...') AS text ";
        if (NULL !== $id) {
            $where = " AND n.news_id=:id ";
            $text = '';
        } else if (NULL !== $themeId) {
            $where = " AND n.theme_id=:theme_id ";
        } else if(NULL !== $year && NULL !== $month) {
            $where = " AND DATE_FORMAT(n.date, '%Y-%c') = CONCAT(:year, '-', :month) ";
        } else if (NULL !== $year) {
            $where = " AND DATE_FORMAT(n.date, '%Y') = :year";
        }
        $sql = "SELECT 
                SQL_CALC_FOUND_ROWS
                n.*,
                t.theme_title,
                DATE_FORMAT(n.date, '%Y-%m-%d') AS date,
                DATE_FORMAT(n.date, '%Y') AS yy, 
                DATE_FORMAT(n.date, '%m') AS mm
                $text
                FROM news n
                LEFT JOIN themes t on n.theme_id = t.theme_id
                WHERE TRUE $where
                ORDER BY n.date DESC
                LIMIT :limit OFFSET :offset";
        $sth = $this->pdo->prepare($sql);
        if (NULL !== $id) {
            $sth->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        } else if (NULL !== $themeId) {
            $sth->bindValue(':theme_id', (int) $themeId, \PDO::PARAM_INT);
        } else if(NULL !== $year && NULL !== $month) {
            $sth->bindValue(':year', (int) $year, \PDO::PARAM_STR);
            $sth->bindValue(':month', (int) $month, \PDO::PARAM_STR);
        } else if (NULL !== $year) {
            $sth->bindValue(':year', (int) $year, \PDO::PARAM_STR);
        }
        $sth->bindValue(':limit', (int) self::LIMIT, \PDO::PARAM_INT);
        $sth->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
        $sth->execute();
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $rv = $sth->fetchAll();
        if (!empty($rv)) {
            $sth = $this->pdo->query('SELECT FOUND_ROWS()', \PDO::FETCH_ASSOC);
            $rows = (int) $sth->fetch()['FOUND_ROWS()'];
            $rv['pagination']['max'] = (int) ceil($rows / self::LIMIT);
        }
        return $rv;
    }

    /**
     * Удалить новость
     * @param int $id - ID новости
     * @return bool
     */
    public function deleteNews(int $id): bool
    {
        if (!empty($id)) {
            $sql = "DELETE FROM news WHERE news_id=?";
            $sth = $this->pdo->prepare($sql);
            return $sth->execute([$id]);
        }
        return false;
    }

    /**
     * Получить список тем
     * @return array
     */
    public function getThemeList(): array
    {
        $sth = $this->pdo->query("SELECT theme_id, theme_title FROM themes", \PDO::FETCH_ASSOC);
        return $sth->fetchAll();
    }

    /**
     * Добавить или редактировать новость
     * @param int|NULL $id - ID новости (в случаяъ редактирования новостей)
     * @param string $title
     * @param string $text
     * @param string $date
     * @param string $theme
     * @return bool
     * @throws \Exception
     */
    public function addNews(?int $id, string $title, string $text, string $date, string $theme): bool
    {
        $date = (new \DateTime($date))->format('Y-m-d');
        if (empty($id)) {
            $sql = "INSERT INTO news (date, theme_id, text, title)
                VALUES (:date, :theme_id, :text, :title)";
            $sth = $this->pdo->prepare($sql);
            $sth->bindParam(':date', $date);
            $sth->bindParam(':theme_id', $theme);
            $sth->bindParam(':text', $text);
            $sth->bindParam(':title', $title);
            $rv = $sth->execute();
        } else {
            $sql = "UPDATE news SET date=:date, theme_id=:theme_id, text=:text, title=:title WHERE news_id=:id";
            $sth = $this->pdo->prepare($sql);
            $sth->bindParam(':date', $date);
            $sth->bindParam(':theme_id', $theme);
            $sth->bindParam(':text', $text);
            $sth->bindParam(':title', $title);
            $sth->bindParam(':id', $id);
            $rv = $sth->execute();
        }
        return $rv;
    }

    /**
     * @var \PDO
     */
    protected $pdo;
    /**
     * лимит
     */
    CONST LIMIT = 5;
}