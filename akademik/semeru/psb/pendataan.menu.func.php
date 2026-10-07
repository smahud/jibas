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
function ShowSelectDepartemen($db)
{
    global $departemen;

    try
    {
        $dep = getDepartemen($db, SI_USER_ACCESS());
     
        echo "<select id='departemen' onchange='onChangeDept()' style='width:300px' class='inputbox'>";
        foreach ($dep as $value) 
        {
            if ($departemen == "") 
                $departemen = $value;
            
            $sel = $departemen == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowProsesAktif($db)
{
    global $departemen, $idProses;

    $sql = "SELECT replid, proses
              FROM jbsakad.prosespenerimaansiswa
             WHERE departemen = '$departemen'
               AND aktif = 1 ";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_assoc($res))
    {
        $idProses = $row['replid'];
        $namaProses = $row['proses'];

        echo "<input id='idproses' type='hidden' value='$idProses'>";        
        echo "<input id='proses' type='text' class='inputbox inputbox-readonly' style='width:290px' value='$namaProses' readonly>";
    }
    else 
    {
        echo "<input id='idproses' type='hidden' value='0'>";        
        echo "<input id='proses' type='text' class='inputbox inputbox-readonly' style='width:290px' value='belum ada proses aktif' readonly>";
    }
}

function ShowSelectKelompok($db)
{
    global $idProses;

    $sql = "SELECT replid, kelompok, kapasitas
              FROM jbsakad.kelompokcalonsiswa
             WHERE idproses = $idProses
             ORDER BY kelompok";
    $res = $db->QueryDb($sql);
    echo "<select id='kelompok' class='inputbox' style='width:300px' onchange='onChangeKelompok()'>";
    while ($row = mysqli_fetch_assoc($res))
    {
        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.calonsiswa
                 WHERE aktif = 1
                   AND idkelompok = $row[replid]";
        $terisi = $db->ExecuteScalar($sql, 0);

        $key64 = base64_encode(json_encode([$row['replid'], $row['kelompok'], $row['kapasitas'], $terisi]));
        
        $label = $row['kelompok'] . ", kapasitas: " . $row['kapasitas'] . ", terisi: " . $terisi;
        echo "<option value='$key64'>$label</option>";
    }
    echo "</select>";    
}
?>