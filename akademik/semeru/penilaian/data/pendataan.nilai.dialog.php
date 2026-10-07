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
require_once('pendataan.nilai.dialog.func.php');

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", "");
$tahunAjaran = RequestData("tahunajaran", "");
$idSemester = RequestData("idsemester", "");
$semester = RequestData("semester", "");
$idKelas = RequestData("idkelas", "");
$kelas = RequestData("kelas", "");
$idTingkat = RequestData("idtingkat", "");
$tingkat = RequestData("tingkat", "");
$idAturanNhb = RequestData("idaturannhb", "");
$idPelajaran = RequestData("idpelajaran", "");
$pelajaran = RequestData("pelajaran", "");
$idJenisUjian = RequestData("idjenisujian", "");
$jenisUjian = RequestData("jenisujian", "");
$nip = RequestData("nip", "");
$nama = RequestData("nama", "");

$tanggal = date("Y-m-d");

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="../../images/jibas.ico" rel="shortcut icon" />
    <title>Input Nilai</title>
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
    <script language="javascript" src="pendataan.nilai.dialog.js?<?=filemtime('pendataan.nilai.dialog.js')?>"></script>
</head>
<body style="margin: 0px;"> 
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idtahunajaran" value="<?=$idTahunAjaran?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunAjaran?>">
<input type="hidden" id="idsemester" value="<?=$idSemester?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="idkelas" value="<?=$idKelas?>">
<input type="hidden" id="kelas" value="<?=$kelas?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idaturannhb" value="<?=$idAturanNhb?>">
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="idjenisujian" value="<?=$idJenisUjian?>">
<input type="hidden" id="jenisujian" value="<?=$jenisUjian?>">
<input type="hidden" id="nip" value="<?=$nip?>">
<input type="hidden" id="nama" value="<?=$nama?>">


<div style="display: flex; width: 100%; height: 98vh; gap: 5px;">
    <div style="flex: 1.5; display: flex; flex-direction: column;">
        <div id='dvInfo' style="flex: 1; overflow-y: scroll; padding: 10px; background-color: #efefef;">

        <span class="dialogTitle">Pendataan Nilai</span>
        <br><br>

        <span class="fg-secondary fs-12">Pengujian</span><br>
        <span class="fs-14 fs-bold" style="margin-left: 5px;"><?= "$jenisUjian, $pelajaran, $nama" ?></span>
        <br><br>

        <span class="fg-secondary fs-12">Kelas</span><br>
        <span class="fs-14 fs-bold" style="margin-left: 5px;"><?= "$departemen, tingkat $tingkat, $kelas" ?></span>
        <br><br>

        <span class="fg-secondary fs-12">Kode Ujian</span><br>
        <input type="text" class="inputbox" id="kode" style="width: 200px;" maxlength="20">
        <br><br>

        <span class="fg-secondary fs-12">RPP</span><br>
<?php   ShowSelectRpp($db) ?>
        <br><br>

        <span class="fg-secondary fs-12">Tanggal</span><?=$tag_mandatory?><br>
        <input id="tanggal" type="text" class="inputbox_readonly" style="width: 150px" readonly value="<?= LongDateFormat($tanggal) ?>"  onclick="showPilihTanggal()">
        <input type="hidden" id="tanggal_value" value="<?= $tanggal ?>">
        <img src="../../images/ico/calendar.png" id="btntanggal" style="cursor:pointer" title="pilih tanggal" onclick="showPilihTanggal()">
        <br><br>

        <span class="fg-secondary fs-12">Materi</span><?=$tag_mandatory?><br>
        <textarea class="inputbox" id="materi" style="width: 250px;" rows="3" maxlength="100"></textarea>
        <br><br>

        <span>
            <input type="button" id="btSimpan" class="dialogButtonPositive" value="Simpan" style="width: 80px" onclick="simpan()">
            <input type="button" id="btTutup" class="dialogButtonNegative" value="Tutup" style="width: 80px" onclick="window.close()">
        </span>

        </div>
    </div>
    <div style="flex: 3; display: flex; flex-direction: column;">
        <div id='dvNilai' style="flex: 1; overflow-y: scroll; padding: 10px;">
        <span class="ablue" style="display: inline-block; height: 20px" id="spBatchInput" onclick="showBatchInput();" >show batch input</span>
        <div id="dvBatchInput" class="fg-secondary fs-11" style="padding: 5px; display: none;">
            Nilai: <input type="text" id="nilaisel" class="inputbox" style="width: 40px" maxlength="5">&nbsp;&nbsp;
            Keterangan: <input type="text" id="keterangansel" class="inputbox" style="width: 80px" maxlength="80">
            <input type="button" class="dialogButtonGray" value="apply selected" style="width: 100px; min-height: 22px" onclick="applySelected()">
            <input type="button" class="dialogButtonGray" value="clear all" style="width: 60px; min-height: 22px" onclick="clearAll()">
            <span class="ablue" onclick="hideBatchInput();" >hide</span>
        </div>            
<?php   ShowTableNilaiSiswa($db) ?>
        </div>
    </div>
</div>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>