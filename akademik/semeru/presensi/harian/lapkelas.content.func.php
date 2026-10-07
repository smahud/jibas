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
function FormatNumber($value, $total)
{
    if ($value == 0)
        return "";

    if ($total == 0)
        return "<b>$value</b>";

    $pct = round(($value / $total * 100), 2);

    return "<b>$value</b><br><span class='fg-secondary'>$pct %</span>";
}

function ShowLaporanPresensiHarianKelas($db)
{
    global $idKelas, $idSemester, $tglAwal, $tglAkhir, $kelas;

    $sql = "SELECT s.nis, s.nama, SUM(ph.hadir) AS hadir, SUM(ph.ijin) AS ijin, 
                   SUM(ph.sakit) AS sakit, SUM(ph.alpa) AS alpa, SUM(ph.cuti) AS cuti, s.idkelas, s.aktif 
              FROM jbsakad.siswa s 
              LEFT JOIN (jbsakad.phsiswa ph INNER JOIN jbsakad.presensiharian p ON p.replid = ph.idpresensi) ON ph.nis = s.nis 
             WHERE s.idkelas = '$idKelas' 
               AND p.idsemester = '$idSemester' 
               AND (((p.tanggal1 BETWEEN '$tglAwal' AND '$tglAkhir') OR (p.tanggal2 BETWEEN '$tglAwal' AND '$tglAkhir')) OR 
                    (('$tglAwal' BETWEEN p.tanggal1 AND p.tanggal2) OR ('$tglAkhir' BETWEEN p.tanggal1 AND p.tanggal2))) 
            GROUP BY s.nis 
            ORDER BY s.nama";

    echo "<div style='overflow: auto; width: 100%; height: 450px;'>";

    echo "<div id='dvTableContent'>";

    echo "<table class='tab tabShadow' id='table' width='100%' align='center'>";
    echo "<tr height='30' class='header'>";
    echo "<td width='4%' align='center'>No</td>";
    echo "<td width='*'>Siswa</td>";
    echo "<td width='10%' align='center'>Hadir</td>";
    echo "<td width='10%' align='center'>Ijin</td>";
    echo "<td width='10%' align='center'>Sakit</td>";
    echo "<td width='10%' align='center'>Alpa</td>";
    echo "<td width='10%' align='center'>Cuti</td>";
    echo "<td width='10%' align='center'>Total</td>";
    echo "<td width='10%' align='center' class='hide-in-report'>Detail</td>";
    echo "</tr>";

    $totHadir = 0;
    $totIjin = 0;
    $totSakit = 0;
    $totAlpa = 0;
    $totCuti = 0;
    $totJumlah = 0;

    $cnt = 0;
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        $cnt += 1;

        $jumlah = $row['hadir'] + $row['ijin'] + $row['sakit'] + $row['alpa'] + $row['cuti'];
        
        $totHadir += $row['hadir'];
        $totIjin += $row['ijin'];
        $totSakit += $row['sakit'];
        $totAlpa += $row['alpa'];
        $totCuti += $row['cuti'];
        $totJumlah += $jumlah;

        $nis = $row['nis'];
        $nama = $row['nama'];

        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>" . $cnt . "</td>";
        echo "<td style='position: relative;'>";
        echo "<b>$nama<b><br><span class='fg-secondary'>$nis</span>";
        echo "<span style='position:absolute; right: 10px; top: 8px;'>";
        echo "<img src='../../images/ico/lihat.png' class='cur-hand hide-in-report' onclick='showInfoSiswa(\"$nis\")'>&nbsp;&nbsp;";
        echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='showDashboardSiswa(\"$nis\")'>";
        echo "</span>";
        echo "</td>";

        echo "<td align='center' class='fs-12 ff-courier'>" . FormatNumber($row['hadir'], $jumlah) . "</td>";

        echo "<td align='center' class='fs-12 ff-courier'>" . FormatNumber($row['ijin'], $jumlah) . "</td>";

        echo "<td align='center' class='fs-12 ff-courier'>" . FormatNumber($row['sakit'], $jumlah) . "</td>";

        echo "<td align='center' class='fs-12 ff-courier'>" . FormatNumber($row['alpa'], $jumlah) . "</td>";

        echo "<td align='center' class='fs-12 ff-courier'>" . FormatNumber($row['cuti'], $jumlah) . "</td>";

        echo "<td align='center' class='fs-12 ff-courier'><b>" . $jumlah . "</b></td>";

        echo "<td align='center' class='hide-in-report'><img src='../../images/ico/more.png' class='cur-hand' title='Detail' onclick='showRincian(\"$nis\", \"$nama\")'/></td>";
        echo "</tr>";
    }
    echo "<tr height='30'>";
    echo "<td colspan='2' class='bg-gray-100' align='right'><b>Total</b></td>";
    echo "<td class='bg-gray-100 fs-14 ff-courier' align='center'>" . FormatNumber($totHadir, $totJumlah) . "</td>";
    echo "<td class='bg-gray-100 fs-14 ff-courier' align='center'>" . FormatNumber($totIjin, $totJumlah) . "</td>";
    echo "<td class='bg-gray-100 fs-14 ff-courier' align='center'>" . FormatNumber($totSakit, $totJumlah) . "</td>";
    echo "<td class='bg-gray-100 fs-14 ff-courier' align='center'>" . FormatNumber($totAlpa, $totJumlah) . "</td>";
    echo "<td class='bg-gray-100 fs-14 ff-courier' align='center'>" . FormatNumber($totCuti, $totJumlah) . "</td>";
    echo "<td class='bg-gray-100 fs-14 ff-courier' align='center'><b>" . $totJumlah . "</b></td>";
    echo "<td class='bg-gray-100 hide-in-report' align='center'>&nbsp;</td>";
    echo "</tr>";
    echo "</table>";
    echo "</div>"; // dvTableContent
    echo "</div>"; // dvScrol

    $values = [ $totHadir, $totIjin, $totSakit, $totAlpa, $totCuti];
    $labels = [ "Hadir", "Ijin", "Sakit", "Alpa", "Cuti"];
        
    $chartData[] = "barchart";
    $chartData[] = "Statistik Presensi Kelas $kelas";
    $chartData[] = "Jenis";
    $chartData[] = "Jumlah";
    $chartData[] = $values;
    $chartData[] = $labels;

    $data =  base64_encode(json_encode($chartData));
    echo "<div style='width: 100%; text-align: center'>";
    echo "<img src='../../library/barchart.factory.php?data=$data'>";    
    echo "</div>";
}
?>
