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
require_once('../../library/departemen.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once('inputharian.menu.func.php');

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Pendataan Presensi Harian</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/vldr.js?r=<?=filemtime('../../script/vldr.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?r=<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="inputharian.menu.js?<?=filemtime('inputharian.menu.js')?>"></script>
</head>
<body style="margin-top: 0px;">

<table border="0" cellpadding="3" cellspacing="0">
<tr>
    <td width="80">Departemen</td>
    <td width="300">
<?php
        $departemen = "";
        ShowSelectDepartemen($db);
?>
    </td>
    <td width="80">Tahun Ajaran</td>
    <td width="300">
        <span id='spTahunAjaran'>
<?php
        $idTahunAjaran = "0";
        ShowSelectTahunAjaran($db);
?>
        </span>
        <span id='spSemester'>
<?php
        $idSemester = 0;
        ShowSelectSemester($db);
?>
        </span>
    </td> 
    <td rowspan="3" align="center">
        <img src="../../images/view.png" title="lihat" class="cur-hand"
             onclick="showInputPresensiHarian()">
    </td>
</tr>
<tr>
    <td>Kelas</td>
    <td>
        <span id='spTingkat'>
<?php
        $idTingkat = "";
        ShowSelectTingkat($db);
?>
        </span>
        <span id='spKelas'>
<?php
        $idKelas = "";
        ShowSelectKelas($db);
?>
        </span>
    </td>
    <td>Bulan</td>
    <td>
        <span id='spBulanTahun'>
<?php
        ShowSelectBulanTahun($db);
?>        
        </span>
    </td>
</tr>
</table>

<div style="position: fixed; right: 0; top: 30%; margin-right: 25px; 
            transform: translateY(-50%);">
    <div style="display: flex; flex-direction: column; align-items: flex-end;">
        <div>
            <img class="help-icon-1" src="../../images/help32.png" title="bantuan" onclick="showHelp()" style="margin-bottom: 5px;">
            <span class="pageTitle">Pendataan Presensi Harian</span>
        </div>
        <div>
            <a class="pageLink" href="../presensi.php" target="_parent">Presensi</a>&nbsp;&gt;&nbsp;
            <span class="pageLinkCurrent">Pendataan Presensi Harian</span>
        </div>
    </div>            
</div>

<div id="divDialog"></div>
<div id="toast-container"></div>

</body>
</html>