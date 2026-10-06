<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 33.0 (Jan 05, 2026)
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
require_once('dashboard.nota.dialog.func.php');

$id = RequestData("id", 0);
$title = ($id == 0) ? "Tambah Nota Siswa" : "Ubah Nota Siswa";

$departemen = RequestData("departemen", "");
$bagianNota = RequestData("bagiannota", "Keuangan");
$nis = RequestData("nis", "");
$nama = RequestData("nama", "");

$judul = "";
$nota = "";

$db = new Db();
$db->TryOpenExit();

if ($id > 0)
    LoadNotaSiswa($db);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?= $title ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="dashboard.nota.dialog.js?<?=filemtime('dashboard.nota.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle"><?= $title ?></span><br><br>
<input type="hidden" id="id" value="<?= $id ?>">
<input type="hidden" id="nis" value="<?= $nis ?>">
<input type="hidden" id="nama" value="<?= $nama ?>">
<input type="hidden" id="departemen" value="<?= $departemen ?>">
<input type="hidden" id="userlevel" value="<?= SI_USER_LEVEL() ?>">
<input type="hidden" id="userid" value="<?= SI_USER_ID() ?>">

<table cellpadding="5" cellspacing="0">
<tr>
    <td>Departemen</td>
    <td>
        <input type="text" class="inputbox inputbox-readonly" readonly id="departemen" value="<?= $departemen ?>" style="width: 300px">
    </td>
</tr>
<tr>
    <td>Siswa</td>
    <td>
        <input type='text' class='inputbox inputbox-readonly' readonly id='siswa' value="<?= "$nama - $nis"?>" style="width: 300px">
    </td>
</tr>
<tr>
    <td>Bagian Nota</td>
    <td>
<?php
        ShowSelectBagianNota($db);
?>
    </td>
</tr>
<tr>
    <td>Judul</td>
    <td>
        <input type='text' class='inputbox' id='judul' maxlength="255" style="width: 300px" value="<?= $judul ?>">
    </td>
</tr>
<tr>
    <td valign="top">Nota</td>
    <td>
        <textarea rows="14" cols="40" class="inputbox" id="nota"><?= $nota ?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive" value="Simpan" onclick="simpan()">
        <input type="button" id="btnTutup" class="dialogButtonNegative" value="Tutup" onclick="window.close()">
    </td>
</tr>
</table>

<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>
