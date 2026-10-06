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
function ShowSelectKelompok()
{
    global $kelompok;

    try
    {
        echo "<select id='kelompok' onchange='onChangeCbKelompok()' class='inputbox' style='width: 120px;'>";
        $sel = $kelompok == "---" ? "selected" : "";
        echo "<option value='---' $sel>(tidak ada)</option>";
        
        $sel = $kelompok == "siswa" ? "selected" : "";
        echo "<option value='siswa' $sel>Siswa</option>";
        
        $sel = $kelompok == "calonsiswa" ? "selected" : "";
        echo "<option value='calonsiswa' $sel>Calon Siswa</option>";
        
        $sel = $kelompok == "pegawai" ? "selected" : "";
        echo "<option value='pegawai' $sel>Pegawai</option>";
        echo "</select>";
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
}

function LoadDataNota($db)
{
    global $id, $judul, $nota, $bagianNota, $kelompok, $personName, $personId;

    $sql = "SELECT n.judul, n.nota, n.bagian,
                   IFNULL(n.nis, '') AS nis, IFNULL(s.nama, '') AS namasiswa,     
                   IFNULL(n.nic, '') AS nic, IFNULL(cs.nama, '') AS namacalon,
                   IFNULL(n.nip, '') AS nip, IFNULL(p.nama, '') AS namapegawai
              FROM jbsumum.nota n
              LEFT JOIN jbsakad.siswa s ON n.nis = s.nis
              LEFT JOIN jbsakad.calonsiswa cs ON n.nic = cs.nopendaftaran
              LEFT JOIN jbssdm.pegawai p ON n.nip = p.nip
             WHERE n.id = $id";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))             
    {
        $judul = $row["judul"];
        $nota = $row["nota"];
        $bagianNota = $row["bagian"];
        
        if ($row["nis"] != "")
        {
            $kelompok = "siswa";
            $personId = $row["nis"];
            $personName = $row["namasiswa"];
        }
        else if ($row["nic"] != "")
        {
            $kelompok = "calonsiswa";
            $personId = $row["nic"];
            $personName = $row["namacalon"];
        }
        else if ($row["nip"] != "")
        {
            $kelompok = "pegawai";
            $personId = $row["nip"];
            $personName = $row["namapegawai"];
        }

        $personName = "$personName ($personId)";
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
        $kelompok = RequestData("kelompok", "");
        $personId = RequestData("personid", "");
        $bagianNota = RequestData("bagiannota", "");
        $judul = RequestData("judul", "");
        $nota = RequestData("nota", "");
        $userLevel = RequestData("userlevel", "");
        $userId = RequestData("userid", "");

        $sql = "INSERT INTO jbsumum.nota
                   SET departemen = '$departemen',
                       bagian = '$bagianNota',
                       judul = '$judul',
                       nota = '$nota',
                       kelompok = 1,
                       tanggal = CURDATE(),
                       waktu = NOW()";

        if ($userLevel == 0)
            $sql .= " ,pemilik = NULL";
        else
            $sql .= " ,pemilik = '$userId'";                       

        if ($kelompok == "siswa")            
            $sql .= " ,nis = '$personId'";
        else if ($kelompok == "calonsiswa")            
            $sql .= " ,nic = '$personId'";
        else if ($kelompok == "pegawai")            
            $sql .= " ,nip = '$personId'";

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
        $bagianNota = RequestData("bagiannota", "");
        $kelompok = RequestData("kelompok", "");
        $personId = RequestData("personid", "");

        $sql = "UPDATE jbsumum.nota
                   SET bagian = '$bagianNota',
                       judul = '$judul',
                       nota = '$nota'";

        if ($kelompok == "---")            
            $sql .= " ,nis = NULL, nic = NULL, nip = NULL";
        else if ($kelompok == "siswa")            
            $sql .= " ,nis = '$personId', nic = NULL, nip = NULL";
        else if ($kelompok == "calonsiswa")            
            $sql .= " ,nis = NULL, nic = '$personId', nip = NULL";
        else if ($kelompok == "pegawai")            
            $sql .= " ,nis = NULL, nic = NULL, nip = '$personId'";
                       
        $sql .= " WHERE id = $id";

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