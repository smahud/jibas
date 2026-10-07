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
function GetJamKe($db)
{
    global $departemen, $jamKe;

    $sql = "SELECT COUNT(replid) + 1 AS jamke
              FROM jbsakad.jam 
             WHERE departemen = '$departemen'";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $jamKe = $row[0];
}

function LoadJam($db)
{
    global $replid, $jamKe, $jamMulai, $menitMulai, $jamAkhir, $menitAkhir, $keterangan;
    
    $sql = "SELECT jamke, jam1, jam2, keterangan
              FROM jbsakad.jam
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $jamKe = $row[0];
    $waktuMulai = $row[1];
    $waktuAkhir = $row[2];
    $keterangan = $row[3];

    $arrMulai = explode(":", $waktuMulai);
    $arrAkhir = explode(":", $waktuAkhir);

    $jamMulai = str_pad($arrMulai[0], 2, "0", STR_PAD_LEFT);
    $menitMulai = str_pad($arrMulai[1], 2, "0", STR_PAD_LEFT);
    $jamAkhir = str_pad($arrAkhir[0], 2, "0", STR_PAD_LEFT);
    $menitAkhir = str_pad($arrAkhir[1], 2, "0", STR_PAD_LEFT);
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $jamKe = RequestData("jamke", 0);
        $waktuMulai = RequestData("waktumulai", "");
        $waktuAkhir = RequestData("waktuakhir", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT jamke, jam1, jam2
                  FROM jbsakad.jam
                 WHERE ('$waktuMulai' > jam1 AND '$waktuMulai' < jam2)
                   AND departemen = '$departemen'";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            return json_encode([0, "Jam mulai $waktuMulai bentrok dengan jam ke-$row[0] ($row[1] - $row[2])"]);
        }
        
        $sql = "SELECT jamke, jam1, jam2
                  FROM jbsakad.jam
                 WHERE ('$waktuAkhir' > jam1 AND '$waktuAkhir' < jam2)
                   AND departemen = '$departemen'";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            return json_encode([0, "Jam akhir $waktuAkhir bentrok dengan jam ke-$row[0] ($row[1] - $row[2])"]);
        }

        $sql = "INSERT INTO jbsakad.jam (departemen, jamke, jam1, jam2, keterangan) 
                VALUES ('$departemen', '$jamKe', '$waktuMulai', '$waktuAkhir', '$keterangan')";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil disimpan"]);                           
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
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

        $replid = RequestData("replid", 0);
        $departemen = RequestData("departemen", "");
        $jamKe = RequestData("jamke", 0);
        $waktuMulai = RequestData("waktumulai", "");
        $waktuAkhir = RequestData("waktuakhir", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT jamke, jam1, jam2
                  FROM jbsakad.jam
                 WHERE ('$waktuMulai' > jam1 AND '$waktuMulai' < jam2)
                   AND departemen = '$departemen'
                   AND replid <> $replid";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            return json_encode([0, "Jam mulai $waktuMulai bentrok dengan jam ke-$row[0] ($row[1] - $row[2])"]);
        }
        
        $sql = "SELECT jamke, jam1, jam2
                  FROM jbsakad.jam
                 WHERE ('$waktuAkhir' > jam1 AND '$waktuAkhir' < jam2)
                   AND departemen = '$departemen'
                   AND replid <> $replid";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            return json_encode([0, "Jam akhir $waktuAkhir bentrok dengan jam ke-$row[0] ($row[1] - $row[2])"]);
        }

        $sql = "UPDATE jbsakad.jam 
                   SET jam1 = '$waktuMulai',
                       jam2 = '$waktuAkhir',
                       keterangan = '$keterangan'
                 WHERE replid = $replid";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil disimpan"]);                           
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?> 