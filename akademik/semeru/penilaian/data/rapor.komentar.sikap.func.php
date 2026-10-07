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
function ShowSelectPredikat($index)
{
    global $predikat;

    echo "<select name='predikat$index' id='predikat$index' class='inputbox' style='width: 180px;'>";
    $sel = $predikat == 4 ? "selected" : "";
    echo "<option value='4' $sel>Istimewa</option>";
    $sel = $predikat == 3 ? "selected" : "";
    echo "<option value='3' $sel>Baik</option>";
    $sel = $predikat == 2 ? "selected" : "";
    echo "<option value='2' $sel>Cukup</option>";
    $sel = $predikat == 1 ? "selected" : "";
    echo "<option value='1' $sel>Kurang</option>";
    $sel = $predikat == 0 ? "selected" : "";
    echo "<option value='0' $sel>Buruk</option>";
    echo "</select>";
}

function NamaPredikat($predikat)
{
    switch($predikat)
    {
        case 4: return "Istimewa";
        case 3: return "Baik";
        case 2: return "Cukup";
        case 1: return "Kurang";
        case 0: return "Buruk";
        default: return "";
    }
}

function ShowSelectKomentarSikap($db, $idTingkat, $kdJenis, $index)
{
    $sql = "SELECT replid, komentar
              FROM jbsakad.pilihkomensos
             WHERE jenis = '$kdJenis'";
    $res = $db->QueryDb($sql);

    echo "<select class='inputbox' name='komensikap$index' id='komensikap$index' style='width: 400px;'>";
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

function PilihKomentarSikap($db, $replid, $kodeJenis)
{
    $sql = "SELECT komentar
              FROM jbsakad.pilihkomensos
             WHERE replid = '$replid'
               AND jenis = '$kodeJenis'";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        return base64_encode($row[0]);
    
    return "NOT FOUND";
}

function SimpanKomentarSikap()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $nJenis = RequestData("njenis", 0);
        $nis = RequestData("nis", 0);
        $nama = RequestData("nama", "");
        $idKelas = RequestData("idkelas", 0);
        $kelas = RequestData("kelas", 0);
        $idSemester = RequestData("idsemester", 0);
        $semester = RequestData("semester", 0);
        $penulis = SI_USER_LEVEL() == 0 ? "Administrator JIBAS" : SI_USER_NAME() . " (" . SI_USER_ID() . ")";

        $db->BeginTrans();

        for ($i = 0; $i < $nJenis; $i++)
        {
            $kodeJenis = RequestData("kodejenis$i", "");
            $predikat = RequestData("predikat$i", 3);
            $idKomenRapor = RequestData("idkomenrapor$i", 0);
            $komentar64 = RequestData("komentar64$i", "");
            $komentar = base64_decode($komentar64);

            if ($idKomenRapor == 0)
            {
                $sql = "INSERT INTO jbsakad.komenrapor 
                           SET nis = ?, idkelas = ?, idsemester = ?, jenis = ?, predikat = ?, 
                               komentar = ?, penulis = ?, waktu = NOW() ";               
                $stmt = $db->PrepareStatement($sql);
                $stmt->bind_param("siisiss", $nis, $idKelas, $idSemester, $kodeJenis, $predikat, $komentar, $penulis);
                $stmt->execute();

                $idKomenRapor = $db->InsertId();
            }
            else 
            {
                $sql = "UPDATE jbsakad.komenrapor 
                           SET predikat = ?, komentar = ?, penulis = ?, waktu = NOW() 
                         WHERE replid = ? ";
                $stmt = $db->PrepareStatement($sql);
                $stmt->bind_param("isss", $predikat, $komentar, $penulis, $idKomenRapor);
                $stmt->execute();
            }
        }

        $deskripsi = "Komentar sikap rapor siswa $nama ($nis) kelas $kelas semester $semester";
        RiwayatInput::Save($db, $departemen, "KRS", "1", $idKomenRapor, $deskripsi);

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