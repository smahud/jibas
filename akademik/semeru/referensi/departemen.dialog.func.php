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

function LoadDepartemen($db, $replid)
{
    global $departemen, $nipkepsek, $namakepsek, $urutan, $keterangan;

    $sql = "SELECT d.departemen, d.nipkepsek, p.nama AS namakepsek, d.urutan, d.keterangan
              FROM jbsakad.departemen d
              LEFT JOIN jbssdm.pegawai p ON d.nipkepsek = p.nip
             WHERE d.replid = $replid";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);

    $departemen = $row['departemen'];
    $nipkepsek = $row['nipkepsek'];
    $namakepsek = $row['namakepsek'];
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
        $nipkepsek = RequestData("nipkepsek", "");
        $urutan = (int) RequestData("urutan", 0);
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.departemen WHERE departemen = '$departemen'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Departemen $departemen sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "INSERT INTO jbsakad.departemen
                   SET departemen='$departemen', nipkepsek='$nipkepsek', urutan=$urutan, keterangan='$keterangan', aktif=1";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "dpt1");
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
        $nipkepsek = RequestData("nipkepsek", "");
        $urutan = (int) RequestData("urutan", 0);
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.departemen
                 WHERE departemen = '$departemen' AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Departemen $departemen sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "UPDATE jbsakad.departemen
                   SET departemen='$departemen', nipkepsek='$nipkepsek', urutan=$urutan, keterangan='$keterangan'
                 WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "dpt2");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
?>