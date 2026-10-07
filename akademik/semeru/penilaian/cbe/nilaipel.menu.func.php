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
        echo Msg::InfoError($ex->getMessage(), "k70qn");
    }
}

function ShowSelectPelajaran($db)
{
     global $departemen;

    try
    {
        $sql = "SELECT pel.replid, pel.nama
                  FROM jbsakad.pelajaran pel, jbscbe.pengujian p
                 WHERE p.idpelajaran = pel.replid
                   AND pel.departemen = '$departemen'";
        $res = $db->QueryDb($sql);
        
        echo "<select id='pelajaran' onchange='onChangePelajaran()' class='inputbox' style='width:250px'>";
        while ($row = mysqli_fetch_row($res)) 
        {
            echo "<option value='$row[0]'>$row[1]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectJumlahPengujian()
{
    echo "<select id='jumlah' onchange='clearContent()' class='inputbox' style='width:60px'>";
    echo "<option value='10'>10</option>";
    echo "<option value='20'>20</option>";
    echo "<option value='30'>30</option>";
    echo "</select>";
}

function ShowSelectJenisPengujian()
{
    echo "<select id='jenis' onchange='clearContent()' class='inputbox' style='width:120px'>";
    echo "<option value='1,2'>Semua Jenis</option>";
    echo "<option value='1'>Khusus</option>";
    echo "<option value='2'>Umum</option>";
    echo "</select>";
}
?>