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

function LoadJenisMutasi($db, $replid)
{
    global $jenisMutasi, $keterangan;

    $sql = "SELECT jenismutasi, keterangan
              FROM jbsakad.jenismutasi
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $jenisMutasi = $row['jenismutasi'];
        $keterangan = $row['keterangan'];
    }
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $jenisMutasi = RequestData("jenismutasi", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.jenismutasi WHERE jenismutasi = '$jenisMutasi'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Jenis Mutasi $jenisMutasi sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "INSERT INTO jbsakad.jenismutasi 
                   SET jenismutasi='$jenisMutasi', keterangan='$keterangan'";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "tkt1");
        return json_encode([-1,$msg]);
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
        $jenisMutasi = RequestData("jenismutasi", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.jenismutasi
                 WHERE jenismutasi = '$jenisMutasi' AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Jenis Mutasi $jenisMutasi sudah ada";
            return json_encode([-1,$msg]);
        }
       
        $sql = "UPDATE jbsakad.jenismutasi
                   SET jenismutasi='$jenisMutasi', keterangan='$keterangan'
                 WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "tkt2");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
?>