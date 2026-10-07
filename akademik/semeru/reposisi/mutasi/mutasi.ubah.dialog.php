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
require_once('mutasi.ubah.dialog.func.php');

$db = new Db();
$db->TryOpenExit();

$data64 = RequestData("data64","");
$lsInfo = json_decode(base64_decode($data64));

//$data64 = base64_encode(json_encode([$idMutasi, $idJenisMutasi, $tglMutasi, $keterangan, $nis, $nama]));
$idMutasi = $lsInfo[0];
$idJenisMutasi = $lsInfo[1];
$tglMutasi = $lsInfo[2];
$keterangan = $lsInfo[3];
$nis = $lsInfo[4];
$nama = $lsInfo[5];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Ubah Mutasi</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/stringutil.js"></script>
    <script language="javascript" src="../../script/dateutil.js"></script>
    <script language="javascript" src="../../script/vldr.js?<?=filemtime('../../script/vldr.js')?>"></script>
    <script language="javascript" src="../../script/dialogbox.js"></script>
    <script language="javascript" src="../../script/qsbuilder.js?<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="mutasi.ubah.dialog.js?<?=filemtime('mutasi.ubah.dialog.js')?>"></script>
</head>
<body>
<?php



?>
<input type="hidden" id="idmutasi" value="<?= $idMutasi ?>">
<input type="hidden" id="nis" value="<?= $nis ?>">
<input type="hidden" id="nama" value="<?= $nama ?>">

<span class="dialogTitle">Ubah Data Mutasi</span><br><br>

<?php

echo "<table border='0' cellpadding='5' cellspacing='0'>";
echo "<tr>";
echo "<td style='width: 120px'>Siswa</td>";
echo "<td>";
echo "<b>$nama</b><br><span class='fg-secondary'>$nis</span>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Tanggal Mutasi $tag_mandatory</td>";
echo "<td>";
echo "<input id='tglmutasi' type='text' class='inputbox_readonly' style='width: 150px' readonly value='" . LongDateFormat($tglMutasi) . "'  onclick='showPilihTglMutasi()'>";
echo "<input type='hidden' id='tglmutasi_value' value='$tglMutasi'>&nbsp;&nbsp;";
echo "<img src='../../images/ico/calendar.png' id='btntglmutasi' style='cursor:pointer' title='pilih tanggal' onclick='showPilihTglMutasi()'>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Jenis Mutasi $tag_mandatory</td>";
echo "<td>";
ShowSelectJenisMutasi($db);
echo "</td>";
echo "<tr>";
echo "<td>Keterangan</td>";
echo "<td>";
echo "<input type='text' id='keterangan' class='inputbox' style='width: 250px;' maxlength='255' value='$keterangan'>";
echo "</td>";
echo "</tr>";
echo "</table>";
?>

<br>
<div style="margin-top: 10px; width: 98%; text-align: center;">
    <input type='button' id="btSimpan" class='dialogButtonPositive' value='Simpan' style='width: 120px' onclick='simpanMutasi()'>
    <input type='button' id="btBatal" class='dialogButtonNegative' value='Batal' style='width: 120px' onclick='window.close()'>
</div>

<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>
