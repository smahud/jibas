<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 36.0 (Oct 07, 2026)
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
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/msg.php');
require_once('../../include/rupiah.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');

$db = new Db();
$db->TryOpenExit();

$nis = RequestData("nis","");
$nama = RequestData("nama","");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Iuran Yang Belum Lunas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="../../images/jibas.ico" rel="shortcut icon" />
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js?<?=filemtime('../../script/vldr.js')?>"></script>
    <script language="javascript" src="../../script/dialogbox.js"></script>
    <script language="javascript" src="../../script/qsbuilder.js?<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="alumni.dialog.js?<?=filemtime('alumni.dialog.js')?>"></script>
    <script>
        document.addEventListener('keydown', function(event) 
        {
            if (event.key === 'Escape') 
                window.close(); 
        });

    </script>
</head>
<body>
   
<span class="dialogTitle">Iuran Yang Belum Lunas - <?= $nama ?> (<?= $nis ?>)</span><br><br>

<?php
$sql = "SELECT DISTINCT b.replid AS id, b.besar, b.lunas, b.keterangan, d.nama, b.idpenerimaan 
          FROM jbsfina.besarjtt b, jbsfina.penerimaanjtt p, jbsfina.datapenerimaan d 
         WHERE p.idbesarjtt = b.replid 
           AND b.idpenerimaan = d.replid 
           AND b.nis = '$nis' 
           AND b.lunas = 0
         ORDER BY nama";
$totalbesarwjb = 0;
$totalbayarwjb = 0;
$totaldiskonwjb = 0;
$totalsisawjb = 0;

$result = $db->QueryDb($sql);
$nJtt = mysqli_num_rows($result);

if ($nJtt > 0)
{
    echo "<table class='tab' id='tablejtt' border='1' cellpadding='5' style='border-collapse:collapse' cellspacing='0'>";
    echo "<tr height='30' align='center' class='header'>";
    echo "<td width='30'>No</td>";
    echo "<td width='200'>Iuran Wajib</td>";
    echo "<td width='150' align='center'>BP<br><span style='font-size: 9px; font-weight: normal'>Besar Pembayaran</span></td>";
    echo "<td width='150' align='center'>TP<br><span style='font-size: 9px; font-weight: normal'>Total Pembayaran</span></td>";
    echo "<td width='150' align='center'>TD<br><span style='font-size: 9px; font-weight: normal'>Total Diskon</span></td>";
    echo "<td width='150' align='center'>Sisa<br><span style='font-size: 9px; font-weight: normal'>Tunggakan</span></td>";
    echo "<td width='250' align='center'>PT<br><span style='font-size: 9px; font-weight: normal'>Pembayaran Terakhir</span></td>";
    echo "</tr>";
}

$cnt = 0;
while ($row = mysqli_fetch_array($result))
{
    $cnt += 1;

    $idbesarjtt = $row['id'];
    $namapenerimaan = $row['nama'];
    $idpenerimaan = $row['idpenerimaan'];
    $besar = $row['besar'];
    $lunas = $row['lunas'];
    $keterangan = $row['keterangan'];

    $sql = "SELECT SUM(jumlah), SUM(info1) 
              FROM jbsfina.penerimaanjtt 
             WHERE idbesarjtt = '$idbesarjtt'";
    $row2 = $db->FetchSingleRow($sql);
    $pembayaran = $row2[0] + $row2[1];
    $diskon = $row2[1];
    $sisa = $besar - $pembayaran;

    $totalbesarwjb += $besar;
    $totalbayarwjb += $pembayaran;
    $totaldiskonwjb += $diskon;
    $totalsisawjb += $sisa;

    $sql = "SELECT p.jumlah, DATE_FORMAT(p.tanggal, '%d-%b-%Y') AS ftanggal, p.info1, j.nokas
              FROM jbsfina.penerimaanjtt p, jbsfina.jurnal j
             WHERE p.idjurnal = j.replid
               AND p.idbesarjtt = '$idbesarjtt'
             ORDER BY p.tanggal DESC
             LIMIT 1";

    $result2 = $db->QueryDb($sql);
    $byrakhir = 0;
    $dknakhir = 0;
    $tglakhir = "";
    $nojurnal = "";

    if (mysqli_num_rows($result2))
    {
        $row2 = mysqli_fetch_row($result2);
        $byrakhir = $row2[0];
        $tglakhir = $row2[1];
        $dknakhir = $row2[2];
        $nojurnal = $row2[3];
    };

    echo "<tr>";
    echo "<td align='center' class='numberColumn'>$cnt</td>";
    echo "<td align='left' style='line-height: 20px;'><b>$namapenerimaan</b><br>";
    echo "<span class='bg-danger fg-white br5' style='padding: 5px 5px; margin-top: 20px;'>BELUM LUNAS</span>";
    echo "</td>";
    echo "<td align='right'><b>" . FormatRupiah($besar) . "</b></td>";
    echo "<td align='right'><b>" . FormatRupiah($pembayaran) . "</b></td>";
    echo "<td align='right'><b>" . FormatRupiah($diskon) . "</b></td>";
    echo "<td align='right'><b>" . FormatRupiah($sisa) . "</b></td>";
    echo "<td align='left'>";
    echo "<b>" . FormatRupiah($byrakhir) . "</b><br><i>diskon: " . FormatRupiah($dknakhir) . "</i><br><i>tanggal: " . $tglakhir . "</i><br><i>no jurnal: $nojurnal</i>";
    echo "</td>";
    echo "</tr>";
}

if ($nJtt > 0)
{
    echo "<tr height='45'>";
    echo "<td align='right' colspan='2' class='footerColumn'><b>TOTAL</b></td>";
    echo "<td align='right' class='footerColumn'><b>" . FormatRupiah($totalbesarwjb) . "</b></td>";
    echo "<td align='right' class='footerColumn'><b>" . FormatRupiah($totalbayarwjb) . "</b></td>";
    echo "<td align='right' class='footerColumn'><b>" . FormatRupiah($totaldiskonwjb) . "</b></td>";
    echo "<td align='right' class='footerColumn'><b>" . FormatRupiah($totalsisawjb) . "</b></td>";
    echo "<td align='left' class='footerColumn'>&nbsp;</td>";
    echo "</tr>";
    echo "</table>";
}

?>

<br>

<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>
