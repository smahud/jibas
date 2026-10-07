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
echo "<legend><strong>Deskripsi Nilai Rapor</strong></legend>";

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

echo "<table width='100%' class='tab' id='table'>";
echo "<tr>";
echo "<td width='5%' class='bg-table-header'><div align='center'>No</div></td>";
echo "<td width='25%' class='bg-table-header'><div align='center'>Pelajaran</div></td>";
echo "<td width='15%' class='bg-table-header'><div align='center'>Aspek</div></td>";
echo "<td width='*' class='bg-table-header'><div align='center'>Deskripsi</div></td>";
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
        echo "<tr style='height: 30px'>
                <td colspan='4' align='left' style='font-size:12px; font-weight: bold; background-color: #ddd'>$nmkpel</td>
              </tr>";
    }

    echo "<tr height='40'>";
    echo "<td align='center' rowspan='$naspek' valign='middle' style='background-color: #f5f5f5;'>$no</td>";
    echo "<td align='left' rowspan='$naspek' valign='middle'>$nmpel</td>";

    $set_tr = false;
    for($i = 0; $i < count($aspekarr); $i++)
    {
        $asp = $aspekarr[$i][0];
        $nmasp = $aspekarr[$i][1];

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
            $komentar = $row[2];
        }

        if ($set_tr)
            echo "<tr height='40'>";

        echo "<td align='left' valign='middle' style='font-size: 12px'>$nmasp</td>
              <td align='left' valign='middle'>$komentar</td>";
        echo "</tr>";

        $set_tr = true;
    }
}
echo "</table>";
echo "</fieldset><br><br>";
?>