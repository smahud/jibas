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
require_once('lapsiswa.content.func.php');

$page = 1;
$departemen = RequestData("departemen", "");
$nis = RequestData("nis", "");
$nama = RequestData("nama", "");
$tahunawal = RequestData("tahunawal", "");
$bulanawal = RequestData("bulanawal", "");
$tanggalawal = RequestData("tanggalawal", "");
$tahunakhir = RequestData("tahunakhir", "");
$bulanakhir = RequestData("bulanakhir", "");
$tanggalakhir = RequestData("tanggalakhir", "");

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Presensi Harian Siswa</title>
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
    <script language="javascript" src="lapsiswa.content.js?<?=filemtime('lapsiswa.content.js')?>"></script>
</head>
<body style="margin: 5px; padding: 10px;"> 

<br>
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="nis" value="<?=$nis?>">
<input type="hidden" id="nama" value="<?=$nama?>">
<input type="hidden" id="tahunawal" value="<?=$tahunawal?>">
<input type="hidden" id="bulanawal" value="<?=$bulanawal?>">
<input type="hidden" id="tanggalawal" value="<?=$tanggalawal?>">
<input type="hidden" id="tahunakhir" value="<?=$tahunakhir?>">
<input type="hidden" id="bulanakhir" value="<?=$bulanakhir?>">
<input type="hidden" id="tanggalakhir" value="<?=$tanggalakhir?>">

<div id="divMenuContent" style="position: relative; width: 100%;">
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
    ShowLaporanPresensiHarianSiswa($db);
?>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>