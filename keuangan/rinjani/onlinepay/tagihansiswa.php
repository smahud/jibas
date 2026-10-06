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
ini_set('max_execution_time', '300');

require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/common.func.php');
require_once('../library/departemen.php');
require_once('../library/msg.php');
require_once('tagihansiswa.func.php');

$departemen = $_REQUEST["departemen"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Buat Tagihan per Siswa</title>
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
    <script language="javascript" src="../script/request.factory.js?r=<?=filemtime('../script/request.factory.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="tagihansiswa.js?r=<?=filemtime('tagihansiswa.js')?>"></script>
</head>

<body >
<table border="0" width="100%" height="100%">
<tr>
    <td align="center" valign="top">

        <table border="0" width="95%" align="center">
    <tr>
        <td align="right" valign="top">

            <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showTagihanSiswaHelp()">
            <span class="pageTitle">Buat Tagihan per Siswa</span><br>
            <a class="pageLink" href="onlinepay.php">Online Payment</a>&nbsp&gt;&nbsp
            <span class="pageLinkCurrent">Buat Tagihan per Siswa</span>

        </td>
    </tr>
    </table>
    <br><br>

        <table border="0" width="100%" align="left">
        <tr>
            <td align="left" valign="top" width="10%">
                &nbsp;
            </td>
            <td align="left" valign="top" width="*">


                <table border="0" cellspacing="2" cellpadding="2">
                <tr style="height: 35px">
                    <td width="120" align="right" valign="middle">
                        <strong>Departemen:</strong>&nbsp;
                    </td>
                    <td align="left" valign="middle">
<?php                   ShowSelectDept(); ?>&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a><br>
                    </td>
                </tr>
                <tr style="height: 35px">
                    <td width="120" align="right" valign="middle">
                        <strong>Tahun Buku&nbsp;</strong>
                    </td>
                    <td align="left" valign="middle">
                        <span id="divtahunbuku">
<?php
                        $idTahunBuku = "";
                        $tahunBuku = "";
                        ShowSelectTahunBuku();
?>
                        </span>
                    </td>
                </tr>
                <tr style="height: 55px">
                    <td width="120" align="right" valign="top">
                        <strong>Bulan Tagihan:</strong>&nbsp;
                    </td>
                    <td align="left" valign="top">
<?php
                        ShowSelectBulan();
                        ShowSelectTahun(); ?>
                        <br><br>
                        <input type="radio" id="skipinvoice" name="checkpaid" checked="checked" onchange="showInvoiceList()">&nbsp;<i>tidak dibuat tagihan apabila sudah membayar cicilan iuran di bulan ini</i><br><br>
                        <input type="radio" id="includeinvoice" name="checkpaid" onchange="showInvoiceList()">&nbsp;<i>buat tagihan meski sudah membayar cicilan iuran di bulan ini</i>

                        <br><br>
                    </td>
                </tr>
                <tr>
                    <td align="right" valign="middle"><strong>Siswa:</strong>&nbsp;</td>
                    <td  align="left" valign="middle">
                        <input type="hidden" name="kelompok" id="kelompok">
                        <input type="text" name="noid" id="noid" size="15" readonly class='inputbox' style="background-color:#daefff; font-size: 14px;" onclick="SearchUser()">
                        <input type="text" name="nama" id="nama" size="30" readonly class='inputbox' style="background-color:#daefff; font-size: 14px;" onclick="SearchUser()">
                        <input type="hidden" name="kelas" id="kelas">
                        <input type="button" class="but" value="..." style="width: 40px; height: 23px;" onclick="SearchUser()">
                        &nbsp;&nbsp;&nbsp;
                        <i>Scan Barcode</i>
                        <input name="txBarcode" id="txBarcode" type="text" class='inputbox' style="width: 200px; font-size: 18px;"
                               onfocus="this.style.background = '#27d1e5'"
                               onblur="this.style.background = '#FFFFFF'"
                               onkeyup="return scanBarcode(event)">
                        <br>
                        <span id="spScanInfo" name="spScanInfo" style="color: red"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="left" valign="top">
                        <div id="divContent">

                        </div>
                    </td>
                </tr>
                </table>

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