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
require_once('kalendersusun.dialog.func.php');

$replid = RequestData("replid", 0);
$title = ($replid == 0) ? "Tambah Kegiatan" : "Ubah Kegiatan";

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idKalender = RequestData("idkalender", 0);
$kalender = RequestData("kalender", "");
$kode = "";
$kegiatan = "";
$tanggalAwal = "";
$tanggalAwalValue = "";
$tanggalAkhir = "";
$tanggalAkhirValue = "";
$keterangan = "";

if ($replid > 0) 
{
    $db = new Db();
    $db->TryOpenExit();
    
    LoadKegiatanKalender($db);
}
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
    <style>
        .ui-datepicker {
            z-index: 9999 !important;
        }
    </style>
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/stringutil.js?<?=filemtime('../script/stringutil.js')?>"></script>
    <script language="javascript" src="../script/dateutil.js?<?=filemtime('../script/dateutil.js')?>"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/tinymce/tinymce.min.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="kalendersusun.dialog.js?<?=filemtime('kalendersusun.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">


<input type="hidden" id="replid" value="<?= $replid ?>">
<input type="hidden" id="idtahunajaran" value="<?= $idTahunAjaran ?>">
<input type="hidden" id="idkalender" value="<?= $idKalender ?>">

<table width="99%">
<tr>
    <td width="30%" align="left">
        <span class="dialogTitle"><?= $title ?></span><br><br>
    </td>        
    <td width="*" align="right">
        &nbsp;
    </td>        
</tr>
</table>

<div style="width: 100%; display: flex;">
    <div style="flex:1; gap: 20px">
        <span class='fg-secondary'>Departemen</span><br>
        <span class='fs-13 fst-bold'><?= $departemen ?></span>
    </div>
    <div style="flex:1; gap: 20px">
        <span class='fg-secondary'>Tahun Ajaran</span><br>
        <span class='fs-13 fst-bold'><?= $tahunAjaran ?></span>
    </div>
    <div style="flex:1; gap: 20px">
        <span class='fg-secondary'>Kalender Akademik</span><br>
        <span class='fs-13 fst-bold'><?= $kalender ?></span>
    </div>
</div>
<br>
<table cellpadding="3" cellspacing="0">
<tr>
    <td style="width:120px">Kode<?= $tag_mandatory ?></td>
    <td>
        <input id="kode" type="text" class="inputbox" style="width: 80px" maxlength="10" value="<?= $kode ?>">
    </td>
</tr>
<tr>
    <td>Kegiatan<?= $tag_mandatory ?></td>
    <td>
        <input id="kegiatan" type="text" class="inputbox" style="width: 300px" maxlength="255" value="<?= $kegiatan ?>">
    </td>
</tr>
<tr>
    <td>Tanggal Awal<?= $tag_mandatory ?></td>
    <td>
        <input id="tanggalawal" type="text" class="inputbox inputbox-readonly" readonly style="width: 150px" maxlength="3" value="<?= $tanggalAwal ?>">
        <input type="hidden" id="tanggalawal_value" value="<?= $tanggalAwalValue ?>">
        <img src="../images/ico/calendar.png" class='cur-hand' title="pilih tanggal" onclick="showPilihTglAwal()">
    </td>
</tr>
<tr>
    <td>Tanggal Akhir<?= $tag_mandatory ?></td>
    <td>
        <input id="tanggalakhir" type="text" class="inputbox inputbox-readonly" readonly style="width: 150px" maxlength="3" value="<?= $tanggalAkhir ?>">
        <input type="hidden" id="tanggalakhir_value" value="<?= $tanggalAkhirValue ?>">
        <img src="../images/ico/calendar.png" class='cur-hand' title="pilih tanggal" onclick="showPilihTglAkhir()">
    </td>
</tr>
<tr>
    <td colspan="2">
        Keterangan<br>
        <textarea id="keterangan" class="inputbox" rows="10" cols="55"><?= $keterangan ?></textarea><br>
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

</body>
</html>