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

$departemen = RequestData("departemen", "");
$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");
$jenis = RequestData("jenis", "");
$data64 = RequestData("data", "");

$data = json_decode(base64_decode($data64), true);
$idUjian = $data[0];
$idRemedUjian = $data[1];
$ujian = $data[2];
$tanggal = $data[3];
$skalaNilai = $data[4];
$kkm = $data[5];
$idPengujian = $data[6];
$pengujian = $data[7];
$status = $data[8];
$nSiswa = $data[9];

$idUjianInUjianSerta = $idRemedUjian != 0 ? $idRemedUjian : $idUjian;

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Pelajaran Siswa</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js"></script>
    <script language="javascript" src="../../script/qsbuilder.js"></script>
    <script language="javascript" src="nilaipel.laporan.js?r=<?=filemtime('nilaipel.laporan.js')?>"></script>
</head>
<body style="padding: 5px;">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="jumlah" value="<?=$jumlah?>">
<input type="hidden" id="jenis" value="<?=$jenis?>">
<input type="hidden" id="nsiswa" value="<?=$nSiswa?>">
<input type="hidden" id="idremedujian" value="<?=$idRemedUjian?>">
<input type="hidden" id="idujian" value="<?=$idUjian?>">
<input type="hidden" id="ujian" value="<?=$ujian?>">
<input type="hidden" id="tanggal" value="<?=$tanggal?>">
<input type="hidden" id="pengujian" value="<?=$pengujian?>">
<input type="hidden" id="kkm" value="<?=$kkm?>">
<input type="hidden" id="skalanilai" value="<?=$skalaNilai?>">

<?php
echo "<div style='position: relative; width: 60%'>";
echo "<table border='0'>";
echo "<tr>";
echo "<td width='270' valign='top'>";
echo "<span class='fg-secondary'>Ujian</span><br>";
echo "<span class='fs-16 fs-bold'>" . $ujian . "</span><br>";
echo "<span class='fg-secondary'>" . $tanggal . "</span>";
echo "</td>";
echo "<td width='180' valign='top'>";
echo "<span class='fg-secondary'>Nilai KKM</span><br>";
echo "<span class='fs-16 fs-bold'>" . $kkm . "</span>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<div style='position: absolute; top: 10px; right: 10px;'>";
echo "<span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>";
echo "<img src='../../images/ico/refresh.png' border='0'>&nbsp;refresh";
echo "</span>&nbsp;&nbsp;";
echo "<span class='cur-hand fg-secondary' onclick='cetak()' title='cetak'>";
echo "<img src='../../images/ico/print.png' border='0'>&nbsp;cetak";
echo "</span>";
echo "</div>";
echo "</div>";
echo "<br>";

echo "<div id='dvTableContent' style='width: 100%; display: inline-block;'>";
$page = 1;
ShowTableHasilUjian($db);
echo "</div>";
echo "<br><br>";

echo "<div id='dvPageControl'>";
ShowPageControl($db);
echo "</div>";
?>

</body>
</html>