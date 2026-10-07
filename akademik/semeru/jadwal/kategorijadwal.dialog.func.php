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
function LoadKategori($db)
{
    global $replid, $kategori, $keterangan, $urutan;

    $sql = "SELECT deskripsi, keterangan, urutan
              FROM jbsakad.infojadwal
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $kategori = $row['deskripsi'];
        $keterangan = $row['keterangan'];
        $urutan = $row['urutan'];
    }             
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idTahunAjaran = RequestData("idtahunajaran", 0);
        $kategori = RequestData("kategori","");
        $urutan = RequestData("urutan", 0);
        $keterangan = RequestData("keterangan","");

        $sql = "INSERT INTO jbsakad.infojadwal (idtahunajaran, deskripsi, urutan, keterangan, aktif)
                VALUES ($idTahunAjaran, '$kategori', $urutan, '$keterangan', 1)";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil disimpan!"]);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        return json_encode([-1, $ex->getMessage()]);
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
        $kategori = RequestData("kategori","");
        $urutan = RequestData("urutan", 0);
        $keterangan = RequestData("keterangan","");

        $sql = "UPDATE jbsakad.infojadwal
                   SET deskripsi = '$kategori', urutan = $urutan, keterangan = '$keterangan'
                 WHERE replid = $replid";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil diupdate!"]);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>
