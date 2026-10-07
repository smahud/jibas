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

function LoadAspekPenilaian($db, $replid)
{
    global $kode, $nama, $urutan;

    $sql = "SELECT dasarpenilaian, keterangan, urutan 
              FROM jbsakad.dasarpenilaian 
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);

    $kode = $row['dasarpenilaian'];
    $nama = $row['keterangan'];
    $urutan = $row['urutan'];
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $kode = RequestData("kode", "");
        $nama = RequestData("nama", "");
        $urutan = (int) RequestData("urutan", 0);

        $sql = "SELECT COUNT(replid) 
                  FROM jbsakad.dasarpenilaian 
                 WHERE dasarpenilaian = '$kode'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Kode $kode sudah digunakan!";
            return json_encode([-1,$msg]);
        }

        $sql = "INSERT INTO jbsakad.dasarpenilaian
                   SET dasarpenilaian='$kode', keterangan='$nama', urutan=$urutan, aktif=1";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "k00cs");
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
        $kode = RequestData("kode", "");
        $nama = RequestData("nama", "");
        $urutan = (int) RequestData("urutan", 0);

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.dasarpenilaian
                 WHERE dasarpenilaian = '$kode' 
                   AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Kode $kode sudah digunakan!";
            return json_encode([-1,$msg]);
        }

        $sql = "UPDATE jbsakad.dasarpenilaian
                   SET dasarpenilaian='$kode', keterangan='$nama', urutan=$urutan
                 WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "k07ep");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
?>