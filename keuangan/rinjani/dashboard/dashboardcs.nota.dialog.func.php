<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 33.0 (Jan 05, 2026)
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
function LoadNotaCalonSiswa($db)
{
    global $id, $judul, $nota;

    $sql = "SELECT judul, nota
              FROM jbsumum.nota
             WHERE id = $id";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_row($res))             
    {
        $judul = $row[0];
        $nota = $row[1];
    }
}

function ShowSelectBagianNota($db)
{
    global $bagianNota;

    try
    {
        echo "<select id='bagiannota' onchange='onChangeBagianNota()' class='inputbox' style='width:150px'>";
        $sql = "SELECT bagian, urutan
                  FROM jbsumum.bagiannota
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        while ($row = mysqli_fetch_row($res))
        {
            $sel = ($bagianNota == $row[0]) ? "selected" : "";
            echo "<option value='$row[0]' $sel>$row[0]</option>";
        }
        echo "</select>";
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $nic = RequestData("nic", "");
        $bagianNota = RequestData("bagiannota", "");
        $judul = RequestData("judul", "");
        $nota = RequestData("nota", "");
        $userLevel = RequestData("userlevel", "");
        $userId = RequestData("userid", "");

        $pemilikSql = "";
        if ($userLevel == 0)
            $pemilikSql = "pemilik = NULL";
        else
            $pemilikSql = "pemilik = '$userId'";

        $sql = "INSERT INTO jbsumum.nota
                   SET departemen = '$departemen',
                       nic = '$nic',
                       bagian = '$bagianNota',
                       judul = '$judul',
                       nota = '$nota',
                       kelompok = 3,
                       tanggal = CURDATE(),
                       waktu = NOW(),
                       $pemilikSql";
        $db->QueryDb($sql);
        
        return json_encode([1, "OK"]);
    }
    catch(Exception $ex)
    {
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}

function SimpanEdit()
{
    $db = new Db();
    try
    {
        $db->Open();

        $id = RequestData("id", 0);
        $judul = RequestData("judul", "");
        $nota = RequestData("nota", "");

        $sql = "UPDATE jbsumum.nota
                   SET judul = '$judul',
                       nota = '$nota'
                 WHERE id = $id";
        $db->QueryDb($sql);
        
        return json_encode([1, "OK"]);
    }
    catch(Exception $ex)
    {
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>