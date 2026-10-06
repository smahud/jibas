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
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/departemen.php');
require_once('../include/errorhandler.php');

$db = new Db(); 
$db->TryOpenExit();

$idTagihanData = $_REQUEST["idtagihandata"];
$noTagihan = $_REQUEST["notagihan"];

$sql = "SELECT td.idbesarjtt, td.status, b.besar, b.cicilan, td.idpenerimaan, td.penerimaan, td.jtagihan, td.jdiskon
          FROM jbsfina.tagihansiswadata2 td, jbsfina.besarjtt b
         WHERE td.idbesarjtt = b.replid
           AND td.replid = $idTagihanData";
$res = $db->QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    echo "Data tagihan tidak ditemukan!";
    return;
}

$row = mysqli_fetch_row($res);
$idBesarJtt = $row[0];
$status = $row[1];
$besarJtt = $row[2];
$cicilanJtt = $row[3];
$idPenerimaan = $row[4];
$penerimaan = $row[5];
$tagihan = $row[6];
$diskon = $row[7];

$jumlahBayar = 0;
$jumlahSisa = 0;
$sql = "SELECT SUM(jumlah) + SUM(info1)
          FROM jbsfina.penerimaanjtt
         WHERE idbesarjtt = $idBesarJtt";
$res = $db->QueryDb($sql);
if ($row = mysqli_fetch_row($res))
{
    $jumlahBayar = $row[0];
    $jumlahSisa = $besarJtt - $jumlahBayar;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Data Tagihan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/rupiah2.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/request.factory.js?r=<?=filemtime('../script/request.factory.js')?>"></script>
    <script language="javascript" src="daftartagihan.edit.js?r=<?=filemtime('daftartagihan.edit.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
</head>

<body >
<table border="0" width="100%" cellpadding="10">
<tr>
    <td align="left" valign="top">

    <span style="font-size: 14pt">Data Tagihan <?=$penerimaan?></span><br><br>
    <input type="hidden" id="idtagihandata" value="<?=$idTagihanData?>">
    <input type="hidden" id="notagihan" value="<?=$noTagihan?>">

    <span class="bs_secondary">Informasi Pembayaran, Cicilan &amp; Sisa Tunggakan:</span>
    <table class="tab" cellpadding="5" cellspacing="0">
    <tr>
        <td align="right" width="90px">Besar Iuran</td>
        <td align="left" width="180px"><span class="fst_currency"><?= FormatRupiah($besarJtt) ?></span></td>
    </tr>
    <tr>
        <td align="right" valign="middle"><i>Cicilan</i></td>
        <td align="left">
            <span class="fst_currency"><?= FormatRupiah($cicilanJtt) ?></span>
            <input type="hidden" id="jcicilan" value="<?=$cicilanJtt?>">
        </td>
    </tr>
    <tr>
        <td align="right" valign="middle"><i>Terbayarkan</i></td>
        <td align="left"><span class="fst_currency"><?= FormatRupiah($jumlahBayar) ?></span></td>
    </tr>
    <tr>
        <td align="right" valign="middle"><i>Sisa</i></td>
        <td align="left">
            <span class="fst_currency"><?= FormatRupiah($jumlahSisa) ?></span>
            <input type="hidden" id="jsisa" value="<?=$jumlahSisa?>">
        </td>
    </tr>
    </table><br>

    <span class="bs_secondary">Jumlah Tagihan:</span><br>
    <input type="text" id="jtagihan" value="<?= FormatRupiah($tagihan) ?>"
           style="width: 180px; font-size: 16px;" class="inputbox-money" 
           onfocus="unformatRupiah('jtagihan');" onblur="formatRupiah('jtagihan')" onkeyup="calcPay1()"><br><br>

    <span class="bs_secondary">Jumlah Diskon:</span><br>
    <input type="text" id="jdiskon" value="<?= FormatRupiah($diskon) ?>"
           style="width: 180px; font-size: 16px;" class="inputbox-money" 
           onfocus="unformatRupiah('jdiskon');" onblur="formatRupiah('jdiskon')" onkeyup="calcPay2()"><br><br>

    <span class="bs_secondary">Jumlah Pembayaran:</span><br>
    <span id="jpembayaran" class="fst_currency" style="font-size:16px;"><?= FormatRupiah($tagihan - $diskon)?> </span><br><br>

    <input type="button" id="btSimpan" class="dialogButtonPositive" style="height: 35px; width: 80px" value="Simpan" onclick="simpanEdit()">
    <input type="button" class="dialogButtonNegative" style="height: 35px; width: 80px" value="Tutup" onclick="window.close()"><br>
    <span style="color: blue; font-size: 12px; font-style: italic;" id="spInfo"></span>

    </td>
</tr>
</table>
</body>
</html>
