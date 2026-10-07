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
function LoadKegiatanKalender($db)
{
    global $replid, $tanggalAkhir, $tanggalAwal, $kode, $kegiatan, $keterangan, $tanggalAwalValue, $tanggalAkhirValue;

    $sql = "SELECT tanggalawal, tanggalakhir, kode, kegiatan, keterangan 
              FROM jbsakad.aktivitaskalender 
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $tanggalAwalValue = $row["tanggalawal"];
        $tanggalAwal = LongDateFormat($tanggalAwalValue);
        $tanggalAkhirValue = $row["tanggalakhir"];
        $tanggalAkhir = LongDateFormat($tanggalAkhirValue);
        $kode = $row["kode"];
        $kegiatan = $row["kegiatan"];
        $keterangan = $row["keterangan"];
    }
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idKalender = RequestData("idkalender","");
        $kode = RequestData("kode", "");
        $kegiatan = RequestData("kegiatan","");
        $tanggalAwal = RequestData("tanggalawal","0000-00-00");
        $tanggalAkhir = RequestData("tanggalakhir","0000-00-00");

        $keterangan = $_REQUEST["keterangan"];
        $keterangan = str_replace("`", "'", $keterangan);
        $keterangan = "<div style='font-size: 14px;'>$keterangan</div>";

        $sql = "INSERT INTO jbsakad.aktivitaskalender (idkalender, tanggalawal, tanggalakhir, kode, kegiatan, keterangan)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->PrepareStatement($sql);
        $stmt->bind_param("isssss", $idKalender, $tanggalAwal, $tanggalAkhir, $kode, $kegiatan, $keterangan);
        $stmt->execute();

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

        $replid = RequestData("replid","");
        $kode = RequestData("kode", "");
        $kegiatan = RequestData("kegiatan","");
        $tanggalAwal = RequestData("tanggalawal","0000-00-00");
        $tanggalAkhir = RequestData("tanggalakhir","0000-00-00");
        
        $keterangan = $_REQUEST["keterangan"];
        $keterangan = str_replace("`", "'", $keterangan);
        $keterangan = "<div style='font-size: 14px;'>$keterangan</div>";

        $sql = "UPDATE jbsakad.aktivitaskalender 
                   SET tanggalawal = ?,
                       tanggalakhir = ?,
                       kode = ?,
                       kegiatan = ?,
                       keterangan = ?
                 WHERE replid = ?";
        $stmt = $db->PrepareStatement($sql);
        $stmt->bind_param("sssssi", $tanggalAwal, $tanggalAkhir, $kode, $kegiatan, $keterangan, $replid);
        $stmt->execute();

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
