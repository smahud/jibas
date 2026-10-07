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

function ShowTahunAjaranAktif($db)
{
    global $departemen, $idTahunAjaran, $tahunAjaran;

    try
    {
        $sql = "SELECT replid, tahunajaran
                  FROM jbsakad.tahunajaran
                 WHERE departemen = '$departemen'
                   AND aktif = 1";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) <= 0)
        {
            echo "<input type='text' id='tahunajaran' class='inputbox inputbox-readonly' readonly style='width 150px' value='belum ada data tahun ajaran'>";
            echo "<input type='hidden' id='idtahunajaran' value='0'>";
            return;
        }

        $row = mysqli_fetch_row($res);
        $idTahunAjaran = $row[0];
        $tahunAjaran = $row[1];
        echo "<input type='text' id='tahunajaran' class='inputbox inputbox-readonly' readonly style='width 150px' value='$tahunAjaran'>";
        echo "<input type='hidden' id='idtahunajaran' value='$idTahunAjaran'>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectTingkat($db)
{
    global $departemen, $idTingkat;

    try
    {
        $sql = "SELECT replid, tingkat 
                  FROM jbsakad.tingkat 
                 WHERE aktif = 1 
                   AND departemen = '$departemen' 
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        
        echo "<select id='tingkat' onchange='onChangeTingkat()' class='inputbox' style='width:150px'>";
        $sel = $idTingkat == 0 ? "selected" : "";
        echo "<option value='0'>Semua Tingkat</option>";
        while ($row = mysqli_fetch_array($res)) 
        {
            $sel = $idTingkat == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[tingkat]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectKelas($db)
{
    global $idTingkat, $idTahunAjaran;

    try
    {
        if ($idTingkat == 0)
        {
            echo "<select id='kelas' onchange='onChangeKelas()' class='inputbox' style='width:250px'>";
            echo "<option value='0'>Semua Kelas</option>";
            echo "</select>";
            return;
        }
        
        $sql = "SELECT replid, kelas 
                  FROM jbsakad.kelas
                 WHERE aktif = 1 
                   AND idtingkat = '$idTingkat' 
                   AND idtahunajaran = '$idTahunAjaran' 
                 ORDER BY kelas";
        $res = $db->QueryDb($sql);
        
        echo "<select id='kelas' onchange='onChangeKelas()' class='inputbox' style='width:250px'>";
        echo "<option value='0'>Semua Kelas</option>";
        while ($row = mysqli_fetch_array($res)) 
        {
            echo "<option value='$row[replid]'>$row[kelas]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectBulan()
{
    $arrMonth = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    
    $month = date("m");
    echo "<select id='bulan' onchange='onChangeBulan()' class='inputbox' style='width:250px'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = $i == $month ? "selected" : "";
        echo "<option value='$i' $sel>" . $arrMonth[$i - 1] . "</option>";
    }
    echo "</select>";
}
?>