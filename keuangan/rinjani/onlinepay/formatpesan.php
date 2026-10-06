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

$defaultNotifTagihan = "Kami informasikan {NAMA} {NIS} memiliki tagihan sebesar {JUMLAH} untuk {IURAN} bulan {BULAN} {TAHUN}";
$defaultNotifBayar = "Terima kasih, kami telah menerima pembayaran sebesar {JUMLAH} dari {NAMA} {NIS} untuk {TRANSAKSI}";

$dept = isset($_REQUEST["dept"]) ? $_REQUEST["dept"] : "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Format Pesan Notifikasi Tagihan</title>
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
    <script language="javascript" src="formatpesan.js?<?=filemtime('formatpesan.js')?>"></script>
</head>
<body>

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

    <table border="0" width="95%" align="center">
    <tr>
        <td align="right" valign="top">

            <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showFormatPesanHelp()">
            <span class="pageTitle">Format Pesan Notifikasi</span><br>
            <a class="pageLink" href="onlinepay.php">Online Payment</a>&nbsp&gt;&nbsp
            <span class="pageLinkCurrent">Format Pesan Notifikasi</span>

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
            <div style="width: 750px; text-align: left; margin-bottom: 10px;">
            <span style="font-size: 14px; font-weight: bold">Format Pesan Notifikasi Tagihan</span>&nbsp;&nbsp;&nbsp;
            <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a><br>
            <i>Format pesan yang akan dikirimkan yang berisi informasi tagihan</i>
            </div>
            
            <table id="table" class="tab" border="1" cellpadding="5" style="border-width: 1px; border-collapse: collapse; border-color: #dddddd">
                <tr style="height: 30px">
                    <td class="header" width="30" align="center">No</td>
                    <td class="header" width="250" align="center">Departemen</td>
                    <td class="header" width="450" align="center">Pesan</td>
                </tr>
<?php
                $lsDept = [];
                $sql = "SELECT departemen 
                          FROM jbsakad.departemen 
                         WHERE aktif = 1 
                         ORDER BY urutan";
                $res = $db->QueryDb($sql);
                while($row = mysqli_fetch_row($res))
                {
                    $lsDept[] = $row[0];
                }

                $no = 0;
                foreach($lsDept as $dept)
                {
                    $no += 1;

                    $pesan = "";

                    $sql = "SELECT pesan 
                              FROM jbsfina.formatpesanpg2 
                             WHERE departemen = '$dept' 
                               AND kelompok = 'TAGIHAN'";
                    $res2 = $db->QueryDb($sql);

                    if ($row2 = mysqli_fetch_row($res2))
                    {
                        $pesan = $row2[0];
                    }
                    else
                    {
                        $sql = "INSERT INTO jbsfina.formatpesanpg2 
                                   SET departemen = '$dept', pesan = '$defaultNotifTagihan', kelompok = 'TAGIHAN', issync = 0";
                        $db->QueryDb($sql);

                        $pesan = $defaultNotifTagihan;
                    } ?>
                    <tr>
                        <td align="center" valign="top" style="background-color: #efefef"><?=$no?></td>
                        <td align="left" valign="top"><?=$dept?></td>
                        <td align="left" valign="top">
                            <input type="hidden" id="npdept<?=$no?>" name="npdept<?=$no?>" value="<?=$dept?>">
                            <textarea rows="3" cols="55" id="pesan<?=$no?>" class="inputbox" name="pesan<?=$no?>"><?=$pesan?></textarea>
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
                <div style="width: 750px; text-align: left; margin-top: 10px;">
                <input type="button" class="dialogButtonPositive" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanPesanTagihan()">
                &nbsp;&nbsp;<span style="color: #0000ff; font-style: italic;" id="statuspesan"></span>
                </div>
<?php       } ?>

            
            <div style="width: 750px; text-align: left; margin-bottom: 10px; margin-top: 20px;">
            <span style="font-size: 14px; font-weight: bold">Format Pesan Notifikasi Pembayaran</span><br>
            <i>Format pesan yang akan dikirimkan yang berisi informasi transaksi pembayaran yang dilakukan</i>
            </div>

            <table id="table" class="tab" border="1" cellpadding="5" style="border-width: 1px; border-collapse: collapse; border-color: #dddddd">
                <tr style="height: 30px">
                    <td class="header" width="30" align="center">No</td>
                    <td class="header" width="250" align="center">Departemen</td>
                    <td class="header" width="450" align="center">Pesan</td>
                </tr>
                <?php
                
                $no = 0;
                foreach($lsDept as $dept)
                {
                    $no += 1;

                    $pesan = "";
                    $sql = "SELECT pesan 
                              FROM jbsfina.formatpesanpg2 
                             WHERE departemen = '$dept' 
                               AND kelompok = 'PEMBAYARAN'";
                    $res2 = $db->QueryDb($sql);

                    if ($row2 = mysqli_fetch_row($res2))
                    {
                        $pesan = $row2[0];
                    }
                    else
                    {
                        $sql = "INSERT INTO jbsfina.formatpesanpg2 
                                   SET departemen = '$dept', pesan = '$defaultNotifBayar', kelompok = 'PEMBAYARAN', issync = 0";
                        $db->QueryDb($sql);

                        $pesan = $defaultNotifBayar;
                    } ?>
                    <tr>
                        <td align="center" valign="top" style="background-color: #efefef"><?=$no?></td>
                        <td align="left" valign="top"><?=$dept?></td>
                        <td align="left" valign="top">
                            <input type="hidden" id="npdeptb<?=$no?>" name="npdeptb<?=$no?>" value="<?=$dept?>">
                            <textarea rows="3" cols="55" id="pesanb<?=$no?>" name="pesanb<?=$no?>" class="inputbox"><?=$pesan?></textarea>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
            </table>
            <br>
<?php       if (getLevel() != 2) { ?>
                <div style="width: 750px; text-align: left; margin-top: 10px;">
                <input type="button" class="dialogButtonPositive" value="Simpan" style="width: 80px; height: 30px;"  onclick="simpanPesanPembayaran()">
                &nbsp;&nbsp;<span style="color: #0000ff; font-style: italic;" id="statuspesanb"></span>
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