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
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/logger.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once("../library/class/jpgraph.php");
require_once("../library/class/jpgraph_bar.php");
require_once("../library/class/jpgraph_line.php");
require_once('statistik.content.func.php');

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Data_Siswa_Statistik.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$departemen = RequestData("departemen", "");
$idProses = RequestData("idproses", 0);
$proses = RequestData("proses", "");
$idKelompok = RequestData("idkelompok", 0);
$kelompok = RequestData("kelompok", "");
$jenisStatistik = RequestData("jenisstatistik", "");
$jenisStatistikText = RequestData("jenisstatistiktext", "");
$label = RequestData("label", "");
$stReplid = RequestData("streplid", "");
?>

<table>
<tr>
    <td>Departemen</td>
    <td><?= $departemen; ?></td>
</tr>
<tr>
    <td>Proses</td>
    <td><?= $proses; ?></td>
</tr>
<tr>
    <td>Kelompok</td>
    <td><?= $kelompok; ?></td>
</tr>
<tr>
    <td>Jenis Statistik</td>
    <td><?= $jenisStatistikText; ?></td>
</tr>
<tr>
    <td>Data</td>
    <td><?= $label; ?></td>
</tr>
</table>

<?php

$db = new Db();
$db->TryOpenExit();

echo "<table id='tableExcel' class='tab tabShadow' width='100%'>";
echo "<tr>";
echo "<td>No</td>";
echo "<td>NIS</td>";
echo "<td>Nama</td>";
echo "<td>Panggilan</td>";
echo "<td>Status</td>";
echo "<td>Departemen</td>";
echo "<td>Tingkat</td>";
echo "<td>Kelompok</td>";
echo "</tr>";

$sql = "SELECT s.replid, nopendaftaran, nama, s.aktif, 
               IFNULL(panggilan, '') AS fpanggilan,
               p.departemen, p.proses, k.kelompok
          FROM jbsakad.calonsiswa s, jbsakad.prosespenerimaansiswa p, jbsakad.kelompokcalonsiswa k
         WHERE s.idkelompok = k.replid
           AND k.idproses = p.replid 
           AND s.replid IN ($stReplid)";
$res = $db->QueryDb($sql);

$no = 0;
while($row = mysqli_fetch_assoc($res))
{
    $no += 1;

    $replid = $row['replid'];

    echo "<tr>";
    echo "<td align='center' class='numberColumn'>$no</td>";
    echo "<td>" . $row['nis'] . "</td>";
    echo "<td>" . $row['nama'] . "</td>";
    echo "<td>" . $row['fpanggilan'] . "</td>";
    echo "<td>" . ($row['aktif'] == 1 ? "Aktif" : "Tidak Aktif") . "</td>";
    echo "<td>" . $row['departemen'] . "</td>";
    echo "<td>" . $row['proses'] . "</td>";
    echo "<td>" . $row['kelompok'] . "</td>";
    echo "</tr>";
}

echo "</table>";

?>