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
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../library/common.func.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/departemen.php');
require_once('../../library/msg.php');
require_once('../../library/colorfactory.php');
require_once('../../library/qsbuilder.php');
require_once('../../util/peek.php');
require_once('../../include/errorhandler.php');
require_once("nilaipel.laporan.func.php");

$op = RequestData("op", "");
if ($op == "hasilujian")
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $idPelajaran = RequestData("idpelajaran", 0);
        $pelajaran = RequestData("pelajaran", "");
        $jumlah = RequestData("jumlah", 0);
        $jenis = RequestData("jenis", 0);
        $nSiswa = RequestData("nsiswa", 0);
        $idRemedUjian = RequestData("idremedujian", 0);
        $idUjian = RequestData("idujian", 0);
        $idUjianInUjianSerta = RequestData("idujianinujianserta", 0);
        $kkm = RequestData("kkm", 0);
        $skalaNilai = RequestData("skalanilai", 0);
        $page = RequestData("page", 1);

        ShowTableHasilUjian($db);
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
    finally
    {
        $db->Close();
    }
}
?>