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
require_once('pendataan.dialog.func.php');

$replid = RequestData("replid", 0);
$title = ($replid == 0) ? "Tambah Calon Siswa" : "Ubah Calon Siswa";

$departemen = RequestData("departemen", "");
$idProses = RequestData("idproses", 0);
$proses = RequestData("proses", "");
$kelompok = RequestData("kelompok", "");
$idKelompok = RequestData("idkelompok", 0);
$noPendaftaran = "";
$nama = "";
$panggilan = "";
$tmpLahir = "";
$tglLahir = "";
$blnLahir = "";
$thnLahir = "";
$keterangan = "";
$kelamin = "l";
$panggilan = "";

if ($replid > 0)
    LoadCalonSiswa();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?= $title ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="../images/jibas.ico" rel="shortcut icon" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
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
    <script language="javascript" src="pendataan.dialog.js?<?=filemtime('pendataan.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle"><?= $title ?></span><br><br>
<input type="hidden" id="replid" value="<?= $replid ?>">
<input type="hidden" id="idproses" value="<?= $idProses ?>">
<input type="hidden" id="proses" value="<?= $proses ?>">
<input type="hidden" id="idkelompok" value="<?= $idKelompok ?>">
<input type="hidden" id="departemen" value="<?= $departemen ?>">

<div style='display: flex; width: 98%;'>
    <div style='flex: 1; gap: 20px'>
        <span class='fg-secondary'>Departemen</span><br>
        <span class='fs-16'><?= $departemen ?></span>
    </div>
    <div style='flex: 2; gap: 20px'>
        <span class='fg-secondary'>Proses</span><br>
        <span class='fs-16'><?= $proses ?></span>
    </div>
    <div style='flex: 1; gap: 20px'>
        <span class='fg-secondary'>Kelompok</span><br>
        <span class='fs-16'><?= $kelompok ?></span>
    </div>
</div>

<br>
<table cellpadding="5" cellspacing="0" style="margin-left: -5px;">
<tr id='row_nopendaftaran'>
    <td>No Pendaftaran</td>
    <td>
        <input type='text' class='inputbox inputbox-readonly' readonly="readonly" id='nopendaftaran' maxlength="20" style="width: 150px" value="<?= $noPendaftaran ?>">
    </td>
</tr>
<tr>
    <td style='width: 120px'>Nama<?=$tag_mandatory?></td>
    <td>
        <input type='text' class='inputbox' id='nama' maxlength="100" style="width: 250px" value="<?= $nama ?>">
    </td>
</tr>
<tr>
    <td>Jenis Kelamin<?=$tag_mandatory?></td>
    <td>
        <input type="radio" name="gender" id="laki" value="l" <?=($kelamin == "l") ? "checked" : ""?>> Laki-laki&nbsp;&nbsp;&nbsp;
        <input type="radio" name="gender" id="perempuan" value="p" <?=($kelamin == "p") ? "checked" : ""?>> Perempuan
    </td>
</tr>
<tr>
    <td>Panggilan</td>
    <td>
        <input type='text' class='inputbox' id='panggilan' maxlength="30" style="width: 300px" value="<?= $panggilan ?>">
    </td>
</tr>
<tr>
    <td>Tempat Lahir</td>
    <td>
        <input type='text' class='inputbox' id='tmplahir' maxlength="50" style="width: 150px" value="<?= $tmpLahir ?>">
    </td>
</tr>
<tr>
    <td>Tanggal Lahir</td>
    <td>
        <input type='text' class='inputbox' id='tgllahir' maxlength="2" style="width: 30px" placeholder="tgl" value="<?= $tglLahir ?>">
        <input type='text' class='inputbox' id='blnlahir' maxlength="2" style="width: 30px" placeholder="bln" value="<?= $blnLahir ?>">
        <input type='text' class='inputbox' id='thnlahir' maxlength="4" style="width: 50px" placeholder="thn" value="<?= $thnLahir ?>">
    </td>
</tr>
<tr>
    <td>Keterangan</td>
    <td>
        <textarea rows="3" cols="40" class="inputbox" id="keterangan"><?= $keterangan ?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpan()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
</table>

<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>
