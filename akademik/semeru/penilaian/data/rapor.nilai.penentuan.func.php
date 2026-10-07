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
function SimpanNilaiRapor()
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
        $idPelajaran = RequestData("idpelajaran", "");
        $pelajaran = RequestData("pelajaran", "");
        $dasarPenilaian = RequestData("dasarpenilaian", "");
        $judulPenilaian = RequestData("judulpenilaian", "");
        $nip = RequestData("nip", "");
        $nama = RequestData("nama", "");

        $nSiswa = RequestData("nsiswa", 0);
        $nilaiKkm = RequestData("nilaikkm", 0);
        $idInfo = RequestData("idinfo", 0);

        $db->BeginTrans();

        if ($idInfo == 0)
        {
            $sql = "INSERT INTO jbsakad.infonap 
                       SET idpelajaran = '$idPelajaran', idsemester='$idSemester', idkelas='$idKelas', nilaimin='$nilaiKkm'";
            $db->QueryDb($sql);
            $idInfo = $db->InsertID();
        }
        else 
        {
            $sql = "UPDATE jbsakad.infonap 
                       SET nilaimin = '$nilaiKkm' 
                     WHERE replid = '$idInfo'";
            $db->QueryDb($sql);
        }

        $sql = "SELECT a.replid
				  FROM jbsakad.aturannhb a, jbsakad.kelas k 
				 WHERE a.nipguru = '$nip' 
				   AND a.idtingkat = k.idtingkat 
                   AND k.replid = '$idKelas' 
				   AND a.idpelajaran = '$idPelajaran' 
                   AND a.dasarpenilaian = '$dasarPenilaian' 
                 ORDER BY a.replid ASC LIMIT 1";
		$res = $db->QueryDb($sql);
		$row = mysqli_fetch_array($res);
		$idAturan = $row['replid'];

        for($i = 1; $i <= $nSiswa; $i++)
        {
            $nis = RequestData("nis" . $i, "");
            $nilaiAngka = RequestData("nilaiangka" . $i, "");
            $nilaiHuruf = RequestData("nilaihuruf" . $i, "");

            $idNap = 0;
            $sql = "SELECT replid
                      FROM jbsakad.nap
                     WHERE nis='$nis'
                       AND idaturan='$idAturan'
                       AND idinfo='$idInfo'";
            $res = $db->QueryDb($sql);
            if ($row = mysqli_fetch_row($res))
                $idNap = $row[0];

            if ($idNap == 0)
            {
                $sql = "INSERT INTO jbsakad.nap 
                           SET nis='$nis',
                               idinfo='$idInfo',
                               idaturan='$idAturan',
                               nilaiangka='$nilaiAngka',
                               nilaihuruf='$nilaiHuruf',
                               komentar=''";
                $db->QueryDb($sql);

                $sql = "INSERT INTO jbsakad.komennap 
                           SET nis = '$nis',
                               idinfo = '$idInfo',
                               predikat = '',
                               komentar = ''";
                $db->QueryDb($sql);
            }
            else
            {
                $sql = "UPDATE jbsakad.nap 
                           SET nilaiangka='$nilaiAngka',
                               nilaihuruf='$nilaiHuruf',
                               idaturan='$idAturan'
                         WHERE replid='$idNap'";
                $db->QueryDb($sql);
            }
            
        }

        $db->CommitTrans();

        ShowToastAfterLoad("Nilai rapor telah tersimpan", "success", "bottom");

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

