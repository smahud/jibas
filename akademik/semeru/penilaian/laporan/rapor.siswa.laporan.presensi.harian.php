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
echo "<fieldset class='tab tabShadow' style='padding: 15px; border-radius: 10px;'>";
echo "<legend><strong>Presensi Harian</strong></legend>";

echo "<table width='100%' class='tab' id='table'>";
echo "<tr>";
echo "<td colspan='2' align='center' class='bg-table-header fst-bold'>Hadir</td>";
echo "<td colspan='2' align='center' class='bg-table-header fst-bold'>Sakit</td>";
echo "<td colspan='2' align='center' class='bg-table-header fst-bold'>Ijin</td>";
echo "<td colspan='2' align='center' class='bg-table-header fst-bold'>Alpa</td>";
echo "<td colspan='2' align='center' class='bg-table-header fst-bold'>Cuti</td>";
echo "</tr>";
echo "<tr>";
echo "<td align='center' class='bg-table-header'>Jumlah</div></td>";
echo "<td align='center' class='bg-table-header'>%</div></td>";
echo "<td align='center' class='bg-table-header'>Jumlah</div></td>";
echo "<td align='center' class='bg-table-header'>%</div></td>";
echo "<td align='center' class='bg-table-header'>Jumlah</div></td>";
echo "<td align='center' class='bg-table-header'>%</div></td>";
echo "<td align='center' class='bg-table-header'>Jumlah</div></td>";
echo "<td align='center' class='bg-table-header'>%</div></td>";
echo "<td align='center' class='bg-table-header'>Jumlah</div></td>";
echo "<td align='center' class='bg-table-header'>%</div></td>";
echo "</tr>";

$sql = "SELECT SUM(ph.hadir) as hadir, SUM(ph.ijin) as ijin, SUM(ph.sakit) as sakit, SUM(ph.cuti) as cuti, SUM(ph.alpa) as alpa, SUM(ph.hadir+ph.sakit+ph.ijin+ph.alpa+ph.cuti) as tot 
          FROM jbsakad.presensiharian p, jbsakad.phsiswa ph, jbsakad.siswa s 
         WHERE ph.idpresensi = p.replid 
           AND ph.nis = s.nis 
           AND ph.nis = '$nis' 
           AND ((p.tanggal1 BETWEEN '$tglAwal' AND '$tglAkhir') 
            OR (p.tanggal2 BETWEEN '$tglAwal' AND '$tglAkhir'))
         ORDER BY p.tanggal1";
$res = $db->QueryDb($sql);
$row = mysqli_fetch_array($res);
$hadir = $row['hadir'];
$sakit = $row['sakit'];
$ijin = $row['ijin'];
$alpa = $row['alpa'];
$cuti = $row['cuti'];
$all = $row['tot'];

if ($hadir != 0 && $all != 0)
    $p_hadir = $hadir / $all * 100;

if ($sakit != 0 && $all != 0)
    $p_sakit = $sakit / $all * 100;

if ($ijin != 0 && $all != 0)
    $p_ijin = $ijin / $all * 100;

if ($alpa != 0 && $all != 0)
    $p_alpa = $alpa / $all * 100;

if ($cuti != 0 && $all != 0)
    $p_cuti = $cuti / $all * 100;

echo "<tr>";
echo "<td height='25' align='center'>$hadir</td>";
echo "<td height='25' align='center'>" . round($p_hadir, 2) . "&nbsp;%</td>";
echo "<td height='25' align='center'>$sakit</td>";
echo "<td height='25' align='center'>" . round($p_sakit, 2) . "&nbsp;%</td>";
echo "<td height='25' align='center'>$ijin</td>";
echo "<td height='25' align='center'>" . round($p_ijin, 2) . "&nbsp;%</td>";
echo "<td height='25' align='center'>$alpa</td>";
echo "<td height='25' align='center'>" . round($p_alpa, 2) . "&nbsp;%</td>";
echo "<td height='25' align='center'>$cuti</td>";
echo "<td height='25' align='center'>" . round($p_cuti, 2) . "&nbsp;%</td>";
echo "</tr>";
echo "</table>";

echo "</fieldset><br><br>";
?>