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

$op = $_REQUEST["op"];
if ($op == "439278934234")
{
    $db = new Db(); 
    try
    {
        $db->Open();
        $db->BeginTrans();

        $idTagihanData = $_REQUEST["idtagihandata"];
        $noTagihan = $_REQUEST["notagihan"];
        $jTagihan = $_REQUEST["jtagihan"];
        $jDiskon = $_REQUEST["jdiskon"];

        $sql = "UPDATE jbsfina.tagihansiswadata2 
                   SET jtagihan = $jTagihan, jdiskon = $jDiskon, issync = 0
                 WHERE replid = $idTagihanData";
        $db->QueryDb($sql);

        $jumlah = 0;
        $sql = "SELECT SUM(jtagihan - jdiskon) 
                  FROM jbsfina.tagihansiswadata2 
                 WHERE notagihan = '$noTagihan'";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $jumlah = $row[0];

        $sql = "UPDATE jbsfina.tagihansiswainfo2
                   SET jumlah = $jumlah
                 WHERE notagihan = '$noTagihan'";
        $db->QueryDb($sql);

        $db->CommitTrans();

        echo "[1,\"OK\",\"\"]";
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();

        $msg = $ex->getMessage();
        echo "[-1,\"ERROR\",\"$ex\"]";
    }
    finally
    {
        $db->Close();
    }

}
?>