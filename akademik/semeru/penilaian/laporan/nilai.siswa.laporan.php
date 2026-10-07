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
require_once('nilai.siswa.laporan.func.php');

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idSemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");
$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");    
$nis = RequestData("nis", "");
$nama = RequestData("nama", "");

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Riwayat Nilai Siswa</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css?<?=filemtime('../../style/toast.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/tinymce-small-toolbar.css?<?=filemtime('../../style/tinymce-small-toolbar.css')?>">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js"></script>
    <script language="javascript" src="../../script/tinymce/tinymce.min.js"></script>
    <script language="javascript" src="../../script/qsbuilder.js?r=<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="nilai.siswa.laporan.js?r=<?=filemtime('nilai.siswa.laporan.js')?>"></script>
</head>
<style>

</style>
<body style="padding: 5px">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idtahunajaran" value="<?=$idTahunAjaran?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunAjaran?>">
<input type="hidden" id="idsemester" value="<?=$idSemester?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idkelas" value="<?=$idKelas?>">
<input type="hidden" id="kelas" value="<?=$kelas?>">
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="nis" value="<?=$nis?>">
<input type="hidden" id="nama" value="<?=$nama?>">

<?php
echo "<div style='position: relative; width: 99%'>";
echo "<table border='0'>";
echo "<tr>";
echo "<td width='180' valign='top' style='position: relative'>";
echo "<span class='fg-secondary'>Siswa</span><br>";
echo "<span class='fs-14 fs-bold'>" . $nama . "</span><br>";
echo "<span class='fg-secondary'>" . $nis . "</span>";
echo "<span style='position: absolute; right: 5px; top: 0px;'>";
echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='profilSiswa(\"$nis\")'>&nbsp;&nbsp;";
echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='dashboardSiswa(\"$nis\")'>";
echo "</span>";
echo "</td>";
echo "<td width='180' valign='top'>";
echo "<span class='fg-secondary'>Tahun Ajaran</span><br>";
echo "<span class='fs-14 fs-bold'>" . $tahunAjaran . "</span>";
echo "</td>";
echo "<td width='180' valign='top'>";
echo "<span class='fg-secondary'>Semester</span><br>";
echo "<span class='fs-14 fs-bold'>" . $semester . "</span>";
echo "</td>";
echo "<td width='180' valign='top'>";
echo "<span class='fg-secondary'>Kelas</span><br>";
echo "<span class='fs-14 fs-bold'>$tingkat, $kelas</span>";
echo "</td>";
echo "<td width='180' valign='top'>";
echo "<span class='fg-secondary'>Pelajaran</span><br>";
echo "<span class='fs-14 fs-bold'>" . $pelajaran . "</span>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<div style='position: absolute; top: 10px; right: 10px;'>";
echo "<span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>";
echo "<img src='../../images/ico/refresh.png' border='0'>&nbsp;refresh";
echo "</span>";
echo "&nbsp;&nbsp";
echo "<span class='cur-hand fg-secondary' onclick='cetak()'>";
echo "<img src='../../images/ico/print.png' border='0'>&nbsp;cetak";
echo "</span>";
echo "</div>";
echo "</div>";
echo "<br>";



echo "<div id='dvAspek'>";
echo "Aspek Penilaian: ";

$kodeAspek = "";
ShowSelectAspekPenilaian($db);

echo "</div>";
echo "<br>";

echo "<div id='dvContent'>";

ShowRiwayatNilaiSiswa($db);

echo "</div>";
?>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>