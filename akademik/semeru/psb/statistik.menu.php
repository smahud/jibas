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
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/logger.php');
require_once('../library/departemen.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('statistik.menu.func.php');

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Statistik Calon Siswa</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/vldr.js?r=<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?r=<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="statistik.menu.js?<?=filemtime('statistik.menu.js')?>"></script>
</head>
<body style="margin: 5px;">

<table border="0">
<tr>
    <td width="80">Departemen</td>
    <td width="260">
<?php
        $departemen = "";
        ShowSelectDepartemen($db);
?>
    </td>
    <td width="80">&nbsp;</td>
    <td width="260">
        &nbsp;
    </td> 
    <td rowspan="3" align="center">
        <img src="../images/view.png" title="lihat" class="cur-hand"
             onclick="showStatistikCalonSiswa()">
    </td>
</tr>
<tr>
    <td>Kelompok</td>
    <td colspan="3">
        <span id='spProses'>
<?php
            $idProses = 0;
            ShowSelectProses($db);
?>
        </span>
        <span id='spKelompok'>
<?php
            ShowSelectKelompok($db);
?>
        </span>
    </td>
</tr>
<tr>
    <td>Statistik</td>
    <td colspan="3">
        <span id='spStatistik'>
<?php
            ShowSelectJenisStatistik();
?>        
        </span>
    </td>
</tr>

</table>

<div style="position: fixed; right: 0; top: 50%; margin-right: 25px; 
            transform: translateY(-80%);">
    <div style="display: flex; flex-direction: column; align-items: flex-end;">
        <div>
            <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()" style="margin-bottom: 5px;">
            <span class="pageTitle">Statistik Calon Siswa</span>
        </div>
        <div>
            <a class="pageLink" href="psb.php" target="_parent">Penerimaan Siswa Baru</a>&nbsp;&gt;&nbsp;
            <span class="pageLinkCurrent">Statistik Calon Siswa</span>
        </div>
    </div>            
</div>

<div id="divDialog"></div>
<div id="toast-container"></div>

</body>
</html>