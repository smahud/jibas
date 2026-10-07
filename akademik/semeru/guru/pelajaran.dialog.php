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
 * MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE.  See the
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
require_once('pelajaran.dialog.func.php');

$replid = RequestData("replid", 0);
$title = ($replid == 0) ? "Tambah Pelajaran" : "Ubah Pelajaran";

$departemen = RequestData("departemen", "");
$kode = "";
$nama = "";
$sifat = "";
$kelompok = "";
$keterangan = "";
$urutan = "";

$db = new Db();
$db->TryOpenExit(true);

if ($replid > 0)
    LoadPelajaran($db, $replid);
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
    <script language="javascript" src="pelajaran.dialog.js?<?=filemtime('pelajaran.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle"><?=$title?></span><br><br>
<input type="hidden" id="replid" value="<?=$replid?>">

<table cellpadding="5" cellspacing="0">
<tr>
    <td>Departemen<?=$tag_mandatory?></td>
    <td>
        <input id="departemen" type="text" class="inputbox_readonly" style="width: 300px" readonly value="<?=$departemen?>">
    </td>
</tr>
<tr>
    <td>Nama<?=$tag_mandatory?></td>
    <td>
        <input id="nama" type="text" class="inputbox" style="width: 300px" maxlength="50" value="<?=$nama?>">
    </td>
</tr>
<tr>
    <td>Singkatan<?=$tag_mandatory?></td>
    <td>
        <input id="kode" type="text" class="inputbox" style="width: 100px" maxlength="4" value="<?=$kode?>">
    </td>
</tr>
<tr>
    <td>Sifat<?=$tag_mandatory?></td>
    <td>
<?php   if ($sifat == "" || $sifat == 1) { ?>
        <input type="radio" id="sifat1" name="sifat" value="1" checked>&nbsp;Wajib&nbsp;
        <input type="radio" id="sifat0" name="sifat" value="0">&nbsp;Tambahan&nbsp;
<?php   } else { ?>
        <input type="radio" id="sifat1" name="sifat" value="1">&nbsp;Wajib&nbsp;
        <input type="radio" id="sifat0" name="sifat" value="0" checked>&nbsp;Tambahan&nbsp;
<?php   } ?>
    </td>
</tr>
<tr>
    <td>Kelompok<?=$tag_mandatory?></td>
    <td>
        <select id="kelompok" class="inputbox" style="width: 250px">
<?php       ShowSelectKelompokPelajaran($db); ?>
        </select>
    </td>
</tr>
<tr>
    <td>Urutan<?=$tag_mandatory?></td>
    <td>
        <input id="urutan" type="text" class="inputbox" style="width: 60px" maxlength="4" value="<?=$urutan?>">
    </td>
</tr>
<tr>
    <td valign="top">Keterangan</td>
    <td>
        <textarea rows="3" cols="40" class="inputbox" id="keterangan"><?=$keterangan?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpanPelajaran()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
</table>

</body>
</html>