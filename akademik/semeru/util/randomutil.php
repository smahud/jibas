<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 36.0 (Oct 07, 2026)
 * @notes:
 *
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php
class RandomUtil
{
    public static function RandStr($length)
    {
        $dict = "abcdefghijklmnopqrstuvwxyz0123456789";
        return RandomUtil::Generate($dict, $length);
    }

    public static function RandInt($length)
    {
        if ($length <= 0)
            return "";

        $result = RandomUtil::Generate("123456789", 1);
        if ($length > 1)
        {
            $result .= RandomUtil::Generate("0123456789", $length - 1);
        }

        return $result;
    }

    private static function Generate($dict, $length)
    {
        $dictLen = strlen($dict);

        $result = "";
        for($i = 0; $i < $length; $i++)
        {
            $result .= $dict[rand(0, $dictLen - 1)];
        }

        return $result;
    }
}
?>