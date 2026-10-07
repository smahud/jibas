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
require_once('rekapinput.menu.func.php');

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Rekapitulasi Input Data</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/vldr.js?r=<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?r=<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="rekapinput.menu.js?<?=filemtime('rekapinput.menu.js')?>"></script>
</head>
<body>

<table border="0">
<tr>
    <td width="80">Departemen</td>
    <td width="360">
<?php
        $departemen = "";
        ShowSelectDepartemen($db);
?>
    </td>
    <td rowspan="2" align="center">
        <img src="../images/view.png" title="lihat" class="cur-hand"
             onclick="showRekapInput()">
    </td>
</tr>
<tr>
    <td>Laporan</td>
    <td>
<?php
        ShowSelectJenisLaporan();
        ShowSelectRentangLaporan();
?>        
    </td>
</tr>
<tr>
    <td>Cari</td>
    <td>
        <input type="text" name="keyword" id="keyword" class="inputbox" style="width:250px" placeholder="deskripsi data (opsional)">
    </td>
</tr>
</table>

<div style="position: fixed; right: 0; top: 40%; margin-right: 25px; 
            transform: translateY(-50%);">
    <div style="display: flex; flex-direction: column; align-items: flex-end;">
        <div>
            <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()" style="margin-bottom: 5px;">
            <span class="pageTitle">Rekap Input Data</span>
        </div>
        <div>
            <a class="pageLink" href="referensi.php" target="_parent">Referensi</a>&nbsp;&gt;&nbsp;
            <span class="pageLinkCurrent">Rekap Input Data</span>
        </div>
    </div>            
</div>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>

</body>
</html>