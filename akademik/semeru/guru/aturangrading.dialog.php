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
require_once('aturangrading.dialog.func.php');

$mode = RequestData("mode", 0);
$title = ($mode == "tambah") ? "Tambah Aturan Grading" : "Ubah Aturan Grading";

$departemen = RequestData("departemen", "");
$pelajaran = RequestData("pelajaran", "");
$idpelajaran = RequestData("idpelajaran", 0);
$nip = RequestData("nip", "");
$nama = RequestData("nama", "");
$idtingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$aspek = RequestData("aspek", "");

$db = new Db();
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?= $title ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="aturangrading.dialog.js?<?=filemtime('aturangrading.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle"><?= $title ?></span><br><br>
<input type="hidden" id="replid" value="0">
<input type="hidden" id="idpelajaran" value="<?= $idpelajaran ?>">
<input type="hidden" id="idtingkat" value="<?= $idtingkat ?>">
<input type="hidden" id="nip" value="<?= $nip ?>">
<input type="hidden" id="nama" value="<?= $nama ?>">
<input type="hidden" id="departemen" value="<?= $departemen ?>">
<input type="hidden" id="pelajaran" value="<?= $pelajaran ?>">
<input type="hidden" id="tingkat" value="<?= $tingkat ?>">

<table cellpadding="5" cellspacing="0">
<tr>
    <td colspan='2'>
    <div style='display: flex; width: 550px;'>
        <div style='flex: 1; padding-right: 5px;'>
            Departemen<br><b><?= $departemen ?></b>
        </div>
        <div style='flex: 1; padding-right: 5px;'>
            Tingkat<br><b><?= $tingkat ?></b>
        </div>
        <div style='flex: 1; padding-right: 5px;'>
            Pelajaran<br><b><?= $pelajaran ?></b>
        </div>
        <div style='flex: 1; padding-right: 5px;'>
            Guru<br><b><?= $nama ?></b><br>
            <span class='fg-secondary'><?= $nip ?></span>
        </div>
    </div>
    </td>
</tr>
<tr>
    <td width="120">Aspek Penilaian: <?= $tag_mandatory ?></td>
    <td>
<?php   ShowSelectAspek($db); ?>        
    </td>
</tr>
<tr>
    <td colspan='2'>
        Aturan Grading: <?= $tag_mandatory ?><br>
<?php   ShowAturanGradingTable($db); ?>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpanAturanGrading()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
</table>

</body>
</html>