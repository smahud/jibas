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
function ShowSelectTahunAjaran($db)
{
    global $departemen, $nis, $idTahunAjaran;

    try
    {
        $sql = "SELECT DISTINCT(t.replid), t.tahunajaran, t.aktif 
                  FROM jbsakad.riwayatkelassiswa r, jbsakad.kelas k, jbsakad.tahunajaran t 
                 WHERE r.nis = '$nis' 
                   AND r.idkelas = k.replid 
                   AND k.idtahunajaran = t.replid 
                 ORDER BY t.aktif DESC";
        $res = $db->QueryDb($sql);
        echo "<select id='tahunajaran' onchange='onChangeTahunAjaran()' class='inputbox' style='width:250px'>";
        while ($row = mysqli_fetch_row($res)) 
        {
            if ($idTahunAjaran == 0) 
                $idTahunAjaran = $row[0];
            
            $aktif = $row[2] == 1 ? " (Aktif)" : "";
            $sel = $idTahunAjaran == $row[0] ? "selected" : "";

            echo "<option value='$row[0]' $sel>$row[1] $aktif</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo $ex->getMessage();
    }
}

function ShowSelectSemester($db)
{
    global $departemen, $idSemester;

    try
    {
        $sql = "SELECT replid, semester, aktif 
                  FROM jbsakad.semester
                 WHERE departemen = '$departemen'
                 ORDER BY aktif DESC";
        $res = $db->QueryDb($sql);

        echo "<select id='semester' onchange='clearReport()' class='inputbox' style='width:250px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idSemester == 0) 
                $idSemester = $row['replid'];
            
            $sel = $idSemester == $row['replid'] ? "selected" : "";
            $aktif = $row['aktif'] == 1 ? "(Aktif)" : "";
            echo "<option value='$row[replid]' $sel>$row[semester] $aktif</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo $ex->getMessage();
    }    
}

function ShowSelectKelas($db)
{
    global $idTahunAjaran, $nis, $idKelas;

    try
    {
        $sql = "SELECT DISTINCT(r.idkelas), k.kelas, t.replid, t.tingkat
                  FROM jbsakad.riwayatkelassiswa r, jbsakad.kelas k, jbsakad.tingkat t 
                 WHERE r.nis = '$nis' 
                   AND r.idkelas = k.replid 
                   AND k.idtingkat = t.replid
                   AND k.idtahunajaran = $idTahunAjaran";
        $res = $db->QueryDb($sql);

        echo "<select id='kelas' onchange='onChangeKelas()' class='inputbox' style='width:250px'>";
        while ($row = mysqli_fetch_row($res)) 
        {
            if ($idKelas == 0) 
                $idKelas = $row[0];
            
            $data64 = base64_encode(json_encode([$row[0], $row[1], $row[2], $row[3]]));
            $sel = $idKelas == $row[0] ? "selected" : "";
            echo "<option value='$data64' $sel>$row[3], $row[1]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo $ex->getMessage();
    }    
}

function ShowTablePelajaran($db)
{
    global $nis, $idKelas;

    $sql = "SELECT DISTINCT p.replid, p.nama 
              FROM jbsakad.ujian u, jbsakad.pelajaran p, jbsakad.nilaiujian n 
             WHERE u.idpelajaran = p.replid 
               AND u.idkelas = $idKelas 
               AND u.replid = n.idujian 
               AND n.nis = '$nis' 
             ORDER BY p.urutan, p.nama";
    $res = $db->QueryDb($sql);
    
    if (mysqli_num_rows($res) == 0)
    {
        HintInfo::ShowLeft("Belum ada data nilai");
        return;
    }

    echo "<table class='tab tabShadow' id='tablePelajaran' border='1' width='100%' align='left' cellpadding='3'>";
    echo "<tr height='25' align='left'>";
    echo "<td width='10%' class='bg-table-header' align='center'>No</td>";
    echo "<td colspan='2' class='bg-table-header' width='*'>Pelajaran</td>";
    echo "</tr>";

    

    $no = 0;
    while ($row = mysqli_fetch_assoc($res))
    {
        $no++;
        $idPelajaran = $row['replid'];
        $pelajaran = $row['nama'];
    
        echo "<tr>";
        echo "<td width='10%' class='bg-table-number-column' align='center'>$no</td>";
        echo "<td width='*' class='cur-hand' onclick='showNilaiSiswa(\"$idPelajaran\", \"$pelajaran\")'>";
        echo "$pelajaran";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";             

    
}
?>