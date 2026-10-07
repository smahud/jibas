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
function ShowLaporanPresensiPelajaranGuru($db)
{
    global $idSemester, $idPelajaran, $nipGuru, $tglAwal, $tglAkhir;
    $sql = "SELECT DATE_FORMAT(p.tanggal, '%Y-%m-%d') AS ftanggal, p.jam, s.status, p.jumlahjam, p.keterlambatan,
                   p.materi, p.objektif, p.refleksi, p.rencana, p.keterangan, p.replid, l.nama, k.kelas, p.replid 
              FROM jbsakad.presensipelajaran p, jbsakad.kelas k, jbsakad.pelajaran l, jbsakad.statusguru s, jbsakad.tingkat t   
             WHERE p.idkelas = k.replid 
               AND p.idpelajaran = l.replid 
               AND p.gurupelajaran = '$nipGuru' 
               AND p.tanggal BETWEEN '$tglAwal' AND '$tglAkhir' 
               AND p.jenisguru = s.replid 
               AND p.idsemester = '$idSemester' 
               AND p.idkelas = k.replid 
               AND k.idtingkat = t.replid";
    if ($idPelajaran > 0) 
        $sql .= " AND p.idpelajaran = '$idPelajaran'";
    $sql .= " ORDER BY p.tanggal, p.jam";

    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        echo "<i>Belum ada data presensi pelajaran</i>";
        return;
    }

    echo "<div id='dvTableContent'>";
    echo "<table class='tab tabShadow' id='table' width='95%' align='center'>";
    echo "<tr height='30' class='header'>";
    echo "<td width='5%' align='center'>No</td>";
    echo "<td width='15%' align='center'>Tanggal/Jam</td>";
    echo "<td width='15%' align='center'>Pelajaran/Kelas</td>";
    echo "<td width='15%' align='center'>Waktu/Telat</td>";
    echo "<td width='*' align='left'>Rekapitulasi</td>";
    echo "</tr>";

    $lsName = ["Hadir", "Izin", "Sakit", "Alpa", "Cuti"];

    $cnt = 0;
    while($row = mysqli_fetch_array($res))
    {
        $cnt += 1;

        $idPresensi = $row['replid'];

        $lsJumlah = [0, 0, 0, 0, 0];
        $sql = "SELECT statushadir, COUNT(replid)
                  FROM jbsakad.ppsiswa
                 WHERE idpp = $idPresensi
                 GROUP BY statushadir";
        $res2 = $db->QueryDb($sql);
        while($row2 = mysqli_fetch_row($res2))
        {
            $status = $row2[0];
            $jumlah = $row2[1];

            $lsJumlah[$status] = $jumlah;
        }

        $stRekap = "";
        for($i = 0; $i < 5; $i++)
        {
            if ($stRekap != "")
                $stRekap .= ", ";
            $stRekap .= $lsName[$i] . " = " . $lsJumlah[$i] . "&nbsp;&nbsp;";
        }

        echo "<tr height='30' valign='center'>";
        echo "<td align='center' valign='top' rowspan='2' class='bg-table-number-column' >$cnt</td>";
        echo "<td align='center'>" . $row['ftanggal'] . "<br>" . $row['jam'] . "</td>";
        echo "<td align='center'>" . $row['nama'] . "<br>" . $row['kelas'] . "</td>";
        echo "<td align='center'>" . $row['jumlahjam'] . " jam<br>" . $row['keterlambatan'] . " menit</td>";
        echo "<td align='left'>";
        echo "<div style='position: relative; width: 100%'>";
        echo $stRekap;
        echo "<img src='../../images/ico/lihat.png' title='rincian' style='position: absolute; right: 5px; top: -5px; cursor: pointer;' onclick='showRincian(\"$idPresensi\")'>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td colspan='4' align='left' style='line-height:18px; background-color: #fff;'>";
        echo "<b>Materi:</b> " . $row['materi'] . "<br>";
        echo "<b>Refleksi:</b> " . $row['refleksi'] . "<br>";
        echo "<b>Keterangan:</b> " . $row['keterangan'];
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}
?>
