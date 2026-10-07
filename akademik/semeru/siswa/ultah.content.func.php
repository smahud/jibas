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

function ShowTableTanggal($db)
{
    global $bulan, $tahun, $departemen, $idTingkat, $idKelas;

    $currYear = date('Y');
    $currMonth = date('n');
    $tahun = $bulan >= $currMonth ? $currYear : $currYear + 1;

    echo "<span class='fs-14'>" . NamaBulan($bulan) . " " . $tahun . "</span><br><br>";
    
    $sql = "SELECT DAY(s.tgllahir), COUNT(s.replid)
              FROM jbsakad.siswa s";
    if ($idTingkat == 0)
    {
        $sql .= " INNER JOIN jbsakad.angkatan a ON s.idangkatan = a.replid
                  WHERE s.aktif = 1 
                    AND a.departemen = '$departemen'
                    AND MONTH(s.tgllahir) = '$bulan'
                  GROUP BY DAY(s.tgllahir)
                  ORDER BY DAY(s.tgllahir)";
    }
    else if ($idKelas == 0)
    {
        $sql .= " INNER JOIN jbsakad.kelas k ON s.idkelas = k.replid
                  WHERE s.aktif = 1 
                    AND k.idtingkat = $idTingkat
                    AND MONTH(s.tgllahir) = '$bulan'
                  GROUP BY DAY(s.tgllahir)
                  ORDER BY DAY(s.tgllahir)";
    }
    else 
    {
        $sql .= " WHERE s.aktif = 1 
                    AND s.idkelas = '$idKelas'
                   AND  MONTH(s.tgllahir) = '$bulan'
                  GROUP BY DAY(s.tgllahir)
                  ORDER BY DAY(s.tgllahir)";
    }
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        HintInfo::ShowLeft("Belum ada siswa yang berulang tahun", 300);
        return;
    }

    echo "<input type='hidden' id='tahun' value='$tahun'>";
    echo "<table width='98%' align='center' border='0' cellpadding='5'>";

    $nCol = 0;
    while($row = mysqli_fetch_row($res))
    {
        $tgl = $row[0];
        $jumlah = $row[1];

        if ($nCol == 0)
            echo "<tr>";

        $dateString = "$tahun-$bulan-$tgl";
        $wk = date('N', strtotime($dateString));

        echo "<td width='33%' align='center'>";
        echo "<div onclick='showDaftarSiswa($tgl)' class='bg-gray-100 cur-hand' style='padding: 5px; border:1px; border-radius: 5px; width: 80px; height: 70px; text-align: center; line-height: 25px;' >";
        echo NamaHari($wk) . "<br>";
        echo "<span class='fs-24 fst-bold'>$tgl</span><br>";
        echo "<span class='fg-secondary fst-italic'>$jumlah siswa</span>";
        echo "</div>";
        echo "</td>";
        
        $nCol += 1;
        if ($nCol == 3)
        {
            echo "</tr>";
            $nCol = 0;
        }
    }

    if ($nCol != 3)
        echo "</tr>";
    echo "</table>";
   
}

