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
function SimpanPenempatanSiswa()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $departemen = RequestData("departemen", "");
        $idAngkatan = RequestData("idangkatan", 0);
        $idKelas = RequestData("idkelas", 0);
        $noPendaftaran = RequestData("nopendaftaran", "");
        $nis = RequestData("nis", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT 1
                  FROM jbsakad.siswa
                 WHERE nis = '$nis'";
        $res = $db->QueryDb($sql) ;
        if (mysqli_num_rows($res) > 0)
            return json_encode([-1, "NIS $nis sudah digunakan"]);

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.siswa
                 WHERE idkelas = '$idKelas'";
        $terisi = $db->ExecuteScalar($sql, 0);

        $sql = "SELECT kapasitas
                  FROM jbsakad.kelas
                 WHERE replid = '$idKelas'";
        $kapasitas = $db->ExecuteScalar($sql, 0);

        if ($terisi >= $kapasitas)
            return json_encode([-1, "Kelas tujuan sudah penuh"]);

        $pinSiswa = RandInt(5);
        $pinOrtu = RandInt(5);  
		$pinOrtuIbu = RandInt(5);   

        $db->BeginTrans();

        $sql = "INSERT INTO jbsakad.siswa (nis, nisn, nik, noun, nama, panggilan, aktif, tahunmasuk, idangkatan, idkelas, suku, agama, status, kondisi, kelamin, tmplahir, tgllahir, warga, anakke, jsaudara, statusanak, jkandung, jtiri, bahasa, berat, tinggi, darah, foto, pinsiswa, alamatsiswa, jarak, kodepossiswa, telponsiswa, hpsiswa, emailsiswa, kesehatan, asalsekolah, noijasah, tglijasah, ketsekolah, namaayah, namaibu, statusayah, statusibu, tmplahirayah, tmplahiribu, tgllahirayah, tgllahiribu, almayah, almibu, pendidikanayah, pendidikanibu, pekerjaanayah, pekerjaanibu, wali, penghasilanayah, penghasilanibu, alamatortu, telponortu, hportu, emailayah, emailibu, alamatsurat, keterangan, hobi, pinortu, pinortuibu, info1, info2, info3, frompsb)
                SELECT '$nis', nisn, nik, noun, nama, panggilan, aktif, YEAR(CURDATE()), '$idAngkatan', '$idKelas', suku, agama, status, kondisi, kelamin, tmplahir, tgllahir, warga, anakke, jsaudara, statusanak, jkandung, jtiri, bahasa, berat, tinggi, darah, foto, '$pinSiswa', alamatsiswa, jarak, kodepossiswa, telponsiswa, hpsiswa, emailsiswa, kesehatan, asalsekolah, noijasah, tglijasah, ketsekolah, namaayah, namaibu, statusayah, statusibu, tmplahirayah, tmplahiribu, tgllahirayah, tgllahiribu, almayah, almibu, pendidikanayah, pendidikanibu, pekerjaanayah, pekerjaanibu, wali, penghasilanayah, penghasilanibu, alamatortu, telponortu, hportu, emailayah, emailibu, alamatsurat, keterangan, hobi, '$pinOrtu', '$pinOrtuIbu', info1, info2, info3, 1 
                  FROM jbsakad.calonsiswa 
                 WHERE replid = $replid";
        $db->QueryDb($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $replidSiswa = $db->ExecuteScalar($sql, 0);

        $sql = "UPDATE jbsakad.calonsiswa
                   SET replidsiswa = '$replidSiswa'
                 WHERE replid = '$replid'";
        $db->QueryDb($sql);

        $sql = "INSERT INTO jbsakad.riwayatdeptsiswa 
                   SET nis = '$nis', departemen = '$departemen', mulai = CURDATE()";
        $db->QueryDb($sql);

        $sql = "INSERT INTO jbsakad.riwayatkelassiswa 
                   SET nis = '$nis', idkelas = '$idKelas', mulai = CURDATE()";
        $db->QueryDb($sql);

        $sql = "INSERT INTO jbsakad.tambahandatasiswa (nis, idtambahan, jenis, teks, filedata, filename, filemime, filesize)
                SELECT '$nis', idtambahan, jenis, teks, filedata, filename, filemime, filesize
                  FROM jbsakad.tambahandatacalon
                 WHERE nopendaftaran = '$noPendaftaran'";
        $db->QueryDb($sql);                 

        $db->CommitTrans();
  
        return json_encode([1, "OK"]);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}

?> 