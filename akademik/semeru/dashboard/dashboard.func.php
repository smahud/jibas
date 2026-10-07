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
function GetReplidSiswa($db, $nis)
{
    $sql = "SELECT replid 
              FROM jbsakad.siswa
             WHERE nis = '$nis'";
    $res = $db->QueryDb($sql); 
    if ($row = mysqli_fetch_row($res))
        return $row[0];
    
    return 0;             
}

function ShowInfoSiswa($db)
{
    global $replid, $nis, $nama, $idTingkat, $tingkat, $idKelas, $kelas, $departemen, $idAngkatan, $angkatan;

    $sql = "SELECT s.nis, s.nama, k.replid AS idkelas, k.kelas, t.replid AS idtingkat, t.tingkat, t.departemen,
               IF(s.foto IS NULL, 0, 1) AS fotoexist, IF(s.foto IS NULL, '', TO_BASE64(s.foto)) as foto64,
               s.panggilan, s.idangkatan, a.angkatan
          FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.angkatan a
         WHERE s.idkelas = k.replid
           AND k.idtingkat = t.replid
           AND s.idangkatan = a.replid
           AND s.replid = '$replid'";

    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $nis = $row['nis'];
        $nama = $row['nama'];
        $idTingkat = $row['idtingkat'];
        $tingkat = $row['tingkat'];
        $idKelas = $row['idkelas'];
        $kelas = $row['kelas'];
        $departemen = $row['departemen'];
        $userFoto = $row['fotoexist'] == 1 ? $row['foto64'] : UserInfo::$DefaultFoto;
        $idAngkatan = $row['idangkatan'];
        $angkatan = $row['angkatan'];
    }

    echo "<table border='0' width='100%'>";
    echo "<tr>";
    echo "<td width='130'>";
    echo "<img style='width: 100px; height: 100px;' class='avatar-circle'";
    echo "src='data:image/jpg;base64," .  $userFoto . "'>";
    echo "</td>";
    echo "<td>";
    echo "<span class='fg-secondary fs-10'>Dashboard Siswa</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 24px; color: #333; font-weight: bold\">";
    echo $nama;
    echo "</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 18px; color: #333;\">";
    echo $nis;
    echo "</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 12px; color: #666;\">";
    echo $departemen . ' | ' . $angkatan . ' | ' . $tingkat . ' | ' . $kelas;
    echo "</span>&nbsp;&nbsp;";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<input type='hidden' id='replid' value='$replid'>";
    echo "<input type='hidden' id='nis' value='$nis'>";
    echo "<input type='hidden' id='nama' value='$nama'>";
    echo "<input type='hidden' id='departemen' value='$departemen'>";
    echo "<input type='hidden' id='idkelas' value='$idKelas'>";
    echo "<input type='hidden' id='kelas' value='$kelas'>";
    echo "<input type='hidden' id='idtingkat' value='$idTingkat'>";
    echo "<input type='hidden' id='tingkat' value='$tingkat'>";
    echo "<input type='hidden' id='idangkatan' value='$idAngkatan'>";
    echo "<input type='hidden' id='angkatan' value='$angkatan'>";
}
?>