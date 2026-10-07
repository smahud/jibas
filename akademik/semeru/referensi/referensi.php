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
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
</head>
<body>

<table border="0" cellspacing="0" cellpadding="0" align="center" width="70%">
<tr>
    <td align="center" width="100%">
        <span class="pageTitle">REFERENSI</span>
        <br><br>

        <table border="0" cellpadding="5" cellspacing="0" align="center">
        <tr>
            <td colspan="7" align="center">
                <span class="pageLinkCurrent">PENGATURAN</span><br>
            </td>
        </tr>
        <tr>
            <td align="center" width="120">
                <a href="pegawai.php">
                    <img src="../images/pegawai01.png" style="width: 40px" border="0" title="Pegawai"><br>
                    Pegawai
                </a>
            </td>
            <td align="center" width="10">&nbsp;</td>
            <td align="center" width="120">
                <a href="departemen.php">
                    <img src="../images/cate06.png" style="width: 40px" border="0" title="Departemen"><br>
                    Departemen
                </a>
            </td>
            <td align="center" width="10">&nbsp;</td>
            <td align="center" width="120">
                 <a href="angkatan.php">
                    <img src="../images/calendar02.png" style="width: 40px" border="0" title="Angkatan"><br>
                    Angkatan
                </a>
            </td>
            <td align="center" width="10">&nbsp;</td>
            <td align="center" width="120">
                <a href="tingkat.php">
                    <img src="../images/level01.png" style="width: 40px" border="0" title="Tingkat"><br>
                    Tingkat
                </a>
            </td>
        </tr>
        </table>
        <br><br>

        <table border="0" cellpadding="5" cellspacing="0" align="center">
        <tr>
            <td align="center" width="120">
                <a href="tahunajaran.php">
                    <img src="../images/tahun01.png" style="width: 40px" border="0" title="Tahun Ajaran"><br>
                    Tahun Ajaran
                </a>
            </td>
            <td align="center" width="10">&nbsp;</td>
            <td align="center" width="120">
                <a href="semester.php">
                    <img src="../images/cate03.png" style="width: 40px" border="0" title="Semester"><br>
                    Semester
                </a>
            </td>
            <td align="center" width="10">&nbsp;</td>
            <td align="center" width="120">
                 <a href="kelas.php">
                    <img src="../images/kelas01.png" style="width: 40px" border="0" title="Kelas"><br>
                    Kelas
                </a>
            </td>
        </tr>
        </table>
        <br><br><br>

         <table border="0" cellpadding="5" cellspacing="0" align="center">
        <tr>
            <td colspan="5" align="center">
                <span class="pageLinkCurrent">INFORMASI</span><br>
            </td>
        </tr>
        <tr>
            <td align="center" width="120">
                <a href="identitas.php">
                    <img src="../images/header01.png" style="width: 40px" border="0" title="Identitas Sekolah"><br>
                    Kop Identitas Sekolah
                </a>
            </td>
            <td align="center" width="10">&nbsp;</td>
            <td align="center" width="120">
                <a href="auditnilai.php">
                    <img src="../images/audit01.png" style="width: 40px" border="0" title="Audit Nilai"><br>
                    Audit Perubahan Nilai
                </a>
            </td>
            <td align="center" width="10">&nbsp;</td>
            <td align="center" width="120">
                <a href="rekapinput.php">
                    <img src="../images/lookup01.png" style="width: 40px" border="0" title="Rekapitulasi Input Data"><br>
                    Rekapitulasi Input Data
                </a>
            </td>
        </tr>

    </td>
</tr>
</table>

</body>
</html>