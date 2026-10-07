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
require_once('../library/hintinfo.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('pendataan.content.func.php');

$page = 1;
$departemen = RequestData("departemen", "");
$idProses = RequestData("idproses", 0);
$proses = RequestData("proses", "");
$idKelompok = RequestData("idkelompok", 0);
$kelompok = RequestData("kelompok", "");
$page = RequestData("page", 1);
$orderBy = RequestData("orderby", 1);

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Pendataan Calon Siswa</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/toast.js?<?=filemtime('../script/toast.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="pendataan.content.js?<?=filemtime('pendataan.content.js')?>"></script>
</head>
<body style="padding: 20px;">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idproses" value="<?=$idProses?>">
<input type="hidden" id="proses" value="<?=$proses?>">
<input type="hidden" id="idkelompok" value="<?=$idKelompok?>">
<input type="hidden" id="kelompok" value="<?=$kelompok?>">

<table border='0' cellpadding='0' cellspacing='0' width='100%' align='center'>
<tr>
    <td width='50%' align='left'>
        <input type='button' class='dialogButtonGray' value='< Info Nilai & Sumbangan' style="width: 200px;" onclick='showInfoNilai()'>
        &nbsp;&nbsp;
        Urut:
        <span id="dvOrderBy">
<?php
            ShowSelectOrderBy();
?>
        </span>
    </td>
    <td width='*' valign='bottom' align='right'>
        <span class='cur-hand' onclick='refresh()'><img src='../images/ico/refresh.png' border='0' title='refresh' />&nbsp;refresh</span>&nbsp;&nbsp;
        <span class='cur-hand' onclick='cetak()'><img src='../images/ico/print.png' border='0' title='Cetak' />&nbsp;cetak</span>&nbsp;&nbsp;
        <span class='cur-hand' onclick='excel()'><img src='../images/ico/excel.png' border='0' title='Excel' />&nbsp;excel</span>&nbsp;&nbsp;
        <img src='../images/ico/tambah.png' border='0' title='tambah simple' />
        &nbsp;tambah&nbsp;
        <span class='ablue cur-hand' onclick='tambahSimple()'>dasar</span>&nbsp;|&nbsp;
        <span class='ablue cur-hand' onclick='tambahLengkap()'>lengkap</span>
    </td>
</tr>
</table>
<br>


<div id='dvTableContent'>
<?php
    $nData = 0;
    ShowTableCalonSiswaInfo($db);
?>
</div>
<br>

<div id='dvPageControl'>
<?php
    ShowPageControl($db);
?>
</div>
   

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>