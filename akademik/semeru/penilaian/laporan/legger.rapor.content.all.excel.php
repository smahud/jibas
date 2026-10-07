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

header('Content-Type: application/vnd.ms-excel');
header('Content-Type: application/x-msexcel');
header('Content-Disposition: attachment; filename=LeggerRapor.xls');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Legger Nilai Rapor</title>
</head>
<body>

<table border="0">
<tr>
    <td colspan="2" align="left"><h3>Laporan Legger Nilai Rapor</h3></td>    
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
$stidpel = "";
$pelarr = array();

if ($idPelajaran == 0)
{
    $sql = "SELECT DISTINCT p.replid, p.nama
          FROM jbsakad.infonap i, jbsakad.pelajaran p
         WHERE i.idpelajaran = p.replid
           AND i.idsemester = '$idSemester' 
           AND i.idkelas = '$idKelas'
         ORDER BY p.nama";
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $pelarr[] = array($row[0], $row[1]);

        if ($stidpel != "") $stidpel .= ",";
        $stidpel .= $row[0];
    }
    $npel = count($pelarr);
}
else 
{
    $stidpel = $idPelajaran;
    $pelarr[] = array($idPelajaran, $pelajaran);
    $npel = 1;
}

if ($stidpel == "")
{
    echo "<i>Belum ada nilai rapor</i>";
    exit();
}

$aspekarr = array();

$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
          FROM jbsakad.infonap i, jbsakad.nap n, jbsakad.aturannhb a, jbsakad.dasarpenilaian d
         WHERE i.replid = n.idinfo
           AND n.idaturan = a.replid 	   
           AND a.dasarpenilaian = d.dasarpenilaian
           AND i.idpelajaran IN ($stidpel)  
           AND i.idsemester = '$idSemester' 
           AND i.idkelas = '$idKelas'";
$res = $db->QueryDb($sql);
while($row = mysqli_fetch_row($res))
{
    $aspekarr[] = array($row[0], $row[1]);
}
$naspek = count($aspekarr);
$colwidth = $naspek == 0 ? "*" : round(600 / $naspek);

$sql = "SELECT aktif
          FROM jbsakad.tahunajaran
         WHERE replid = '$idTahunAjaran'";
$res = $db->QueryDb($sql);
$row = mysqli_fetch_row($res);
$ta_aktif = (int) $row[0];

if ($ta_aktif == 0)
    $sql = "SELECT r.nis, s.nama
              FROM jbsakad.riwayatkelassiswa r, jbsakad.siswa s
             WHERE r.nis = s.nis
               AND r.idkelas = '$idKelas'
             ORDER BY s.nama";
else
    $sql = "SELECT nis, nama
              FROM jbsakad.siswa
             WHERE idkelas = '$idKelas'
               AND aktif = 1
             ORDER BY nama";
$res = $db->QueryDb($sql);

$siswa = array();
while($row = mysqli_fetch_row($res))
{
    $siswa[] = array($row[0], $row[1]);
}
$nsiswa = count($siswa);

echo "<table width='$allwidth'>";
echo "<tr>";
echo "<td width='30' rowspan='2' align='center'>No</td>";
echo "<td width='100' rowspan='2'>NIS</td>";
echo "<td width='300' rowspan='2'>Nama</td>";
for($i = 0; $i < $naspek; $i++)
{
    echo "<td width='$colwidth' align='center' colspan='2'>" . $aspekarr[$i][1]. "</td>";
}
echo "<td width='100' align='center' rowspan='2'>Rata-Rata<br>Siswa</td>";
echo "</tr>";

$colwidth2 = $colwidth / 2;
echo "<tr>";
for($i = 0; $i < $naspek; $i++)
{
    echo "<td width='$colwidth2' align='center'>Nilai Angka</td>";
    echo "<td width='$colwidth2' align='center'>Nilai Huruf</td>";
}
echo "</tr>";

$npelspan = 3 + 2 * $naspek + 1;
for($p = 0; $p < $npel; $p++)
{
    $idpel = $pelarr[$p][0];
    $nmpel = $pelarr[$p][1];

    // PELAJARAN ROW TITLE
    echo "<tr height='25' >";
    echo "<td align='left' colspan='$npelspan'><strong>$nmpel</strong></td>";
    echo "</tr>";

    $ratapel = array();
    for($j = 0; $j < $naspek; $j++)
    {
        $ratapel[] = array(0, 0); // totna, divna
    }

    $totratasis = 0;
    $ntotratasis = 0;

    $no = 0;
    for($s = 0; $s < $nsiswa; $s++)
    {
        $no += 1;

        $nis = $siswa[$s][0];
        $nama = $siswa[$s][1];

        echo "<tr height='25'>";
        echo "<td align='center'>$no</td>";
        echo "<td align='left'>";
        echo "<b>$nis</b>";
        echo "</td>";
        echo "<td align='left'>";
        echo "<b>$nama</b>";
        echo "</td>";

        $ratasis = 0;
        $nratasis = 0;
        for($j = 0; $j < $naspek; $j++)
        {
            $asp = $aspekarr[$j][0];

            $na = "";
            $nh = "";
            $komentar = "";

            $sql = "SELECT nilaiangka, nilaihuruf, komentar
                      FROM jbsakad.infonap i, jbsakad.nap n, jbsakad.aturannhb a 
                     WHERE i.replid = n.idinfo 
                       AND n.nis = '$nis' 
                       AND i.idpelajaran = '$idpel' 
                       AND i.idsemester = '$idSemester' 
                       AND i.idkelas = '$idKelas'
                       AND n.idaturan = a.replid 	   
                       AND a.dasarpenilaian = '$asp'";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
            {
                $row = mysqli_fetch_row($res);
                $na = $row[0];
                $nh = $row[1];
                $komentar = $row[2];

                $ratasis += $na;
                $nratasis += 1;

                $ratapel[$j][0] += $na;
                $ratapel[$j][1] += 1;
            }
            echo "<td align='center'><strong>$na</strong></td>";
            echo "<td align='center'><strong>$nh</strong></td>";
        }
        $rata = ($nratasis == 0) ? "" : round($ratasis / $nratasis, 2);
        echo "<td align='center'><strong>$rata</strong></td>";
        echo "</tr>";

        if ($nratasis != 0)
        {
            $totratasis += $rata;
            $ntotratasis += 1;
        }
    }

    $valtotratasis = $ntotratasis == 0 ? "" : round($totratasis / $ntotratasis, 2);

    // RATA-RATA PER PELAJARAN
    echo "<tr height='25'>";
    echo "<td colspan='3' align='right'><i><strong>Rata-Rata $nmpel</strong></i></td>";
    for($j = 0; $j < $naspek; $j++)
    {
        $totratapel = $ratapel[$j][0];
        $nratapel = $ratapel[$j][1];
        $valratapel = $nratapel == 0 ? "" : round($totratapel / $nratapel, 2);
        echo "<td align='center'><strong>$valratapel</strong></td>";
        echo "<td align='center'><strong>&nbsp;</strong></td>";
    }
    echo "<td align='center'><strong>$valtotratasis</strong></td>";
    echo "</tr>";

    echo "<tr height='15'>";
    echo "<td colspan='$npelspan'>&nbsp;</td>";
    echo "</tr>";
}

echo "</table>";
?>
</body>
</html>