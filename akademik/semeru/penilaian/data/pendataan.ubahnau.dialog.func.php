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
function SimpanUbahNau()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $idTahunAjaran = RequestData("idtahunajaran", "");
        $tahunAjaran = RequestData("tahunajaran", "");
        $idSemester = RequestData("idsemester", "");
        $semester = RequestData("semester", "");
        $idKelas = RequestData("idkelas", "");
        $kelas = RequestData("kelas", "");
        $idTingkat = RequestData("idtingkat", "");
        $tingkat = RequestData("tingkat", "");
        $idAturanNhb = RequestData("idaturannhb", "");
        $idPelajaran = RequestData("idpelajaran", "");
        $pelajaran = RequestData("pelajaran", "");
        $idJenisUjian = RequestData("idjenisujian", "");
        $jenisUjian = RequestData("jenisujian", "");
        $idUjian = RequestData("idujian", 0);
        $idNilaiNau = RequestData("idnilainau", 0);
        $nis = RequestData("nis", "");
        $nama = RequestData("nama", "");
        $nilai = RequestData("nilai", "");
        $nilaiAsli = RequestData("nilaiasli", "");
        $keterangan = RequestData("keterangan", "");
        $alasan = RequestData("alasan", "");

        $db->BeginTrans();

        $pengguna = SI_USER_ID() . " - ". SI_USER_NAME();
        $infoAudit = "Ubah nilai akhir ujian $jenisUjian $pelajaran siswa $nama ($nis) tanggal " . date('Y-m-d H:i:s');
        $infoAudit = SafeInput($infoAudit);
        $sql = "INSERT INTO jbsakad.auditnilai 
                   SET jenisnilai='nau', idnilai='$idNilaiNau', nasli='$nilaiAsli', 
                       nubah='$nilai', alasan='$alasan', pengguna='$pengguna', informasi='$infoAudit'";
        $db->QueryDb($sql);

        $sql = "UPDATE jbsakad.nau 
                   SET nilaiAU='$nilai', 
                       keterangan='Nilai akhir diubah manual', 
                       info1=1 
                 WHERE replid='$idNilaiNau'";
        $db->QueryDb($sql);

        $db->CommitTrans();

        return json_encode([1, "OK"]);
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