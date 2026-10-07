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
    <title>Reposisi Siswa</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
</head>
<body>

<table border="0" cellspacing="0" cellpadding="0" align="center" width="70%">
<tr>
    <td align="center" width="100%">
        <span class="pageTitle">REPOSISI SISWA</span>
        <br><br>

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80">
                <img src="../images/reposisi01.png" style="width: 40px" title="Pindah Kelas">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent fs-14">Pindah Kelas</span><br>
                <?=$bullet_blue?><a href="pindah/pindahkelas.php">Pindah Kelas</a><br>
            </td>
        </tr>
        </table>
        <br><br>

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80">
                <img src="../images/reposisi02.png" style="width: 40px" title="Pindah Kelas">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent fs-14">Kenaikan Kelas</span><br>
                <?=$bullet_blue?><a href="kenaikan/naikkelas.php">Naik Kelas</a><br>
                <hr style="border: 1px dashed #666; width: 250px; margin: 5px 0;">
                <?=$bullet_blue?><a href="kenaikan/tinggalkelas.php">Tinggal Kelas</a><br>
            </td>
        </tr>
        </table>
        <br><br>

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80" valign="top">
                <img src="../images/reposisi03.png" style="width: 40px; margin-top: 10px" title="Kelulusan &amp; Alumni">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent fs-14">Kelulusan &amp; Alumni</span><br>
                <?=$bullet_blue?><a href="kelulusan/lulus.php">Kelulusan Siswa</a>&nbsp;<span class='fg-secondary'>(pindah departemen)</span><br>
                <hr style="border: 1px dashed #666; width: 250px; margin: 5px 0;">
                <?=$bullet_blue?><a href="kelulusan/alumni.php">Pendataan Alumni</a><br>
                <?=$bullet_blue?><a href="kelulusan/daftaralumni.php">Daftar Alumni</a><br>
                <?=$bullet_blue?><a href="kelulusan/carialumni.php">Pencarian Alumni</a><br>
            </td>
        </tr>
        </table>
        <br><br>

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80" valign="top">
                <img src="../images/reposisi04.png" style="width: 40px; margin-top: 10px" title="Kelulusan &amp; Alumni">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent fs-14">Mutasi &amp; Siswa</span><br>
                <?=$bullet_blue?><a href="mutasi/jenismutasi.php">Jenis Mutasi</a><br>
                <?=$bullet_blue?><a href="mutasi/mutasi.php">Mutasi Siswa</a><br>
                <hr style="border: 1px dashed #666; width: 250px; margin: 5px 0;">
                <?=$bullet_blue?><a href="mutasi/daftarmutasi.php">Daftar Mutasi</a><br>
                <?=$bullet_blue?><a href="mutasi/statistik.php">Statistik Mutasi</a><br>
            </td>
        </tr>
        </table>
        <br><br>

    </td>
</tr>
</table>

</body>
</html>