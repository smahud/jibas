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
require_once('../library/logger.php');
require_once('../util/peek.php');
require_once('jam.dialog.func.php');

$db = new Db();
$db->TryOpenExit();

$replid = RequestData("replid", 0);
$title = ($replid == 0) ? "Tambah Jam" : "Ubah Jam";

$departemen = RequestData("departemen", "");
$jamKe = "";
$jamMulai= "";
$menitMulai = "";
$jamAkhir = "";
$menitAkhir = "";
$keterangan = "";

if ($replid > 0) 
    LoadJam($db);
else 
    GetJamKe($db);    
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
    <link rel="stylesheet" type="text/css" href="../style/toast.css?<?=filemtime('../style/toast.css')?>">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js?<?=filemtime('../script/toast.js')?>">"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="jam.dialog.js?<?=filemtime('jam.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">


<input type="hidden" id="replid" value="<?= $replid ?>">
<input type="hidden" id="departemen" value="<?= $departemen ?>">

<span class="dialogTitle"><?= $title ?></span><br><br>


<br>
<table cellpadding="5" cellspacing="0">
<tr>
    <td style="width:80px">Departemen<?= $tag_mandatory ?></td>
    <td>
        <b><?= $departemen ?></b>
    </td>
</tr>
<tr>
    <td>Jam ke<?= $tag_mandatory ?></td>
    <td>
        <input type="text" id="jamke" class="inputbox inputbox-readonly" readonly style="width: 50px;" maxlength="2" value="<?= $jamKe ?>">
    </td>
</tr>
<tr>
    <td>Waktu Mulai<?= $tag_mandatory ?></td>
    <td>
        <input type="text" id="jammulai" class="inputbox" style="width: 50px;" placeholder="jam" maxlength="2" value="<?= $jamMulai ?>">
        &nbsp;:&nbsp;
        <input type="text" id="menitmulai" class="inputbox" style="width: 50px;" placeholder="menit" maxlength="2" value="<?= $menitMulai ?>">
    </td>
</tr>
<tr>
    <td>Waktu Akhir<?= $tag_mandatory ?></td>
    <td>
        <input type="text" id="jamakhir" class="inputbox" style="width: 50px;" placeholder="jam" maxlength="2" value="<?= $jamAkhir ?>">
        &nbsp;:&nbsp;
        <input type="text" id="menitakhir" class="inputbox" style="width: 50px;" placeholder="menit" maxlength="2" value="<?= $menitAkhir ?>">
    </td>
</tr>
<tr>
    <td>Keterangan</td>
    <td>
        <input type="text" id="keterangan" class="inputbox" maxlength="255" style="width: 300px;" value="<?= $keterangan ?>">
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpan()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
<tr>
    <td colspan="2" style="padding-top: 10px">
        <span id="spInfo" class="fg-red"></span>
    </td>
</tr>
</table>

<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>