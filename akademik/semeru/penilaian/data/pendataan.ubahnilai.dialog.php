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
$dataNilai64 = RequestData("datanilai64", "");

$data = json_decode(base64_decode($dataNilai64), true);
$idUjian = $data[0];
$idNilaiUjian = $data[1];
$nilaiUjian = $data[2];
$keterangan = $data[3];
$nis = $data[4];
$nama = $data[5];

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ubah Nilai</title>
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
    <script language="javascript" src="pendataan.ubahnilai.dialog.js?<?=filemtime('pendataan.ubahnilai.dialog.js')?>"></script>
</head>
<body style="padding: 10px;"> 
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
<input type="hidden" id="idujian" value="<?=$idUjian?>">
<input type="hidden" id="idnilaiujian" value="<?=$idNilaiUjian?>">
<input type="hidden" id="nis" value="<?=$nis?>">
<input type="hidden" id="nama" value="<?=$nama?>">

<span class="fs-16 fst-bold">Ubah Nilai Ujian</span><br><br>

<table border="0" cellpadding="5">
<tr>
    <td width="120">Siswa</td>
    <td>
        <span class="fs-16 fst-bold"><?= $nama ?></span><br>
        <span class="fg-secondary"><?= $nis ?></span>
    </td>
</tr>
<tr>
    <td>Nilai <?= $tag_mandatory ?> </td>
    <td>
        <input type="text" id="nilai" class="inputbox fs-16" maxlength="5" style="width: 60px;" value="<?= $nilaiUjian ?>">
        <input type="hidden" id="nilaiasli" value="<?= $nilaiUjian ?>">
    </td>
</tr>
<tr>
    <td>Keterangan</td>
    <td>
        <textarea class="inputbox" id="keterangan" rows="2" cols="40"><?= $keterangan ?></textarea>
    </td>
</tr>
<tr>
    <td>Alasan<br>Perubahan Data <?= $tag_mandatory ?></td>
    <td>
        <textarea class="inputbox" id="alasan" rows="2" cols="40"></textarea>
    </td>
</tr>    
<tr>
    <td>&nbsp;</td>
    <td>
        <input type="button" id="btSimpan" class="dialogButtonPositive" value="Simpan" style="width: 80px" onclick="simpan()">
        <input type="button" id="btTutup" class="dialogButtonNegative" value="Tutup" style="width: 80px" onclick="window.close()">
    </td>
</tr>    
</table>



<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>