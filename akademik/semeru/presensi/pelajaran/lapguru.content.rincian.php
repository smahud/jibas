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
require_once('../../include/sessionchecker.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../library/common.func.php');
require_once('../../include/db.onfunc.php');
require_once('../../include/getheader2.php');
require_once('../../include/errorhandler.php');

$db = new Db();
$db->TryOpenExit();

$idPresensi = RequestData("idpresensi", 0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../../style/style.css">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rincian Presensi Pelajaran</title>
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
</head>
<body>

<?php
$sql = "SELECT p.replid, p.gurupelajaran, p.keterangan, p.materi, p.objektif, p.refleksi, p.rencana, p.keterlambatan, 
               p.jumlahjam, p.jenisguru, p.idkelas, p.idsemester, p.idpelajaran, p.tanggal, g.nama, k.idtahunajaran, p.jam,
               sg.status AS statusguru
          FROM jbsakad.presensipelajaran p, jbssdm.pegawai g, jbsakad.kelas k, jbsakad.statusguru sg 
         WHERE p.replid = '$idPresensi' 
           AND g.nip = p.gurupelajaran 
           AND sg.replid = p.jenisguru
           AND p.idkelas = k.replid";	   	
$idPresensi = 0;
$nip = "";
$nama = "";
$keterangan = "";
$refleksi = "";
$materi = "";
$statusGuru = "";
$jumlahJam = 0;
$keterlambatan = 0;

$res = $db->QueryDb($sql);
if ($row = mysqli_fetch_assoc($res)) 
{
    $idPresensi = $row["replid"];
    $nip = $row["gurupelajaran"];
    $nama = $row["nama"];
    $keterangan = $row["keterangan"];
    $refleksi = $row["refleksi"];
    $materi = $row["materi"];
    $isStatusGuru = $row["jenisguru"];
    $jumlahJam = $row["jumlahjam"];
    $keterlambatan = $row["keterlambatan"];
    $statusGuru = $row["statusguru"];

    $sqlSiswa = "SELECT s.nis, s.nama, s.idkelas, k.kelas, s.aktif 
                   FROM jbsakad.siswa s, jbsakad.ppsiswa p, jbsakad.kelas k 
                  WHERE p.idpp = '$idPresensi' 
                    AND p.nis = s.nis 
                    AND s.idkelas = k.replid 
                  ORDER BY s.nama";
}
else 
{
    $sqlSiswa = "SELECT s.nis, s.nama, s.idkelas, k.kelas, s.aktif 
                   FROM jbsakad.siswa s, jbsakad.kelas k 
                  WHERE s.idkelas = '$idKelas' 
                    AND s.aktif = 1 
                    AND s.alumni = 0 
                    AND k.replid = s.idkelas 
                  ORDER BY s.nama";
}           
?>

<table border="0" cellpadding="5" cellspacing="0" width="95%" align="center">
<tr>
    <td style="width: 100px;"><b>Guru</b></td>
    <td>
        <?= "$nama ($nip)" ?>
    </td>
</tr>
<tr>
    <td><b>Status Guru</b></td>
    <td>
        <?= $statusGuru ?>
    </td>
</tr>
<tr>
	<td><b>Jumlah Jam Mengajar</b></td>
    <td>
        <?= $jumlahJam ?> jam
    </td>
</tr>
<tr>
	<td><b>Keterlambatan</b></td>
    <td>
        <?= $keterlambatan ?> menit
    </td>
</tr>
<tr>
	<td><b>Materi</b></td>
    <td>
        <?= $materi ?>
    </td>
</tr>
<tr>
	<td><b>Refleksi</b></td>
    <td>
        <?= $refleksi ?>
    </td>
</tr>
<tr>
	<td><b>Keterangan</b></td>
    <td>
        <?= $keterangan ?>
    </td>
</tr>
</table>
<br>

<?php
echo "<table class='tab tabShadow' id='table' width='95%' align='center'>";
echo "<tr height='30' class='header'>";
echo "<td width='5%' align='center'>No</td>";
echo "<td width='*'>Siswa</td>";
echo "<td width='8%' align='center'>Hadir</td>";
echo "<td width='8%' align='center'>Ijin</td>";
echo "<td width='8%' align='center'>Sakit</td>";
echo "<td width='8%' align='center'>Alpa</td>";
echo "<td width='8%' align='center'>Cuti</td>";
echo "<td width='20%' align='left'>Keterangan</td>";
echo "</tr>";

$cnt = 0;
$res = $db->QueryDb($sqlSiswa);
while($row = mysqli_fetch_array($res))
{
    $cnt += 1;

    $nis = $row["nis"];
    $nama = $row["nama"];

    $idPp = 0;
    $statusHadir = 0;
    $catatan = "";

    $sql = "SELECT replid, statushadir, catatan 
              FROM jbsakad.ppsiswa 
             WHERE idpp = '$idPresensi' 
               AND nis = '$nis'";
    $res2 = $db->QueryDb($sql);
    if ($row2 = mysqli_fetch_array($res2))
    {
        $idPp = $row2["replid"];
        $statusHadir = $row2["statushadir"];
        $catatan = $row2["catatan"];
    }

    $radioName = "status$cnt";

    echo "<tr>";
    echo "<td align='center' class='bg-table-number-column'>" . $cnt . "</td>";
    echo "<td>";
    echo "<b>$nama<b><br><span class='fg-secondary'>$nis</span>";
    echo "</td>";

    $checked = $statusHadir == 0 ? "checked" : "";
    $bgColor = $statusHadir == 0 ? "#d0eefc" : "#ffffff";
    echo "<td align='center' id='row0$cnt' style='background-color: $bgColor; pointer-events: none'><input type='radio' style='transform: scale(1.35)' name='$radioName' value='0' $checked readonly onclick='onStatusChanged($cnt, 0, \"#d0eefc\")'></td>";

    $checked = $statusHadir == 1 ? "checked" : "";
    $bgColor = $statusHadir == 1 ? "#caf1d4" : "#ffffff";
    echo "<td align='center' id='row1$cnt' style='background-color: $bgColor; pointer-events: none'><input type='radio' style='transform: scale(1.35)' name='$radioName' value='1' $checked readonly onclick='onStatusChanged($cnt, 1, \"#caf1d4\")'></td>";

    $checked = $statusHadir == 2 ? "checked" : "";
    $bgColor = $statusHadir == 2 ? "#ecd9ea" : "#ffffff";
    echo "<td align='center' id='row2$cnt' style='background-color: $bgColor; pointer-events: none'><input type='radio' style='transform: scale(1.35)' name='$radioName' value='2' $checked readonly onclick='onStatusChanged($cnt, 2, \"#ecd9ea\")'></td>";

    $checked = $statusHadir == 3 ? "checked" : "";
    $bgColor = $statusHadir == 3 ? "#e9caca" : "#ffffff";
    echo "<td align='center' id='row3$cnt' style='background-color: $bgColor; pointer-events: none'><input type='radio' style='transform: scale(1.35)' name='$radioName' value='3' $checked readonly onclick='onStatusChanged($cnt, 3, \"#e9caca\")'></td>";

    $checked = $statusHadir == 4 ? "checked" : "";
    $bgColor = $statusHadir == 4 ? "#ebe2ce" : "#ffffff";
    echo "<td align='center' id='row4$cnt' style='background-color: $bgColor; pointer-events: none'><input type='radio' style='transform: scale(1.35)' name='$radioName' value='4' $checked readonly onclick='onStatusChanged($cnt, 4, \"#ebe2ce\")'></td>";

    echo "<td align='left'>$catatan</td>";
    echo "</tr>";
}
echo "</table><br>";
echo "<input type='hidden' id='nsiswa' value='$cnt'>";
?>

</body>
</html>