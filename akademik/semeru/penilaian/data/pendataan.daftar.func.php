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
function RecountRerata()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idSemester = RequestData("idsemester", "");
        $idKelas = RequestData("idkelas", "");
        $idAturan = RequestData("idaturan", "");

        $db->BeginTrans();

        RecountRerataFunc($db, $idKelas, $idSemester, $idAturan);
       
        $db->CommitTrans();

        ShowToastAfterLoad("Penhitungan ulang rata-rata selesai");

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

function RecountRerataFunc($db, $idKelas, $idSemester, $idAturan)
{
    $lsNis = [];
    $sql = "SELECT nis 
              FROM jbsakad.siswa 
             WHERE idkelas = '$idKelas' 
               AND aktif=1";
    $res = $db->QueryDb($sql);
    while ($row = mysqli_fetch_row($res))
    {
        $lsNis[] = $row[0];
    }

    $nSiswa = count($lsNis);
    for($i = 0; $i < $nSiswa; $i++)
    {
        $nis = $lsNis[$i];
        HitungRataSiswa($db, $idKelas, $idSemester, $idAturan, $nis);
    }

    $lsUjian = [];
    $sql = "SELECT replid 
              FROM jbsakad.ujian 
             WHERE idkelas='$idKelas' 
               AND idsemester='$idSemester' 
               AND idaturan='$idAturan'";
    $res = $db->QueryDb($sql);
    while ($row = mysqli_fetch_row($res))
    {
        $lsUjian[] = $row[0];
    }

    $nUjian = count($lsUjian);
    for($i = 0; $i < $nUjian; $i++)
    {
        $idUjian = $lsUjian[$i];
        HitungRataKelasUjian($db, $idKelas, $idSemester, $idUjian);
    }
}

function HapusNilaiUjian()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idUjian = RequestData("idujian", 0);
        $idAturan = RequestData("idaturan", 0);
        $idKelas = RequestData("idkelas", 0);
        $idSemester = RequestData("idsemester", 0);
        
        $db->BeginTrans();

        $sql = "DELETE FROM jbsakad.nau 
                 WHERE idaturan ='$idAturan' 
                   AND idkelas = '$idKelas' 
                   AND idsemester = '$idSemester'";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.nilaiujian 
                 WHERE idujian = '$idUjian'";	
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.ujian 
                 WHERE replid = '$idUjian'";	
    
        $db->QueryDb($sql);

        RecountRerataFunc($db, $idKelas, $idSemester, $idAturan);

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

function HapusNau()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idAturan = RequestData("idaturan", 0);
        $idKelas = RequestData("idkelas", 0);
        $idSemester = RequestData("idsemester", 0);

        $sql = "DELETE FROM jbsakad.nau 
                 WHERE idaturan='$idAturan' 
                   AND idkelas='$idKelas' 
                   AND idsemester='$idSemester'";
        $db->QueryDb($sql);

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

function HitungNauOtomatis()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idAturan = RequestData("idaturan", 0);
        $idKelas = RequestData("idkelas", 0);
        $idSemester = RequestData("idsemester", 0);
        $idJenisUjian = RequestData("idjenisujian", 0);
        $idPelajaran = RequestData("idpelajaran", 0);

        $lsSelected = [];
        $lsDeleted = [];
        $nBobot = RequestData("nbobot", 0);
        for($i = 1; $i <= $nBobot; $i++)
        {
            $ckBobot = RequestData("ckbobot$i", 0);
            $idUjian = RequestData("idujian$i", 0);
            $idBobot = RequestData("idbobot$i", 0);
            $bobot = RequestData("bobot$i", 0);

            if ($ckBobot == 1)
            {
                $lsSelected[] = [$idUjian, $idBobot, $bobot];
            }
            else 
            {
                if ($idBobot != 0)
                    $lsDeleted[] = $idBobot;
            }
        }


        $lsNis = [];
        $sql = "SELECT nis 
                  FROM jbsakad.siswa 
                 WHERE idkelas = '$idKelas' 
                   AND aktif = 1";
        $res = $db->QueryDb($sql);
        while ($row = mysqli_fetch_row($res))
        {
            $lsNis[] = $row[0];
        }

        $db->BeginTrans();

        $nNis = count($lsNis);
        $nSelected = count($lsSelected);
        for ($i = 0; $i < $nNis; $i++)
        {
            $nis = $lsNis[$i];

            $nisTotalNilai = 0;
            $nisTotalBobot = 0;
            for($j = 0; $j < $nSelected; $j++)
            {
                $idUjian = $lsSelected[$j][0];
                $idBobot = $lsSelected[$j][1];
                $bobot = $lsSelected[$j][2];

                $nilaiUjian = 0;
                $sql = "SELECT nilaiujian 
                          FROM jbsakad.nilaiujian 
                          WHERE idujian = '$idUjian' AND nis = '$nis'";
                $res = $db->QueryDb($sql);
                if ($row = mysqli_fetch_row($res))
                {
                    $nilaiUjian = $row[0];
                }

                $nisTotalNilai += ($nilaiUjian * $bobot);
                $nisTotalBobot += $bobot;
            }

            $nisNau = 0;
            if ($nisTotalBobot > 0)
                $nisNau = round($nisTotalNilai / $nisTotalBobot, 2);

            $sql = 	"SELECT replid    
				       FROM jbsakad.nau 
			          WHERE nis = '$nis' 
                        AND idkelas = '$idKelas' 
                        AND idsemester ='$idSemester' 
                        AND idaturan = '$idAturan'";
		    $res = $db->QueryDb($sql);
            if ($row = mysqli_fetch_row($res))
            {
                $idNau = $row[0];
                $sql = 	"UPDATE jbsakad.nau 
                            SET nilaiAU = '$nisNau' 
                          WHERE replid = $idNau";
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
                                nilaiAU = '$nisNau'";
                $db->QueryDb($sql);
            }
        }

        foreach ($lsDeleted as $idBobot)
        {
            $sql = "DELETE FROM jbsakad.bobotnau 
                    WHERE replid = '$idBobot'";
            $db->QueryDb($sql);
        }

        for($i = 0; $i < count($lsSelected); $i++)
        {
            $idUjian = $lsSelected[$i][0];
            $idBobot = $lsSelected[$i][1];
            $bobot = $lsSelected[$i][2];

            if ($idBobot != 0)
            {
                $sql = "UPDATE jbsakad.bobotnau 
                           SET bobot = '$bobot' 
                         WHERE replid = '$idBobot'";
                $db->QueryDb($sql);
            }
            else
            {
                $sql = 	"INSERT INTO jbsakad.bobotnau 
                            SET idujian = '$idUjian',
                                idaturan = '$idAturan',
                                bobot = '$bobot'";
                $db->QueryDb($sql);
            }
        }

        $db->CommitTrans();

        ShowToastAfterLoad("Data berhasil disimpan");

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