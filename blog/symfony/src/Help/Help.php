<?php
/**
 * @copyright Copyright (c) 2019 Eduard Rudakan.
 * @author    Eduard Rudakan <rudiwork@ya.ru>
 * Project: blog
 * File: Help.php
 * Date: 25.05.19
 * Time: 23:01
 */

namespace App\Help;


class Help
{
    /**
     * Перевод числового предстваления месяца в строчное
     * @param int $num - порядковый номер месяца
     * @param bool $ucfirst - следует ли первый символ месяца предоставить вверхнем регистре
     * @return mixed
     */
    public static function monthToRus(int $num, bool $ucfirst = true)
    {
        $month = [
            'январь',
            'февраль',
            'март',
            'апрель',
            'май',
            'июнь',
            'июль',
            'август',
            'сентябрь',
            'октябрь',
            'ноябрь',
            'декабрь'
        ];
        return true === $ucfirst?
            mb_strtoupper(mb_substr($month[$num-1], 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($month[$num-1], 1, null,'UTF-8')
          : $month[$num-1];
    }
}