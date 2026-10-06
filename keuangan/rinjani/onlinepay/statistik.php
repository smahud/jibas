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
require_once('../library/msg.php');
require_once('onlinepay.util.func.php');
require_once('statistik.func.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Statistik</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?r=<?= filemtime('../style/style.css') ?>">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/rupiah2.js"></script>
    <script language="javascript" src="../script/stringutil.js"></script>
    <script language="javascript" src="../script/dateutil.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="onlinepay.util.js?r=<?=filemtime('onlinepay.util.js')?>"></script>
    <script language="javascript" src="statistik.js?r=<?=filemtime('statistik.js')?>"></script>

</head>

<body >
<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

    <table border="0" width="100%" align="center">
    <tr>
        <td align="left" valign="top">

            <table border="0" width="95%" align="center">
            <tr>
                <td align="right" valign="top">

                    <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showStatistikHelp()">
                    <span class="pageTitle">Statistik</span><br>
                    <a class="pageLink" href="onlinepay.php">Online Payment</a>&nbsp&gt;&nbsp
                    <span class="pageLinkCurrent">Statistik</span>

                </td>
            </tr>
            </table>
            
        </td>
    </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="10" align="left">
    <tr>
        <td valign="top" width="380" style="border-right: 1px solid;">
            &nbsp;
            <table id="tabSelection" border="0" cellspacing="0" cellpadding="5" width="100%">
            <tr>
                <td width="25%"><strong>Departemen:</strong></td>
                <td width="75%">
<?php               $departemen = "";
                    ShowSelectDepartemen(); ?>
                </td>
            </tr>
            <tr>
                <td><strong>Laporan:</strong></td>
                <td>
                    <select id="laporan" class="inputbox" style="width: 250px" onchange="changeLaporan(); clearContent()">
                        <option value="0" selected>Harian</option>
                        <option value="1">Bulanan</option>
                    </select>
                </td>
            </tr>
            <tr id="trHarian" style="display: table-row">
                <td><strong>Tanggal:</strong></td>
                <td>
                    <input type="hidden" id="dttanggal1" value="<?= date('Y-m-d', strtotime("-1 months")) ?>">
                    <input type="text" id="tanggal1" name="tanggal1" class="inputbox" value="<?= formatInaMySqlDate(date('Y-m-d', strtotime("-1 months"))) ?>" style="width: 90px" onclick="showDatePicker1()">
                    s/d
                    <input type="hidden" id="dttanggal2" value="<?= date('Y-m-d') ?>">
                    <input type="text" id="tanggal2" name="tanggal2" class="inputbox" value="<?= formatInaMySqlDate(date('Y-m-d')) ?>" style="width: 90px" onclick="showDatePicker2()">
                </td>
            </tr>
            <tr id="trBulanan" style="display: none">
                <td><strong>Bulan:</strong></td>
                <td>
<?php               ShowSelectBulan() ?>
                </td>
            </tr>
            <tr>
                <td><strong>Metode:</strong></td>
                <td>
                    <select id="metode" class="inputbox" style="width: 250px" onchange="clearContent()">
                        <option value="0" selected>Semua Metode Transaksi</option>
                        <option value="1">Pembayaran Tagihan</option>
                        <option value="2">Pembayaran Keranjang</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Bank:</strong></td>
                <td>
                    <div id='dvBank'>
<?php               ShowSelectBank() ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Petugas:</strong></td>
                <td>
<?php               ShowSelectPetugas() ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <br><br>
                    <input type="button" style="width: 150px; height: 40px" value="Lihat Statistik" class="dialogButtonPositive" onclick="showStatistik()">
                </td>
            </tr>
            </table>

        </td>
        <td valign="top" width="*">
            <div id="dvContent">

            </div>
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