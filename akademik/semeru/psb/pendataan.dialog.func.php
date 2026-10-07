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
function LoadCalonSiswa()
{
    global $replid;
    global $noPendaftaran, $nama, $panggilan, $idAngkatan, $tahunMasuk, $tmpLahir, $tglLahir, $blnLahir, $thnLahir, $keterangan, $kelamin;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT nopendaftaran, nama, 
                       IFNULL(panggilan, '') AS panggilan,
                       IFNULL(tmplahir, '') AS tmplahir, 
                       IFNULL(tgllahir, '') AS tgllahir,
                       kelamin, 
                       IFNULL(keterangan, '') AS keterangan
                  FROM jbsakad.calonsiswa
                 WHERE replid = $replid";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_assoc($res))
        {
            $noPendaftaran = $row['nopendaftaran'];
            $nama = $row['nama'];
            $panggilan = $row['panggilan'];
            $tmpLahir = $row['tmplahir'];
            $temp = $row['tgllahir'];
            if ($temp != "")
            {
                $ls = explode("-", $temp);
                $tglLahir = $ls[2];
                $blnLahir = $ls[1];
                $thnLahir = $ls[0];
            }
            $keterangan = $row['keterangan'];
            $kelamin = $row['kelamin'];
        }

    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function SimpanEdit()
{
    global $replid;

    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        
        $nama = RequestData("nama", "");
        $panggilan = RequestData("panggilan", "");
        $tmplahir = RequestData("tmplahir", "");
        $tgllahir = RequestData("tgllahir", 0);
        $blnlahir = RequestData("blnlahir", 0);
        $thnlahir = RequestData("thnlahir", 0);
        $gender = RequestData("gender", "l");
        $keterangan = RequestData("keterangan", "");

        $panggilanValue = $panggilan == "" ? "NULL" : "'$panggilan'";
        $tmplahirValue = $tmplahir == "" ? "NULL" : "'$tmplahir'";
        $keteranganValue = $keterangan == "" ? "NULL" : "'$keterangan'";
        $tglLahirValue = $tgllahir == "" ? "NULL" : "'$thnlahir-$blnlahir-$tgllahir'";

        $sql = "UPDATE jbsakad.calonsiswa
                   SET nama = '$nama',
                       panggilan = $panggilanValue,
                       kelamin = '$gender',
                       tmplahir = $tmplahirValue,
                       tgllahir = $tglLahirValue,
                       keterangan = $keteranganValue
                 WHERE replid = $replid";
        $db->QueryDb($sql);

        return json_encode([1, "Calon siswa berhasil disimpan!"]);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}

function GenerateNoPendaftaran($db, $idProses)
{
    $sql = "SELECT kodeawalan 
              FROM jbsakad.prosespenerimaansiswa 
             WHERE replid = '$idProses'";	
    $res = $db->QueryDb($sql);	
    $row = mysqli_fetch_row($res);	
    $kode_no = $row[0];
    $kodelen = strlen($kode_no);

    $sql = "SELECT MAX(LPAD(nopendaftaran, " . ($kodelen + 20) . ",'*')) 
              FROM jbsakad.calonsiswa 
             WHERE idproses = '$idProses'";
    $res = $db->QueryDb($sql);	
    $row = mysqli_fetch_row($res);
    $nom = $row[0];
    $nom = str_replace("*", "", $nom);
    $counter = (int)substr($nom, $kodelen + 2);
    $thn_no = substr(date('Y'), 2);
    do
    {
        $counter += 1;
        $no = $kode_no . $thn_no . sprintf("%04d", $counter);
        
        $sql = "SELECT COUNT(replid) 
                  FROM jbsakad.calonsiswa 
                 WHERE nopendaftaran='$no'";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $ndata = (int)$row[0];
    }
    while($ndata > 0);

    return $no;
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();
        
        $departemen = RequestData("departemen", "");
        $idProses = RequestData("idproses", 0);
        $idKelompok = RequestData("idkelompok", 0);
        $nama = RequestData("nama", "");
        $panggilan = RequestData("panggilan", "");
        $tmplahir = RequestData("tmplahir", "");
        $tgllahir = RequestData("tgllahir", 0);
        $blnlahir = RequestData("blnlahir", 0);
        $thnlahir = RequestData("thnlahir", 0);
        $gender = RequestData("gender", "l");
        $keterangan = RequestData("keterangan", "");

        $panggilanValue = $panggilan == "" ? "NULL" : "'$panggilan'";
        $tmplahirValue = $tmplahir == "" ? "NULL" : "'$tmplahir'";
        $keteranganValue = $keterangan == "" ? "NULL" : "'$keterangan'";
        $tglLahirValue = $tgllahir == "" ? "NULL" : "'$thnlahir-$blnlahir-$tgllahir'";

        $pinSiswa = RandInt(5); // rand(10000, 99999);
        $noPendaftaran = GenerateNoPendaftaran($db, $idProses);

        $db->BeginTrans();

        $sql = "INSERT INTO jbsakad.calonsiswa
                   SET nopendaftaran = '$noPendaftaran',
                       nama = '$nama',
                       panggilan = $panggilanValue,
                       idproses = '$idProses',
                       idkelompok = '$idKelompok',
                       kelamin = '$gender',
                       tmplahir = $tmplahirValue,
                       tgllahir = $tglLahirValue,
                       pinsiswa = '$pinSiswa',
                       keterangan = $keteranganValue";
        $db->QueryDb($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $idCalonSiswa = (int)$row[0];

        $deskripsi = "Pendataan calon siswa $nama ($noPendaftaran) - dasar";
        RiwayatInput::Save($db, $departemen, "CSIS", "1", $idCalonSiswa, $deskripsi);
        
        $db->CommitTrans();

        return json_encode([1, "Calon siswa berhasil disimpan!"]);
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