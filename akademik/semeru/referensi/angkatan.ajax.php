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
require_once('../library/logger.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('angkatan.func.php');

$op = $_REQUEST['op'];
if ($op == 'count')
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData('departemen', '');
        $nData = CountData($db);
        
        echo json_encode([1, $nData]);
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

        $departemen = RequestData("departemen", "");
        $page = RequestData("page", 1);
        $nData = RequestData("ndata", 0);

        ShowTableAngkatan($db);
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
    $nData = RequestData('ndata', 0);

    ShowPageControl();
}
else if ($op == "setaktif")
{
    echo ChangeAktif();
}
else if ($op == "hapus")
{
    echo HapusAngkatan();
}
