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
function LoadRpp()
{
    global $replid, $kodeRpp, $materi, $deskripsi, $urutan;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT koderpp, rpp, deskripsi, urutan
                  FROM jbsakad.rpp
                 WHERE replid = $replid";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_assoc($res))
        {
            $kodeRpp = $row['koderpp'];
            $materi = $row['rpp'];
            $deskripsi = $row['deskripsi'];
            $urutan = $row['urutan'];
        }
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "kjpgd");

        return null;
    }
    finally
    {
        $db->Close();
    }
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $kodeRpp = RequestData("koderpp", "");
        $materi = RequestData("materi", "");
        $urutan = RequestData("urutan", 0);
        $departemen = RequestData("departemen", 0);
        $idpelajaran = RequestData("idpelajaran", 0);
        $pelajaran = RequestData("pelajaran", "");
        $idsemester = RequestData("idsemester", 0);
        $semester = RequestData("semester", "");
        $idtingkat = RequestData("idtingkat", 0);
        $tingkat = RequestData("tingkat", "");

        $deskripsi = $_REQUEST["deskripsi"];
        $deskripsi = str_replace("`", "'", $deskripsi);
        $deskripsi = "<div style='font-size: 14px;'>$deskripsi</div>";

        $deskripsi_data = stripHtmlTags($deskripsi);

        $db->BeginTrans();

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.rpp
                 WHERE idpelajaran = '$idpelajaran'
                   AND idsemester = '$idsemester'
                   AND idtingkat = '$idtingkat'
                   AND koderpp = '$kodeRpp'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
            return json_encode(["-1", "Kode RPP $kodeRpp sudah digunakan"]);
        
        $sql = "INSERT INTO jbsakad.rpp 
                   SET idtingkat = ?, idsemester = ?, idpelajaran = ?, 
                       koderpp = ?, rpp = ?, deskripsi = ?, deskripsi_data = ?,
                       aktif = 1, urutan = ?";
        $stmt = $db->PrepareStatement($sql);
        $stmt->bind_param("iiissssi", $idtingkat, $idsemester, $idpelajaran, $kodeRpp, $materi, $deskripsi, $deskripsi_data, $urutan);
        $stmt->execute();

        $sql = "SELECT LAST_INSERT_ID()";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $idRpp = $row[0];

        $deskripsi = "Pendataan RPP $pelajaran ($semester - $tingkat) kode $kodeRpp";
        RiwayatInput::Save($db, $departemen, "RPP", "1", $idRpp, $deskripsi);

        $db->CommitTrans();

        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();

        Logger::LogErrorOnce($ex, "kxnpb");

        return json_encode(["-1", $ex->getMessage()]);
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
        $kodeRpp = RequestData("koderpp", "");
        $materi = RequestData("materi", "");
        $urutan = RequestData("urutan", 0);
        $idpelajaran = RequestData("idpelajaran", 0);
        $idsemester = RequestData("idsemester", 0);
        $idtingkat = RequestData("idtingkat", 0);

        $deskripsi = $_REQUEST["deskripsi"];
        $deskripsi = str_replace("`", "'", $deskripsi);
        $deskripsi = "<div style='font-size: 14px;'>$deskripsi</div>";

        $deskripsi_data = stripHtmlTags($deskripsi);

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.rpp
                 WHERE idpelajaran = '$idpelajaran'
                   AND idsemester = '$idsemester'
                   AND idtingkat = '$idtingkat'
                   AND koderpp = '$kodeRpp'
                   AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
            return json_encode(["-1", "Kode RPP $kodeRpp sudah digunakan"]);

        $sql = "UPDATE jbsakad.rpp 
                   SET koderpp = ?, rpp = ?, deskripsi = ?, deskripsi_data = ?, urutan = ?
                 WHERE replid = ?";
        $stmt = $db->PrepareStatement($sql);
        $stmt->bind_param("sssssi", $kodeRpp, $materi, $deskripsi, $deskripsi_data, $urutan, $replid);
        $stmt->execute();

        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "kxnpb");

        return json_encode(["-1", $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }   
}
?>