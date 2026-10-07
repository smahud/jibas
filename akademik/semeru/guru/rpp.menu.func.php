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

function ShowSelectSemester($db)
{
    global $departemen, $semester;

    try
    {
        $sql = "SELECT replid, semester, aktif 
                  FROM jbsakad.semester 
                 WHERE departemen = '$departemen' 
                 ORDER BY aktif DESC";
        $res = $db->QueryDb($sql);
        
        echo "<select id='semester' onchange='onChangeSemester()' class='inputbox' style='width:150px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($semester == "") 
                $semester = $row['replid'];
            
            $sel = $semester == $row['replid'] ? "selected" : "";
            $aktif = $row['aktif'] == 1 ? "(Aktif)" : "";    
            
            echo "<option value='$row[replid]' $sel>$row[semester] $aktif</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectTingkat($db)
{
    global $departemen, $tingkat;

    try
    {
        $sql = "SELECT replid, tingkat 
                  FROM jbsakad.tingkat 
                 WHERE aktif = 1 
                   AND departemen = '$departemen' 
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        
        echo "<select id='tingkat' onchange='onChangeTingkat()' class='inputbox' style='width:150px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($tingkat == "") 
                $tingkat = $row['replid'];
            
            $sel = $tingkat == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[tingkat]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectPelajaran($db)
{
    global $departemen, $pelajaran;

    try
    {
        $sql = "SELECT replid, nama 
                  FROM jbsakad.pelajaran 
                 WHERE departemen = '$departemen' 
                   AND aktif = 1 
                 ORDER BY nama";
        $res = $db->QueryDb($sql);
        
        echo "<select id='pelajaran' onchange='onChangePelajaran()' class='inputbox' style='minWidth:200px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($pelajaran == "") 
                $pelajaran = $row['replid'];
            
            $sel = $pelajaran == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[nama]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}
?>