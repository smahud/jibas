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

function ShowSelectTahunMutasi($db)
{
    global $departemen, $tahunMutasi;
    
    $sql = "SELECT DISTINCT YEAR(tglmutasi) AS tahun 
              FROM jbsakad.mutasisiswa 
             WHERE departemen = '$departemen' 
             ORDER BY tahun DESC";
    
    $res = $db->QueryDb($sql);
    echo "<select name='tahunmutasi' id='tahunmutasi' style='width:200px' class='inputbox' onchange='onChangeTahunMutasi();'>";
    while ($row = mysqli_fetch_assoc($res))
    {
        if ($tahunMutasi == 0)
            $tahunMutasi = $row['tahun'];
        
        $sel = ($tahunMutasi == $row['tahun']) ? "selected" : "";
        echo "<option value='$row[tahun]' $sel>$row[tahun]</option>";
    }
    echo "</select>";
}
?>