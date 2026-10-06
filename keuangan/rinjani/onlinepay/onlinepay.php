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
require_once('../library/qsbuilder.php');
require_once('../util/peek.php');
require_once('../include/config.php');
require_once('../library/logger.php');
require_once('../include/db.onfunc.php');
require_once("onlinepay.func.php");
require_once('pgservice.config.php');
require_once('pgserver.config.php');
require_once('pgschoolid.config.php');
require_once('appserver.config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SchoolPay</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="onlinepay.js?<?= filemtime('onlinepay.js')?>" ></script>
    <style>
        .top-right-fixed {
            position: fixed;
            top: 20px;   /* Distance from the top edge */
            right: 50px; /* Distance from the right edge */
            
            /* Styling */
            padding: 10px 20px;
            border-radius: 8px;
            z-index: 1000; /* Ensures it stays on top of other elements */
        }

        .circle {
            width: 25px;
            height: 25px;
            background-color: #ccc;
            border-radius: 50%; /* This creates the circle */
        }
    </style>
</head>
<body>

<?php
$pgAvailable = 1;
if ($PG_SCHOOL_ID == "" || $PG_DATABASE_ID == "")
    $pgAvailable = 0;
?>
<input type="hidden" id="pgavailable" value="<?= $pgAvailable ?>">
<div id="dvSjsInfo" class="top-right-fixed">
    <table border='0' width='220' cellpadding='3' cellspacing='0'>
    <tr>
        <td width='170' align='right' valign='top'>
            <span class='bs_dark'>
            Koneksi ke<br>JIBAS Payment Gateway
            </span>
        </td>
        <td width='*' align='center' valign='top'>
            <div id='dvSjsStatus' class="circle"></div>
        </td>
        <td width='20' align='center' valign='middle'>
            <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showKoneksiPgHelp()">    
        </td>
    </tr>
    <tr>
        <td colspan='3' align="right">
            <span id="spSjsMesssage" class='bs_secondary fst_italic'></span>
        </td>
    </tr>
    </table>
</div>

<table border="0" cellspacing="0" cellpadding="0" align="center" width="70%">
<tr>
    <td align="center" width="100%">
        <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showOnlinePayHelp()">
        <span class="pageTitle">ONLINE PAYMENT</span><br><br>

        <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" width="80">
                <img src="../images/settings01.png" style="width: 40px" title="Referensi">
            </td>
            <td align="left" valign="top" width="400">
                <span class="pageLinkCurrent">Persiapan</span><br>
                <?=$bullet_blue?><a href="https://www.jibas.net/content/paygate/registrasi.php" target="_blank">Aktifasi Payment Gateway</a><span class="bs_secondary fst_italic fsz_10"> - website</span><br>
                <?=$bullet_blue?><a href="appserver.php">Sinkronisasi Jendela Sekolah</a> <?php CheckJsSyncAddrConfig() ?><br>
                <?=$bullet_blue?><a href="statuspg.php">Status Registrasi Payment Gateway</a> <?php CheckStatusPgConfig() ?><br>
            </td>
        </tr>
        <tr style="height: 30px">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="top" width="80">
                <img src="../images/adjust01.png" style="width: 40px" title="Pengaturan">
            </td>
            <td align="left" valign="top" width="400">
                <span class="pageLinkCurrent">Pengaturan</span><br>
                <?=$bullet_blue?><a href="bank.php">Bank</a> <?php CheckBankConfig() ?><br>
                <?=$bullet_blue?><a href="servicefee.php">Biaya Layanan</a> <br>
                <?=$bullet_blue?><a href="formattagihan.php">Kode Awalan Nomor Tagihan</a> <?php CheckFormatNomorTagihanConfig() ?><br>
                <?=$bullet_blue?><a href="formatpesan.php">Format Pesan Notifikasi</a> <?php CheckPesanPgConfig() ?><br>
                <?=$bullet_blue?><a href="infobayar.php">Informasi Tambahan</a> <?php CheckInfoBayarConfig() ?><br>
                <?=$bullet_blue?><a href="vasiswa.php">Virtual Account Siswa</a><br>
            </td>
        </tr>
        <tr style="height: 30px">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="top" width="80">
                <img src="../images/invoice02.png" style="width: 40px" title="Kartu Pembayaran">
            </td>
            <td align="left" valign="top" width="400">
                <span class="pageLinkCurrent">Tagihan</span><br>
                <?=$bullet_blue?><a href="#" onclick="checkAllConfigReady(1)">Buat Tagihan per Kelas</a><br>
                <?=$bullet_blue?><a href="#" onclick="checkAllConfigReady(2)">Buat Tagihan per Siswa</a><br>
                <?=$bullet_blue?><a href="#" onclick="checkAllConfigReady(3)">Daftar Tagihan</a><br>
                <?=$bullet_blue?><a href="#" onclick="checkAllConfigReady(4)">Cari Tagihan</a><br>
            </td>
        </tr>
        <tr style="height: 30px">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="top" width="80">
                <img src="../images/jspreview1.png" style="width: 50px" title="Pembayaran">
            </td>
            <td align="left" valign="top" width="400">
                <span class="pageLinkCurrent">Transaksi Pembayaran</span><br>
                <?=$bullet_blue?><a href="https://jibas.net/content/jendelasekolah/jsmenu.php" target="_blank">JIBAS Jendela Sekolah</a><span class="bs_secondary fst_italic fsz_10"> - website</span><br>
                <?=$bullet_blue?><a href="https://paygate.jendelasekolah.id" target="_blank">JIBAS Payment Gateway</a><span class="bs_secondary fst_italic fsz_10"> - website</span><br>
            </td>
        </tr>
        <tr style="height: 30px">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="top" width="80">
                <img src="../images/report04a.png" style="width: 40px" title="Pelaporan">
            </td>
            <td align="left" valign="top" width="400">
                <span class="pageLinkCurrent">Pelaporan</span><br>
                <?=$bullet_blue?><a href="#" onclick="checkAllConfigReady(5)">Riwayat Transaksi</a><br>
                <?=$bullet_blue?><a href="#" onclick="checkAllConfigReady(10)">Kelebihan Pembayaran</a><br>
                <?=$bullet_blue?><a href="#" onclick="checkAllConfigReady(7)">Statistik</a><br>
                <?=$bullet_blue?><a href="#" onclick="checkAllConfigReady(8)">Saldo Bank</a><br>
                <?=$bullet_blue?><a href="#" onclick="checkAllConfigReady(9)">Mutasi Bank</a><br>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>

<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>

</body>
</html>