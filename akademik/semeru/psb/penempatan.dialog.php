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
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('penempatan.dialog.func.php');

$departemen = RequestData("departemen", "");
$idAngkatan = RequestData("idangkatan", 0);
$angkatan = RequestData("angkatan", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");
$replid = RequestData("replid", 0);
$noPendaftaran = RequestData("nopendaftaran", "");
$nama = RequestData("nama", "");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Penempatan Calon Siswa</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="penempatan.dialog.js?<?=filemtime('penempatan.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle">Penempatan Calon Siswa</span><br><br>
<input type="hidden" id="departemen" value="<?= $departemen ?>">
<input type="hidden" id="idangkatan" value="<?= $idAngkatan ?>">
<input type="hidden" id="idtahunajaran" value="<?= $idTahunAjaran ?>">
<input type="hidden" id="idtingkat" value="<?= $idTingkat ?>">
<input type="hidden" id="idkelas" value="<?= $idKelas ?>">
<input type="hidden" id="replid" value="<?= $replid ?>">
<input type="hidden" id="nopendaftaran" value="<?= $noPendaftaran ?>">

<br>
<span class='fs-14 fst-bold ff-sansserif'>Informasi Calon Siswa</span>
<table cellpadding="2" cellspacing="0" style="margin-left: 10px; margin-top: 10px;">
<tr>
    <td style="width: 100px;">Nama:</td>
    <td><input type='text' class='inputbox inputbox-readonly' style="width: 250px;" readonly value='<?= $nama ?>'></td>
</tr>
<tr>
    <td>No Pendaftaran:</td>
    <td><input type='text' class='inputbox inputbox-readonly' style="width: 250px;" readonly value='<?= $noPendaftaran ?>'></td>
</tr>
</table>
<br>
<span class='fs-14 fst-bold ff-sansserif'>Informasi Penempatan</span>
<table cellpadding="2" cellspacing="0" style="margin-left: 10px; margin-top: 10px;">
<tr>
    <td style="width: 100px;">Departemen:</td>
    <td><input type='text' class='inputbox inputbox-readonly' style="width: 250px;" readonly value='<?= $departemen ?>'></td>
</tr>
<tr>
    <td>Angkatan:</td>
    <td><input type='text' class='inputbox inputbox-readonly' style="width: 250px;" readonly value='<?= $angkatan ?>'></td>
</tr>
<tr>
    <td>Tahun Ajaran:</td>
    <td><input type='text' class='inputbox inputbox-readonly' style="width: 250px;" readonly value='<?= $tahunAjaran ?>'></td>
</tr>
<tr>
    <td>Tingkat:</td>
    <td><input type='text' class='inputbox inputbox-readonly' style="width: 250px;" readonly value='<?= $tingkat ?>'></td>
</tr>
<tr>
    <td>Kelas Tujuan:</td>
    <td><input type='text' class='inputbox inputbox-readonly' style="width: 250px;" readonly value='<?= $kelas ?>'></td>
</tr>
</table>
<br>
<span class='fs-14 fst-bold ff-sansserif'>Informasi Kesiswaan</span>
<table cellpadding="2" cellspacing="0" style="margin-left: 10px; margin-top: 10px;">
<tr>
    <td style="width: 100px;">NIS Baru: <?= $tag_mandatory ?></td>
    <td><input type='text' id='nis' class='inputbox' style="width: 150px;" maxlength="20"></td>
</tr>
<tr>
    <td>Keterangan:</td>
    <td><input type='text' id='keterangan' class='inputbox' style="width: 250px;" maxlength="255"></td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type='button' name='btnSimpan' value='Simpan' class='dialogButtonPositive w80 h30' onclick='simpan()'>
        <input type='button' name='btnBatal' value='Tutup' class='dialogButtonNegative w80 h30' onclick='window.close()'>
    </td>
</tr>
</table>

<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>
