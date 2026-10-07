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
require_once('../library/common.func.php');
require_once('../library/qsbuilder.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Penilaian</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="penilaian.js?<?=filemtime('penilaian.js')?>"></script>
</head>
<body>

<center>
<span class="pageTitle">PENILAIAN</span>
</center>
<br><br>

<table border="0" cellspacing="0" cellpadding="0" align="center" width="60%">
<tr>
    <td align="left" valign="top" width="50%">

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80" valign="top">
                <img src="../images/penilaian01.png" style="width: 40px; margin-top: 10px" title="Penilaian">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent fs-14">Penilaian</span><br>
                <?=$bullet_blue?><a href="data/pendataan.php">Pendataan Nilai</a><br>
            </td>
        </tr>
        </table>
        <br><br>

    </td>
    <td align="left"  valign="top" width="50%">

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80" valign="top">
                <img src="../images/penilaian04.png" style="width: 40px; margin-top: 10px" title="Ekspor Impor">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent fs-14">Ekspor-Impor Nilai</span><br>
<?php           
                $display = isset($_SESSION["SHOW_EXIM"]) ? "block" : "none";
                echo "<div id='dvMenuExim' style='display: $display;'>";
                echo $bullet_blue."<a href='exim/expnilai.php'>Form Excel Penilaian</a><br>";
                echo "<hr style='border: 1px dashed #aaa; width: 250px; margin: 5px 0;'>";
                echo $bullet_blue."<a href='exim/impnilai.php'>Import Excel Penilaian</a><br>";
                echo "</div>";

                $display = isset($_SESSION["SHOW_EXIM"]) ? "none" : "block";
                echo "<span id='spMenuExim' class='ablue' onclick='toggleMenuExim()' style='display: $display'>tampilkan</span>";                
?>                
            </td>
        </tr>
        </table>
        <br><br>

    </td>
</tr>
<tr>
    <td colspan="2" align="left" valign="top">
        <br>
        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="70" valign="top">
                <img src="../images/penilaian02.png" style="width: 40px; margin-top: 10px" title="Penyusunan Rapor">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent fs-14">Penyusunan Rapor</span><br>
                <?=$bullet_blue?><a href="data/rapor.nilai.php">Penentuan Nilai Rapor</a><br>
                <?=$bullet_blue?><a href="data/rapor.komentar.php">Penentuan Komentar Rapor</a><br>
                <hr style="border: 1px dashed #666; width: 250px; margin: 5px 0;">
                <?=$bullet_blue?><a href="laporan/rapor.siswa.php">Rapor Siswa</a><br>
            </td>
        </tr>
        </table>
        <br><br>

    </td>
</tr>    
<tr>
    <td align="left" valign="top" width="50%">
        <br>
        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80" valign="top">
                <img src="../images/penilaian03.png" style="width: 40px; margin-top: 10px" title="Penyusunan Rapor">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent fs-14">Pelaporan</span><br>
                <?=$bullet_blue?><a href="laporan/rpp.kelas.php">Rerata RPP per Kelas</a><br>
                <?=$bullet_blue?><a href="laporan/rpp.siswa.php">Rerata RPP per Siswa</a><br>
                <?=$bullet_blue?><a href="laporan/nilai.siswa.php">Riwayat Nilai Siswa</a><br>
                <?=$bullet_blue?><a href="laporan/nilairapor.siswa.php">Riwayat Nilai Rapor Siswa</a><br>
                <hr style="border: 1px dashed #aaa; width: 250px; margin: 5px 0;">
                <?=$bullet_blue?><a href="laporan/legger.nilai.php">Legger Nilai</a><br>
                <?=$bullet_blue?><a href="laporan/legger.rapor.php">Legger Nilai Rapor per Pelajaran</a><br>
                <?=$bullet_blue?><a href="laporan/legger.rapor.kelas.php">Legger Nilai Rapor per Kelas</a><br>
            </td>
        </tr>
        </table>
        <br><br>

    </td>
    <td align="left" valign="top" width="50%">
        <br>
        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80" valign="top">
                <img src="../images/penilaian03.png" style="width: 40px; margin-top: 10px" title="Penyusunan Rapor">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent fs-14">Computer Based Exam</span><br>

<?php
                $display = isset($_SESSION["SHOW_CBE"]) ? "block" : "none";
                echo "<div id='dvMenuCbe' style='display: $display;'>";
                echo "$bullet_blue<a href='https://www.jibas.net/content/cbe/cbe.php' target='_blank'>Ujian Berbasis Komputer</a> <span class='fg-secondary fst-italic fs-10'> - website</span><br>";
                echo "<hr style='border: 1px dashed #aaa; width: 250px; margin: 5px 0;'>";
                echo "$bullet_blue<a href='cbe/nilaipel.php'>Nilai per Pelajaran</a><br>";
                echo "$bullet_blue<a href='cbe/nilaisiswa.php'>Nilai per Siswa</a><br>";                
                echo "</div>";

                $display = isset($_SESSION["SHOW_CBE"]) ? "none" : "block";
                echo "<span id='spMenuCbe' class='ablue' onclick='toggleMenuCbe()' style='display: $display'>tampilkan</span>";
?>

            </td>
        </tr>
        </table>
        <br><br>

    </td>
</tr>
        

        

        

    </td>
</tr>
</table>

</body>
</html>