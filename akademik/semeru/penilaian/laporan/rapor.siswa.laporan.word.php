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
require_once('../../include/sessionchecker.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../library/common.func.php');
require_once('../../include/db.onfunc.php');
require_once('../../include/getheader2.php');
require_once('../../include/errorhandler.php');
require_once('rapor.siswa.laporan.func.php');

$db = new Db();
$db->TryOpenExit();

$departemen = RequestData("departemen", "yayasan");
$nis = RequestData("nis", "");
$nama = RequestData("nama", "");
$tahunAjaran = RequestData("tahunajaran", "");
$semester = RequestData("semester", "");
$idSemester = RequestData("idsemester", "");
$tingkat = RequestData("tingkat", "");
$idTingkat = RequestData("idtingkat", "");
$kelas = RequestData("kelas", "");
$idKelas = RequestData("idkelas", "");
$tglAwal = RequestData("tglawal", "");
$tglAkhir = RequestData("tglakhir", "");
$harian = RequestData("harian", 0);
$pelajaran = RequestData("pelajaran", 0);

$namaWaliKelas = "";
$nipWaliKelas = "";
$sql = "SELECT p.nama as namawalikelas, p.nip as nipwalikelas 
          FROM jbssdm.pegawai p 
         INNER JOIN jbsakad.kelas k ON k.nipwali = p.nip 
         WHERE k.replid = '$idKelas'";
$res = $db->QueryDb($sql);
if ($row = mysqli_fetch_array($res))
{
    $namaWaliKelas = $row['namawalikelas'];
    $nipWaliKelas = $row['nipwalikelas'];
}

$namaKepsek = "";
$nipKepsek = "";
$sql = "SELECT d.nipkepsek as nipkepsek,p.nama as namakepsek 
          FROM jbssdm.pegawai p, jbsakad.departemen d 
         WHERE p.nip = d.nipkepsek 
           AND d.departemen = '$departemen'";
$res = $db->QueryDb($sql);
if ($row = mysqli_fetch_array($res))
{
    $namaKepsek = $row['namakepsek'];
    $nipKepsek = $row['nipkepsek'];
}

header('Content-Type: application/vnd.ms-word'); //IE and Opera  
header('Content-Type: application/w-msword'); // Other browsers  
header('Content-Disposition: attachment; filename=NilaiRaporSiswa.doc');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 11">
<meta name=Originator content="Microsoft Word 11">
<link rel=File-List href="Doc1_files/filelist.xml">
<?php
    require_once("rapor.siswa.word.style.php");
    require_once("rapor.siswa.word.header.php");
?>
</head>

<body lang=EN-US style='tab-interval:36.0pt'>

<div class=Section1>
<?=     getHeader2($db, $departemen) ?>

<table width="100%" border="0">
<tr><td>
<?php
$title = "LAPORAN HASIL BELAJAR";
require("rapor.siswa.word.title.php");
?>
</td></tr>

<tr><td>
<br>
<?php  
require_once("rapor.siswa.laporan.komentar.php"); 
?>
</td></tr>

<tr><td>
<br>
<?php
require_once("rapor.siswa.laporan.nilai.php");
?>
</td></tr>

<tr><td>
<br>
<?php
require_once("rapor.siswa.laporan.nilai.deskripsi.php");
?>
</td></tr>

<tr><td>
<?php
require("rapor.siswa.word.ttd.php");
?>
</td></tr>
</table>

</div> <!-- Section2 //-->

<?php
if ($harian != 0)
{   ?>

<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'></span>
<div class=Section4>
<table width="100%" border="0">

<tr><td>
<?php
$title = "PRESENSI HARIAN";
require("rapor.siswa.word.title.php");
?>
</td></tr>

<tr><td>
<br>
<?php
require_once("rapor.siswa.laporan.presensi.harian.php");
?>
</td></tr>

<tr><td>
<?php
require("rapor.siswa.word.ttd.php");
?>
</td></tr>

</table>
</div>
<?php
}
?>

<?php
if ($pelajaran != 0) 
{   
?>

<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:
"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;
mso-bidi-language:AR-SA'><br clear=all style='page-break-before:always;
mso-break-type:section-break'></span>

<div class=Section3>
<table width="100%" border="0">
<tr><td>
<?php
$title = "PRESENSI PELAJARAN";
require("rapor.siswa.word.title.php");
?>
</td></tr>

<tr><td>
<br>
<?php
require_once("rapor.siswa.laporan.presensi.pelajaran.php");
?>
</td></tr>

</div> <!-- Section3 //-->

<tr><td>
<?php
require("rapor.siswa.word.ttd.php");
?>
</td></tr>

<?php
}
?>

</table>
</div>

</body>
</html>