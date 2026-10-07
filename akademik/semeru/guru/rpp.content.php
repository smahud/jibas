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
require_once('rpp.content.func.php');

$page = RequestData("page", 1);
$status = RequestData("status", "1");
$departemen = RequestData("departemen", "");
$idSemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Jenis Pengujian</title>
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
    <script language="javascript" src="rpp.content.js?<?=filemtime('rpp.content.js')?>"></script>
</head>
<body>

<input type="hidden" id="mode" value="list">
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="idsemester" value="<?=$idSemester?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">

<table border="0" width="90%" align="center">
<tr>
    <td>
        <table border='0' cellpadding='0' cellspacing='0' width='100%' align='center'>
        <tr>
            <td width='50%' align='left'>
                <span class="fs-18 fg-black"><?= $pelajaran ?></span><br>
                <span class="fs-11 fg-secondary"><?= "$departemen | $tingkat | $semester" ?></span>
            </td>
            <td width='*' valign='bottom' align='right'>
                Cari: <input type='text' id='cari' class='inputbox' maxlength="100" style="width: 150px;">&nbsp;&nbsp;
                Status:
                <select id='status' class='inputbox' onchange='onStatusChanged()'>
                    <option value='1' <?= IsAktifSelected($status, "1") ?>>Aktif</option>
                    <option value='0' <?= IsAktifSelected($status, "0") ?>>Non Aktif</option>
                    <option value='1,0' <?= IsAktifSelected($status, "1,0") ?>>Semua</option>
                </select>&nbsp;&nbsp;&nbsp;   
                <span class='cur-hand fg-secondary' onclick='document.location.reload()'><img src='../images/ico/refresh.png' border='0' title='refresh' />&nbsp;refresh</span>&nbsp;&nbsp;
                <span class='cur-hand fg-secondary' onclick='tambah()'><img src='../images/ico/tambah.png' border='0' title='Tambah' />&nbsp;tambah</span>
            </td>
        </tr>
        </table>
            
    </td>
</tr>
<tr>
    <td align="left" valign="top">

    <div id='dvTableContent'>
<?php
        ShowListRpp();
?>
    </div><br>
    <div id='dvPageControl'>
<?php
        ShowPageControl();
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