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
require_once('../../library/common.func.php');
require_once('../../include/getheader2.php');
require_once('../../util/peek.php');

$departemen = RequestData("departemen", "");
$nis = RequestData("nis", "");
$nama = RequestData("nama", "");
$tglAwal = RequestData("tglawal", "");
$tglAkhir = RequestData("tglakhir", "");

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rincian Presensi Harian Siswa</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/dialogbox.js"></script>
    <script language="javascript" src="../../script/util.js?<?=filemtime('../../script/util.js')?>"></script>
    <script language="javascript" src="../../script/toast.js?<?=filemtime('../../script/toast.js')?>"></script>
</head>
<body style="margin: 5px; padding: 10px;"> 

<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
    <td align="left" valign="top">

<?=     getHeader2($db, $departemen) ?>

        <center><font size="4"><strong>REKAPITULASI PRESENSI HARIAN</strong></font><br /> </center><br>

        <span><span style='display: inline-block; min-width: 100px'>Departemen:</span> <b><?= $departemen ?></b></span><br>
        <span><span style='display: inline-block; min-width: 100px'>Nama:</span> <b><?= $nama ?></b></span><br>
        <span><span style='display: inline-block; min-width: 100px'>NIS:</span> <b><?= $nis ?></b></span><br>
        <span><span style='display: inline-block; min-width: 100px'>Tanggal:</span> <b><?= $tglAwal ?> - <?= $tglAkhir ?></b></span><br>

        <br>
        <table class="tab" id="table" width="100%" align="left">
        <tr height="30" align="center">
            <td width="5%" class="bg-table-header">No</td>
            <td width="40%" class="bg-table-header">Tanggal</td>
            <td width="7%" class="bg-table-header">Hadir</td>
            <td width="7%" class="bg-table-header">Ijin</td>            
            <td width="7%" class="bg-table-header">Sakit</td>
            <td width="7%" class="bg-table-header">Alpa</td>
            <td width="7%" class="bg-table-header">Cuti</td>      
            <td width="*" class="bg-table-header">Keterangan</td>      
        </tr>

<?php
        $sql = "SELECT DAY(p.tanggal1), MONTH(p.tanggal1), YEAR(p.tanggal1), 
                       DAY(p.tanggal2), MONTH(p.tanggal2), YEAR(p.tanggal2), 
                       ph.hadir, ph.ijin, ph.sakit, ph.alpa, ph.cuti, ph.keterangan, s.nama, 
                       m.semester, k.kelas, m.departemen 
                  FROM jbsakad.presensiharian p, jbsakad.phsiswa ph, jbsakad.siswa s, jbsakad.semester m, jbsakad.kelas k 
                 WHERE ph.idpresensi = p.replid 
                   AND ph.nis = s.nis 
                   AND ph.nis = '$nis' 
                   AND p.idsemester = m.replid 
                   AND p.idkelas = k.replid 
                   AND (((p.tanggal1 BETWEEN '$tglAwal' AND '$tglAkhir') OR (p.tanggal2 BETWEEN '$tglAwal' AND '$tglAkhir')) OR 
                        (('$tglAwal' BETWEEN p.tanggal1 AND p.tanggal2) OR ('$tglAkhir' BETWEEN p.tanggal1 AND p.tanggal2))) 
                 ORDER BY p.tanggal1";
        $res = $db->QueryDb($sql);

        $cnt = 0;
	    $h = 0;
	    $i = 0;
	    $s = 0;
	    $a = 0;
	    $c = 0;

        $cnt = 0;
        while($row = mysqli_fetch_row($res))
        {
            $tgl1 = $row[0].' '. NamaBulan($row[1]).' '.$row[2];
            $tgl2 = $row[3].' '. NamaBulan($row[4]).' '.$row[5];

            $h += $row[6];
            $i += $row[7];
            $s += $row[8];
            $a += $row[9];
            $c += $row[10];
            
            echo "<tr height='25'>";
            echo "<td align='center' class='bg-table-number-column'>" . ++$cnt. "</td>";
            echo "<td align='left'><b>" . $tgl1.' &mdash; '.$tgl2. "</b><br>" . $row[14] . "<br>" . $row[13] . "</td>";
            echo "<td align='center' class='ff-courier fs-14'>" . $row[6]. "</td>";
            echo "<td align='center' class='ff-courier fs-14'>" . $row[7]. "</td>";
            echo "<td align='center' class='ff-courier fs-14'>" . $row[8]. "</td>";
            echo "<td align='center' class='ff-courier fs-14'>" . $row[9]. "</td>";
            echo "<td align='center' class='ff-courier fs-14'>" . $row[10]. "</td>";
            echo "<td>" . $row[11]. "</td>";
            echo "</tr>";
        }
        echo "<tr height='25'>";
        echo "<td colspan='2' align='right' class='bg-gray-100'><b>Total</b></td>";
        echo "<td align='center' class='ff-courier fs-14 bg-gray-100'><b>" . $h. "</b></td>";
        echo "<td align='center' class='ff-courier fs-14 bg-gray-100'><b>" . $i. "</b></td>";
        echo "<td align='center' class='ff-courier fs-14 bg-gray-100'><b>" . $s. "</b></td>";
        echo "<td align='center' class='ff-courier fs-14 bg-gray-100'><b>" . $a. "</b></td>";
        echo "<td align='center' class='ff-courier fs-14 bg-gray-100'><b>" . $c. "</b></td>";
        echo "<td class='bg-gray-100'></td>";
        echo "</tr>";
        echo "</table>";
?>
    

    </td>
</tr>
</table>



</body>
</html>