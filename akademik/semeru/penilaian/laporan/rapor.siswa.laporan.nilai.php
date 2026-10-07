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
echo "<legend><strong>Nilai Rapor</strong></legend>";

$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
          FROM jbsakad.infonap i, jbsakad.nap n, jbsakad.aturannhb a, jbsakad.dasarpenilaian d
         WHERE i.replid = n.idinfo AND n.nis = '$nis' 
           AND i.idsemester = '$idSemester' 
           AND i.idkelas = '$idKelas'
           AND n.idaturan = a.replid 	   
           AND a.dasarpenilaian = d.dasarpenilaian
           AND d.aktif = 1
         ORDER BY d.urutan";
$res = $db->QueryDb($sql);
$i = 0;
while($row = mysqli_fetch_row($res))
{
    $aspekarr[$i++] = array($row[0], $row[1]);
}
$naspek = count($aspekarr);
$colwidth = $naspek == 0 ? "*" : round(50 / $naspek) . "%";

echo "<table width='100%' class='tab' id='table'>";
echo "<tr>";
echo "<td width='5%' rowspan='2' class='bg-table-header'><div align='center'>No</div></td>";
echo "<td width='35%' rowspan='2' class='bg-table-header'><div align='center'>Pelajaran</div></td>";
echo "<td width='10%' rowspan='2' class='bg-table-header'><div align='center'>KKM</div></td>";
for($i = 0; $i < count($aspekarr); $i++)
    echo "<td class='bg-table-header' colspan='2' align='center' width='$colwidth'>" . $aspekarr[$i][1] . "</td>"; 
echo "</tr>";

echo "<tr>";
$colwidth = $naspek == 0 ? "*" : round(50 / (2 * $naspek)) . "%";
for($i = 0; $i < count($aspekarr); $i++)
{
    echo "<td class='bg-table-header' align='center' width='$colwidth'>Nilai</td>";
    echo "<td class='bg-table-header' align='center' width='$colwidth'>Predikat</td>";
}
echo "</tr>";

$sql = "SELECT pel.replid, pel.nama, pel.idkelompok, kpel.kelompok
          FROM jbsakad.ujian uji, jbsakad.nilaiujian niluji, jbsakad.siswa sis, jbsakad.pelajaran pel, jbsakad.kelompokpelajaran kpel 
         WHERE uji.replid = niluji.idujian 
           AND niluji.nis = sis.nis 
           AND uji.idpelajaran = pel.replid 
           AND pel.idkelompok = kpel.replid
           AND uji.idsemester = $idSemester
           AND uji.idkelas = $idKelas
           AND sis.nis = '$nis' 
         GROUP BY kpel.urutan, pel.nama";
$respel = $db->QueryDb($sql);
$previdkpel = 0;
$no = 0;
while($rowpel = mysqli_fetch_row($respel))
{
    $no += 1;

    $idpel = $rowpel[0];
    $nmpel = $rowpel[1];
    $idkpel = $rowpel[2];
    $nmkpel = $rowpel[3];

    if ($idkpel != $previdkpel)
    {
        $previdkpel = $idkpel;
        $colspan = $naspek * 2 + 3;
        echo "<tr style='height: 30px'>
              <td colspan='$colspan' align='left' style='font-size:12px; font-weight: bold; background-color: #ddd'>$nmkpel</td>
              </tr>";
    }

    $sql = "SELECT nilaimin 
            FROM jbsakad.infonap
            WHERE idpelajaran = $idpel
            AND idsemester = $idSemester
            AND idkelas = $idKelas";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $nilaimin = $row[0];

    echo "<tr height='30'>";
    echo "<td align='center'valign='middle' style='background-color: #f5f5f5'>$no</td>";
    echo "<td align='left' valign='middle'>$nmpel</td>";
    echo "<td align='center' valign='middle' style='font-size: 12px'>$nilaimin</td>";

    for($i = 0; $i < count($aspekarr); $i++)
    {
        $asp = $aspekarr[$i][0];

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
        if ($res && mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            $na = $row[0];
            $nh = $row[1];
            $komentar = $row[2];
        }
        echo "<td align='center'valign='middle' style='font-size: 12px'><strong>$na</strong></td>
              <td align='center'valign='middle' style='font-size: 12px'><strong>$nh</strong></td>";

    }
    echo "</tr>";
}

echo "</table>";
echo "</fieldset><br><br>";
?>