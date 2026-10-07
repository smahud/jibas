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

function LoadAngkatan($db, $replid)
{
    global $departemen, $angkatan, $keterangan;

    $sql = "SELECT departemen, angkatan, keterangan
              FROM jbsakad.angkatan
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);

    $departemen = $row['departemen'];
    $angkatan = $row['angkatan'];
    $keterangan = $row['keterangan'];
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $angkatan = RequestData("angkatan", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.angkatan WHERE departemen = '$departemen' AND angkatan = '$angkatan'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Angkatan $angkatan sudah digunakan pada departemen $departemen";
            return json_encode([-1,$msg]);
        }

        $sql = "INSERT INTO jbsakad.angkatan
                   SET departemen='$departemen', angkatan='$angkatan', keterangan='$keterangan', aktif=1";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "akt1");
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
        $departemen = RequestData("departemen", "");
        $angkatan = RequestData("angkatan", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.angkatan WHERE departemen = '$departemen' AND angkatan = '$angkatan' AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Angkatan $angkatan sudah digunakan pada departemen $departemen";
            return json_encode([-1,$msg]);
        }

        $sql = "UPDATE jbsakad.angkatan
                   SET angkatan='$angkatan', keterangan='$keterangan'
                 WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "akt2");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
?>