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
    echo CreateSelect("yy1", $yy1, $yy2, $yy1, 80); echo " &nbsp; s/d &nbsp;<br>";
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