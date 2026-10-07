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

function ShowSelectAspekPenilaian($db)
{
    global $idPelajaran, $idSemester, $nis, $kodeAspek;

    $lsAspek = [];
    $sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
              FROM jbsakad.ujian u, jbsakad.nilaiujian n, jbsakad.aturannhb a, jbsakad.dasarpenilaian d
             WHERE u.replid = n.idujian
               AND n.nis = '$nis'
               AND u.idpelajaran = '$idPelajaran'
               AND u.idsemester = '$idSemester'
               AND u.idaturan = a.replid
               AND a.dasarpenilaian = d.dasarpenilaian
             ORDER BY d.keterangan;";	  	
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $lsAspek[] = [ $row[0], $row[1] ];
    }

    echo "<select class='inputbox fs-14' id='aspek' style='width:250px; background-color: #fdfcc9ff;' onchange='onChangeAspek()'>";
    for($i = 0; $i < count($lsAspek); $i++)
    {
        $kode = $lsAspek[$i][0];
        $keterangan = $lsAspek[$i][1];

        if ($kodeAspek == "")
            $kodeAspek = $kode;

        $sel = $kode == $kodeAspek ? "selected" : "";
        echo "<option value='$kode' $sel>$keterangan</option>";
    }
    echo "</select>";
}

function ShowRiwayatNilaiSiswa($db)
{
    global $idPelajaran, $idTingkat, $kodeAspek, $idKelas, $idSemester, $nis;
    
    $lsJenisUjian = [];
    $sql = "SELECT j.replid, j.jenisujian, a.replid 
              FROM jbsakad.aturannhb a, jbsakad.jenisujian j 
             WHERE a.idpelajaran='$idPelajaran' 
               AND a.dasarpenilaian='$kodeAspek'
               AND a.idjenisujian=j.replid 
               AND a.idtingkat='$idTingkat' 
               AND a.aktif = 1
             ORDER BY j.urutan, j.jenisujian";

    $res = $db->QueryDb($sql);
    while ($row = mysqli_fetch_row($res))
    {
        $lsJenisUjian[] = [ $row[0], $row[1], $row[2] ];
    }

    for($i = 0; $i < count($lsJenisUjian); $i++)
    {
        $idJenisUjian = $lsJenisUjian[$i][0];
        $jenisUjian = $lsJenisUjian[$i][1];
        $idAturanNhb = $lsJenisUjian[$i][2];
        
        $sql = "SELECT DATE_FORMAT(u.tanggal, '%d-%b-%Y') AS ftanggal, u.deskripsi, n.nilaiujian, IFNULL(n.keterangan, '') AS keterangan, rk.nilaiRK
                  FROM jbsakad.ujian u, jbsakad.pelajaran p, jbsakad.nilaiujian n, jbsakad.ratauk rk
                 WHERE u.idpelajaran = p.replid 
                   AND u.replid = n.idujian 
                   AND rk.idujian = u.replid
                   AND u.idkelas = '$idKelas'
                   AND u.idpelajaran = '$idPelajaran' 
                   AND u.idsemester = '$idSemester'
                   AND u.idjenis = '$idJenisUjian' 
                   AND u.idaturan = '$idAturanNhb'
                   AND n.nis = '$nis'
                ORDER BY u.tanggal DESC";
        $res = $db->QueryDb($sql);

        if (mysqli_num_rows($res) == 0)
        {
            echo "<span class='fg-secondary fst-italic'>tidak ada data</span>";
            continue;
        }
    
        echo "<fieldset class='tab tabShadow' style='padding: 15px; border-radius: 10px;'>";
        echo "<legend><b>$jenisUjian</b></legend>";

        echo "<table class='tab tabShadow' id='tableUjian$i' border='1' align='left' cellpadding='3'>";
        echo "<tr height='25' align='left'>";
        echo "<td width='20' class='bg-table-header' align='center'>No</td>";
        echo "<td width='250' class='bg-table-header' align='center'>Materi/Tanggal</td>";
        echo "<td width='80' class='bg-table-header' align='center'>Nilai</td>";
        echo "<td width='80' class='bg-table-header' align='center'>Rerata Kelas</td>";
        echo "<td width='80' class='bg-table-header' align='center'>Persentase</td>";
        echo "<td width='200' class='bg-table-header' align='center'>Keterangan</td>";
        echo "</tr>";

        $totalNilai = 0;
        $totalNilaiRk = 0;
        $no = 0;
        while ($row = mysqli_fetch_row($res))
        {
            $no++;
            $tanggal = $row[0];
            $deskripsi = $row[1];
            $nilaiujian = $row[2];
            $keterangan = $row[3];
            $nilaiRK = $row[4];

            $totalNilai += $nilaiujian;
            $totalNilaiRk += $nilaiRK;

            if ($nilaiRK > 0)
                $dev = ($nilaiujian - $nilaiRK) / $nilaiRK;
            else
                $dev = 0;
            $dev = round($dev * 100, 2);
            $colorDev = $dev >= 0 ? "#0000FF" : "#FF0000";
            $mark = $dev >= 0 ? "+" : "";
            $devStr = $mark . $dev . "%";

            echo "<tr>";
            echo "<td align='center' class='bg-table-number-column' >$no</td>";
            echo "<td align='left'>$deskripsi<br><span class='fg-secondary'>$tanggal</span></td>";
            echo "<td align='center' class='fs-14 ff-courier'>$nilaiujian</td>";
            echo "<td align='center' class='fs-14 ff-courier'>$nilaiRK</td>";
            echo "<td align='center' class='fs-14 ff-courier' style='color: $colorDev;'>$devStr</td>";
            echo "<td align='left' class='fs-14'>$keterangan</td>";
            echo "</tr>";
        }

        if ($no > 0)
        {
            $avg = round($totalNilai / $no, 2);
            $avgRk = round($totalNilaiRk / $no, 2);

            if ($avgRk > 0)
                $dev = ($avg - $avgRk) / $avgRk;
            else
                $dev = 0;
            $dev = round($dev * 100, 2);
            $colorDev = $dev >= 0 ? "#0000FF" : "#FF0000";
            $mark = $dev >= 0 ? "+" : "";
            $devStr = $mark . $dev . "%";

            echo "<tr>";
            echo "<td align='right' class='bg-gray-100' colspan='2'><b>Rerata</b></td>";
            echo "<td align='center' class='fs-14 bg-gray-100 ff-courier'>$avg</td>";
            echo "<td align='center' class='fs-14 bg-gray-100 ff-courier'>$avgRk</td>";
            echo "<td align='center' class='fs-14 bg-gray-100 ff-courier' style='color: $colorDev;'>$devStr</td>";
            echo "<td align='left' class='bg-gray-100'></td>";
            echo "</tr>";
        }
        echo "</table>";
    
        echo "</fieldset><br><br>";
    } // end foreach lsJenisUjian

    echo "<input type='hidden' id='njenisujian' value='" . count($lsJenisUjian) . "'>";
}
?>