function ShowTableListSiswa()
{
    global $bulan, $tahun, $departemen, $idTingkat, $idKelas;

    $departemen = RequestData("departemen", "");
    $idTingkat = RequestData("idTingkat", 0);
    $idKelas = RequestData("idKelas", 0);
    $bulan = RequestData("bulan", "");
    $tahun = RequestData("tahun", 0);
    $tanggal = RequestData("tanggal", 0);

    $currYear = date('Y');
    $currMonth = date('n');
    $tahun = $bulan >= $currMonth ? $currYear : $currYear + 1;
    
    $sql = "SELECT s.replid, s.nis, s.nama, s.aktif,
                   IFNULL(panggilan, '') AS fpanggilan,
                   t.departemen, t.tingkat, k.kelas,
                   IFNULL(tmplahir, '') AS ftmplahir, 
                   IF(tgllahir IS NULL, '', DATE_FORMAT(tgllahir, '%Y-%m-%d')) AS ftgllahir
              FROM jbsakad.siswa s";
    if ($idTingkat == 0)
    {
        $sql .= " INNER JOIN jbsakad.angkatan a ON s.idangkatan = a.replid
                  INNER JOIN jbsakad.kelas k ON s.idkelas = k.replid
                  INNER JOIN jbsakad.tingkat t ON k.idtingkat = t.replid  
                  WHERE s.aktif = 1 
                    AND a.departemen = '$departemen'
                    AND MONTH(s.tgllahir) = '$bulan'
                    AND DAY(s.tgllahir) = '$tanggal'
                  ORDER BY s.nama";
    }
    else if ($idKelas == 0)
    {
        $sql .= " INNER JOIN jbsakad.kelas k ON s.idkelas = k.replid
                  INNER JOIN jbsakad.tingkat t ON k.idtingkat = t.replid  
                  WHERE s.aktif = 1 
                    AND k.idtingkat = $idTingkat
                    AND MONTH(s.tgllahir) = '$bulan'
                    AND DAY(s.tgllahir) = '$tanggal'
                  ORDER BY s.nama";
    }
    else 
    {
        $sql .= " INNER JOIN jbsakad.kelas k ON s.idkelas = k.replid
                  INNER JOIN jbsakad.tingkat t ON k.idtingkat = t.replid  
                  WHERE s.aktif = 1 
                    AND k.idkelas = '$idKelas'
                    AND MONTH(s.tgllahir) = '$bulan'
                    AND DAY(s.tgllahir) = '$tanggal'        
                  ORDER BY s.nama";
    }

    $db = new Db();
    try
    {
        $db->Open();

        echo "<input type='hidden' id='tanggal' value='$tanggal'>";
        echo "<span style='right: 15px; position: absolute;' class='cur-hand hide-in-report' onclick='cetak()'>cetak</span>&nbsp;&nbsp;<br>";
        echo "<table id='tableSiswa' class='tab tabShadow' width='100%'>";
        echo "<tr>";
        echo "<td class='bg-table-header fg-white' width='35' align='center'>No</td>";
        echo "<td class='bg-table-header fg-white' width='150' align='center'>Siswa</td>";
        echo "<td class='bg-table-header fg-white' width='150' align='center'>Kelahiran</td>";
        echo "<td class='bg-table-header fg-white' width='150' align='center'>Kelas</td>";
        echo "<td class='bg-table-header fg-white hide-in-report' width='50' align='center'>&nbsp;</td>";
        echo "</tr>";

        $res = $db->QueryDb($sql);

        $no = 0;
        while($row = mysqli_fetch_assoc($res))
        {
            $no += 1;

            $replid = $row['replid'];

            echo "<tr>";
            echo "<td align='center' class='numberColumn'>$no</td>";

            echo "<td valign='top'>";
            echo "<span id='nama$replid' class='fs-11 fst-bold'>" . $row['nama'] . "</span><br>";
            echo "<span id='nis$replid' class='ff-consolas fs-11'>" . $row['nis'] . "</span><br>";
            echo "<span id='panggilan$replid' class='fst-italic fs-11 fg-secondary'>" . $row['fpanggilan'] . "</span><br>";
            echo "<span id='spInfoAktif$replid' class='fg-red fst-italic fs-10'>";
            if ($row['aktif'] == 0)
                echo "[Tidak Aktif]";
            echo "</span>";
            echo "</td>";

            echo "<td valign='top'>";
            echo "<span class='fg-secondary'>Kelahiran:</span> " . $row['ftmplahir'] . ", " . LongDateFormat($row['ftgllahir']) . "<br>";
            echo "<span class='fg-secondary'>Usia:</span> " . CountAge($row['ftgllahir']) . "<br>";
            echo "</td>";

            echo "<td valign='top'>";
            echo $row['departemen'] . "<br>&nbsp;&nbsp;" . $row['tingkat'] . " " . $row['kelas'] . "<br>";
            echo "</td>";

            echo "<td align='center' class='hide-in-report'>";
            echo "<img src='../images/ico/lihat.png' class='cur-hand' onclick='profilSiswa($replid)'>&nbsp;&nbsp;";
            echo "<img src='../images/ico/stat01.png' title='dashboard' class='cur-hand' onclick='dashboardSiswa($replid)'>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}
?>