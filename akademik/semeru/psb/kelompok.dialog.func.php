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

function LoadKelompok($db, $replid)
{
    global $departemen, $idProsesPsb, $prosesPsb, $kelompok, $kapasitas, $keterangan;

    if ($replid == 0)
        return;

    $sql = "SELECT p.departemen, p.proses, k.kelompok, k.kapasitas, k.keterangan, k.idproses 
              FROM jbsakad.kelompokcalonsiswa k 
             INNER JOIN jbsakad.prosespenerimaansiswa p ON k.idproses = p.replid 
             WHERE k.replid = $replid";

    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_assoc($res))
    {
        $departemen = $row['departemen'];
        $idProsesPsb = $row['idproses'];
        $prosesPsb = $row['proses'];
        $kelompok = $row['kelompok'];
        $kapasitas = $row['kapasitas'];
        $keterangan = $row['keterangan'];
    }
}

function SaveKelompok()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = (int)RequestData('replid', 0);
        $departemen = RequestData('departemen', '');
        $idProsesPsb = (int)RequestData('idprosespsb', 0);
        $prosesPsb = RequestData('prosespsb', '');
        $kelompok = RequestData('kelompok', '');
        $kapasitas = RequestData('kapasitas', '');
        $keterangan = RequestData('keterangan', '');
        
        if ($replid == 0)
        {
            $sql = "SELECT 1 
                      FROM jbsakad.kelompokcalonsiswa 
                     WHERE kelompok = '$kelompok' 
                       AND idproses = $idProsesPsb";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
                return "[-1,\"Nama $kelompok sudah digunakan!\"]";

            $sql = "INSERT INTO jbsakad.kelompokcalonsiswa 
                       SET kelompok = '$kelompok', 
                           kapasitas = '$kapasitas', 
                           idproses = $idProsesPsb, 
                           keterangan = '$keterangan'";
            $db->QueryDb($sql);
        }
        else
        {
            $sql = "SELECT 1 
                      FROM jbsakad.kelompokcalonsiswa 
                     WHERE kelompok = '$kelompok' 
                       AND idproses = $idProsesPsb 
                       AND replid <> $replid";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
                return "[-1,\"Nama $kelompok sudah digunakan!\"]";

            $sql = "UPDATE jbsakad.kelompokcalonsiswa 
                       SET kelompok = '$kelompok', 
                           kapasitas = '$kapasitas', 
                           keterangan = '$keterangan' 
                     WHERE replid = $replid";
            $db->QueryDb($sql);
        }

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $msg = Msg::InfoError($ex->getMessage(), "atvwu");
        return "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }
}
