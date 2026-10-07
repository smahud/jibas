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

function LoadProses($db, $replid)
{
    global $departemen, $proses, $kodeawalan, $keterangan;

    if ($replid == 0)
        return;

    $sql = "SELECT departemen, proses, kodeawalan, keterangan 
              FROM jbsakad.prosespenerimaansiswa 
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_assoc($res))
    {
        $departemen = $row['departemen'];
        $proses = $row['proses'];
        $kodeawalan = $row['kodeawalan'];
        $keterangan = $row['keterangan'];
    }
}

function SaveProses()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = (int)RequestData('replid', 0);
        $departemen = RequestData('departemen', '');
        $proses = RequestData('proses', '');
        $kodeawalan = RequestData('kodeawalan', '');
        $keterangan = RequestData('keterangan', '');

        // Validate
        if ($proses === '')
            return "[-1,\"Nama Proses wajib diisi.\"]";

        if ($kodeawalan === '')
            return "[-1,\"Kode Awalan wajib diisi.\"]";

        // Unique checks
        if ($replid == 0)
        {
            $sql = "SELECT 1 
                      FROM jbsakad.prosespenerimaansiswa 
                     WHERE proses = '$proses' 
                       AND departemen = '$departemen'";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
                return "[-1,\"Nama proses $proses sudah digunakan!\"]";

            $sql = "SELECT 1 
                      FROM jbsakad.prosespenerimaansiswa 
                     WHERE kodeawalan = '$kodeawalan'";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
                return "[-1,\"Kode awalan $kodeawalan sudah digunakan!\"]";

            $sql = "INSERT INTO jbsakad.prosespenerimaansiswa 
                       SET proses = '$proses', kodeawalan = '$kodeawalan', 
                           departemen = '$departemen', keterangan = '$keterangan'";
            $db->QueryDb($sql);

            $sql = "SELECT LAST_INSERT_ID(replid) 
                      FROM jbsakad.prosespenerimaansiswa 
                     ORDER BY replid DESC 
                     LIMIT 1";
            $res = $db->QueryDb($sql);
            $row = mysqli_fetch_row($res);
            $newId = (int)$row[0];

            $sql = "UPDATE jbsakad.prosespenerimaansiswa 
                       SET aktif = 0 
                     WHERE replid <> $newId 
                       AND departemen = '$departemen'";
            $db->QueryDb($sql);
        }
        else
        {
            $sql = "SELECT 1 
                      FROM jbsakad.prosespenerimaansiswa 
                     WHERE proses = '$proses' 
                       AND departemen = '$departemen' 
                       AND replid <> $replid";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
                return "[-1,\"Nama proses $proses sudah digunakan!\"]";

            $sql = "SELECT 1 
                      FROM jbsakad.prosespenerimaansiswa 
                     WHERE kodeawalan = '$kodeawalan' 
                       AND replid <> $replid";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
                return "[-1,\"Kode awalan $kodeawalan sudah digunakan!\"]";

            $sql = "UPDATE jbsakad.prosespenerimaansiswa 
                       SET proses = '$proses', kodeawalan = '$kodeawalan', keterangan = '$keterangan' 
                     WHERE replid = $replid";
            $db->QueryDb($sql);
        }

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $msg = Msg::InfoError($ex->getMessage(), "a8ft3");
        return "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }
}