function HitungUlangSimpanNilaiRapor()
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
        $idPelajaran = RequestData("idpelajaran", "");
        $pelajaran = RequestData("pelajaran", "");
        $dasarPenilaian = RequestData("dasarpenilaian", "");
        $judulPenilaian = RequestData("judulpenilaian", "");
        $nip = RequestData("nip", "");
        $nama = RequestData("nama", "");

        $idInfo = 0;
        $sql = "SELECT replid, nilaimin 
                  FROM jbsakad.infonap 
                 WHERE idpelajaran = '$idPelajaran' 
                   AND idsemester = '$idSemester' 
                   AND idkelas = '$idKelas'";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            $idInfo = $row[0];

            $sql = "SELECT COUNT(n.replid)
                      FROM jbsakad.aturannhb a, jbsakad.kelas k, jbsakad.nap n
                     WHERE n.idaturan = a.replid 
                       AND a.nipguru = '$nip' 
                       AND a.idtingkat = k.idtingkat 
                       AND k.replid = '$idKelas'
                       AND a.idpelajaran = '$idPelajaran' 
                       AND a.dasarpenilaian = '$dasarPenilaian'
                       AND n.idinfo = '$idInfo' ";
            $res = $db->QueryDb($sql);
            $row = mysqli_fetch_row($res);
        }

        // get jumlah pengujian
        $lsAturanNhb = [];
        $sql = "SELECT j.jenisujian as jenisujian, a.bobot as bobot, a.replid, a.idjenisujian 
                  FROM jbsakad.aturannhb a, jbsakad.jenisujian j, jbsakad.kelas k 
                 WHERE a.idtingkat = k.idtingkat 
                   AND k.replid = '$idKelas' 
                   AND a.nipguru = '$nip' 
                   AND a.idpelajaran = '$idPelajaran' 
                   AND a.dasarpenilaian = '$dasarPenilaian' 
                   AND a.idjenisujian = j.replid 
                   AND a.aktif = 1 
                 ORDER BY j.urutan, j.jenisujian";  
        $res = $db->QueryDb($sql);
        $jumNhb = mysqli_num_rows($res);
        while ($row = mysqli_fetch_assoc($res))
        {
            $lsItem = [$row['replid'], $row['bobot'], $row['idjenisujian'], $row['jenisujian']];
            $lsAturanNhb[] = $lsItem;
        }

        //Ambil nilai grading
        $lsGrading = [];
        $sql = "SELECT grade, nmin, nmax 
                  FROM jbsakad.aturangrading a, jbsakad.kelas k 
                 WHERE a.idpelajaran = '$idPelajaran' 
                   AND a.idtingkat = k.idtingkat 
                   AND k.replid = '$idKelas' 
                   AND a.dasarpenilaian = '$dasarPenilaian' 
                   AND a.nipguru = '$nip'
                 ORDER BY nmin DESC";
        $res = $db->QueryDb($sql);
        while ($row = mysqli_fetch_array($res)) 
        {
            $lsGrading[] = [ $row['grade'], $row['nmin'], $row['nmax'] ];
        }
        $nGrading = count($lsGrading);
        
        $lsNilaiRapor = [];
        $sql = "SELECT replid, nis, nama 
                  FROM jbsakad.siswa 
                 WHERE idkelas = '$idKelas' 
                   AND aktif = 1 
                 ORDER BY nama";
        $res = $db->QueryDb($sql);
        $cnt = 0;
        $nSiswa = mysqli_num_rows($res);
        while ($row = mysqli_fetch_assoc($res))
        {
            $cnt += 1;

            $replid = $row["replid"];
            $nis = $row["nis"];
            $nama = $row["nama"];

	        $totalNau = 0;
            $totalBobot = 0;
            for($i = 0; $i < $jumNhb; $i++ )
            {
                $idAturan = $lsAturanNhb[$i][0];
                $bobot = $lsAturanNhb[$i][1];
                $idJenisUjian = $lsAturanNhb[$i][2];
                $jenisUjian = $lsAturanNhb[$i][3];
                $totalBobot += $bobot;

                $sql = "SELECT n.nilaiAU as nilaiujian 
                          FROM jbsakad.nau n, jbsakad.aturannhb a 
                         WHERE n.idpelajaran = '$idPelajaran' 
                           AND n.idkelas = '$idKelas' 
                           AND n.nis = '$nis' 
                           AND n.idsemester = '$idSemester' 
                           AND n.idjenis = '$idJenisUjian' 
                           AND n.idaturan = a.replid 
                           AND a.replid = '$idAturan'";
                $res2 = $db->QueryDb($sql);
                if ($row2 = mysqli_fetch_assoc($res2))
                {
                    $nilaiUjian = $row2["nilaiujian"];
                    $totalNau += $nilaiUjian * $bobot;
                }
                else
                {
			        $nilaiUjian = 0;
		        }
            }
	
            if ($totalBobot == 0)
            {
                $nilaiAngka = 0;
                $nilaiHuruf = $lsGrading[$nGrading - 1][0];
            }
            else
            {
                $nilaiAngka = round($totalNau / $totalBobot, 2);
                for ($i = 0; $i < $nGrading; $i++)
                {
                    $nmin = $lsGrading[$i][1];
                    $nmax = $lsGrading[$i][2];
                    if ($nilaiAngka >= $nmin && $nilaiAngka < $nmax)
                    {
                        $nilaiHuruf = $lsGrading[$i][0];
                        break;
                    }
                }       
            }      
            
            $lsNilaiRapor[] = [$nis, $nilaiAngka, $nilaiHuruf];
        } // while

        for($i = 0; $i < count($lsNilaiRapor); $i++)
        {
            $nis = $lsNilaiRapor[$i][0];
            $nilaiAngka = $lsNilaiRapor[$i][1];
            $nilaiHuruf = $lsNilaiRapor[$i][2];

            $idNap = 0;
            $sql = "SELECT replid
                      FROM jbsakad.nap
                     WHERE nis='$nis'
                       AND idinfo='$idInfo'";
            $res = $db->QueryDb($sql);
            if ($row = mysqli_fetch_row($res))
                $idNap = $row[0];

            if ($idNap == 0)
            {
                $sql = "INSERT INTO jbsakad.nap 
                           SET nis='$nis',
                               idinfo='$idInfo',
                               idaturan='$idAturan',
                               nilaiangka='$nilaiAngka',
                               nilaihuruf='$nilaiHuruf'";
            }
            else
            {
                $sql = "UPDATE jbsakad.nap 
                           SET nilaiangka='$nilaiAngka',
                               nilaihuruf='$nilaiHuruf',
                               idaturan='$idAturan'
                         WHERE replid='$idNap'";
            }
            $db->QueryDb($sql);
        }
        
        $db->CommitTrans();

        ShowToastAfterLoad("Nilai rapor telah dihitung ulang dan tersimpan", "success", "bottom");

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

function HapusNilaiKomentarRapor()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idInfo = RequestData("idinfo", 0);

        $db->BeginTrans();

        $sql = "DELETE FROM jbsakad.nap WHERE idinfo='$idInfo'";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.komennap WHERE idinfo='$idInfo'";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.infonap WHERE replid='$idInfo'";
        $db->QueryDb($sql);

        $db->CommitTrans();

        ShowToastAfterLoad("Nilai rapor dan komentar telah dihapus", "success", "bottom");

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