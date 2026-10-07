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
 * MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php

function LoadPelajaran($db, $replid)
{
    global $departemen, $kode, $nama, $sifat, $kelompok, $keterangan, $urutan;

    $sql = "SELECT kode, nama, sifat, keterangan, departemen, idkelompok, urutan
              FROM jbsakad.pelajaran
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);

    $departemen = $row['departemen'];
    $kode = $row['kode'];
    $nama = $row['nama'];
    $sifat = $row['sifat'];
    $kelompok = $row['idkelompok'];
    $keterangan = $row['keterangan'];
    $urutan = $row['urutan'];
}

function ShowSelectKelompokPelajaran($db)
{
    global $kelompok;

    $sql = "SELECT replid, kelompok FROM jbsakad.kelompokpelajaran ORDER BY urutan";
    $res = $db->QueryDb($sql);

    while ($row = mysqli_fetch_array($res))
    {
        $replid = $row['replid'];
        $kelompokNama = $row['kelompok'];
        $sel = ($kelompok == $replid) ? "selected" : "";
        echo "<option value='$replid' $sel>$kelompokNama</option>";
    }
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $kode = RequestData("kode", "");
        $nama = RequestData("nama", "");
        $sifat = RequestData("sifat", 1);
        $kelompok = RequestData("kelompok", "");
        $keterangan = RequestData("keterangan", "");
        $urutan = RequestData("urutan", 0);

        $sql = "SELECT COUNT(replid) FROM jbsakad.pelajaran WHERE kode = '$kode'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Singkatan $kode sudah digunakan!";
            return json_encode([-1,$msg]);
        }

        $sql = "INSERT INTO jbsakad.pelajaran
                   SET kode='$kode', departemen='$departemen', nama='$nama', sifat=$sifat, keterangan='$keterangan', idkelompok='$kelompok', urutan=$urutan, aktif=1";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "pelajaran1");
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
        $kode = RequestData("kode", "");
        $nama = RequestData("nama", "");
        $sifat = RequestData("sifat", 1);
        $kelompok = RequestData("kelompok", "");
        $keterangan = RequestData("keterangan", "");
        $urutan = RequestData("urutan", 0);

        $sql = "SELECT COUNT(replid) FROM jbsakad.pelajaran WHERE kode = '$kode' AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Singkatan $kode sudah digunakan!";
            return json_encode([-1,$msg]);
        }

        $sql = "UPDATE jbsakad.pelajaran
                   SET kode='$kode', nama='$nama', sifat=$sifat, keterangan='$keterangan', idkelompok='$kelompok', urutan=$urutan
                 WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "pelajaran2");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
?>