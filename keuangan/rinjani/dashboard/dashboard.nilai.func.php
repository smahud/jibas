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
function ShowSelectJumlahNilai()
{
    echo "<select id='jumlahnilai' onchange='onChangeJumlahNilai()' class='inputbox' style='width:50px'>";
    echo "<option value='5' selected>5</option>";
    echo "<option value='10'>10</option>";
    echo "<option value='15'>15</option>";
    echo "<option value='20'>20</option>";
    echo "</select>";
}

function ShowLaporanNilaiTerbaru($db)
{
    global $nis, $jumlahNilai;

    $sql = "SELECT nu.nilaiujian, nu.keterangan, DATE_FORMAT(u.tanggal, '%d-%b-%Y') AS xtanggal,
                   ju.info1 AS kodeujian, ju.jenisujian, p.nama AS pelajaran, p.kode AS kodepelajaran,
                   IF(u.idrpp IS NULL, 0, u.idrpp) AS idrpp, rk.nilaiRK
              FROM jbsakad.nilaiujian nu, jbsakad.ujian u,
                   jbsakad.jenisujian ju, jbsakad.pelajaran p, jbsakad.ratauk rk
             WHERE nu.idujian = u.replid
               AND u.idjenis = ju.replid
               AND ju.idpelajaran = p.replid
               AND rk.idujian = u.replid
               AND nu.nis = '$nis'
             ORDER BY u.tanggal DESC
             LIMIT $jumlahNilai";
    $res = $db->QueryDb($sql);

    if (mysqli_num_rows($res) == 0)
    {
        echo "<br><i>belum ada data nilai siswa<i>";
        return;
    }

    echo "<br><br>";
    echo "<table class='tab' id='tablenilai' cellpadding='2' cellspacing='0'>";
    echo "<tr height='30' align='center' class='bg-table-header'>";
    echo "<td width='30'>No</td>";
    echo "<td width='120'>Tanggal</td>";
    echo "<td width='220'>Pelajaran</td>";
    echo "<td width='180'>Jenis Ujian</td>";
    echo "<td width='100'>Nilai</td>";
    echo "<td width='120'>Rata-rata Kelas</td>";
    echo "</tr>";
    $no = 0;
    while ($row = mysqli_fetch_array($res))
    {
        $no++;

        $nu = $row['nilaiujian'];
        $nrk = $row['nilaiRK'];

        if ($nrk != 0)
            $dev = ($nu - $nrk) / $nrk;
        else 
            $dev = 0;

        $dev = round($dev, 2);
        $colorDev = $dev >= 0 ? "blue" : "red";
        $mark = $dev >= 0 ? "+" : "";
        $devStr = $mark . $dev . "%";

        echo "<tr align='center'>";
        echo "<td class='numberColumn'>$no</td>";
        echo "<td align='center'>$row[xtanggal]</td>";
        echo "<td align='left'>$row[pelajaran]</td>";
        echo "<td align='left'>$row[jenisujian]</td>";
        echo "<td align='center'>$row[nilaiujian]</td>";
        echo "<td align='center'>$row[nilaiRK]<br><span style='color: $colorDev'>$devStr</span></td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>