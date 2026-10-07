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
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../library/common.func.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/departemen.php');
require_once('../../library/msg.php');
require_once('../../library/qsbuilder.php');
require_once('../../util/peek.php');
require_once('../../include/errorhandler.php');

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idSemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");
$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");    

$db = new Db;
$db->TryOpenExit(true);

header('Content-Type: application/vnd.ms-excel'); 
header('Content-Type: application/x-msexcel'); 
header('Content-Disposition: attachment; filename=Lap_Legger_Nilai.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Legger Nilai</title>
</head>
<body>

<table border="0">
<tr>
    <td colspan="2" align="left"><h3>Laporan Legger Nilai</h3></td>    
</tr>
<tr>
    <td align="left">Departemen:</td>
    <td align="left"><?=$departemen?></td>
</tr>
<tr>
    <td align="left">Tahun Ajaran:</td>
    <td align="left"><?=$tahunAjaran?></td>
</tr>
<tr>
    <td align="left">Tingkat:</td>
    <td align="left"><?=$tingkat?></td>
</tr>
<tr>
    <td align="left">Kelas:</td>
    <td align="left"><?=$kelas?></td>
</tr>
<tr>
    <td align="left">Semester:</td>
    <td align="left"><?=$semester?></td>
</tr>
<tr>
    <td align="left">Pelajaran:</td>
    <td align="left"><?=$pelajaran?></td>
</tr>
</table>
<br>

<?php
$info = array();
$sql = "SELECT DISTINCT u.idjenis, j.jenisujian
          FROM jbsakad.ujian u, jbsakad.jenisujian j
         WHERE u.idjenis = j.replid
           AND u.idpelajaran = '$idPelajaran'
           AND u.idkelas = '$idKelas'
           AND u.idsemester = '$idSemester'
         ORDER BY j.jenisujian";
$res = $db->QueryDb($sql);
$njenis = 0;
while($row = mysqli_fetch_row($res))
{
    $idjenis = $row[0];
    $namajenis = $row[1];
    
    $sql = "SELECT replid, DAY(tanggal), MONTH(tanggal)
              FROM jbsakad.ujian
             WHERE idpelajaran = '$idPelajaran'
               AND idkelas = '$idKelas'
               AND idsemester = '$idSemester'
               AND idjenis = '$idjenis'
             ORDER BY tanggal";
    $res2 = $db->QueryDb($sql);
    $nujian = mysqli_num_rows($res2);
    $idujian = "";
    $tglujian = "";
    while($row2 = mysqli_fetch_row($res2))
    {
        if ($idujian != "")
            $idujian .= "#";
        $idujian .= $row2[0];
        
        if ($tglujian != "")
            $tglujian .= "#";
        $tglujian .= $row2[1] . "/" . $row2[2];    
    }
    
    $info[$njenis][0] = $idjenis;
    $info[$njenis][1] = $namajenis;
    $info[$njenis][2] = $nujian;
    $info[$njenis][3] = $idujian;
    $info[$njenis][4] = $tglujian;
    
    $njenis += 1;
}

$sql = "SELECT aktif
          FROM jbsakad.tahunajaran
         WHERE replid = '$idTahunAjaran'";
$res = $db->QueryDb($sql);
$row = mysqli_fetch_row($res);
$ta_aktif = (int)$row[0];

if ($ta_aktif == 0)
    $sql = "SELECT r.nis, s.nama
              FROM jbsakad.riwayatkelassiswa r, jbsakad.siswa s
             WHERE r.nis = s.nis
               AND r.idkelas = '$idKelas'
             ORDER BY nama";
else
    $sql = "SELECT nis, nama
              FROM jbsakad.siswa
             WHERE idkelas = '$idKelas'
               AND aktif = 1
             ORDER BY nama";

$res = $db->QueryDb($sql);
$siswa = array();
$nsiswa = 0;
while($row = mysqli_fetch_row($res))
{
    $siswa[$nsiswa][0] = $row[0];
    $siswa[$nsiswa][1] = $row[1];
    $nsiswa += 1;
}

$allwidth = 30 + 300;
for($i = 0; $i < $njenis; $i++)
{
    $nujian = $info[$i][2];
    $allwidth += $nujian * 60;
}

echo "<table width='$allwidth'>";
echo "<tr>";
echo "<td width='30' rowspan='2' align='center'>No</td>";
echo "<td width='100' rowspan='2'>NIS</td>";
echo "<td width='200' rowspan='2'>Nama</td>";
for($i = 0; $i < $njenis; $i++)
{
    $namajenis = $info[$i][1];
    $nujian = $info[$i][2];
    $width = 60 * $nujian; 
    echo "<td width='$width' colspan='$nujian' align='center' >$namajenis</td>";
}
echo "</tr>";
echo "<tr>";
for($i = 0; $i < $njenis; $i++)
{
    $nujian = $info[$i][2];
    $tglujian = $info[$i][4];
    $arrtgl = explode("#", $tglujian);
    for($j = 0; $j < count($arrtgl); $j++)
    {
        echo "<td width='60' align='center'>" . $arrtgl[$j]. "</td>";
    }
}
echo "</tr>";

$rerata = [];
for($j = 0; $j < $njenis; $j++)
{
    for($u = 0; $u < $nujian; $u++)
    {
        $pos = $j + $u;
        $rerata[] = [0, 0];
    }
}

for($s = 0; $s < $nsiswa; $s++)
{
    $no = $s + 1;
    $nis = $siswa[$s][0];
    $nama = $siswa[$s][1];
    
    echo "<tr height='25'>";
    echo "<td align='center'>$no</td>";
    echo "<td align='left'>$nis</td>";
    echo "<td align='left'>$nama</td>";
    
    for($j = 0; $j < $njenis; $j++)
    {
        $idujian = $info[$j][3];
        $arrujian = explode("#", $idujian);
        $nujian = count($arrujian);
        for($u = 0; $u < $nujian; $u++)
        {
            $id = $arrujian[$u];
            $sql = "SELECT nilaiujian
                      FROM jbsakad.nilaiujian
                     WHERE nis = '$nis'
                       AND idujian = '$id'";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
            {
                $row = mysqli_fetch_row($res);
                echo "<td align='center'>$row[0]</td>";

                $pos = $j + $u;
                $rerata[$pos][0] += $row[0];
                $rerata[$pos][1] += 1;
            }
            else
            {
                echo "<td align='center'>-</td>";
            }
        }
    }
    echo "</tr>";
}

echo "<tr style='height: 35px;'>";
echo "<td colspan='3' align='right'><b>Rerata</b></td>";
for($i = 0; $i < $njenis; $i++)
{
    $nujian = $info[$i][2];
    for($j = 0; $j < $nujian; $j++)
    {
        $pos = $i + $j;

        if ($rerata[$pos][1] > 0)
        {
            $rr = round($rerata[$pos][0] / $rerata[$pos][1], 2);
            echo "<td align='center'><b>$rr</b></td>";
        }
        else
        {
            echo "<td align='center'><b>-</b></td>";
        }
    }
}
echo "</tr>";
echo "</table>";
?>

</body>
</html>