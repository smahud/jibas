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
     
        echo "<select id='departemen' onchange='onChangeDept()' style='width:200px' class='inputbox'>";
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
        echo Msg::InfoError($ex->getMessage(), "k70qn");
    }
}

function ShowSelectTahunAjaran($db)
{
    global $departemen, $idTahunAjaran;

    try
    {
        $sql = "SELECT replid, tahunajaran, aktif 
                  FROM jbsakad.tahunajaran 
                 WHERE departemen = '$departemen' 
                 ORDER BY aktif DESC, replid DESC";
        $res = $db->QueryDb($sql);
        
        echo "<select id='tahunajaran' onchange='onChangeTahunAjaran()' class='inputbox' style='width:170px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idTahunAjaran == "") 
                $idTahunAjaran = $row['replid'];
            
            $aktif = $row['aktif'] == 1 ? " (Aktif)" : "";
            $sel = $idTahunAjaran == $row['replid'] ? "selected" : "";

            echo "<option value='$row[replid]' $sel>$row[tahunajaran] $aktif</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kxnau");
    }
}

function ShowSelectKalender($db)
{
    global $departemen, $idTahunAjaran;

    $sql = "SELECT replid, kalender
              FROM jbsakad.kalenderakademik
             WHERE departemen = '$departemen' 
               AND idtahunajaran = '$idTahunAjaran'
               AND aktif = 1
             ORDER BY kalender";
    $res = $db->QueryDb($sql);
        
    echo "<select id='kalender' onchange='onChangeKalender()' class='inputbox' style='width:250px'>";
    while ($row = mysqli_fetch_array($res)) 
    {
        echo "<option value='$row[replid]'>$row[kalender]</option>";
    }
    echo "</select>";
}

?>