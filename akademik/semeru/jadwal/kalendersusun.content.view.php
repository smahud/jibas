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
require_once('kalendersusun.dialog.func.php');

$replid = RequestData("replid", 0);
$departemen = RequestData("departemen", "");
$tahunAjaran = RequestData("tahunajaran", "");
$kalender = RequestData("kalender", "");
$kode = "";
$kegiatan = "";
$tanggalAwal = "";
$tanggalAwalValue = "";
$tanggalAkhir = "";
$tanggalAkhirValue = "";
$keterangan = "";

if ($replid > 0) 
{
    $db = new Db();
    $db->TryOpenExit();
    
    LoadKegiatanKalender($db);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Kegiatan Kalender Akademik</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <style>
        .ui-datepicker {
            z-index: 9999 !important;
        }
    </style>
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/stringutil.js?<?=filemtime('../script/stringutil.js')?>"></script>
    <script language="javascript" src="../script/dateutil.js?<?=filemtime('../script/dateutil.js')?>"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/tinymce/tinymce.min.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="kalendersusun.dialog.js?<?=filemtime('kalendersusun.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<table width="99%">
<tr>
    <td align="left">
        <span class="dialogTitle"><?= "$kode $kegiatan" ?></span><br>
        <span class="fg-secondary"><?= LongDateFormat($tanggalAwal) ?> s/d <?= LongDateFormat($tanggalAkhir) ?></span>
        <br><br>
    </td>        
</tr>
</table>

<div style="width: 100%; display: flex;">
    <div style="flex:1; gap: 20px">
        <span class='fg-secondary'>Departemen</span><br>
        <span class='fs-13 fst-bold'><?= $departemen ?></span>
    </div>
    <div style="flex:1; gap: 20px">
        <span class='fg-secondary'>Tahun Ajaran</span><br>
        <span class='fs-13 fst-bold'><?= $tahunAjaran ?></span>
    </div>
    <div style="flex:1; gap: 20px">
        <span class='fg-secondary'>Kalender Akademik</span><br>
        <span class='fs-13 fst-bold'><?= $kalender ?></span>
    </div>
</div>
<br>
<?= $keterangan ?>

</body>
</html>