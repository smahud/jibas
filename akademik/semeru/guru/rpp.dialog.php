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
require_once('rpp.dialog.func.php');

$replid = RequestData("replid", 0);
$title = ($replid == 0) ? "Tambah RPP" : "Ubah RPP";

$departemen = RequestData("departemen", "");
$idsemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idtingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idpelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");
$kodeRpp = "";
$urutan = "";
$materi = "";
$deskripsi = "";

if ($replid > 0) 
    LoadRpp();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?= $title ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="../images/jibas.ico" rel="shortcut icon" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/tinymce/tinymce.min.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="rpp.dialog.js?<?=filemtime('rpp.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">


<input type="hidden" id="replid" value="<?= $replid ?>">
<input type="hidden" id="departemen" value="<?= $departemen ?>">
<input type="hidden" id="idsemester" value="<?= $idsemester ?>">
<input type="hidden" id="semester" value="<?= $semester ?>">
<input type="hidden" id="idtingkat" value="<?= $idtingkat ?>">
<input type="hidden" id="tingkat" value="<?= $tingkat ?>">
<input type="hidden" id="idpelajaran" value="<?= $idpelajaran ?>">
<input type="hidden" id="pelajaran" value="<?= $pelajaran ?>">

<table width="99%">
<tr>
    <td width="30%" align="left">
        <span class="dialogTitle"><?= $title ?></span><br><br>
    </td>        
    <td width="*" align="right">
        <span class="fs-18 fg-black"><?= $pelajaran ?></span><br>
        <span class="fs-12 fg-secondary"><?= "$departemen | $tingkat | $semester" ?></span>
    </td>        
</tr>
</table>


<table cellpadding="3" cellspacing="0">
<tr>
    <td style="width:80px">Kode RPP<?= $tag_mandatory ?></td>
    <td>
        <input id="koderpp" type="text" class="inputbox" style="width: 80px" maxlength="10" value="<?= $kodeRpp ?>">
    </td>
</tr>
<tr>
    <td style="width:80px">Urutan<?= $tag_mandatory ?></td>
    <td>
        <input id="urutan" type="text" class="inputbox" style="width: 40px" maxlength="3" value="<?= $urutan ?>">
    </td>
</tr>
<tr>
    <td colspan="2">
        Materi<?= $tag_mandatory ?><br>
        <input id="materi" type="text" maxlength="255" style="width: 450px;" class="inputbox" value="<?= $materi ?>">
    </td>
</tr>
<tr>
    <td colspan="2">
        Deskripsi<?= $tag_mandatory ?><br>
        <textarea id="deskripsi" class="inputbox" rows="20" cols="55"><?= $deskripsi ?></textarea><br>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpan()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
</table>

</body>
</html>