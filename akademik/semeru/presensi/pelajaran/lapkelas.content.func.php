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

function ShowLaporanPresensiPelajaranKelas($db)
{
    global $idKelas, $idSemester, $tglAwal, $tglAkhir, $idPelajaran, $kelas, $pelajaran;

    $sql = "SELECT DISTINCT s.nis, s.nama, s.aktif
              FROM jbsakad.siswa s, jbsakad.presensipelajaran p, jbsakad.ppsiswa pp, jbsakad.kelas k 
             WHERE pp.idpp = p.replid 
               AND pp.nis = s.nis 
               AND s.idkelas = '$idKelas' 
               AND p.idsemester = '$idSemester' 
               AND p.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'";
    if ($idPelajaran != 0)               
        $sql .= " AND p.idpelajaran = '$idPelajaran'";
    $sql .= " ORDER BY s.nama"; 

    echo "<div style='overflow: auto; width: 100%; height: 400px;'>";

    echo "<div id='dvTableContent'>";
    echo "<table class='tab tabShadow' id='table' width='95%' align='center'>";
    echo "<tr height='30' class='header'>";
    echo "<td width='5%' align='center'>No</td>";
    echo "<td width='*' align='center'>Siswa</td>";
    echo "<td width='8%' align='center'>Hadir</td>";
    echo "<td width='8%' align='center'>Izin</td>";
    echo "<td width='8%' align='center'>Sakit</td>";
    echo "<td width='8%' align='center'>Alpa</td>";
    echo "<td width='8%' align='center'>Cuti</td>";
    echo "<td width='8%' align='center'>Tidak Hadir</td>";
    echo "<td width='8%' align='center'>Total</td>";
    echo "</tr>";
    
    $lsColor = ["#d0eefc", "#caf1d4", "#ecd9ea", "#e9caca", "#ebe2ce"];

    $lsJumlahAll = [0, 0, 0, 0, 0];
    $totalJumlahAll = 0;
    $totalTidakHadirAll = 0;
    $totalHadirAll = 0;

    $cnt = 0;;
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        $cnt += 1;

        $nis = $row["nis"];
        $nama = $row["nama"];

        $lsJumlah = [0, 0, 0, 0, 0];
        $subTotalJumlah = 0;
        $subTotalHadir = 0;
        $subTotalTidakHadir = 0;

        $sql = "SELECT pp.statushadir, COUNT(pp.replid)
                  FROM jbsakad.ppsiswa pp, jbsakad.presensipelajaran p 
                 WHERE pp.nis = '$nis' 
                   AND pp.idpp = p.replid 
                   AND p.idkelas = '$idKelas' 
                   AND p.idsemester = '$idSemester' 
                   AND p.tanggal BETWEEN '$tglAwal' AND '$tglAkhir' " ;	
        if ($idPelajaran != 0)               
            $sql .= " AND p.idpelajaran = '$idPelajaran'";
        $sql .= " GROUP BY pp.statushadir"; 
        $res2 = $db->QueryDb($sql);
        while($row2 = mysqli_fetch_row($res2))
        {
            $status = $row2[0];
            $jumlah = $row2[1];

            $lsJumlah[$status] = $jumlah;
            $subTotalJumlah += $jumlah;

            if ($status != 0)
                $subTotalTidakHadir += $jumlah;

            if ($status == 0)
                $subTotalHadir += $jumlah;
        }

        for($i = 0; $i < 5; $i++)
        {
            $lsJumlahAll[$i] += $lsJumlah[$i];
        }
        $totalJumlahAll += $subTotalJumlah;
        $totalTidakHadirAll += $subTotalTidakHadir;

        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>" . $cnt . "</td>";
        echo "<td style='position: relative;'>";
        echo "<b>$nama<b><br><span class='fg-secondary'>$nis</span>";
        echo "<span style='position:absolute; right: 10px; top: 8px;'>";
        echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='showInfoSiswa(\"$nis\")'>&nbsp;&nbsp;";
        echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='showDashboardSiswa(\"$nis\")'>";
        echo "</span>";
        echo "</td>";

        for($i = 0; $i < 5; $i++)
        {
            $jumlah = $lsJumlah[$i];

            $info = "";
            if ($jumlah > 0)
            {
                $bgColor = $lsColor[$i];
                $info = "<b>$jumlah</b><br><span class='fg-secondary'>" . Percentage($jumlah, $subTotalJumlah) . "</span";
            }
            else 
            {
                $bgColor = "transparent";
                $info = "<span class='fg-secondary'>0</span>";
            }
            echo "<td align='center' style='background-color: $bgColor'>";
            echo $info;
            echo "</td>";
        }
        $info = "<b>$subTotalTidakHadir</b><br><span class='fg-secondary'>" . Percentage($subTotalTidakHadir, $subTotalJumlah) . "</span";
        echo "<td align='center'>$info</td>";
        echo "<td align='center'><b>$subTotalJumlah</b></td>";
        echo "</tr>";
    }
    echo "<tr style='height: 35px'>";
    echo "<td colspan='2' align='right' class='bg-gray-100'><b>TOTAL</b></td>";
    for($i = 0; $i < 5; $i++)
    {
        $bgColor = $lsColor[$i];
        $info = "<b>" . $lsJumlahAll[$i] . "</b><br><span class='fg-secondary'>" . Percentage($lsJumlahAll[$i], $totalJumlahAll) . "</span>";
        echo "<td align='center' style='background-color: $bgColor'>";
        echo $info;
        echo "</td>";
    }
    $info = "<b>$totalTidakHadirAll</b><br><span class='fg-secondary'>" . Percentage($totalTidakHadirAll, $totalJumlahAll) . "</span>";
    echo "<td align='center' class='bg-gray-100'>$info</td>";
    echo "<td align='center' class='bg-gray-100'><b>$totalJumlahAll</b></td>";
    echo "</tr>";
    echo "</table>";
    echo "</div>"; //dvTableContent
    echo "</div>"; // div overflow
    echo "<br>";

    $lsName = ["Hadir", "Ijin", "Sakit", "Alpa", "Cuti"];

    $chartData[] = "barchart";
    $chartData[] = "Statistik Presensi Pelajaran $pelajaran kelas $kelas";
    $chartData[] = "Jenis";
    $chartData[] = "Jumlah";
    $chartData[] = $lsJumlahAll;
    $chartData[] = $lsName;

    $data =  base64_encode(json_encode($chartData));
    echo "<div id='dvBarChart' style='width: 100%; text-align: center'>";
    echo "<img src='../../library/barchart.factory.php?data=$data'>";    
    echo "</div>";

}
?>