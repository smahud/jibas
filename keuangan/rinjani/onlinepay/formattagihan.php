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
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('appserver.config.php');

$db = new Db();
$db->TryOpenExit();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Kode Awalan Nomor Tagihan</title>
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
    <script language="javascript" src="formattagihan.js?<?=filemtime('formattagihan.js')?>"></script>
</head>
<body>

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

    <table border="0" width="95%" align="center">
    <tr>
        <td align="right" valign="top">

            <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showFormatTagihanHelp()">
            <span class="pageTitle">Kode Awalan Nomor Tagihan</span><br>
            <a class="pageLink" href="onlinepay.php">Online Payment</a>&nbsp&gt;&nbsp
            <span class="pageLinkCurrent">Kode Awalan Nomor Tagihan</span>

        </td>
    </tr>
    </table>
    <br><br>

    <table border="0" width="100%" align="left">
    <tr>
        <td align="left" valign="top" width="10%">
            &nbsp;
        </td>
        <td align="center" valign="top" width="*">

            <br><br>

            <div style="width: 450px; text-align: right; margin-bottom: 10px;">
                <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a><br>
            </div>

            <table id="table" class="tab" border="1" cellpadding="5" style="border-width: 1px; border-collapse: collapse; border-color: #dddddd">
            <tr style="height: 30px">
                <td class="header" width="30" align="center">No</td>
                <td class="header" width="250" align="center">Departemen</td>
                <td class="header" width="150" align="center">Kode Awalan</td>
            </tr>
<?php
            $no = 0;
            $sql = "SELECT departemen FROM jbsakad.departemen WHERE aktif = 1 ORDER BY urutan";
            $res = $db->QueryDb($sql);
            while($row = mysqli_fetch_row($res))
            {
                $no += 1;

                $awalan = "";
                $sql = "SELECT awalan FROM jbsfina.formatnomortagihan2 WHERE departemen = '$row[0]'";
                $res2 = $db->QueryDb($sql);
                if ($row2 = mysqli_fetch_row($res2))
                {
                    $awalan = $row2[0];
                }
                else
                {
                    $sql = "INSERT INTO jbsfina.formatnomortagihan2 SET awalan = '$no', departemen = '$row[0]', issync = 0";
                    $db->QueryDb($sql);

                    $awalan = $no;
                }
?>
                <tr>
                    <td align="center" valign="top" style="background-color: #efefef;"><?=$no?></td>
                    <td align="left" valign="top"><?=$row[0]?></td>
                    <td align="center" valign="top">
                        <input type="hidden" id="ntdept<?=$no?>" name="ntdept<?=$no?>" value="<?=$row[0]?>">
                        <input type="text" id="awalan<?=$no?>" name="awalan<?=$no?>" maxlength="1" value="<?=$awalan?>" class="inputbox" style="width: 50px">
                    </td>
                </tr>
<?php
            }
?>
            <tr>
            </table>
            <br>

            <input type="hidden" id="ndept" name="ndept" value="<?=$no?>">
<?php       if (getLevel() != 2) { ?>
                <div style="width: 450px; text-align: left; margin-top: 10px;">
                <input type="button" class="dialogButtonPositive" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanNomorTagihan()">
                &nbsp;&nbsp;
                <span style="color: #0000ff; font-style: italic;" id="statusawalan"></span>
                </div>
<?php       } ?>
    
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