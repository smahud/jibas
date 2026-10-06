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
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/departemen.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('vasiswa.func.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Virtual Account Siswa</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>" ></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
    <script language="javascript" src="vasiswa.js?<?=filemtime('vasiswa.js')?>"></script>
</head>
<body>

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

    <table border="0" width="95%" align="center">
    <tr>
        <td align="right" valign="top">

            <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showVaSiswaHelp()">
            <span class="pageTitle">Virtual Account Siswa</span><br>
            <a class="pageLink" href="onlinepay.php">Online Payment</a>&nbsp&gt;&nbsp
            <span class="pageLinkCurrent">Virtual Account Siswa</span>

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
            
            <table border="0" cellpadding="0">
            <tr>
                <td width="100">Departemen</td>
                <td width="300">
<?php               $departemen = "";
                    ShowSelectDepartemen() ?>                    
                </td>
                <td width="100" rowspan="3" valign="middle" align="center">
                    <a href="#" onclick="showDaftarVaSiswa()" title="lihat daftar virtual account siswa">
                        <img src="../images/view.png" border="0">
                </a>
            </td>
            </tr>
            <tr>
                <td>Tingkat</td>
                <td>
                    <div id="divTingkat">
<?php               $idTingkat = "";
                    $tingkat = "";
                    ShowSelectTingkat() ?> 
                    </div>
                </td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>
                    <div id="divKelas">
<?php               ShowSelectKelas() ?> 
                    </div>
                </td>
            </tr>
            </table>
            
            <br><br>
            <div id="divContent"></div>
            
        </td>
    </tr>
    </table>

    </td>
</tr>
</table>
</body>

<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>

</html>