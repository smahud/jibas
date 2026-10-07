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
require_once('../../library/logger.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once('../../library/class/jpgraph.php');
require_once('../../library/class/jpgraph_bar.php');
require_once('../../library/class/jpgraph_line.php');
require_once('pendataan.ubahinfo.dialog.func.php');

$idUjian = RequestData("idujian", 0);
$idKelas = RequestData("idkelas",0);
$idTingkat = RequestData("idtingkat",0);
$idSemester = RequestData("idsemester",0);
$idPelajaran = RequestData("idpelajaran",0);

$db = new Db();
$db->TryOpenExit();

$sql = "SELECT kode, tanggal, IFNULL(idrpp, '') AS fidrpp, deskripsi
          FROM jbsakad.ujian
         WHERE replid = $idUjian";
$res = $db->QueryDb($sql);
if (mysqli_num_rows($res) == 0) 
{
    echo "Data ujian tidak ditemukan";
    exit;
}         

$row = mysqli_fetch_array($res);
$kode = $row["kode"];
$tanggal = $row["tanggal"];
$idRpp = $row["fidrpp"];
$deskripsi = $row["deskripsi"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ubah Informasi Ujian</title>
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
    <script language="javascript" src="../../script/dialogbox.js"></script>
    <script language="javascript" src="../../script/util.js?<?=filemtime('../../script/util.js')?>"></script>
    <script language="javascript" src="../../script/toast.js?<?=filemtime('../../script/toast.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="pendataan.ubahinfo.dialog.js?<?=filemtime('pendataan.ubahinfo.dialog.js')?>"></script>
</head>
<body style="padding: 10px;"> 
<input type="hidden" id="idujian" value="<?=$idUjian?>">

<span class="fs-16 fst-bold">Ubah Informasi Ujian</span><br><br>

<table border="0" cellpadding="5">

 <span class="fg-secondary fs-12">Kode Ujian</span><br>
<input type="text" class="inputbox" id="kode" style="width: 200px;" maxlength="20" value="<?=$kode?>">
<br><br>

<span class="fg-secondary fs-12">RPP</span><br>
<?php   
    ShowSelectRpp($db) 
?>
<br><br>

<span class="fg-secondary fs-12">Tanggal</span><?=$tag_mandatory?><br>
<input id="tanggal" type="text" class="inputbox_readonly" style="width: 150px" readonly value="<?= LongDateFormat($tanggal) ?>"  onclick="showPilihTanggal()">
<input type="hidden" id="tanggal_value" value="<?= $tanggal ?>">
<img src="../../images/ico/calendar.png" id="btntanggal" style="cursor:pointer" title="pilih tanggal" onclick="showPilihTanggal()">
<br><br>

<span class="fg-secondary fs-12">Materi</span><?=$tag_mandatory?><br>
<textarea class="inputbox" id="materi" style="width: 250px;" rows="3" maxlength="100"><?= $deskripsi?></textarea>
<br><br>

<span>
    <input type="button" id="btSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpan()">
    <input type="button" id="btTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
</span>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>