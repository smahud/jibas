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
class TableInfo
{
    public $Row;
    public $Col;
    public $Data;

}

class ExcelData
{
    private static $data = [];
    private static $maxRow = 0;
    private static $maxCol = 0;

    public static function AddData($row, $col, $data, $rowspan = 1, $colspan = 1)
    {
        self::$data[] = [$row, $col, $data, $rowspan, $colspan];

        if ($row > self::$maxRow) 
            self::$maxRow = $row;

        if ($col > self::$maxCol) 
            self::$maxCol = $col;
    }

    public static function ToTableData64()
    {
        $tableInfo = new TableInfo();
        $tableInfo->Row = self::$maxRow;
        $tableInfo->Col = self::$maxCol;
        $tableInfo->Data = self::$data;

        return base64_encode(json_encode($tableInfo));
    }

    public static function FromTableData64($data64)
    {
        $tableInfo = json_decode(base64_decode($data64));

        self::$maxRow = $tableInfo->Row;
        self::$maxCol = $tableInfo->Col;
        self::$data = $tableInfo->Data;
    }




}
?>
