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
        
        echo "<select id='tingkat' onchange='onChangeTingkat()' class='inputbox' style='width:80px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idTingkat == "") 
                $idTingkat = $row['replid'];
            
            $sel = $idTingkat == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[tingkat]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k4pqj");
    }
}

function ShowSelectKelas($db)
{
    global $idTingkat, $idKelas, $idTahunAjaran;

    try
    {
        $sql = "SELECT replid, kelas 
                  FROM jbsakad.kelas 
                 WHERE aktif = 1 
                   AND idtingkat = '$idTingkat'
                   AND idtahunajaran = '$idTahunAjaran'
                 ORDER BY kelas";
        $res = $db->QueryDb($sql);
        
        echo "<select id='kelas' onchange='onChangeKelas()' class='inputbox' style='width:160px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idKelas == "") 
                $idKelas = $row['replid'];
            
            $sel = $idKelas == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[kelas]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kzyqk");
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
                   AND aktif = 1
                 ORDER BY aktif DESC, replid DESC";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_array($res))
        {
            $idTahunAjaran = $row['replid'];
            echo "<input type='text' class='inputbox_readonly' style='width:150px' readonly id='tahunajaran' value='$row[tahunajaran] (Aktif)'>";
            echo "<input type='hidden' id='idtahunajaran' value='$row[replid]'>";
        }
        else
        {
            echo "<input type='text' class='inputbox_readonly' style='width:150px' readonly id='tahunajaran' value='(belum ada)'>";
            echo "<input type='hidden' id='idtahunajaran' value='0'>";
        }
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kxnau");
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
        if ($row = mysqli_fetch_array($res))
        {
            $idSemester = $row['replid'];
            echo "<input type='text' class='inputbox_readonly' style='width:150px' readonly id='semester' value='$row[semester] (Aktif)'>";
            echo "<input type='hidden' id='idsemester' value='$row[replid]'>";
        }
        else
        {
            echo "<input type='text' class='inputbox_readonly' style='width:150px' readonly id='semester' value='(belum ada)'>";
            echo "<input type='hidden' id='idsemester' value='0'>";
        }
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kzyqk");
    }    
}

function ShowSelectTanggalTahunAjaran($db)
{
    global $idTahunAjaran;

    if ($idTahunAjaran == 0)
    {
        echo "<input type='hidden' id='dd1' value=''>";
        echo "<input type='hidden' id='mm1' value=''>";
        echo "<input type='hidden' id='yy1' value=''>";
        echo "<input type='hidden' id='dd2' value=''>";
        echo "<input type='hidden' id='mm2' value=''>";
        echo "<input type='hidden' id='yy2' value=''>";
        return;
    }

    $sql = "SELECT YEAR(tglmulai) AS yy1, MONTH(tglmulai) AS mm1, DAY(tglmulai) as dd1,
                   YEAR(tglakhir) AS yy2, MONTH(tglakhir) AS mm2, DAY(tglakhir) as dd2
              FROM jbsakad.tahunajaran 
             WHERE replid = $idTahunAjaran";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);
    $yy1 = $row[0];
    $mm1 = $row[1];
    $dd1 = $row[2];
    $yy2 = $row[3];
    $mm2 = $row[4];
    $dd2 = $row[5];

    echo CreateSelect("dd1", 1, 31, $dd1, 50); echo "&nbsp;";
    echo CreateSelect("mm1", 1, 12, $mm1, 60); echo "&nbsp;";
    echo CreateSelect("yy1", $yy1, $yy2, $yy1, 80); echo " &nbsp; s/d &nbsp;";
    echo CreateSelect("dd2", 1, 31, $dd2, 50); echo "&nbsp;";
    echo CreateSelect("mm2", 1, 12, $mm2, 60); echo "&nbsp;";
    echo CreateSelect("yy2", $yy1, $yy2, $yy2, 80);
}

function CreateSelect($name, $min, $max, $value, $width)
{
    $select = "<select name='$name' id='$name' class='inputbox' style='width: $width px' onChange='clearContent()'>";
    for($i = $min; $i <= $max; $i++)
    {
        $sel = $i == $value ? "selected" : "";

        $text = $i;
        if ($name == "mm1" || $name == "mm2")
            $text = NamaBulanPendek($i);
        
        $select .= "<option value='$i' $sel>$text</option>";
    }
    $select .= "</select>";

    return $select;
}
?>
