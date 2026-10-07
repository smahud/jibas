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
    <title>Penerimaan Siswa Baru</title>
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
        <span class="pageTitle">PENERIMAAN SISWA BARU</span>
        <br><br>

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80" valign="top">
                <img src="../images/settings01.png" style="width: 40px; margin-top: 20px" title="Referensi">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent">Referensi</span><br>
                <?=$bullet_blue?><a href="proses.php">Proses Penerimaan Calon Siswa</a><br>
                <?=$bullet_blue?><a href="kelompok.php">Kelompok Calon Siswa</a><br>
                <hr style="border: 1px dashed #aaa; width: 250px; margin: 5px 0;">
<?php
                $qsb = new QsBuilder();
                $qsb->Add('sourceurl', '../psb/psb.php');
                $qsb->Add('sourcefrom', 'Penerimaan Siswa Baru');
?>                
                <?=$bullet_blue?><a href="../referensi/kolomtambahan.php?<?=$qsb->CreateQs()?>">Kolom Data Tambahan Calon Siswa</a><br>
                <?=$bullet_blue?><a href="settingpsb.php">Informasi Sumbangan dan Nilai</a><br>
            </td>
        </tr>
        <tr style="height: 30px">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" width="80">
                <img src="../images/regis01.png" style="width: 40px" title="Pendataan">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent">Pendataan</span><br>
                <?=$bullet_blue?><a href="pendataan.php">Pendataan Calon Siswa</a><br>
            </td>
        </tr>
        <tr style="height: 30px">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" width="80">
                <img src="../images/arrange01.png" style="width: 40px" title="Pengelolaan">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent">Pengelolaan</span><br>
                <?=$bullet_blue?><a href="daftarpin.php">PIN Calon Siswa</a><br>
                <?=$bullet_blue?><a href="cari.php">Pencarian Calon Siswa</a><br>
                <?=$bullet_blue?><a href="pindahkelompok.php">Pindah Kelompok Calon Siswa</a><br>
                <?=$bullet_blue?><a href="penempatan.php">Penempatan Calon Siswa</a><br>
            </td>
        </tr>
        <tr style="height: 30px">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" width="80">
                <img src="../images/report04a.png" style="width: 40px" title="Laporan">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent">Laporan</span><br>
                <?=$bullet_blue?><a href="statistik.php">Statistik Calon Siswa</a><br>
            </td>
        </tr>
        </table>

    </td>
</tr>
</table>

</body>
</html>