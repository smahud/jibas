<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
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
require_once('../library/common.func.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../include/errorhandler.php');
require_once('../library/logger.php');
require_once('../library/smsmanager2.func.php');
require_once('daftartagihan.func.php');
require_once('pgservice.config.php');

$op = $_REQUEST["op"];
if ($op == "8374687346839274")
{
    DaftarTagihanInfo();
}
else if ($op == "930248032948023948")
{
    DaftarTagihanData();
}
else if ($op == "984723846234")
{
    HapusTagihanData();
}
else if ($op == "49384729847682934")
{
    HapusTagihanSiswa();
}
else if ($op == "23894762874632")
{
    $departemen = $_REQUEST["departemen"];
    $bulan = $_REQUEST["bulan"];
    $tahun = $_REQUEST["tahun"];

    $db = new Db();
    try
    {
        $db->Open();

        ShowTagihanSet($db);
    }
    catch (Exception $ex)
    {
        echo "ERROR: " . $ex->getMessage();
    }
}
else if ($op == "7856875634875")
{
    ShowPrepareBatchNotif();
}
else if ($op == "8273468874356743723468324")
{
    SendBatchNotif();
}
else if ($op == "8374628746238746728346")
{
    SendNotif();
}
else if ($op == "36547346837463")
{
    HapusTagihanSet();
}
?>