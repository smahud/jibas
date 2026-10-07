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

function ShowLaporanPresensiHarianSiswa($db)
{
    global $nis, $nama, $tahunawal, $bulanawal, $tanggalawal, $tahunakhir, $bulanakhir, $tanggalakhir;

    try
    {
        $tglawal = "$tahunawal-$bulanawal-$tanggalawal";
        $tglakhir = "$tahunakhir-$bulanakhir-$tanggalakhir";
        
        $sql = "SELECT DAY(p.tanggal1), MONTH(p.tanggal1), YEAR(p.tanggal1), DAY(p.tanggal2), MONTH(p.tanggal2), YEAR(p.tanggal2),
    				   ph.hadir, ph.ijin, ph.sakit, ph.alpa, ph.cuti, ph.keterangan, s.nama, m.semester, k.kelas
	    		  FROM jbsakad.presensiharian p, jbsakad.phsiswa ph, jbsakad.siswa s, jbsakad.semester m, jbsakad.kelas k   
			     WHERE ph.idpresensi = p.replid 
                   AND ph.nis = s.nis 
                   AND ph.nis = '$nis'
			       AND p.idsemester = m.replid 
                   AND p.idkelas = k.replid 
                   AND (((p.tanggal1 BETWEEN '$tglawal' AND '$tglakhir') OR (p.tanggal2 BETWEEN '$tglawal' AND '$tglakhir')) OR 
                        (('$tglawal' BETWEEN p.tanggal1 AND p.tanggal2) OR ('$tglakhir' BETWEEN p.tanggal1 AND p.tanggal2))) 
                 ORDER BY p.tanggal1";
        
        echo "<div style='overflow: auto; width: 100%; height: 450px;'>";
        echo "<div id='dvTableContent'>";

        echo "<table class='tab tabShadow' id='table' width='100%' >";
        echo "<tr align='center' class='header'>";
        echo "<td width='5%'>No</td>";
        echo "<td width='20%'>Tanggal</td>";
        echo "<td width='15%'>Kelas/Semester</td>";
        echo "<td width='7%'>Hadir</td>";
        echo "<td width='7%'>Ijin</td>";
        echo "<td width='7%'>Sakit</td>";
        echo "<td width='7%'>Alpa</td>";
        echo "<td width='7%'>Cuti</td>";
        echo "<td width='7%'>Total</td>";
        echo "<td width='*'>Keterangan</td>";
        echo "</tr>";

        $cnt = 0;
	    $h = 0;
	    $i = 0;
	    $s = 0;
	    $a = 0;
	    $c = 0;
        $totalJumlah = 0;

        $res = $db->QueryDb($sql);
        while ($row = mysqli_fetch_array($res))
        {
            $h += $row[6];
            $i += $row[7];
            $s += $row[8];
            $a += $row[9];
            $c += $row[10];
            $jumlah = $row[6] + $row[7] + $row[8] + $row[9] + $row[10];
            $totalJumlah += $jumlah;

            $tanggal = $row[0] . " " . NamaBulan($row[1]) . " " . $row[2] . " &mdash; " . $row[3] . " " . NamaBulan($row[4]) . " " . $row[5];
            $kelasSemester = $row[14] . " <br> " . $row[13];

            echo "<tr height='25'>";
            echo "<td align='center' class='bg-table-number-column'>" . ++$cnt. "</td>";
            echo "<td align='left'>" . $tanggal. "</td>";
            echo "<td align='left'>" . $kelasSemester. "</td>";
            echo "<td align='center' class='fs-12 ff-courier'>" . FormatNumber($row[6], $jumlah). "</td>";
            echo "<td align='center' class='fs-12 ff-courier'>" . FormatNumber($row[7], $jumlah). "</td>";
            echo "<td align='center' class='fs-12 ff-courier'>" . FormatNumber($row[8], $jumlah). "</td>";
            echo "<td align='center' class='fs-12 ff-courier'>" . FormatNumber($row[9], $jumlah). "</td>";
            echo "<td align='center' class='fs-12 ff-courier'>" . FormatNumber($row[10], $jumlah). "</td>";
            echo "<td align='center' class='fs-12 ff-courier'><b>" . $jumlah. "</b></td>";
            echo "<td class='fs-14'>" . $row[11]. "</td>";
            echo "</tr>";   
        }
        echo "";
        echo "<tr style='height: 35px;'>";
        echo "<td colspan='3' align='right' class='bg-gray-100'><b>Jumlah</b></td>";
        echo "<td align='center' class='fs-14 ff-courier bg-gray-100'>" . FormatNumber($h, $totalJumlah). "</td>";
        echo "<td align='center' class='fs-14 ff-courier bg-gray-100'>" . FormatNumber($i, $totalJumlah). "</td>";
        echo "<td align='center' class='fs-14 ff-courier bg-gray-100'>" . FormatNumber($s, $totalJumlah). "</td>";
        echo "<td align='center' class='fs-14 ff-courier bg-gray-100'>" . FormatNumber($a, $totalJumlah). "</td>";
        echo "<td align='center' class='fs-14 ff-courier bg-gray-100'>" . FormatNumber($c, $totalJumlah). "</td>";
        echo "<td align='center' class='fs-14 ff-courier bg-gray-100'><b>" . $totalJumlah. "</b></td>";
        echo "<td class='fs-14 bg-gray-100'></td>";
        echo "</tr>";
        echo "</table>";
        echo "</div>"; // dvTableContent
        echo "</div>"; // dvScrol
        echo "<br>";

        $values = [ $h, $i, $s, $a, $c];
        $labels = [ "Hadir", "Ijin", "Sakit", "Alpa", "Cuti"];
        
        $chartData[] = "barchart";
        $chartData[] = "Statistik Presensi $nama ($nis)";
        $chartData[] = "Jenis";
        $chartData[] = "Jumlah";
        $chartData[] = $values;
        $chartData[] = $labels;

        $data =  base64_encode(json_encode($chartData));
        echo "<div id='dvBarChart' style='width: 100%; text-align: center'>";
        echo "<img src='../../library/barchart.factory.php?data=$data'>";    
        echo "</div>";
                     
    } 
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
    }
}
?>