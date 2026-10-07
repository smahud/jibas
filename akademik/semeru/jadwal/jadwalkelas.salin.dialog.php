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
require_once('../library/logger.php');
require_once('../util/peek.php');
require_once('jadwalkelas.salin.dialog.func.php');

$db = new Db();
$db->TryOpenExit();

$departemenRef = RequestData("departemen", "");
$idTahunAjaranRef = RequestData("idtahunajaran", 0);
$tahunAjaranRef = RequestData("tahunajaran", "");
$idTingkatRef = RequestData("idtingkat", 0);
$tingkatRef = RequestData("tingkat", "");
$idKelasRef = RequestData("idkelas", 0);
$kelasRef = RequestData("kelas", "");
$idKategoriRef = RequestData("idkategori", 0);
$kategoriRef = RequestData("kategori", "");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Salin Jadwal Kelas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="../images/jibas.ico" rel="shortcut icon" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css?<?=filemtime('../style/toast.css')?>">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js?<?=filemtime('../script/toast.js')?>">"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="jadwalkelas.salin.dialog.js?<?=filemtime('jadwalkelas.salin.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<input type="hidden" id="departemenref" value="<?= $departemenRef ?>">
<input type="hidden" id="idtahunajaranref" value="<?= $idTahunAjaranRef ?>">
<input type="hidden" id="tahunajaranref" value="<?= $tahunAjaranRef ?>">
<input type="hidden" id="idtingkatref" value="<?= $idTingkatRef ?>">
<input type="hidden" id="tingkatref" value="<?= $tingkatRef ?>">
<input type="hidden" id="idkelasref" value="<?= $idKelasRef ?>">
<input type="hidden" id="kelasref" value="<?= $kelasRef ?>">
<input type="hidden" id="idkategoriref" value="<?= $idKategoriRef ?>">
<input type="hidden" id="kategoriref" value="<?= $kategoriRef ?>">

<span class="dialogTitle">Salin Jadwal Kelas </span><br><br>
<br>

<b>Kelas Tujuan</b><br><br>
<table cellpadding="7" cellspacing="0">
<tr>
    <td style="width:120px">Kelas</td>
    <td>
        <b><?= $tingkatRef.' - '.$kelasRef ?></b>
    </td>
</tr>
<tr>
    <td>Tahun Ajaran</td>
    <td>
        <b><?= $tahunAjaranRef ?></b>
    </td>
</tr>
<tr>
    <td>Kategori Jadwal</td>
    <td>
        <b><?= $kategoriRef ?></b>
    </td>
</tr>
</table>
<br><br>

<b>Salin Jadwal Kelas Dari</b><br><br>

<table cellpadding="7" cellspacing="0">
<tr>
    <td style="width:120px">Departemen<?= $tag_mandatory ?></td>
    <td>
        <span class='fs-12 fst-bold'><?= $departemenRef ?></span>
    </td>
</tr>
<tr>
    <td>Tahun Ajaran<?= $tag_mandatory ?></td>
    <td>
        <span id='spTahunAjaran'>
<?php
        $idTahunAjaran = "";
        ShowSelectTahunAjaran($db);
?>
        </span>
    </td>
</tr>
<tr>
    <td>Kategori Jadwal<?= $tag_mandatory ?></td>
    <td>
        <span id='spKategori'>
<?php
        $kategori = "";
        ShowSelectKategori($db);
?>
        </span>
        
    </td>
</tr>
<tr>
    <td>Tingkat<?= $tag_mandatory ?></td>
    <td>
        <span id='spTingkat'>
<?php
        $idTingkat = "";
        ShowSelectTingkat($db);
?>
        </span>
    </td>
</tr>
<tr>
    <td>Kelas<?= $tag_mandatory ?></td>
    <td>
        <span id='spKelas'>
<?php
        $idKelas = "";
        $idKelasRef = "";
        ShowSelectKelas($db);
?>
        </span>
        <input type="button" class="dialogButtonGray" value="lihat jadwal" onclick="lihatJadwal()">
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpan()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
<tr>
    <td colspan="2" style="padding-top: 10px">
        <span id="spInfo" class="fg-red"></span>
    </td>
</tr>
</table>

<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>