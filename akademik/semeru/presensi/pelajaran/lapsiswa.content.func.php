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
function Percentage($data, $total)
{
  if ($total == 0)
    return "0%";

  return round(($data / $total * 100), 2) . "%";
}

function ShowLaporanPresensiPelajaranSiswa($db)
{
    global $nis, $nama, $tglAwal, $tglAkhir;

    echo "<div style='overflow: auto; width: 100%; height: 400px;'>";

    echo "<div id='dvTableContent'>";
    echo "<table class='tab tabShadow' id='table' width='95%' align='center'>";
    echo "<tr height='30' class='header'>";
    echo "<td width='5%' align='center'>No</td>";
    echo "<td width='7%' align='center'>Status</td>";
    echo "<td width='12%' align='center'>Tanggal/Jam</td>";
    echo "<td width='15%' align='center'>Pelajaran/Kelas</td>";
    echo "<td width='15%' align='center'>Guru</td>";
    echo "<td width='*' align='center'>Materi/Catatan</td>";
    echo "</tr>";

    $lsColor = ["#d0eefc", "#caf1d4", "#ecd9ea", "#e9caca", "#ebe2ce"];
    $lsName = ["Hadir", "Ijin", "Sakit", "Alpa", "Cuti"];
    $lsTotal = [0, 0, 0, 0, 0];

    $cnt = 0;

    $nHadir = 0;
    $nTidakHadir = 0;
    $nTotal = 0;

    $sql = "SELECT k.kelas, DATE_FORMAT(p.tanggal, '%Y-%m-%d') AS ftanggal, p.jam, pp.statushadir, 
                   pp.catatan, l.nama AS namapel, g.nama AS namaguru, g.nip, p.materi, pp.replid 
              FROM jbsakad.presensipelajaran p, jbsakad.ppsiswa pp, jbssdm.pegawai g, jbsakad.kelas k, jbsakad.pelajaran l 
             WHERE pp.idpp = p.replid 
               AND p.idkelas = k.replid 
               AND p.idpelajaran = l.replid 
               AND p.gurupelajaran = g.nip 
               AND pp.nis = '$nis' 
               AND p.tanggal BETWEEN '$tglAwal' AND '$tglAkhir' 
             ORDER BY p.tanggal";
    $res = $db->QueryDb($sql);
    while ($row = mysqli_fetch_assoc($res))
    {
        $cnt += 1;

        $statusHadir = $row["statushadir"];
        $statusName = $lsName[$statusHadir];
        $bgColor = $lsColor[$statusHadir];
        $lsTotal[$statusHadir] += 1;

        $nTotal += 1;
        if ($statusHadir == 0) 
          $nHadir += 1;
        else 
          $nTidakHadir += 1;

        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>$cnt</td>";
        echo "<td align='center' style='background-color:$bgColor;'>$statusName</td>";
        echo "<td align='center'>" . LongDateFormat($row["ftanggal"]) . "<br>" . $row["jam"] . "</td>";
        echo "<td align='left'>" . $row["namapel"] . "<br>" . $row["kelas"] . "</td>";
        echo "<td align='left'>" . $row["namaguru"] . "<br>" . $row["nip"] . "</td>";
        echo "<td align='left'><b>Materi</b>: " . $row["materi"] . "<br>";
        echo "<b>Catatan</b>: " . $row["catatan"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>"; // dvTableContent

    echo "</div>"; // scroll

    echo "<br>";

    if ($nTotal == 0)
        return;
    
    echo "<table border='0' width='85%' align='center'>";
    echo "<tr>";
    echo "<td width='25%' align='left' valign='top'>";

    echo "<div id='dvTableRekap'>";
    echo "<table class='tab tabShadow' id='tablestat' width='90%'>";
    echo "<tr>";
    echo "<td style='width:100px'>Jumlah Data</td>";
    echo "<td style='width:60px' align='center'>" . $nTotal . "</td>";
    echo "<td style='width:60px' align='center'></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Kehadiran</td>";
    echo "<td align='center'>" . $nHadir . "</td>";
    echo "<td align='center'>" . Percentage($nHadir, $nTotal) . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Ketidakhadiran</td>";
    echo "<td align='center'>" . $nTidakHadir . "</td>";
    echo "<td align='center'>" . Percentage($nTidakHadir, $nTotal) . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Hadir</td>";
    echo "<td align='center'>" . $lsTotal[0]   . "</td>";
    echo "<td align='center'>" . Percentage($lsTotal[0], $nTotal) . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Ijin</td>";
    echo "<td align='center'>" . $lsTotal[1] . "</td>";
    echo "<td align='center'>" . Percentage($lsTotal[1], $nTotal) . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Sakit</td>";
    echo "<td align='center'>" . $lsTotal[2] . "</td>";
    echo "<td align='center'>" . Percentage($lsTotal[2], $nTotal) . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Alpa</td>";
    echo "<td align='center'>" . $lsTotal[3] . "</td>";
    echo "<td align='center'>" . Percentage($lsTotal[3], $nTotal) . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Cuti</td>";
    echo "<td align='center'>" . $lsTotal[4] . "</td>";
    echo "<td align='center'>" . Percentage($lsTotal[4], $nTotal) . "</td>";
    echo "</tr>";
    echo "</table>";
    echo "</div>";

    echo "</td>";
    echo "<td width='&' align='center' >";

    $chartData[] = "barchart";
    $chartData[] = "Statistik Presensi Pelajaran $nama ($nis)";
    $chartData[] = "Jenis";
    $chartData[] = "Jumlah";
    $chartData[] = $lsTotal;
    $chartData[] = $lsName;

    $data =  base64_encode(json_encode($chartData));
    echo "<div id='dvBarChart' style='width: 100%; text-align: center'>";
    echo "<img src='../../library/barchart.factory.php?data=$data'>";    
    echo "</div>";

    echo "</td>";
    echo "</table>";
}
?>