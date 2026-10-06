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
require_once('../include/sessionchecker.php');
require_once('../library/common.func.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../include/sessioninfo.php');
require_once('../library/departemen.php');
require_once('../include/errorhandler.php');
require_once('bank.dialog.func.php');

$db = new Db();
$db->TryOpenExit();

$idBank = RequestData("idbank", 0);
$departemen = RequestData("departemen", "");

$bank = "";
$bankLoc = "";
$bankName = "";
$bankNo = "";
$vaNo = "";
$qris = "";
$qrisName = "";
$qrisId = "";
$urutan = "";
$keterangan = "";
$rekKas = "";
$namaRekKas = "";
$rekPendapatan = "";
$namaRekPendapatan = "";

if ($idBank != 0)
    LoadBankValue($db);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Payment Gateway Provider</title>
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
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/jsQR.js" ></script>
    <script language="javascript" src="../script/tooltips.rinjani.js?r=<?=filemtime('../script/tooltips.rinjani.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?r=<?=filemtime('../script/qsbuilder.js')?>" ></script>
    <script language="javascript" src="bank.dialog.js?r=<?=filemtime('bank.dialog.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
</head>

<body >
<div id="toast-container"></div>
<table border="0" width="100%" cellpadding="10">
    <tr>
        <td align="left" valign="top">

            <span style="font-size: 14pt">Bank</span><br><br>

            <input type="hidden" id="idbank" value="<?= $idBank ?>">
            <input type="hidden" id="departemen" value="<?= $departemen ?>">

            <table border="0" width="100%" cellpadding="5" cellspacing="2">
            <tr>
                <td align="right" width="25%">Departemen</td>
                <td align="left" width="*">
                    <b><?= $departemen ?></b>
                </td>
            </tr>
            <tr>
                <td align="right" width="25%">Nama Bank<?= $tag_mandatory ?></td>
                <td align="left" width="*">
                    <input type="text" id="bank" name="bank" class="inputbox" maxlength="100" style="width: 250px" value="<?=$bank?>">
                </td>
            </tr>
            <tr>
                <td align="right" width="25%">Lokasi Bank<?= $tag_mandatory ?></td>
                <td align="left" width="*">
                    <input type="text" id="bankloc" name="bankloc" class="inputbox" maxlength="100" style="width: 250px" value="<?=$bankLoc?>">
                </td>
            </tr>
            <tr>
                <td align="right" width="25%">Nama Pemilik Rekening<?= $tag_mandatory ?></td>
                <td align="left" width="*">
                    <input type="text" id="bankname" name="bankname" class="inputbox" maxlength="100" style="width: 250px" value="<?=$bankName?>">
                </td>
            </tr>
            <tr>
                <td align="right" width="25%">Nomor Rekening<?= $tag_mandatory ?></td>
                <td align="left" width="*">
                    <input type="text" id="bankno" name="bankno" class="inputbox" maxlength="100" style="width: 250px" value="<?=$bankNo?>">
                </td>
            </tr>
            <tr>
                <td align="right" valign="top" width="25%">Gambar QRCode<br>(QRIS)</td>
                <td align="left" width="*">
                    <input type="hidden" id="qrisvalid" value="0">
                    <input type="file" id="qris" name="qris" class="inputbox" maxlength="100" style="width: 250px"
                           accept="image/jpeg,image/png"><br>
                    <span style="color: blue; font-size: 11px">
                        &bull;&nbsp;hanya area <b>QR Code</b> saja (tanpa teks dan gambar lainnya)<br>
                        &bull;&nbsp;ekstensi <b>jpg</b> atau <b>png</b>, min <b>500x500</b>, max <b>700x700, kurang dari <b>200 KB</b></b>
                    </span><br>
                    <span id="qriserror" style="color: red; font-size: 11px"></span>
                </td>
            </tr>
            <tr>
                <td align="right" width="25%">Nama Merchant<br>(QRIS)</td>
                <td align="left" width="*">
                    <input type="text" id="qrisname" name="qrisname" class="inputbox" maxlength="100" style="width: 250px" value="<?=$qrisName?>">
                </td>
            </tr>
            <tr>
                <td align="right" width="25%">ID Merchant<br>(QRIS)</td>
                <td align="left" width="*">
                    <input type="text" id="qrisid" name="qrisid" class="inputbox" maxlength="100" style="width: 250px" value="<?=$qrisId?>">
                </td>
            </tr>
            <tr>
                <td align="right" valign="top">Urutan<?= $tag_mandatory ?></td>
                <td align="left" valign="top">
                    <input type="text" id="urutan" name="urutan" class="inputbox" maxlength="2" style="width: 60px" value="<?=$urutan?>">
                </td>
            </tr>
            <tr>
                <td align="right" valign="top">
                    Rek. Kas<?= $tag_mandatory ?>
                </td>
                <td align="left" valign="top">
                    <input id="inforekkas" type="text" class="inputbox" readonly
                           style="background-color: #efefef; width: 250px;"
                           value="<?= "$rekKas $namaRekKas" ?>">
                    <input id="rekkas" type="hidden" value="<?= $rekKas ?>">
<?php               if ($isReadOnly) { ?>
                        <img src="../images/ico/warning16.png" title="tidak dapat mengubah karena sudah digunakan dalam transaksi">
<?php               } else { ?>
                        <input type='button' class='dialogButtonPositive' style='width: 40px; height: 25px' value='(..)' title='pilih / tambah kode rekening' onclick='showRekAkunDialog("HARTA", "HARTA")'>
<?php               } ?>
                    <img src="../images/help32.png" class="tooltip-icon" title="help"
                         onclick="showTooltip(this, '../help/op_tt_bank.html?r=<?= rand(1000, 9999) ?>', 'auto', 500)"  >
                </td>
            </tr>
            <tr>
                <td align="right" valign="top">
                    Rek. Pendapatan<?= $tag_mandatory ?>
                </td>
                <td align="left" valign="top">
                    <input id="inforekpendapatan" type="text" class="inputbox" readonly
                           style="background-color: #efefef; width: 250px;"
                           value="<?= "$rekPendapatan $namaRekPendapatan" ?>">
                    <input id="rekpendapatan" type="hidden" value="<?= $rekPendapatan ?>">
<?php               if ($isReadOnly) { ?>
                        <img src="../images/ico/warning16.png" title="tidak dapat mengubah karena sudah digunakan dalam transaksi">
<?php               } else { ?>
                        <input type='button' class='dialogButtonPositive' style='width: 40px; height: 25px' value='(..)' title='pilih / tambah kode rekening' onclick='showRekAkunDialog("PENDAPATAN", "PENDAPATAN")'>
<?php               } ?>
                    <img src="../images/help32.png" class="tooltip-icon" title="help"
                         onclick="showTooltip(this, '../help/op_tt_bank.html?r=<?= rand(1000, 9999) ?>', 'auto', 500)"  >
                        
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
                    <input id="btSimpan" type="button" class="dialogButtonPositive" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanBank()">
                    <input id="btTutup" type="button" class="dialogButtonNegative" value="Tutup" style="width: 80px; height: 30px;"  onclick="window.close()">
                </td>
            </tr>
            </table>

        </td>
    </tr>
</table>

<div id="tooltip" class="tooltip hidden" aria-hidden="true">
    <button class="tooltip-close">&times;</button>
    <div class="tooltip-arrow"></div>
    <div class="tooltip-content"></div>
</div>

</body>
</html>
