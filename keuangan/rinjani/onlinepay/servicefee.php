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

$dept = isset($_REQUEST["dept"]) ? $_REQUEST["dept"] : "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Biaya Layanan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="../style/tooltips.rinjani.css?<?=filemtime('../style/tooltips.rinjani.css')?>">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/tooltips.rinjani.js?r=<?=filemtime('../script/tooltips.rinjani.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>" ></script>
    <script language="javascript" src="servicefee.js?<?=filemtime('servicefee.js')?>"></script>
    <script language="javascript" src="appserver.js?r=<?=filemtime('appserver.js')?>"></script>
</head>
<body>

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

    <table border="0" width="95%" align="center">
    <tr>
        <td align="right" valign="top">

            <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showServiceFeeHelp()">
            <span class="pageTitle">Biaya Layanan</span><br>
            <a class="pageLink" href="onlinepay.php">Online Payment</a>&nbsp&gt;&nbsp
            <span class="pageLinkCurrent">Biaya Layanan</span>

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

            <table border="0" width="860" cellpadding="0" cellspacing="0">
            <tr>
                <td align="left" width="70%">                

                    <b>Departemen:</b>&nbsp;
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
                    </select>&nbsp;    
                    <a href="#" onclick="location.reload();" style="font-weight: normal; text-decoration: underline; color: blue;">muat ulang</a>

                </td>
                <td align="right" width="30%">                
<?php           if (getLevel() != 2) { ?>
                    <a href="#" onclick="tambahBiayaLayanan()"><img src="../images/ico/tambah.png" border="0">&nbsp;tambah</a>&nbsp;&nbsp;
<?php           } ?>                    
                </td>
            </tr>
            </table>

            <br>
            <table id="table" border="1" class="tab" cellpadding="5">
            <tr style="height: 30px">
                <td class="header" width="30" align="center">No</td>
                <td class="header" width="250" align="center">Layanan</td>
                <td class="header" width="120" align="center">Biaya</td>
                <td class="header" width="250" align="center">Rekening</td>
                <td class="header" width="100" align="center">Status</td>
                <td class="header" width="80">&nbsp</td>
            </tr>
<?php
                $no = 0;
                $total = 0;
                $sql = "SELECT p.id, p.kode, p.nama, p.biaya, p.rekkas, p.rekpendapatan, p.aktif,
                               r1.nama AS namarekkas, r2.nama AS namarekpendapatan
                          FROM jbsfina.pgservicefee2 p, jbsfina.rekakun r1, jbsfina.rekakun r2
                         WHERE p.rekkas = r1.kode
                           AND p.rekpendapatan = r2.kode
                           AND p.departemen = '$dept'";
                $res = $db->QueryDb($sql);
                while ($row = mysqli_fetch_array($res))
                {
                    $idServiceFee = $row["id"];

                    if (getLevel() != 2)
                    {
                        $imAktif = "<a href='#' onclick='setBiayaLayananAktif($idServiceFee, 1)'><img src='../images/ico/nonaktif.png' border='0' title='set aktif'></a>";
                        if ($row["aktif"] == 1)
                            $imAktif = "<a href='#' onclick='setBiayaLayananAktif($idServiceFee, 0)'><img src='../images/ico/aktif.png' border='0' title='set non aktif'></a>";
                    }
                    else
                    {
                        $imAktif = "<img src='../images/ico/nonaktif.png' border='0' title='set aktif'>";
                        if ($row["aktif"] == 1)
                            $imAktif = "<img src='../images/ico/aktif.png' border='0' title='set non aktif'>";
                    }

                    $no += 1;

                    if ($row["aktif"] == 1)
                        $total += $row["biaya"];
                    ?>

                    <tr>
                        <td align="center" valign="top"  style='background-color: #eee'><?=$no?></td>
                        <td align="left" valign="top"><b><?=$row["kode"]?></b><br><?=$row["nama"]?></td>
                        <td align="right" valign="top"><b><?= FormatRupiah($row["biaya"]) ?></b></td>
                        <td align="left" valign="top">
                            Rek.Kas: <?=$row["rekkas"]?> <?=$row["namarekkas"]?><br>
                            Rek.Pendapatan: <?=$row["rekpendapatan"]?> <?=$row["namarekpendapatan"]?>
                        </td>
                        <td align="center" valign="top">
                            <span id="spAktif<?=$idServiceFee?>">
                            <?=$imAktif?>
                        </span>
                        <td align="center" valign="top">
<?php                       if (getLevel() != 2) { ?>
                                <a href="#" onclick="editBiayaLayanan(<?= $idServiceFee ?>)" ><img src="../images/ico/ubah.png" border="0" alt=""/></a>&nbsp;&nbsp;
                                <a href="#" onclick="hapusBiayaLayanan('<?= $idServiceFee ?>')"><img src="../images/ico/hapus.png" border="0" alt=""/></a>
<?php                       } ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
<?php
                if ($no > 0)
                {
                    ?>
                    <tr style="height: 35px">
                        <td colspan="2" align="right" valign="middle" style='background-color: #ddd'>
                            <b>TOTAL</b>
                        </td>
                        <td align="right" valign="middle" style='background-color: #ddd'>
                            <div id="dvTotal">
                            <b><?= FormatRupiah($total) ?></b>
                            </div>
                        </td>
                        <td colspan="3" style='background-color: #ddd'>&nbsp;</td>
                    </tr>
<?php       }
            else 
            {
                echo "<tr style='height: 60px'><td colspan='6' align='center'><em>Belum ada data biaya layanan.</em></td></tr>";    
            } ?>
            </table>

            <table border="0" width="830" cellspacing="0" cellpadding="5">
            <tr>
                <td align="left">
<?php               if (getLevel() != 2 && $no != 0) { ?>
                    <br>
                    <input type="button" class="dialogButtonPositive" value="Update Tagihan" style="width: 120px; height: 40px" onclick="updateTagihan()">
                    <img src="../images/help32.png" class="tooltip-icon" title="help"
                         onclick="showTooltip(this, '../help/op_tt_updservicefee.html?r=' + Math.random(), 'auto', 500)"  >
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
<div id="tooltip" class="tooltip hidden" aria-hidden="true">
    <button class="tooltip-close">&times;</button>
    <div class="tooltip-arrow"></div>
    <div class="tooltip-content"></div>
</div>

</html>