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

function LoadKelas($db, $replid)
{
    global $departemen, $idtahunajaran, $tahunajaran, $idtingkat, $tingkat, $kelas, $kapasitas, $nipwali, $namawali, $keterangan;

    // When creating a new record, we only have IDs (tahunajaran/tingkat) passed in.
    // Resolve the display name from the IDs so the user sees the proper labels.
    if ($replid == 0)
    {
        if (!empty($idtahunajaran))
        {
            $sql = "SELECT tahunajaran FROM jbsakad.tahunajaran WHERE replid = '$idtahunajaran'";
            $res = $db->QueryDb($sql);
            if ($row = mysqli_fetch_assoc($res))
                $tahunajaran = $row['tahunajaran'];
        }

        if (!empty($idtingkat))
        {
            $sql = "SELECT tingkat FROM jbsakad.tingkat WHERE replid = '$idtingkat'";
            $res = $db->QueryDb($sql);
            if ($row = mysqli_fetch_assoc($res))
                $tingkat = $row['tingkat'];
        }

        return;
    }

    $sql = "SELECT k.kelas, k.kapasitas, k.nipwali, k.keterangan, t.tahunajaran, t.departemen, g.tingkat, k.idtahunajaran, k.idtingkat, p.nama AS wali " .
           "FROM jbsakad.kelas k " .
           "JOIN jbsakad.tahunajaran t ON k.idtahunajaran = t.replid " .
           "JOIN jbsakad.tingkat g ON k.idtingkat = g.replid " .
           "LEFT JOIN jbssdm.pegawai p ON k.nipwali = p.nip " .
           "WHERE k.replid = $replid";

    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_assoc($res);

    $departemen = $row['departemen'];
    $idtahunajaran = $row['idtahunajaran'];
    $tahunajaran = $row['tahunajaran'];
    $idtingkat = $row['idtingkat'];
    $tingkat = $row['tingkat'];
    $kelas = $row['kelas'];
    $kapasitas = $row['kapasitas'];
    $nipwali = $row['nipwali'];
    $namawali = $row['wali'];
    $keterangan = $row['keterangan'];
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $idtahunajaran = RequestData("idtahunajaran", "");
        $idtingkat = RequestData("idtingkat", "");
        $kelas = RequestData("kelas", "");
        $kapasitas = RequestData("kapasitas", 0);
        $nipwali = RequestData("nipwali", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.kelas WHERE kelas = '$kelas' AND idtahunajaran = '$idtahunajaran' AND idtingkat = '$idtingkat'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Kelas $kelas sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "INSERT INTO jbsakad.kelas SET kelas='$kelas', idtahunajaran='$idtahunajaran', idtingkat='$idtingkat', kapasitas=$kapasitas, nipwali='$nipwali', keterangan='$keterangan', aktif=1";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "k3edk");
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
        $idtahunajaran = RequestData("idtahunajaran", "");
        $idtingkat = RequestData("idtingkat", "");
        $kelas = RequestData("kelas", "");
        $kapasitas = RequestData("kapasitas", 0);
        $nipwali = RequestData("nipwali", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.kelas WHERE kelas = '$kelas' AND idtahunajaran = '$idtahunajaran' AND idtingkat = '$idtingkat' AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Kelas $kelas sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "UPDATE jbsakad.kelas SET kelas='$kelas', kapasitas=$kapasitas, nipwali='$nipwali', keterangan='$keterangan' WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "k3edk");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
