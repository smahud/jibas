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
function LoadSiswa()
{
    global $replid;
    global $nis, $nama, $panggilan, $idAngkatan, $tahunMasuk, $tmpLahir, $tglLahir, $blnLahir, $thnLahir, $keterangan, $kelamin;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT nis, nama, idangkatan, tahunmasuk,
                       IFNULL(panggilan, '') AS panggilan,
                       IFNULL(tmplahir, '') AS tmplahir, 
                       IFNULL(tgllahir, '') AS tgllahir,
                       kelamin, 
                       IFNULL(keterangan, '') AS keterangan
                  FROM jbsakad.siswa
                 WHERE replid = $replid";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_assoc($res))
        {
            $nis = $row['nis'];
            $nama = $row['nama'];
            $panggilan = $row['panggilan'];
            $idAngkatan = $row['idangkatan'];
            $tahunMasuk = $row['tahunmasuk'];
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

function ShowSelectAngkatan()
{
    global $departemen, $idAngkatan;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT replid, angkatan
                  FROM jbsakad.angkatan
                 WHERE departemen = '$departemen'
                   AND aktif = 1  
                 ORDER BY replid DESC";
        $res = $db->QueryDb($sql);

        echo "<select id='angkatan' class='inputbox' style='width:220px' onchange='onChangeAngkatan()'>";
        while ($row = mysqli_fetch_assoc($res))
        {
            $sel = $idAngkatan == $row['replid'] ? "selected" : "";
            echo "<option value='" . $row['replid'] . "' $sel>" . $row['angkatan'] . "</option>";
        }
        echo "</select>";

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

        $nis = RequestData("nis", "");
        $replid = RequestData("replid", 0);
        
        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.siswa
                 WHERE nis = '$nis'
                   AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);                 
        if ($nData > 0)
            return json_encode([-1, "NIS $nis sudah terdaftar!"]);

        $idAngkatan = RequestData("idangkatan", 0);
        $tahunmasuk = RequestData("tahunmasuk", 0);
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

        $sql = "UPDATE jbsakad.siswa
                   SET nis = '$nis',
                       nama = '$nama',
                       panggilan = $panggilanValue,
                       tahunmasuk = '$tahunmasuk',
                       idangkatan = '$idAngkatan',
                       kelamin = '$gender',
                       tmplahir = $tmplahirValue,
                       tgllahir = $tglLahirValue,
                       keterangan = $keteranganValue
                 WHERE replid = $replid";
        $db->QueryDb($sql);

        return json_encode([1, "Siswa berhasil disimpan!"]);
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

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nis = RequestData("nis", "");
        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.siswa
                 WHERE nis = '$nis'";
        $nData = $db->ExecuteScalar($sql, 0);                 
        if ($nData > 0)
            return json_encode([-1, "NIS $nis sudah terdaftar!"]);

        $departemen = RequestData("departemen", "");
        $idAngkatan = RequestData("idangkatan", 0);
        $idKelas = RequestData("idkelas", 0);
        $tahunmasuk = RequestData("tahunmasuk", 0);
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
		$pinOrtu = RandInt(5); // rand(10000, 99999);
		$pinOrtuIbu = RandInt(5); // rand(10000, 99999);

        $db->BeginTrans();

        $sql = "INSERT INTO jbsakad.siswa
                   SET nis = '$nis',
                       nama = '$nama',
                       panggilan = $panggilanValue,
                       tahunmasuk = '$tahunmasuk',
                       idangkatan = '$idAngkatan',
                       idkelas = '$idKelas',
                       kelamin = '$gender',
                       tmplahir = $tmplahirValue,
                       tgllahir = $tglLahirValue,
                       pinsiswa = '$pinSiswa',
                       pinortu = '$pinOrtu',
                       pinortuibu = '$pinOrtuIbu',
                       keterangan = $keteranganValue";
        $db->QueryDb($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $idSiswa = $row[0];

        $sql = "INSERT INTO jbsakad.riwayatdeptsiswa 
                   SET nis = '$nis',
                       departemen = '$departemen',
                       mulai = CURDATE()";		
        $db->QueryDb($sql);

        $sql = "INSERT INTO jbsakad.riwayatkelassiswa 
                   SET nis = '$nis',
                       idkelas = '$idKelas',
                       mulai = CURDATE()";
        $db->QueryDb($sql);

        $deskripsi = "Pendataan siswa $nama ($nis) - dasar";
        RiwayatInput::Save($db, $departemen, "SIS", "1", $idSiswa, $deskripsi);

        $db->CommitTrans();

        return json_encode([1, "Siswa berhasil disimpan!"]);
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