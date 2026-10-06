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
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('appserver.config.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Sinkronisasi Jendela Sekolah</title>
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
    <script language="javascript" src="appserver.js?<?=filemtime('appserver.js')?>"></script>
</head>
<body>

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

    <table border="0" width="95%" align="center">
    <tr>
        <td align="right" valign="top">

            <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()">
            <span class="pageTitle">Sinkronisasi Jendela Sekolah</span><br>
            <a class="pageLink" href="onlinepay.php">Online Payment</a>&nbsp&gt;&nbsp
            <span class="pageLinkCurrent">Sinkronisasi Jendela Sekolah</span>

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
            <span style="font-style: italic; font-size: 12px">
                Alamat IP yang diatur di aplikasi JIBAS Sinkronisasi Jendela Sekolah:
            </span>
            <br><br>

<?php       $disabled = getLevel() == 2 ? "disabled" : ""; ?>
            Alamat IP: <input type="text" id="ipaddr" <?=$disabled?> name="ipaddr" class="inputbox" style="width: 200px; font-size: 18px;" value="<?=$SJS_ADDR?>">
            <span style="font-size: 16px; font-weight: bold">:8105</span>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="#" onclick="showAppServerSample()" style="font-weight: normal; text-decoration: underline; color: blue;">lihat contoh</a>&nbsp;&nbsp;
            <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a>
            <br><br>
            <div>
<?php       if (getLevel() != 2) { ?>
                <input type="button" id="simpan" class="dialogButtonPositive" style="height: 30px; width: 80px" value=" Simpan " onclick="saveJsServerAddr()">&nbsp;&nbsp;
                <span id="status"></span>
<?php       } ?>
            <br><br>
            <img id="imContoh" style="width: 780px;">
            </div>
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