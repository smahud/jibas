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
require_once('caritagihan.func.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Cari Tagihan</title>
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
    <script language="javascript" src="../script/qsbuilder.js?r=<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="caritagihan.js?r=<?=filemtime('caritagihan.js')?>"></script>
    <script language="javascript" src="daftartagihan.js?r=<?=filemtime('daftartagihan.js')?>"></script>
</head>

<body >
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<tr>
    <td align="center" valign="top">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="height: 1200px"  align="left">
    <tr>
        <td align="left" valign="top"  style="width: 400px;" rowspan="2">

            <span style="font-size: 16px; font-weight: bold;">Cari Tagihan</span>
            <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a>
            <br><br>
            <table id="tabSelection" border="0" cellspacing="0" cellpadding="5" width="100%">
            <tr>
                <td width="25%">Berdasarkan:</td>
                <td width="75%">
                    <select id="search" class="inputbox" onchange="changeSearch()">
                        <option value="siswa" selected>Siswa</option>
                        <option value="notagihan">Nomor Tagihan</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div id="dvSelection" style="overflow: auto; width: 400px; height: 100px;">
<?php               ShowSearchSiswa() ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="height: auto; background-color: #ffffff">
                    <div id="dvTagihanInfo" style="overflow: auto; width: 400px; height: auto;">

                    </div>
                </td>
            </tr>
            </table>

        </td>
        <td align="left" valign="top" width="*" style="height: 60px;">

            <table border="0" width="95%" align="center">
                <tr>
                    <td align="right" valign="top">

                        <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showCariTagihanHelp()">
                        <span class="pageTitle">Cari Tagihan</span><br>
                        <a class="pageLink" href="onlinepay.php">Online Payment</a>&nbsp&gt;&nbsp
                        <span class="pageLinkCurrent">Cari Tagihan</span>

                    </td>
                </tr>
                </table>
                <br><br>


        </td>
    </tr>
    <tr>
        <td width="*" align="left" valign="top">
            <div id="dvTagihanData" style="background-color: #FFFFFF; width: 100%; height: auto; overflow: auto; padding: 10px;">

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