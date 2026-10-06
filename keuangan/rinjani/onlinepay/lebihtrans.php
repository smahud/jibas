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
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/departemen.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('../library/logger.php');
require_once('onlinepay.util.func.php');
require_once('lebihtrans.func.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Mutasi Bank</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/stringutil.js"></script>
    <script language="javascript" src="../script/dateutil.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>" ></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="onlinepay.util.js?r=<?=filemtime('onlinepay.util.js')?>"></script>
    <script language="javascript" src="lebihtrans.js?r=<?=filemtime('lebihtrans.js')?>"></script>

</head>

<body >

<table border="0" width="100%" cellspacing="0" cellpadding="10" align="left">
<tr>
    <td valign="top" width="5%">
        &nbsp;
    </td>
    <td align="left" valign="top" width="*">

        <table border="0" width="95%" align="center">
        <tr>
            <td align="right" valign="top">

                <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showLebihPembayaranHelp()">
                <span class="pageTitle">Kelebihan Pembayaran</span><br>
                <a class="pageLink" href="onlinepay.php">Online Payment</a>&nbsp&gt;&nbsp
                <span class="pageLinkCurrent">Kelebihan Pembayaran</span>

            </td>
        </tr>
        </table>

        <table id="tabSelection" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td width="100"><strong>Departemen:</strong></td>
            <td width="400">
<?php           $departemen = "";
                ShowSelectDepartemen(); ?>
            </td>
            <td width="100" rowspan="3" valign="middle" align="center">
                <a href="#" onclick="showLebihTrans()" title="lihat laporan">
                    <img src="../images/view.png" border="0">
                </a>
            </td>
        </tr>
        <tr>
            <td><b>Tanggal:</b></td>
            <td>
<?php               $tanggal1 = date('Y-m-d', strtotime("-1 months")) ?>
                <input type="hidden" id="dttanggal1" value="<?= $tanggal1 ?>">
                <input type="text" id="tanggal1" class="inputbox" value="<?= formatInaMySqlDate($tanggal1) ?>" style="width: 140px" onclick="showDatePicker1()">
                s/d
<?php               $tanggal2 = date('Y-m-d'); ?>
                <input type="hidden" id="dttanggal2" value="<?= $tanggal2 ?>">
                <input type="text" id="tanggal2" class="inputbox" value="<?= formatInaMySqlDate($tanggal2) ?>" style="width: 140px" onclick="showDatePicker2()">
            </td>
        </tr>
        <tr>
            <td><b>Status:</b></td>
            <td>
                <select id="status" class="inputbox" style="width: 250px" onchange="clearContent()">
                    <option value="0" selected>Belum diproses</option>
                    <option value="1">Sudah diproses</option>
                </select>
            </td>
        </tr>
        </table>
        <br>
        <div id="dvLebihTrans">
<?php
        $status = 0;
        ShowLebihTransBelum();
?>
        </div>
    </td>
</tr>
</table>

<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>
    
</body>
</html>
