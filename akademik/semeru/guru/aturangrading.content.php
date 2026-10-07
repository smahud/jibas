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
require_once('../util/peek.php');
require_once('aturangrading.content.func.php');

$db = new Db;
$db->TryOpenExit(true);

$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");
$departemen = RequestData("departemen", "");
$nama = RequestData("nama", "");
$nip = RequestData("nip", "");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Aturan Grading Nilai Rapor</title>
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
    <script language="javascript" src="aturangrading.content.js?<?=filemtime('aturangrading.content.js')?>"></script>
</head>
<body>

<input type="hidden" name="nip" id="nip" value="<?= $nip ?>">
<input type="hidden" name="nama" id="nama" value="<?= $nama ?>">
<input type="hidden" name="pelajaran" id="pelajaran" value="<?= $pelajaran ?>">
<input type="hidden" name="idpelajaran" id="idpelajaran" value="<?= $idPelajaran ?>">
<input type="hidden" name="departemen" id="departemen" value="<?= $departemen ?>">

<table border="0" width="100%" align="center">
<tr>
    <td align="left" valign="top">

        <table border="0" width="100%" align="center">
        <tr>
            <td width="40%" align="left" valign="bottom">
                <span class='fs-18'><?= $pelajaran ?></span><br>
                <span class='fs-14 fg-secondary'><?= $departemen ?></span>
            </td>
            <td width="60%" align="right" valign="top">

                <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()">
                <span class="pageTitle">Aturan Grading Nilai Rapor</span><br>
                <a class="pageLink" target="_parent" href="gurupelajaran.php?page=p">Guru &amp; Pelajaran</a>&nbsp;&gt;&nbsp
                <span class="pageLinkCurrent">Aturan Grading Nilai Rapor</span>

            </td>
        </tr>
        </table>
        <br>

    </td>
</tr>
<tr>
    <td>
        <div id="dvContent">        
<?php       ShowAturanGrading() ?>
        </div>
    </td>
</tr>
<!-- END TABLE CENTER -->     
</table>

<div id="divDialog"></div>
<div id="toast-container"></div>

</body>
</html>