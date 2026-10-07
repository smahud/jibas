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
function SimpanKelulusan()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nSiswa = RequestData("nsiswa","");
        $departemen = RequestData("departemen","");
        $idTingkat = RequestData("idtingkat","");
        $departemenTujuan = RequestData("departementujuan","");
        $idAngkatanTujuan = RequestData("idangkatantujuan","");
        $idTahunAjaranTujuan = RequestData("idtahunajarantujuan","");
        $idTingkatTujuan = RequestData("idtingkattujuan","");
        $idKelasTujuan = RequestData("idkelastujuan","");

        $db->BeginTrans();

        for($i = 1; $i <= $nSiswa; $i++)
        {
            $nis = RequestData("nis$i","");
            $idKelasAwal = RequestData("idkelasawal$i","");
            $nisBaru = RequestData("nisbaru$i","");
            $keterangan = RequestData("keterangan$i","");

            if ($nis == $nisBaru)
            {
                $sql = "UPDATE jbsakad.riwayatdeptsiswa 
                           SET aktif = 0 
                         WHERE nis = '$nis' 
                           AND departemen = '$departemen' 
                           AND aktif = 1";
                $db->QueryDb($sql);

                $sql = "INSERT INTO jbsakad.riwayatdeptsiswa 
                           SET nis = '$nisBaru', departemen = '$departemenTujuan', mulai = CURDATE(), status = 1, 
                               keterangan = '$keterangan', nislama = '$nis'";				
                $db->QueryDb($sql);                               

                $sql = "INSERT INTO jbsakad.alumni 
                           SET nis='$nis', tgllulus=CURDATE(), tktakhir='$idTingkat', klsakhir='$idKelasAwal', 
                               departemen = '$departemen'";
                $db->QueryDb($sql);

                $sql = "UPDATE jbsakad.riwayatkelassiswa 
                           SET aktif=0 
                         WHERE nis='$nis' 
                           AND idkelas='$idKelasAwal' 
                           AND aktif = 1";
                $db->QueryDb($sql);

                $sql = "INSERT INTO jbsakad.riwayatkelassiswa 
                           SET nis='$nisBaru', 
                               idkelas='$idKelasTujuan', 
                               mulai=CURDATE(), 
                               keterangan='$keterangan', 
                               aktif=1, 
                               status=1";
                $db->QueryDb($sql);

                $sql = "UPDATE jbsakad.siswa 
                           SET idkelas='$idKelasTujuan', 
                               idangkatan='$idAngkatanTujuan', 
                               tahunmasuk=YEAR(CURDATE()), 
                               frompsb=0 
                         WHERE nis='$nis'";
                $db->QueryDb($sql);
            }
            else 
            {
                $sql= "INSERT INTO jbsakad.siswa
				            (nis,nisn,nama,panggilan,aktif,tahunmasuk,idangkatan,idkelas,suku,agama,`status`,kondisi,kelamin,tmplahir,tgllahir,warga,anakke,jsaudara,bahasa,berat,tinggi,darah,
				             alamatsiswa,kodepossiswa,telponsiswa,hpsiswa,emailsiswa,kesehatan,ketsekolah,namaayah,namaibu,almayah,almibu,wali,penghasilanayah,penghasilanibu,alamatortu,telponortu,
				             hportu,emailayah,emailibu,alamatsurat,keterangan,pinsiswa,pinortu,pinortuibu,asalsekolah,pendidikanayah,pendidikanibu,pekerjaanayah,pekerjaanibu,foto,info1,info2,info3) 
				        (SELECT '$nisBaru',nisn,nama,panggilan,1,YEAR(CURDATE()),'$idAngkatanTujuan','$idKelasTujuan',suku,agama,`status`,kondisi,kelamin,tmplahir,tgllahir,warga,anakke,jsaudara,bahasa,berat,tinggi,darah,
				         alamatsiswa,kodepossiswa,telponsiswa,hpsiswa,emailsiswa,kesehatan,ketsekolah,namaayah,namaibu,almayah,almibu,wali,penghasilanayah,penghasilanibu,alamatortu,telponortu,
				         hportu,emailayah,emailibu,alamatsurat,keterangan,pinsiswa,pinortu,pinortuibu,asalsekolah,pendidikanayah,pendidikanibu,pekerjaanayah,pekerjaanibu,foto,info1,info2,info3
				        FROM jbsakad.siswa WHERE nis='$nis')";
                $db->QueryDb($sql);

                $sql = "INSERT INTO jbsakad.tambahandatasiswa (nis, idtambahan, jenis, teks, filedata, filename, filemime, filesize)
                        SELECT '$nisBaru', idtambahan, jenis, teks, filedata, filename, filemime, filesize
                          FROM jbsakad.tambahandatasiswa
                         WHERE nis = '$nis'";
                $db->QueryDb($sql);

                $sql = "UPDATE jbsakad.siswa 
                           SET aktif=0, alumni=1 
                         WHERE nis='$nis'"; 
                $db->QueryDb($sql);

                $sql = "UPDATE jbsakad.riwayatkelassiswa 
                           SET aktif=0 
                         WHERE nis='$nis' 
                           AND idkelas='$idKelasAwal' 
                           AND aktif = 1";
                $db->QueryDb($sql);

                $sql = "INSERT INTO jbsakad.riwayatkelassiswa 
                           SET nis='$nisBaru', 
                               aktif=1, 
                               status=1, 
                               idkelas='$idKelasTujuan', 
                               keterangan='$keterangan', 
                               mulai=CURDATE()";
                $db->QueryDb($sql);

                $sql = "UPDATE jbsakad.riwayatdeptsiswa 
                           SET aktif=0 
                         WHERE nis='$nis' 
                           AND departemen='$departemen' 
                           AND aktif = 1";
                $db->QueryDb($sql);

                $sql = "INSERT INTO jbsakad.alumni 
                           SET nis='$nis', 
                               tgllulus=CURDATE(), 
                               tktakhir='$idTingkat', 
                               klsakhir='$idKelasAwal', 
                               departemen = '$departemen'";
                $db->QueryDb($sql);

                $sql = "INSERT INTO jbsakad.riwayatdeptsiswa 
                           SET nis='$nisBaru', 
                               departemen='$departemenTujuan', 
                               mulai=CURDATE(), 
                               aktif=1, 
                               nislama='$nis', 
                               status=1";
                $db->QueryDb($sql);
            }
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
