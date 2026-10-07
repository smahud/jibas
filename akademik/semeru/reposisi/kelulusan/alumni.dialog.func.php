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
function SimpanAlumni()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nSiswa = RequestData("nsiswa","");
        $departemen = RequestData("departemen","");

        $db->BeginTrans();

        for($i = 1; $i <= $nSiswa; $i++)
        {
            $nis = RequestData("nis$i","");
            $idKelasAwal = RequestData("idkelasawal$i","");
            $idTingkatAwal = RequestData("idtingkatawal$i","");

            $sql = "UPDATE jbsakad.siswa 
                       SET aktif=0, alumni=1 
                     WHERE nis='$nis'";
            $db->QueryDb($sql);

            $sql = "UPDATE jbsakad.riwayatkelassiswa
                       SET aktif=0
                     WHERE nis='$nis'
                       AND aktif=1";
            $db->QueryDb($sql);

            $sql = "UPDATE jbsakad.riwayatdeptsiswa
                       SET aktif = 0
                     WHERE nis='$nis'
                       AND aktif=1";
            $db->QueryDb($sql);

            $sql = "INSERT INTO jbsakad.alumni
                       SET nis='$nis', tgllulus = CURDATE(), tktakhir='$idTingkatAwal',
                           klsakhir='$idKelasAwal', departemen = '$departemen'";
            $db->QueryDb($sql);
        }

        $db->CommitTrans();
                
        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}

?>