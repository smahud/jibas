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
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/hintinfo.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('../library/departemen.php');
require_once('tingkat.func.php');
require_once('auditnilai.func.php');

$op = RequestData("op", "");
if ($op == "count")
{
    $db = new Db;
    try
    {
        $db->Open();

        $bulan = RequestData("bulan", date('n'));
        $tahun = RequestData("tahun", date('Y'));
        $keyword = RequestData("keyword", "");

        $count = CountDataAudit($db);

        echo json_encode([1, $count]);
    }
    catch (Exception $ex)
    {
        echo json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
else if ($op == "daftar")
{
    $db = new Db;
    try
    {
        $db->Open();

        $bulan = RequestData("bulan", date('n'));
        $tahun = RequestData("tahun", date('Y'));
        $keyword = RequestData("keyword", "");
        $totalData = RequestData("totaldata", 0);
        $page = RequestData("page", 1);

        ShowTableAudit($db);
    }
    catch (Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}
else if ($op == "pagecontrol")
{
    $totalData = RequestData("totaldata", 0);
    $page = RequestData("page", 1);
    
    ShowPageControl();
}