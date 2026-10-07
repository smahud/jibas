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

function ShowSelectJenisMutasi($db)
{
    $sql = "SELECT replid, jenismutasi
              FROM jbsakad.jenismutasi 
             ORDER BY jenismutasi";
    $res = $db->QueryDb($sql);
    echo "<select id='jenismutasi' class='inputbox' style='width:200px'>";
    while ($row = mysqli_fetch_array($res)) 
    {
        echo "<option value='$row[replid]'>$row[jenismutasi]</option>";
    }
    echo "</select>";             
}


function SimpanMutasi()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nSiswa = RequestData("nsiswa","");
        $departemen = RequestData("departemen","");
        $tglMutasi = RequestData("tglmutasi","");
        $jenisMutasi = RequestData("jenismutasi","");

        $db->BeginTrans();

        for($i = 1; $i <= $nSiswa; $i++)
        {
            $nis = RequestData("nis$i","");
            $idKelas = RequestData("idkelasawal$i","");
            $idTingkat = RequestData("idtingkatawal$i","");
            $keterangan = RequestData("keterangan$i","");

            $sql = "UPDATE jbsakad.siswa 
                       SET aktif = 0, statusmutasi = '$jenisMutasi', alumni=1
                     WHERE nis='$nis'";
            $db->QueryDb($sql);

            $sql = "UPDATE jbsakad.riwayatkelassiswa 
                       SET aktif = 0 
                     WHERE nis = '$nis'
                       AND aktif = 1";
            $db->QueryDb($sql);

            $sql = "UPDATE jbsakad.riwayatdeptsiswa 
                       SET aktif = 0 
                     WHERE nis = '$nis'
                       AND aktif = 1";
            $db->QueryDb($sql);

            $sql="INSERT INTO jbsakad.alumni 
                     SET nis='$nis', tgllulus='$tglMutasi', tktakhir='$idTingkat', klsakhir='$idKelas', 
                         departemen = '$departemen'";
            $db->QueryDb($sql);

            $sql = "INSERT INTO jbsakad.mutasisiswa 
                       SET nis='$nis', jenismutasi='$jenisMutasi', tglmutasi='$tglMutasi', 
                           keterangan='$keterangan', departemen = '$departemen'";
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