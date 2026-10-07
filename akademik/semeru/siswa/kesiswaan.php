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
    <title>Referensi</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script>
        
    </script>
</head>
<body>

<table border="0" cellspacing="0" cellpadding="0" align="center" width="70%">
<tr>
    <td align="center" width="100%">
        <span class="pageTitle">KESISWAAN</span>
        <br><br>

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80">
                <img src="../images/settings01.png" style="width: 40px" title="Referensi">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent">Referensi</span><br>
<?php
                $qsb = new QsBuilder();
                $qsb->Add('sourceurl', '../siswa/kesiswaan.php');
                $qsb->Add('sourcefrom', 'Siswa');
?>                
                <?=$bullet_blue?><a href="../referensi/kolomtambahan.php?<?=$qsb->CreateQs()?>">Kolom Data Tambahan Siswa</a><br>
            </td>
        </tr>
        <tr style="height: 30px">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" width="80">
                <img src="../images/siswa01.png" style="width: 40px" title="Pendataan">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent">Pendataan</span><br>
                <?=$bullet_blue?><a href="siswa.php">Pendataan Siswa</a><br>
            </td>
        </tr>
        <tr style="height: 30px">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" width="80">
                <img src="../images/arrange04.png" style="width: 40px" title="Pengelolaan">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent">Pengelolaan</span><br>
                <?=$bullet_blue?><a href="daftarpin.php">PIN Siswa</a><br>
                <?=$bullet_blue?><a href="cari.php">Pencarian Siswa</a><br>
            </td>
        </tr>
        <tr style="height: 30px">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" width="80">
                <img src="../images/statistic01.png" style="width: 40px" title="Statistik">
            </td>
            <td align="left" valign="top" width="400" style="line-height: 18px;">
                <span class="pageLinkCurrent">Pelaporan</span><br>
                <?=$bullet_blue?><a href="statistik.php">Statistik Siswa</a><br>
                <?=$bullet_blue?><a href="ultah.php">Ulang Tahun Siswa</a><br>
            </td>
        </tr>
        </table>

    </td>
</tr>
</table>

</body>
</html>