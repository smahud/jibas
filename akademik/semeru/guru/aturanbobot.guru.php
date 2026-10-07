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
require_once('../library/departemen.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');

$db = new Db;
$db->TryOpenExit(true);

$departemen = RequestData("departemen", "");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Aturan Bobot Nilai Rapor</title>
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/toast.js?<?=filemtime('../script/toast.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="aturanbobot.guru.js?<?=filemtime('aturanbobot.guru.js')?>"></script>
</head>
<body style="background-color: #f5f5f5;">

<table border="0" width="100%">
<tr>
    <td align="left" valign="top">
        <span class="fs-16 fg-secondary">Aturan Perhitungan Nilai Rapor</span>
        <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()">
        <br><br>
        
        <b>Guru:</b>
        <span class='bg-dark fg-white cur-hand' style='padding: 3px; border-radius: 6px;' onclick='cariPegawai()'>&nbsp;pilih&nbsp;</span><br><br>
        <span class='cur-hand' onclick="showInfoPegawai()">
            <span class='fs-18 fg-black' style='margin-left: 6px;' id='spNama'></span><br>
            <span class='fs-14 fg-secondary' style='margin-left: 6px;' id='spNip'></span><br>
        </span>
        <input type="hidden" id="nip" value="">
        <input type="hidden" id="nama" value="">
</tr>
<tr>
    <td align="left" valign="top">
        <div id="dvPelajaran" style="visibility: hidden">
            <br>
            <b>Pelajaran:</b><br><br>
            <div id="dvTablePelajaran" style="margin-left: 6px;"></div>
        </div>  
    </td>
</tr>
</table>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>

</body>
</html>