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
    <title>Presensi</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="presensi.js?<?=filemtime('presensi.js')?>"></script>
</head>
<body>

<table border="0" cellspacing="0" cellpadding="0" align="center" width="70%">
<tr>
    <td align="center" width="100%">
        <span class="pageTitle">PRESENSI</span>
        <br><br>

        <table border="0" cellpadding="2" cellspacing="0">
        <tr>
            <td align="center" width="80" valign="top">
                <img src="../images/presensi01.png" style="width: 40px; margin-top: 20px;" title="Presensi Harian">
            </td>
            <td align="left" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent">Presensi Harian</span><br>
                <?=$bullet_blue?><a href="harian/inputharian.php">Pendataan Presensi Harian</a><br>
                <hr style="border: 1px dashed #666; width: 250px; margin: 5px 0;">
                <?=$bullet_blue?><a href="harian/lapsiswa.php">Laporan Presensi Harian Siswa</a><br>
                <?=$bullet_blue?><a href="harian/lapkelas.php">Laporan Presensi Harian per Kelas</a><br>
            </td>
        </tr>
        </table>
        <br><br>

        <table border="0" cellpadding="2" cellspacing="0">
        <tr>
            <td align="center" width="80" valign="top">
                <img src="../images/presensi02.png" style="width: 40px; margin-top: 20px;" title="Referensi">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent fs-14">Presensi Pelajaran</span><br>
                <div style="margin-top: 10px;">
                <?=$bullet_blue?><a href="pelajaran/inputpp.php">Pendataan Presensi Pelajaran</a><br>
                <hr style="border: 1px dashed #666; width: 250px; margin: 5px 0;">
                <?=$bullet_blue?><a href="pelajaran/lapsiswa.php">Laporan Presensi Pelajaran Siswa</a><br>
                <?=$bullet_blue?><a href="pelajaran/lapkelas.php">Laporan Presensi Pelajaran per Kelas</a><br>
                <?=$bullet_blue?><a href="pelajaran/lapguru.php">Laporan Refleksi Mengajar Guru</a><br>
                </div>
            </td>
        </tr>
        </table>
        <br><br>
        
        <table border="0" cellpadding="3" cellspacing="0">
        <tr>
            <td align="center" width="80" valign="top">
                <img src="../images/presensi03.png" style="width: 40px; margin-top: 20px;" title="Referensi">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent">Presensi Wajah &amp; Fingerprint</span><br>
<?php                
                $display = isset($_SESSION["SHOW_SPTFGR"]) ? "block" : "none";
                echo "<div id='dvMenuSptfgr' style='display: $display;'>";
                echo "$bullet_blue<a href='https://www.jibas.net/content/sptfgr/sptfgr.php' target='_blank'>Presensi Fingerprint</a> <span class='fg-secondary fst-italic fs-10'> - website</span><br>";
                echo "$bullet_blue<a href='https://jibas.net/content/sptface/sptface.php' target='_blank'>Presensi Wajah</a> <span class='fg-secondary fst-italic fs-10'> - website</span><br>";
                echo "<hr style='border: 1px dashed #666; width: 250px; margin: 5px 0;'>";
                echo "$bullet_blue<a href='facefinger/lapharian.php'>Laporan Presensi Harian</a><br>";
                echo "$bullet_blue<a href='facefinger/laphariansiswa.php'>Laporan Presensi Harian Siswa</a><br>";
                echo "$bullet_blue<a href='facefinger/lapharianpegawai.php'>Laporan Presensi Harian Pegawai</a><br>";
                echo "<hr style='border: 1px dashed #666; width: 250px; margin: 5px 0;'>";
                echo "$bullet_blue<a href='facefinger/lapkegiatan.php'>Laporan Presensi Kegiatan</a><br>";
                echo "$bullet_blue<a href='facefinger/lapkegiatansiswa.php'>Laporan Presensi Kegiatan Siswa</a><br>";
                echo "$bullet_blue<a href='facefinger/lapkegiatanpegawai.php'>Laporan Presensi Kegiatan Pegawai</a><br>";
                echo "</div>";

                $display = isset($_SESSION["SHOW_SPTFGR"]) ? "none" : "block";
                echo "<span id='spMenuSptfgr' class='ablue' onclick='toggleMenuSptfgr()' style='display: $display'>tampilkan</span>";
?>
            </td>
        </tr>
        </table>

    </td>
</tr>
</table>

</body>
</html>