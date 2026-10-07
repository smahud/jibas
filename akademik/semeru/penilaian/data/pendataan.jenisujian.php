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
require_once('../../library/hintinfo.php');
require_once('../../library/departemen.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once('pendataan.jenisujian.func.php');

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idSemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");
$nip = RequestData("nip", "");
$nama = RequestData("nama", "");

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Pilih Jenis Ujian</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js?r=<?=filemtime('../../script/tools.js')?>"></script>
    <script language="javascript" src="../../script/stringutil.js?r=<?=filemtime('../../script/stringutil.js')?>"></script>
    <script language="javascript" src="../../script/dateutil.js?r=<?=filemtime('../../script/dateutil.js')?>"></script>
    <script language="javascript" src="../../script/vldr.js?r=<?=filemtime('../../script/vldr.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?r=<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="pendataan.jenisujian.js?<?=filemtime('pendataan.jenisujian.js')?>"></script>
</head>
<body style="padding: 10px; margin: 0px; background-color: #efefef;">
<input type="hidden" id="departemen" value="<?= $departemen ?>">
<input type="hidden" id="idtahunajaran" value="<?= $idTahunAjaran ?>">
<input type="hidden" id="tahunajaran" value="<?= $tahunAjaran ?>">
<input type="hidden" id="idsemester" value="<?= $idSemester ?>">
<input type="hidden" id="semester" value="<?= $semester ?>">
<input type="hidden" id="idtingkat" value="<?= $idTingkat ?>">
<input type="hidden" id="tingkat" value="<?= $tingkat ?>">
<input type="hidden" id="idkelas" value="<?= $idKelas ?>">
<input type="hidden" id="kelas" value="<?= $kelas ?>">
<input type="hidden" id="nip" value="<?= $nip ?>">
<input type="hidden" id="nama" value="<?= $nama ?>">

<b>Pelajaran:</b><br>
<?php
    $idPelajaran = 0;
    ShowSelectPelajaran($db);
?>
<br><br>

<div id="dvJenisUjian">
<?php
    ShowJenisUjian($db);
?>    
</div>

</body>
</html>