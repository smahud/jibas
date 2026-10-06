<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 33.0 (Jan 05, 2026)
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
function ShowNilaiSumbanganCalonSiswa($db)
{
    global $idCalon, $idProses;

    $sql = "SELECT IFNULL(sum1, '') AS fsum1,
                   IFNULL(sum2, '') AS fsum2,
                   IFNULL(ujian1, '') AS fujian1,
                   IFNULL(ujian2, '') AS fujian2,
                   IFNULL(ujian3, '') AS fujian3,
                   IFNULL(ujian4, '') AS fujian4,
                   IFNULL(ujian5, '') AS fujian5,
                   IFNULL(ujian6, '') AS fujian6,
                   IFNULL(ujian7, '') AS fujian7,
                   IFNULL(ujian8, '') AS fujian8,
                   IFNULL(ujian9, '') AS fujian9,
                   IFNULL(ujian10, '') AS fujian10
              FROM jbsakad.calonsiswa cs
             WHERE cs.replid = $idCalon";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) <= 0)
    {
        echo "<br><br><i>tidak ditemukan data nilai & sumbangan";
        return;
    }

    $sql = "SELECT COUNT(replid) 
              FROM jbsakad.settingpsb 
             WHERE idproses = $idProses";
    $ndata = $db->ExecuteScalar($sql, 0);
    if ($ndata > 0)
    {
        $sql = "SELECT * 
                  FROM jbsakad.settingpsb 
                 WHERE idproses = $idProses";
        $res2 = $db->QueryDb($sql);
        $row2 = mysqli_fetch_array($res2);
        
        $kdsum1 = $row2['kdsum1'];
        $kdsum2 = $row2['kdsum2'];
        $kdujian1 = $row2['kdujian1'];
        $kdujian2 = $row2['kdujian2'];
        $kdujian3 = $row2['kdujian3'];
        $kdujian4 = $row2['kdujian4'];
        $kdujian5 = $row2['kdujian5'];
        $kdujian6 = $row2['kdujian6'];
        $kdujian7 = $row2['kdujian7'];
        $kdujian8 = $row2['kdujian8'];
        $kdujian9 = $row2['kdujian9'];
        $kdujian10 = $row2['kdujian10'];
    }

    
    if($row = mysqli_fetch_assoc($res))
    {
        echo "<span class='fs-12 fst-bold'>Sumbangan</span><br>";
        echo "<table id='tabSumbanganCs' class='tab tabShadow' width='400px'>";
        echo "<tr style='height: 30px;'>";
        echo "<td align='center' style='width: 20px;' class='numberColumn'>1</td>";
        echo "<td style='width: 120px'>Sumbangan #1 ($kdsum1)</td>";
        echo "<td align='right' style='width: 120px'>" . FormatRupiah($row['fsum1']) . "</td>";
        echo "</tr>";

        echo "<tr style='height: 30px;'>";
        echo "<td align='center' class='numberColumn'>2</td>";
        echo "<td>Sumbangan #2 ($kdsum2)</td>";
        echo "<td align='right'>" . FormatRupiah($row['fsum2']) . "</td>";
        echo "</tr>";
        echo "</table><br>";

        echo "<span class='fs-12 fst-bold'>Nilai</span><br>";
        echo "<table id='tabNilaiCs' class='tab tabShadow' width='400px'>";
        echo "<tr style='height: 30px;'>";
        echo "<td align='center' style='width: 20px;' class='numberColumn'>1</td>";
        echo "<td style='width: 120px'>Nilai #1 ($kdujian1)</td>";
        echo "<td align='center' style='width: 80px'>" . $row['fujian1'] . "</td>";
        echo "</tr>";

        echo "<tr style='height: 30px;'>";
        echo "<td align='center' class='numberColumn'>2</td>";
        echo "<td>Nilai #2 ($kdujian2)</td>";
        echo "<td align='center'>" . $row['fujian2'] . "</td>";
        echo "</tr>";

        echo "<tr style='height: 30px;'>";
        echo "<td align='center' class='numberColumn'>3</td>";
        echo "<td>Nilai #3 ($kdujian3)</td>";
        echo "<td align='center'>" . $row['fujian3'] . "</td>";
        echo "</tr>";

        echo "<tr style='height: 30px;'>";
        echo "<td align='center' class='numberColumn'>4</td>";
        echo "<td>Nilai #4 ($kdujian4)</td>";
        echo "<td align='center'>" . $row['fujian4'] . "</td>";
        echo "</tr>";

        echo "<tr style='height: 30px;'>";
        echo "<td align='center' class='numberColumn'>5</td>";
        echo "<td>Nilai #5 ($kdujian5)</td>";
        echo "<td align='center'>" . $row['fujian5'] . "</td>";
        echo "</tr>";

        echo "<tr style='height: 30px;'>";
        echo "<td align='center' class='numberColumn'>6</td>";
        echo "<td>Nilai #6 ($kdujian6)</td>";
        echo "<td align='center'>" . $row['fujian6'] . "</td>";
        echo "</tr>";

        echo "<tr style='height: 30px;'>";
        echo "<td align='center' class='numberColumn'>7</td>";
        echo "<td>Nilai #7 ($kdujian7)</td>";
        echo "<td align='center'>" . $row['fujian7'] . "</td>";
        echo "</tr>";

        echo "<tr style='height: 30px;'>";
        echo "<td align='center' class='numberColumn'>8</td>";
        echo "<td>Nilai #8 ($kdujian8)</td>";
        echo "<td align='center'>" . $row['fujian8'] . "</td>";
        echo "</tr>";

        echo "<tr style='height: 30px;'>";
        echo "<td align='center' class='numberColumn'>9</td>";
        echo "<td>Nilai #9 ($kdujian9)</td>";
        echo "<td align='center'>" . $row['fujian9'] . "</td>";
        echo "</tr>";

        echo "<tr style='height: 30px;'>";
        echo "<td align='center' class='numberColumn'>10</td>";
        echo "<td>Nilai #10 ($kdujian10)</td>";
        echo "<td align='center'>" . $row['fujian10'] . "</td>";
        echo "</tr>";
        echo "</table>";
    }
    
}

?>