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
require_once('../library/common.func.php');
require_once('../library/hintinfo.php');
require_once('../util/peek.php');
require_once('guru.content.func.php');

$db = new Db;
$db->TryOpenExit(true);

$id_pel = $_REQUEST['id_pel'];
$nama_dep = $_REQUEST['nama_dep'];
$nama_pel = $_REQUEST['nama_pel'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Pendataan Guru</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/toast.js?<?=filemtime('../script/toast.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="guru.content.js?<?=filemtime('guru.content.js')?>"></script>
</head>
<body>

<input type="hidden" name="nama_pel" id="nama_pel" value="<?=$nama_pel?>">
<input type="hidden" name="nama_dep" id="nama_dep" value="<?=$nama_dep?>">
<input type="hidden" name="preplid" id="preplid" value="<?=$id_pel?>">

<table border="0" width="100%" align="center">
<tr>
    <td align="left" valign="top">

        <table border="0" width="100%" align="center">
        <tr>
            <td align="right" valign="top">

                <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()">
                <span class="pageTitle">Pendataan Guru</span><br>
                <a class="pageLink" target="_parent" href="gurupelajaran.php?page=p">Guru &amp; Pelajaran</a>&nbsp;&gt;&nbsp
                <span class="pageLinkCurrent">Pendataan Guru</span>

            </td>
        </tr>
        </table>
        <br>

    </td>
</tr>
<tr>
    <td>
        <table border='0' cellpadding='0' cellspacing='0' width='100%' align='center'>
        <tr>
            <td width='70%' align='left'>
                <span style='font-size: 19px; color: #333;'><?= $nama_pel ?></span>
            </td>
            <td width='*' valign='bottom' align='right'>
                <span class='cur-hand fg-secondary' onclick='document.location.reload()'><img src='../images/ico/refresh.png' border='0' title='refresh' />&nbsp;refresh</span>&nbsp;&nbsp;
                <span class='cur-hand fg-secondary' onclick='cetak()'><img src='../images/ico/print.png' border='0' title='Cetak' />&nbsp;cetak</span>&nbsp;&nbsp;
<?php           if ($id_pel != 0) { ?>
                    <span class='cur-hand fg-secondary' onclick='tambah()'><img src='../images/ico/tambah.png' border='0' title='Tambah' />&nbsp;tambah</span>
<?php           } ?>
            </td>
        </tr>
        </table>
            
    </td>
</tr>
<tr>
    <td align="left" valign="top">

    <div id='dvTableContent'>
<?php
    ShowTableDaftarGuru($db);
?>
    </div>    
    
    </td>
</tr>
<!-- END TABLE CENTER -->     
</table>

<div id="divDialog"></div>
<div id="toast-container"></div>

</body>
</html>