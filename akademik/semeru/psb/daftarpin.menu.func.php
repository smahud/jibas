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
     
        echo "<select id='departemen' onchange='onChangeDept()' style='width:250px' class='inputbox'>";
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

function ShowSelectProses($db)
{
    global $departemen, $idProses;

    try
    {
        $sql = "SELECT replid, proses 
                  FROM jbsakad.prosespenerimaansiswa 
                 WHERE aktif = 1 
                   AND departemen = '$departemen' 
                 ORDER BY proses";
        $res = $db->QueryDb($sql);
        
        echo "<select id='proses' onchange='onChangeProses()' class='inputbox' style='width:200px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idProses == 0)
                $idProses = $row['replid'];
            $sel = $idProses == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[proses]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectKelompok($db)
{
    global $idProses;

    try
    {
        $sql = "SELECT replid, kelompok 
                  FROM jbsakad.kelompokcalonsiswa
                 WHERE idproses = '$idProses' 
                 ORDER BY kelompok";
         $res = $db->QueryDb($sql);
        
        echo "<select id='kelompok' onchange='onChangeKelompok()' class='inputbox' style='width:250px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            echo "<option value='$row[replid]'>$row[kelompok]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}


?>