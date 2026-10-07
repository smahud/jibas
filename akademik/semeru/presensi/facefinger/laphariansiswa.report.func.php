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

function ShowPresennsiHarianSiswa($db)
{
    global $nis, $nama, $tglAwal, $tglAkhir;

    $sql = "SELECT pk.nis, date_in, time_in, IFNULL(date_out, '') AS fdate_out, 
                   IFNULL(time_out, '') AS ftime_out, source, IF(pk.info1 IS NULL, '', pk.info1) As telat
              FROM jbssat.frpresence pk
             WHERE pk.date_in BETWEEN '$tglAwal' AND '$tglAkhir'
               AND pk.nis = '$nis'
             ORDER BY pk.date_in DESC, pk.time_in ASC";
    $res = $db->QueryDb($sql);
    $nData = mysqli_num_rows($res);
    if ($nData == 0)             
    {
        HintInfo::ShowLeft("Belum ada data presensi harian");
        return;
    }

    echo "<div id='divMenuContent' style='position: relative; width: 100%; line-height: 24px' class='hide-in-report'>";
    echo "<span class='fs-14 fg-secondary' style='margin-left: 5px'>Tanggal " . LongDateFormat($tglAwal) . " s/d " . LongDateFormat($tglAkhir) . " </span><br>";
    echo "<div style='position: absolute; right: 0; top: 50%; transform: translateY(-50%);'>";
    echo "<span class='cur-hand fg-secondary onclick='refresh()'>";
    echo "<img src='../../images/ico/refresh.png' border='0' title='refresh' />&nbsp;refresh";
    echo "</span>&nbsp;&nbsp;";
    echo "<span class='cur-hand fg-secondary' onclick='cetak()'>";
    echo "<img src='../../images/ico/print.png' border='0' title='cetak'/>&nbsp;cetak";
    echo "</span>";
    echo "</div>";
    echo "</div><br>";

    echo "<table id='tabContent' class='tab tabShadow' style='width: 100%' align='left'>";
    echo "<tr>";
    echo "<td width='5%' class='header' align='center'>No</td>";
    echo "<td width='*' class='header' align='left'>Tanggal</td>";
    echo "<td width='12%' class='header' align='center'>Jam Masuk</td>";
    echo "<td width='12%' class='header' align='center'>Telat</td>";
    echo "<td width='12%' class='header' align='center'>Jam Pulang</td>";
    echo "<td width='15%' class='header' align='center'>Sumber</td>";
    echo "</tr>";

    while($row = mysqli_fetch_array($res))
    {
        $no++;

        $source = $row['source'];
        if ($source == "M")
            $source = "Input Manual";
        else if ($source == "FGR" || $source == "F")
            $source = "Fingerprint";
        else if ($source == "FACE" || $source == "W")
            $source = "Wajah";
            
        echo "<tr style='height: 30px'>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td align='left'>";
        echo WeekdayNameFromPhp(date("w", strtotime($row['date_in']))) . ", ";
        echo LongDateFormat($row['date_in']);
        echo "</td>";
        echo "<td align='center'>$row[time_in]</td>";
        echo "<td align='center'>$row[telat]</td>";
        echo "<td align='center'>$row[ftime_out]</td>";
        echo "<td align='center'>$source</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
