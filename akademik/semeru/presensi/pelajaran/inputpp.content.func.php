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
function ShowSelectStatusGuru($db)
{
    global $idStatusGuru;

    try
    {   
        $sql = "SELECT replid, status 
                  FROM jbsakad.statusguru
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        
        echo "<select id='statusguru'  class='inputbox' style='width:200px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idStatusGuru == 0) 
                $idStatusGuru = $row['replid'];
            $sel = $idStatusGuru == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[status]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kzyqk");
    }
}

function SimpanEdit()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idPresensi = RequestData("idpresensi", 0);
        $departemen = RequestData("departemen", "");
        $idKelas = RequestData("idkelas", 0);
        $idPelajaran = RequestData("idpelajaran", 0);
        $idSemester = RequestData("idsemester", 0);
        $nipGuru = RequestData("nipguru", "");
        $statusGuru = RequestData("statusguru", 0);
        $jumlah = RequestData("jumlah", 0);
        $telat = RequestData("telat", 0);
        $materi = RequestData("materi", "");
        $refleksi = RequestData("refleksi", "");
        $keterangan = RequestData("keterangan", "");
        $waktu = RequestData("waktu", "");
        $nSiswa = RequestData("nsiswa", 0);

        $db->BeginTrans();

        $sql = "UPDATE jbsakad.presensipelajaran 
                   SET gurupelajaran='$nipGuru', jenisguru='$statusGuru', keterangan='$keterangan', 
                       materi='$materi', refleksi='$refleksi', keterlambatan='$telat', jumlahjam='$jumlah' 
                 WHERE replid='$idPresensi'";
        $db->QueryDb($sql);
        
        for($i = 1; $i <= $nSiswa; $i++)
        {
            $idPp = RequestData("idpp$i", 0);
            $nis = RequestData("nis$i", "");
            $status = RequestData("status$i", 0);
            $catatan = RequestData("catatan$i", "");

            $sql = "UPDATE jbsakad.ppsiswa
                       SET statushadir = '$status', catatan = '$catatan'
                     WHERE replid = $idPp";
            $db->QueryDb($sql);                     
        }

        $db->CommitTrans();

        return json_encode([1, "UPDATE", $idPresensi]);
    }
    catch(Exception $e)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        return json_encode([-1, $e->getMessage()]);
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

        $idPresensi = RequestData("idpresensi", 0);
        $departemen = RequestData("departemen", "");
        $idKelas = RequestData("idkelas", 0);
        $kelas = RequestData("kelas", "");
        $idPelajaran = RequestData("idpelajaran", 0);
        $pelajaran = RequestData("pelajaran", "");
        $idSemester = RequestData("idsemester", 0);
        $nipGuru = RequestData("nipguru", "");
        $namaGuru = RequestData("namaguru", "");
        $statusGuru = RequestData("statusguru", 0);
        $jumlah = RequestData("jumlah", 0);
        $telat = RequestData("telat", 0);
        $materi = RequestData("materi", "");
        $refleksi = RequestData("refleksi", "");
        $keterangan = RequestData("keterangan", "");
        $tanggal = RequestData("tanggal", "");
        $waktu = RequestData("waktu", "");
        $nSiswa = RequestData("nsiswa", 0);

        $db->BeginTrans();

        $sql = "INSERT INTO jbsakad.presensipelajaran 
                   SET idkelas='$idKelas', idsemester='$idSemester', idpelajaran='$idPelajaran', 
                       tanggal='$tanggal', jam='$waktu', gurupelajaran='$nipGuru', jenisguru='$statusGuru', 
                       keterangan='$keterangan', refleksi='$refleksi', materi='$materi', keterlambatan='$telat', jumlahjam='$jumlah'";
        $db->QueryDb($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_array($res);
        $idPresensi = $row[0];
        
        for($i = 1; $i <= $nSiswa; $i++)
        {
            $nis = RequestData("nis$i", "");
            $status = RequestData("status$i", 0);
            $catatan = RequestData("catatan$i", "");

            $sql = "INSERT INTO jbsakad.ppsiswa
                       SET idpp = '$idPresensi', nis = '$nis', 
                           statushadir = '$status', catatan = '$catatan'";
            $db->QueryDb($sql);                     
        }

        $deskripsi = "Presensi Pelajaran $pelajaran kelas $kelas guru  $namaGuru ($nipGuru) tanggal " . LongDateFormat($tanggal);
        RiwayatInput::Save($db, $departemen, "PP", "1", $idPresensi, $deskripsi);

        $db->CommitTrans();

        return json_encode([1, "INSERT", $idPresensi]);
    }
    catch(Exception $e)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        return json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}


function HapusPresensiPelajaran()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idPresensi = RequestData("idpresensi", 0);

        $db->BeginTrans();

        $sql = "DELETE FROM jbsakad.ppsiswa
                 WHERE idpp = '$idPresensi'";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.presensipelajaran
                 WHERE replid = '$idPresensi'";
        $db->QueryDb($sql);

        RiwayatInput::Delete($db, "PP", "1", $idPresensi);

        $db->CommitTrans();

        return json_encode([1, "DELETE", $idPresensi]);
    }
    catch(Exception $e)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        return json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>