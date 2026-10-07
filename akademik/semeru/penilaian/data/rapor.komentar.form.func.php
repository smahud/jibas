<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
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
function ShowSelectKomentarPelajaran($db, $idPelajaran, $idTingkat, $kdaspek, $index)
{
    $sql = "SELECT replid, komentar, urutan
              FROM jbsakad.pilihkomenpel
             WHERE idpelajaran = '$idPelajaran'
               AND dasarpenilaian = '$kdaspek'
             ORDER BY urutan";
    $res = $db->QueryDb($sql);
    
    echo "<select class='inputbox' name='komenpel$index' id='komenpel$index' style='width: 400px;'>";
    $no = 0;
    while($row = mysqli_fetch_row($res))
    {
        $no += 1;
        $replid = $row[0];
        $komentar = $row[1];

        $komentar = strip_tags($komentar);
        if (strlen($komentar) > 50)
            $komentar = substr($komentar, 0, 50) . " ..";

        echo "<option value='$replid'>$no. $komentar</option>";
    }
    echo "</select>";
}

function PilihKomentarPelajaran($db, $replid)
{
    $sql = "SELECT komentar
              FROM jbsakad.pilihkomenpel
             WHERE replid = '$replid'";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        return base64_encode($row[0]);
    
    return "NOT FOUND";
}

function SimpanKomentarNilai()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $nAspek = RequestData("naspek", 0);
        $idTingkat = RequestData("idtingkat", 0);
        $tingkat = RequestData("tingkat", "");
        $idPelajaran = RequestData("idpelajaran", 0);
        $pelajaran = RequestData("pelajaran", "");
        $nis = RequestData("nis", "");
        $nama = RequestData("nama", "");
        $kelas = RequestData("kelas", "");
        $semester = RequestData("semester", "");
        $penulis = SI_USER_LEVEL() == 0 ? "Administrator JIBAS" : SI_USER_NAME() . " (" . SI_USER_ID() . ")";

        $db->BeginTrans();

        for ($i = 0; $i < $nAspek; $i++)
        {
            $kodeAspek = RequestData("kodeaspek$i", "");
            $namaAspek = RequestData("namaaspek$i", "");
            $idNap = RequestData("idnap$i", 0);
            $komentar64 = RequestData("komentar64$i", "");
            $komentar = base64_decode($komentar64);

            $sql = "UPDATE jbsakad.nap 
                       SET komentar = ?, penulis = ?, waktu = NOW()
                     WHERE replid = ?";
            $stmt = $db->PrepareStatement($sql);
            $stmt->bind_param("ssi", $komentar, $penulis, $idNap);
            $stmt->execute();
        }

        $deskripsi = "Komentar nilai rapor $pelajaran siswa $nama ($nis) kelas $kelas semester $semester";
        RiwayatInput::Save($db, $departemen, "KRN", "1", $idNap, $deskripsi);

        $db->CommitTrans();

        return json_encode([1, "OK"]);
    }
    catch (Exception $e)
    {
        return json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>
