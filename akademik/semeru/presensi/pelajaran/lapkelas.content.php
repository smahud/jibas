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
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/msg.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once('../../library/class/jpgraph.php');
require_once('../../library/class/jpgraph_bar.php');
require_once('../../library/class/jpgraph_line.php');
require_once('lapkelas.content.func.php');

$page = 1;
$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", "");
$tahunAjaran = RequestData("tahunajaran", "");
$idTingkat = RequestData("idtingkat", "");
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", "");
$kelas = RequestData("kelas", "");
$idSemester = RequestData("idsemester", "");
$semester = RequestData("semester", "");
$idPelajaran = RequestData("idpelajaran", "");
$pelajaran = RequestData("pelajaran", "");
$tahunAwal = RequestData("tahunawal", "");
$bulanAwal = RequestData("bulanawal", "");
$tanggalAwal = RequestData("tanggalawal", "");
$tahunAkhir = RequestData("tahunakhir", "");
$bulanAkhir = RequestData("bulanakhir", "");
$tanggalAkhir = RequestData("tanggalakhir", "");

$tglAwal = "$tahunAwal-$bulanAwal-$tanggalAwal";
$tglAkhir = "$tahunAkhir-$bulanAkhir-$tanggalAkhir";

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Presensi Harian Kelas</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/dialogbox.js"></script>
    <script language="javascript" src="../../script/util.js?<?=filemtime('../../script/util.js')?>"></script>
    <script language="javascript" src="../../script/toast.js?<?=filemtime('../../script/toast.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="lapkelas.content.js?<?=filemtime('lapkelas.content.js')?>"></script>
</head>
<body style="margin: 5px; padding: 10px;"> 

<br>
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idtahunajaran" value="<?=$idTahunAjaran?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunAjaran?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idkelas" value="<?=$idKelas?>">
<input type="hidden" id="kelas" value="<?=$kelas?>">
<input type="hidden" id="idsemester" value="<?=$idSemester?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="tahunawal" value="<?=$tahunAwal?>">
<input type="hidden" id="bulanawal" value="<?=$bulanAwal?>">
<input type="hidden" id="tanggalawal" value="<?=$tanggalAwal?>">
<input type="hidden" id="tahunakhir" value="<?=$tahunAkhir?>">
<input type="hidden" id="bulanakhir" value="<?=$bulanAkhir?>">
<input type="hidden" id="tanggalakhir" value="<?=$tanggalAkhir?>">
<input type="hidden" id="tglawal" value="<?= $tglAwal ?>">
<input type="hidden" id="tglakhir" value="<?= $tglAkhir ?>">

<div id="divMenuContent" style="position: relative; width: 95%;">
    <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%);">
    <span class='cur-hand fg-secondary' onclick='refresh()'>
        <img src='../../images/ico/refresh.png' border='0' title='refresh' />&nbsp;refresh
    </span>&nbsp;&nbsp;
    <span class='cur-hand fg-secondary' onclick='cetak()'>
        <img src='../../images/ico/print.png' border='0' title='cetak'/>&nbsp;cetak
    </span>
    </div>
</div>
<br>


<?php
    ShowLaporanPresensiPelajaranKelas($db);
?>


<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>