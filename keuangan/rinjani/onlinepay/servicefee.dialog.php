<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
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
require_once('../library/common.func.php');
require_once('../library/request.func.php');
require_once('../library/departemen.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('servicefee.dialog.func.php');

$db = new Db();
$db->TryOpenExit();

$idServiceFee = $_REQUEST["id"];
$dept = isset($_REQUEST["dept"]) ? $_REQUEST["dept"] : "";
$kode = "";
$nama = "";
$biaya = "Rp 0";
$keterangan = "";
$rekKas = "";
$rekPendapatan = "";
$namaKas = "";
$namaPendapatan = "";
$useInTrans = false;
$lsFeeDept = [];

LoadServiceFeeValue($db);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Biaya Layanan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
     <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.rinjani.css?<?=filemtime('tooltips.rinjani.css')?>">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/rupiah2.js" ></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/tooltips.rinjani.js?r=<?=filemtime('../script/tooltips.rinjani.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?r=<?=filemtime('../script/qsbuilder.js')?>" ></script>
    <script language="javascript" src="servicefee.dialog.js?r=<?=filemtime('servicefee.dialog.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
</head>

<body>

<table border="0" width="100%" cellpadding="10">
<tr>
    <td align="left" valign="top">

        <span style="font-size: 14pt">Biaya Layanan</span><br><br>

        <input type="hidden" id="idservicefee" name="idservicefee" value="<?=$idServiceFee?>">
        <input type="hidden" id="dept" name="dept" value="<?=$dept?>">

        <table border="0" width="100%" cellpadding="5" cellspacing="2">
        <tr>
            <td align="right" width="22%">Departemen</td>
            <td align="left" width="*">
                <b><?= $dept ?></b>
            </td>
        </tr>
        <tr>
            <td align="right">Kode <?= $tag_mandatory ?></td>
            <td align="left" width="*">
                <input type="text" id="kode" class="inputbox" maxlength="5" name="kode" size="15" value="<?= $kode ?>" >
            </td>
        </tr>
        <tr>
            <td align="right">Nama <?= $tag_mandatory ?></td>
            <td align="left" width="*">
                <input type="text" id="nama" class="inputbox" maxlength="100" name="nama" size="40" value="<?= $nama ?>" >
            </td>
        </tr>
        <tr>
            <td align="right">Biaya <?= $tag_mandatory ?></td>
            <td align="left" width="*">
                <input type="text" id="biaya" class="inputbox" maxlength="10" name="biaya" size="20" value="<?= $biaya ?>"
                       onfocus="unformatRupiah('biaya')" onblur="formatRupiah('biaya')" >
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                Rek. Kas <?= $tag_mandatory ?>
            </td>
            <td align="left" valign="top">
                <input type="hidden" id="rekkas" value="<?=$rekKas?>">
                <input type="text" id="inforekkas" class="inputbox" value="<?= "$rekKas $namaKas"  ?>" disabled style="background-color: #eee; width: 250px">
<?php               if ($useInTrans) { ?>
                        <img src="../images/ico/warning16.png" title="tidak dapat mengubah karena sudah digunakan dalam transaksi">
<?php               } else { ?>
                        <input type='button' class='dialogButtonPositive' style='width: 40px; height: 25px' value='(..)' title='pilih / tambah kode rekening' onclick='showRekAkunDialog("HARTA", "HARTA")'>
<?php               } ?>
                <img src="../images/help32.png" class="tooltip-icon" title="help"
                         onclick="showTooltip(this, '../help/op_tt_servicefee.html?r=' + Math.random(), 'auto', 500)"  >
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                Rek. Pendapatan <?= $tag_mandatory ?>
            </td>
            <td align="left" valign="top">
                <input type="hidden" id="rekpendapatan" value="<?=$rekPendapatan?>">
                <input type="text" id="inforekpendapatan" class="inputbox" value="<?= "$rekPendapatan $namaPendapatan" ?>" disabled style="background-color: #eee; width: 250px">
<?php               if ($isReadOnly) { ?>
                        <img src="../images/ico/warning16.png" title="tidak dapat mengubah karena sudah digunakan dalam transaksi">
<?php               } else { ?>
                        <input type='button' class='dialogButtonPositive' style='width: 40px; height: 25px' value='(..)' title='pilih / tambah kode rekening' onclick='showRekAkunDialog("PENDAPATAN", "PENDAPATAN")'>
<?php               } ?>
                <img src="../images/help32.png" class="tooltip-icon" title="help"
                         onclick="showTooltip(this, '../help/op_tt_servicefee.html?r=' + Math.random(), 'auto', 500)"  >
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                Keterangan
            </td>
            <td align="left" valign="top">
                <textarea rows="2" cols="45" id="keterangan" class="inputbox" name="keterangan"><?= $keterangan ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <input type="button" class="dialogButtonPositive" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanBiayaLayanan()">
                <input type="button" class="dialogButtonNegative" value="Tutup" style="width: 80px; height: 30px;"  onclick="window.close()">
            </td>
        </tr>
        </table>

    </td>
</tr>
</table>

<div id="toast-container"></div>
<div id="tooltip" class="tooltip hidden" aria-hidden="true">
    <button class="tooltip-close">&times;</button>
    <div class="tooltip-arrow"></div>
    <div class="tooltip-content"></div>
</div>

</body>
</html>
