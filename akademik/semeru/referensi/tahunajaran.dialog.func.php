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

function LoadTahunAjaran($db, $replid)
{
    global $departemen, $tahunajaran, $tglmulai, $tglakhir, $keterangan;

    $sql = "SELECT departemen, tahunajaran, DATE_FORMAT(tglmulai, '%Y-%m-%d') AS ftglmulai, 
                   DATE_FORMAT(tglakhir, '%Y-%m-%d') AS ftglakhir, keterangan
              FROM jbsakad.tahunajaran
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);

    $departemen = $row['departemen'];
    $tahunajaran = $row['tahunajaran'];
    $tglmulai = $row['ftglmulai'];
    $tglakhir = $row['ftglakhir'];
    $keterangan = $row['keterangan'];
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $tahunajaran = RequestData("tahunajaran", "");
        $tglmulai = RequestData("tglmulai", "");
        $tglakhir = RequestData("tglakhir", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.tahunajaran WHERE tahunajaran = '$tahunajaran' AND departemen = '$departemen'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Tahun Ajaran $tahunajaran sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "INSERT INTO jbsakad.tahunajaran
                   SET departemen='$departemen', tahunajaran='$tahunajaran', tglmulai='$tglmulai', tglakhir='$tglakhir', keterangan='$keterangan', aktif=1";
        $db->QueryDb($sql);

        // make sure only one active per departemen
        $sql = "UPDATE jbsakad.tahunajaran SET aktif=0 WHERE departemen = '$departemen' AND replid <> LAST_INSERT_ID()";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "tja1");
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
        $tahunajaran = RequestData("tahunajaran", "");
        $tglmulai = RequestData("tglmulai", "");
        $tglakhir = RequestData("tglakhir", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.tahunajaran WHERE tahunajaran = '$tahunajaran' AND departemen = '$departemen' AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Tahun Ajaran $tahunajaran sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "UPDATE jbsakad.tahunajaran
                   SET tahunajaran='$tahunajaran', tglmulai='$tglmulai', tglakhir='$tglakhir', keterangan='$keterangan'
                 WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "tja2");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
