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

function LoadJenisPengujian($db, $replid)
{
    global $departemen, $pelajaran, $idpelajaran, $jenisujian, $singkatan, $keterangan, $urutan;

    $sql = "SELECT j.jenisujian, j.info1, j.keterangan, j.urutan, p.nama AS pelajaran, p.departemen
              FROM jbsakad.jenisujian j
              LEFT JOIN jbsakad.pelajaran p ON j.idpelajaran = p.replid
             WHERE j.replid = $replid";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);

    $jenisujian = $row['jenisujian'];
    $singkatan = $row['info1'];
    $keterangan = $row['keterangan'];
    $urutan = $row['urutan'];
    $pelajaran = $row['pelajaran'];
    $departemen = $row['departemen'];
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $jenisujian = RequestData("jenisujianbaru", "");
        $singkatan = RequestData("singkatan", "");
        $idpelajaran = (int) RequestData("idpelajaran", 0);
        $urutan = (int) RequestData("urutan", 0);
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) 
                  FROM jbsakad.jenisujian 
                 WHERE jenisujian = '$jenisujian' 
                   AND idpelajaran = $idpelajaran 
                   AND info1 = '$singkatan'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Jenis Ujian $jenisujian sudah digunakan!";
            return json_encode([-1, $msg]);
        }

        $sql = "INSERT INTO jbsakad.jenisujian 
                   SET jenisujian='$jenisujian', info1='$singkatan', 
                       idpelajaran=$idpelajaran, urutan=$urutan,
                       keterangan='$keterangan'";
        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "kfqy5");
        return json_encode([-1, $msg]);
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
        $idpelajaran = RequestData("idpelajaran", 0);
        $jenisujian = RequestData("jenisujianbaru", "");
        $singkatan = RequestData("singkatan", "");
        $urutan = RequestData("urutan", 0);
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) 
                  FROM jbsakad.jenisujian 
                 WHERE jenisujian = '$jenisujian' 
                   AND idpelajaran = $idpelajaran 
                   AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Jenis Ujian $jenisujian sudah digunakan!";
            return json_encode([-1, $msg]);
        }

        $sql = "UPDATE jbsakad.jenisujian 
                   SET replid=$replid, jenisujian='$jenisujian', 
                       info1='$singkatan', urutan=$urutan, keterangan='$keterangan' 
                 WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "kw0r3");
        return json_encode([-1, $msg]);
    }
    finally
    {
        $db->Close();
    }
}
?>