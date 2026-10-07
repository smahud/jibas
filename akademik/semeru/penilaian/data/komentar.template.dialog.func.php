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
function SimpanKomentarNilai()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idPelajaran = RequestData("idpelajaran", "");
        $idTingkat = RequestData("idtingkat", "");
        $kodeAspek = RequestData("kodeaspek", "");
        $urutan = RequestData("urutan", "");
        $komentar64 = RequestData("komentar64", "IA==");
        $komentar = base64_decode($komentar64);
        $penulis = SI_USER_LEVEL() == 0 ? "Administrator JIBAS" : SI_USER_NAME() . " (" . SI_USER_ID() . ")";

        $sql = "INSERT INTO jbsakad.pilihkomenpel 
                   SET idpelajaran=?, idtingkat=?, dasarpenilaian=?, urutan=?, komentar=?, penulis=?, waktu=NOW()";
        $stmt = $db->PrepareStatement($sql);
        $stmt->bind_param("iisiss", $idPelajaran, $idTingkat, $kodeAspek, $urutan, $komentar, $penulis);
        $stmt->execute();

        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}

function SimpanKomentarSikap()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idTingkat = RequestData("idtingkat", "");
        $kodeJenis = RequestData("kodejenis", "");
        $urutan = RequestData("urutan", "");
        $komentar64 = RequestData("komentar64", "IA==");
        $komentar = base64_decode($komentar64);
        $penulis = SI_USER_LEVEL() == 0 ? "Administrator JIBAS" : SI_USER_NAME() . " (" . SI_USER_ID() . ")";

        $sql = "INSERT INTO jbsakad.pilihkomensos 
                   SET idtingkat=?, jenis=?, urutan=?, komentar=?, penulis=?, waktu=NOW()";
        $stmt = $db->PrepareStatement($sql);
        $stmt->bind_param("isiss", $idTingkat, $kodeJenis, $urutan, $komentar, $penulis);
        $stmt->execute();

        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>