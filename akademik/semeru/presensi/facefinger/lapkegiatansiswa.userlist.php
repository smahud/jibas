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
require_once('../../library/qsbuilder.php');
require_once('../../util/peek.php');
require_once('../../include/errorhandler.php');

$departemen = RequestData("departemen", "");
$idKegiatan = RequestData("idkegiatan", 0);
$kegiatan = RequestData("kegiatan", "");
$tahunAwal = RequestData("tahunawal", "");
$bulanAwal = RequestData("bulanawal", "");
$tanggalAwal = RequestData("tanggalawal", "");
$tahunAkhir = RequestData("tahunakhir", "");
$bulanAkhir = RequestData("bulanakhir", "");
$tanggalAkhir = RequestData("tanggalakhir", "");

$tglAwal = "$tahunAwal-$bulanAwal-$tanggalAwal";
$tglAkhir = "$tahunAkhir-$bulanAkhir-$tanggalAkhir";

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Siswa</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js"></script>
    <script language="javascript" src="../../script/qsbuilder.js"></script>
    <script language="javascript" src="lapkegiatansiswa.userlist.js?r=<?=filemtime('lapkegiatansiswa.userlist.js')?>"></script>
</head>
<body style="margin: 2px">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="tahunawal" value="<?=$tahunAwal?>">
<input type="hidden" id="bulanawal" value="<?=$bulanAwal?>">
<input type="hidden" id="tanggalawal" value="<?=$tanggalAwal?>">
<input type="hidden" id="tahunakhir" value="<?=$tahunAkhir?>">
<input type="hidden" id="bulanakhir" value="<?=$bulanAkhir?>">
<input type="hidden" id="tanggalakhir" value="<?=$tanggalAkhir?>">
<input type="hidden" id="tglawal" value="<?= $tglAwal ?>">
<input type="hidden" id="tglakhir" value="<?= $tglAkhir ?>">


<?php
$tab_relPath = "../../library/";
require_once ("../../library/tabs.siswa.php");
?>

</body>
</html>