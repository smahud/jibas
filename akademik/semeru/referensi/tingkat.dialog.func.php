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

function LoadTingkat($db, $replid)
{
    global $tingkat, $departemen, $urutan, $keterangan;

    $sql = "SELECT tingkat, departemen, urutan, keterangan
              FROM jbsakad.tingkat
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);

    $tingkat = $row['tingkat'];
    $departemen = $row['departemen'];
    $urutan = $row['urutan'];
    $keterangan = $row['keterangan'];
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $tingkat = RequestData("tingkat", "");
        $urutan = (int) RequestData("urutan", 0);
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.tingkat WHERE tingkat = '$tingkat' AND departemen = '$departemen'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Tingkat $tingkat sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "SELECT COUNT(replid) FROM jbsakad.tingkat WHERE urutan = $urutan AND departemen = '$departemen'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Urutan $urutan sudah digunakan";
            return json_encode([-1,$msg]);
        }

        $sql = "INSERT INTO jbsakad.tingkat
                   SET tingkat='$tingkat', departemen='$departemen', urutan=$urutan, keterangan='$keterangan'";
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
        $tingkat = RequestData("tingkat", "");
        $urutan = (int) RequestData("urutan", 0);
        $keterangan = RequestData("keterangan", "");

        $departemen = RequestData("departemen", "");

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.tingkat
                 WHERE tingkat = '$tingkat' AND departemen = '$departemen' AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Tingkat $tingkat sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.tingkat
                 WHERE urutan = $urutan AND departemen = '$departemen' AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Urutan $urutan sudah digunakan";
            return json_encode([-1,$msg]);
        }

        $sql = "UPDATE jbsakad.tingkat
                   SET tingkat='$tingkat', urutan=$urutan, keterangan='$keterangan'
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