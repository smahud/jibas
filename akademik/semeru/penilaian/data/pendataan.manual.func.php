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
function SimpanNilaiAkhir()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idAturan = RequestData("idaturannhb", 0);
        $idKelas = RequestData("idkelas", 0);
        $idSemester = RequestData("idsemester", 0);
        $idJenisUjian = RequestData("idjenisujian", 0);
        $idPelajaran = RequestData("idpelajaran", 0);

        $db->BeginTrans();

        $nSiswa = RequestData("nsiswa", 0);
        for($i = 1; $i <= $nSiswa; $i++)
        {
            $nis = RequestData("$nis$i", "");
            $idNilaiNau = RequestData("idnilainau$i", 0);
            $nilaiNau = RequestData("nilainau$i", "");

            if ($idNilaiNau != 0)
            {
                $sql = 	"UPDATE jbsakad.nau 
                            SET nilaiAU = '$nilaiNau' 
                          WHERE replid = $idNilaiNau";
                $db->QueryDb($sql);
            }
            else
            {
                $sql = 	"INSERT INTO jbsakad.nau 
                            SET nis = '$nis',
                                idkelas = '$idKelas',
                                idsemester ='$idSemester',
                                idaturan ='$idAturan',
                                idjenis = '$idJenisUjian',
                                idpelajaran = '$idPelajaran',
                                nilaiAU = '$nilaiNau'";
                $db->QueryDb($sql);
            }
        }

        ShowToastAfterLoad("Data berhasil disimpan");

        //$db->RollbackTrans();
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