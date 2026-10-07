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
require_once('../../library/datearith.php');
require_once('../../library/msg.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');

$idKegiatan = RequestData("idkegiatan", 0);
$kegiatan = RequestData("kegiatan", "");
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
<link rel="stylesheet" type="text/css" href="../../style/style.css">
<link rel="stylesheet" type="text/css" href="../../style/colors.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<link href="../../images/jibas.ico" rel="shortcut icon" />
<title>Rincian Presensi Kegiatan Siswa</title>
<script>
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
</script>
</head>

<body>
<center>
    <h3>Rincian Presensi Kegiatan</h3>
</center>    
<table border="0" cellpadding="2" cellpadding="0" width="780" align="left">
<tr>
	<td width='60'>Nama</td>
    <td><?= "$nama - $nis" ?></td>
</tr>
<tr>
    <td>Kegiatan</td>
    <td><?= $kegiatan ?></td>
</tr>
<tr>
	<td align="left" valign="top" colspan="2">
        
    <table class="tab" cellspacing='0' cellpadding='5' width='100%'>
    <tr height='25'>
        <td width='7%' align='center' class='header'>No</td>
        <td width='8%' align='left' class='header'>Hari</td>
        <td width='15%' align='center' class='header'>Tanggal<br>Masuk</td>
        <td width='14%' align='center' class='header'>Jam Masuk</td>
        <td width='14%' align='center' class='header'>Jam Pulang</td>
        <td width='12%' align='center' class='header'>Kehadiran</td>
        <td width='12%' align='center' class='header'>Telat</td>
        <td width='*' align='left' class='header'>Keterangan</td>
    </tr>            
<?php

    $cnt = 0;
    $sql = "SELECT p.replid, DATE_FORMAT(date_in, '%d %b %Y') AS date_in, time_in, DATE_FORMAT(date_out, '%d %b %Y') AS date_out, 
                   IF(p.time_out IS NULL, '-', p.time_out) AS time_out, p.smssent, p.smssenthome, p.description, 
                   DAYOFWEEK(date_in) AS weekday, IF(p.info1 IS NULL, 0, p.info1) AS telat, IF(p.nis IS NULL, 0, 1) AS ownertype
              FROM jbssat.frpresensikegiatan p
             WHERE p.date_in BETWEEN '$tglAwal' AND '$tglAkhir'
               AND p.nis = '$nis' 
               AND p.idkegiatan = $idKegiatan
             ORDER BY date_in DESC";
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        $ti = trim($row["time_in"]);
		$to = trim($row["time_out"]);
		$tomark = "";
		
        if ($row['time_out'] == "-")
        {
            $wd = $row['weekday'];
            
            $sql = "SELECT pulangstd
                      FROM jbssat.frjadwalkegiatan
                     WHERE idkegiatan = $idKegiatan
                       AND hari = $wd";
            $res2 = $db->QueryDb($sql);
            if (mysqli_num_rows($res2) > 0)
            {
                $row2 = mysqli_fetch_row($res2);
                
                $to = $row2[0] . ":00";
                $tomark = " (std)";
            }
            else
            {
                $to = "<font color='red'>NA</font>";
            }
        }
		
		$hadir = "<font color='red'>NA</font>";
		if ($to != "<font color='red'>NA</font>")
		{
			$h = 0;
			$m = 0;
			$s = 0;
			DateArith::TimeDiff($to, $ti, $h, $m, $s);
			
			$hadir = DateArith::ToStringHour($h, $m, $s);
		}
		
		$telat = 0;
		if (0 == (int)$row['telat'])
		{
			// Kalo tidak telat, justru dicek
			
			$wd = $row["weekday"];
			$sql = "SELECT telat
					  FROM jbssat.frjadwalkegiatan
					 WHERE idkegiatan = $idKegiatan
					   AND hari = $wd";
			$res2 = $db->QueryDb($sql);
			if (mysqli_num_rows($res2) > 0)
			{
				$row2 = mysqli_fetch_row($res2);
				$telatt = $row2[0];
				
				$telatm = DateArith::TimeToMinute($telatt);
				$tim = DateArith::TimeToMinute($ti);
				
				if ($tim > $telatm)
					$telat = $tim - $telatm;
			}
		}
		else
		{
			$telat = $row['telat'];
		}
        
        $cnt += 1;
?>
        <tr>
            <td align='center' class='bg-table-number-column'><?= $cnt ?></td>
            <td align='left'><?= DateArith::InaDayName($row['weekday'] - 2) ?></td>
            <td align='center'><?= $row['date_in'] ?></td>
            <td align='center'><?= $ti ?></td>
            <td align='center'><?= $to . $tomark ?></td>
            <td align='center'><?= $hadir ?></td>
            <td align='center'><?= DateArith::ToStringHourFromMinute($telat) ?></td>
            <td align='left'><?= $row['description'] ?>&nbsp;</td>
        </tr>
<?php
    }
?>
    </table>
        
    </td>
</tr>	        
</table>

</body>
</html>