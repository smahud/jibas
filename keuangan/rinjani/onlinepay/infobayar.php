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
require_once('../library/logger.php');
require_once('appserver.config.php');

$db = new Db();
$db->TryOpenExit();

$dept = RequestData("dept", "");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Informasi Tambahan</title>
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
    <script language="javascript" src="infobayar.js?<?=filemtime('infobayar.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
</head>
<body>

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

    <table border="0" width="95%" align="center">
    <tr>
        <td align="right" valign="top">

            <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showInfoTambahanHelp()">
            <span class="pageTitle">Informasi Tambahan</span><br>
            <a class="pageLink" href="onlinepay.php">Online Payment</a>&nbsp&gt;&nbsp
            <span class="pageLinkCurrent">Informasi Tambahan</span>

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

            <table border="0" cellpadding="2" cellspacing="2">
            <tr>
                <td align="left">
                    <span style="font-weight: bold; font-size: 14px">Departemen:</span>&nbsp;
                    <select id="dept" name="dept" class="inputbox" style="width: 250px" onchange="changeDept()">
<?php
                    $sql = "SELECT departemen FROM jbsakad.departemen WHERE aktif = 1 ORDER BY urutan";
                    $res = $db->QueryDb($sql);
                    while($row = mysqli_fetch_row($res))
                    {
                        if ($dept == "") $dept = $row[0];
                        $sel = ($dept == $row[0]) ? "selected" : "";

                        echo "<option value='$row[0]' $sel>$row[0]</option>";
                    }
?>
                    </select>&nbsp;&nbsp;
                    <a href="#" onclick="location.reload();"
                       style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <br>
                    <span style="font-weight: bold; font-size: 14px">Prosedur Pembayaran</span><br>
                    <span style="font-style: italic; font-size: 11px">Informasi mengenai prosedur pembayaran, seperti: syarat dan ketentuan umum, cara transfer, konfirmasi pembayaran, nomor untuk notifikasi sudah transfer dan lama waktu verifikasi pembayaran</span>
                </td>
            </tr>
            <tr>
                <td align="left">
<?php
                    $id = 0;
                    $info = "";

                    $sql = "SELECT replid, info FROM jbsfina.infobayar2 WHERE departemen = '$dept' AND bagian = 'bayar'";
                    $res = $db->QueryDb($sql);
                    if ($row = mysqli_fetch_row($res))
                    {
                        $id = $row[0];
                        $info = $row[1];
                    }
?>
                    <input type="hidden" id="idinfobayar" name="idinfobayar" value="<?=$id?>">
                    <textarea rows="10" cols="120" id="infobayar" name="infobayar" class="inputbox" style="font-size: 12px"><?=$info?></textarea><br>
<?php               if (getLevel() != 2) { ?>
                        <input type="button" class="dialogButtonPositive" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanInfo('bayar', '#idinfobayar', '#infobayar', '#statusinfobayar')">&nbsp;&nbsp;
                        <span id="statusinfobayar" name="statusinfobayar" style="font-style: italic; font-size: 12px; color: #0000ff"></span>
<?php               } ?>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <br>
                    <span style="font-weight: bold; font-size: 14px">Prosedur Pembatalan</span><br>
                    <span style="font-style: italic; font-size: 11px">Informasi mengenai prosedur pembatalan pembayaran, seperti: bisa atau tidak dilakukan pembatalan, nomor yang dihubungi untuk pengajuan pembatalan, lama waktu proses pembatalan</span>
                </td>
            </tr>
            <tr>
                <td align="left">
<?php
                    $id = 0;
                    $info = "";

                    $sql = "SELECT replid, info FROM jbsfina.infobayar2 WHERE departemen = '$dept' AND bagian = 'batal'";
                    $res = $db->QueryDb($sql);
                    if ($row = mysqli_fetch_row($res))
                    {
                        $id = $row[0];
                        $info = $row[1];
                    }
?>
                    <input type="hidden" id="idinfobatal" name="idinfobatal" value="<?=$id?>">
                    <textarea rows="10" cols="120" id="infobatal" name="infobatal" class="inputbox" style="font-size: 12px"><?=$info?></textarea><br>
<?php               if (getLevel() != 2) { ?>
                        <input type="button" class="dialogButtonPositive" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanInfo('batal', '#idinfobatal', '#infobatal', '#statusinfobatal')">
                        <span id="statusinfobatal" name="statusinfobatal" style="font-style: italic; font-size: 12px; color: #0000ff"></span>
<?php               } ?>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <br>
                    <span style="font-weight: bold; font-size: 14px">Prosedur Pengembalian</span><br>
                    <span style="font-style: italic; font-size: 11px">Informasi mengenai prosedur pengembalian pembayaran, seperti: bisa atau tidak dilakukan pengembalian, nomor yang dihubungi untuk informasi pengembalian, lama waktu proses pengembalian</span>
                </td>
            </tr>
            <tr>
                <td align="left">
<?php
                    $id = 0;
                    $info = "";

                    $sql = "SELECT replid, info FROM jbsfina.infobayar2 WHERE departemen = '$dept' AND bagian = 'kembali'";
                    $res = $db->QueryDb($sql);
                    if ($row = mysqli_fetch_row($res))
                    {
                        $id = $row[0];
                        $info = $row[1];
                    }
?>
                    <input type="hidden" id="idinfokembali" name="idinfokembali" value="<?=$id?>">
                    <textarea rows="10" cols="120" id="infokembali" name="infokembali" class="inputbox" style="font-size: 12px"><?=$info?></textarea><br>
<?php               if (getLevel() != 2) { ?>
                        <input type="button" class="dialogButtonPositive" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanInfo('kembali', '#idinfokembali', '#infokembali', '#statusinfokembali')">
                        <span id="statusinfokembali" name="statusinfokembali" style="font-style: italic; font-size: 12px; color: #0000ff"></span>
<?php               } ?>
                </td>
            </tr>
            </table>


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