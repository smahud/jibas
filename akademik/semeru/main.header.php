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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Multiple Transactions</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="style/style.css?<?=filemtime('style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="main.header.css?<?=filemtime('main.header.css')?>">
    <link rel="stylesheet" type="text/css" href="script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="main.header.js?r=<?=filemtime('main.header.js')?>"></script>
</head>

<body style="margin: 0;">

<div class="container">
    <div class="div1">
        <img src="images/logo-akademik-01.png" style="height: 30px;">
    </div>

    <div class="div2">
        <div class="menu-bar">
            <div class="menu-item" id="mnHome"><img src='images/home2.png' style='height: 32px; cursor: pointer'></div>
            <div class="menu-item" id="mnReferensi">Referensi</div>
            <div class="menu-item" id="mnPsb">Penerimaan<br>Siswa Baru</div>
            <div class="menu-item" id="mnGuru">Guru &amp;<br>Pelajaran</div>
            <div class="menu-item" style="width: 70px;" id="mnJadwal">Jadwal &amp;<br>Kalender</div>
            <div class="menu-item" id="mnSiswa">Siswa</div>
            <div class="menu-item" id="mnReposisi">Reposisi<br>Siswa</div>
            <div class="menu-item" id="mnPresensi">Presensi</div>
            <div class="menu-item" id="mnPenilaian">Penilaian</div>
            <div class="menu-item" id="mnPengaturan">Pengaturan</div>
        </div>
    </div>

    <div class="div3">
        <table border="0" cellpadding="5" cellspacing="0">
        <tr>
            <td align="right">
                <span style="font-size: 10px; color: #666;">
<?php              if (SI_USER_ID() == "landlord")
                        echo "jibas";
                   else
                        echo SI_USER_ID() ?></span><br>
                <span style="font-size: 12px; color: #333;">
<?php               if (SI_USER_ID() == "landlord")
                        echo "Administrator JIBAS";
                    else
                        echo SI_USER_NAME() ?></span>
            </td>
            <td>
                <img src="images/logout01.png" style="height: 20px; cursor: pointer" title="logout"
                     onclick="confirmLogout()">
            </td>
        </tr>
        </table>
    </div>
</div>

</body>
</html>