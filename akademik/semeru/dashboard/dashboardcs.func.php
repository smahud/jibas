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

function GetReplidCalonSiswa($db, $nic)
{
    $sql = "SELECT replid 
              FROM jbsakad.calonsiswa
             WHERE nopendaftaran = '$nic'";
    $res = $db->QueryDb($sql); 
    if ($row = mysqli_fetch_row($res))
        return $row[0];
    
    return 0;             
}

function ShowInfoCalonSiswa($db)
{
    global $idCalon, $nic, $nama, $idKelompok, $kelompok, $idProses, $proses, $departemen;

    $sql = "SELECT cs.nopendaftaran, cs.nama, k.replid AS idkelompok, k.kelompok, p.replid AS idproses, p.proses, p.departemen,
               IF(cs.foto IS NULL, 0, 1) AS fotoexist, IF(cs.foto IS NULL, '', TO_BASE64(cs.foto)) as foto64,
               cs.panggilan
          FROM jbsakad.calonsiswa cs, jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p
         WHERE cs.idkelompok = k.replid
           AND cs.idproses = p.replid
           AND cs.replid = '$idCalon'";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $nic = $row['nopendaftaran'];
        $nama = $row['nama'];
        $idKelompok = $row['idkelompok'];
        $kelompok = $row['kelompok'];
        $idProses = $row['idproses'];
        $proses = $row['proses'];
        $departemen = $row['departemen'];
        $userFoto = $row['fotoexist'] == 1 ? $row['foto64'] : UserInfo::$DefaultFoto;
    }

    echo "<table border='0' width='100%'>";
    echo "<tr>";
    echo "<td width='130'>";
    echo "<img style='width: 100px; height: 100px;' class='avatar-circle'";
    echo "src='data:image/jpg;base64," .  $userFoto . "'>";
    echo "</td>";
    echo "<td>";
    echo "<span class='fg-secondary fs-10'>Dashboard Calon Siswa</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 24px; color: #333; font-weight: bold\">";
    echo $nama;
    echo "</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 18px; color: #333;\">";
    echo $nic;
    echo "</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 12px; color: #666;\">";
    echo $departemen . ' | ' . $kelompok . ' | ' . $proses;
    echo "</span>&nbsp;&nbsp;";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<input type='hidden' id='idcalon' value='$idCalon'>";
    echo "<input type='hidden' id='nic' value='$nic'>";
    echo "<input type='hidden' id='nama' value='$nama'>";
    echo "<input type='hidden' id='departemen' value='$departemen'>";
    echo "<input type='hidden' id='idkelompok' value='$idKelompok'>";
    echo "<input type='hidden' id='kelompok' value='$kelompok'>";
    echo "<input type='hidden' id='idproses' value='$idProses'>";
    echo "<input type='hidden' id='proses' value='$proses'>";
}
?>