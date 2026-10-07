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
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once("../library/logger.php");
require_once("../library/rupiah.php");
require_once("../include/config.php");
require_once("../include/db.onfunc.php");
require_once("../library/common.func.php");
require_once('../library/riwayatinput.php');

function InsertSetParamSetDefault($request, $defaultValue, $column, &$stInsertSet)
{
    if ($stInsertSet != "")
        $stInsertSet .= ",";

    $value = RequestData($request, $defaultValue);
    if ($value == $defaultValue)
        $stInsertSet .= "$column='$defaultValue'";
    else
        $stInsertSet .= "$column='$value'";        
}

function InsertSetParamSetNull($request, $defaultValue, $column, &$stInsertSet)
{
    if ($stInsertSet != "")
        $stInsertSet .= ",";

    $value = RequestData($request, $defaultValue);
    if ($value == $defaultValue)
        $stInsertSet .= "$column=NULL";
    else
        $stInsertSet .= "$column='$value'";        
}

function InsertSetParamSkipDefault($request, $defaultValue, $column, &$stInsertSet)
{
    $value = RequestData($request, $defaultValue);
    if ($value == $defaultValue)
        return;

    if ($stInsertSet != "")
        $stInsertSet .= ",";

    $stInsertSet .= "$column='$value'";        
}

function BuildInsertSet($noPendaftaran)
{
    $stInsertSet = "";

    InsertSetParamSkipDefault("idproses", 0, "idproses", $stInsertSet);
    InsertSetParamSkipDefault("idkelompok", 0, "idkelompok", $stInsertSet);
    InsertSetParamSkipDefault("nama", "", "nama", $stInsertSet);

    InsertSetParamSetDefault("nisn", "", "nisn", $stInsertSet);
    InsertSetParamSetDefault("nik", "", "nik", $stInsertSet);
    InsertSetParamSetDefault("noun", "", "noun", $stInsertSet);
    InsertSetParamSetDefault("panggilan", "", "panggilan", $stInsertSet);
    InsertSetParamSetDefault("kelamin", "", "kelamin", $stInsertSet);
    InsertSetParamSetDefault("tmplahir", "", "tmplahir", $stInsertSet);

    InsertSetParamSetNull("agama", "0", "agama", $stInsertSet);
    InsertSetParamSetNull("suku", "0", "suku", $stInsertSet);
    InsertSetParamSetNull("statussiswa", "0", "`status`", $stInsertSet);
    InsertSetParamSetNull("kondisisiswa", "0", "kondisi", $stInsertSet);

    InsertSetParamSetDefault("warga", "", "warga", $stInsertSet);
    InsertSetParamSetDefault("urutananak", "", "anakke", $stInsertSet);
    InsertSetParamSetDefault("jumlahanak", "", "jsaudara", $stInsertSet);
    InsertSetParamSetDefault("statusanak", "", "statusanak", $stInsertSet);
    InsertSetParamSetDefault("jkandung", "", "jkandung", $stInsertSet);
    InsertSetParamSetDefault("jtiri", "", "jtiri", $stInsertSet);
    InsertSetParamSetDefault("bahasa", "", "bahasa", $stInsertSet);
    InsertSetParamSetDefault("alamatsiswa", "", "alamatsiswa", $stInsertSet);
    InsertSetParamSetDefault("kodepos", "", "kodepossiswa", $stInsertSet);
    InsertSetParamSetDefault("jarak", "", "jarak", $stInsertSet);
    InsertSetParamSetDefault("telponsiswa", "", "telponsiswa", $stInsertSet);
    InsertSetParamSetDefault("hpsiswa", "", "hpsiswa", $stInsertSet);
    InsertSetParamSetDefault("emailsiswa", "", "emailsiswa", $stInsertSet);
    InsertSetParamSetNull("asalsekolah", 0, "asalsekolah", $stInsertSet);
    InsertSetParamSetDefault("noijasah", "", "noijasah", $stInsertSet);
    InsertSetParamSetDefault("tglijasah", "", "tglijasah", $stInsertSet);
    InsertSetParamSetDefault("ketsekolah", "", "ketsekolah", $stInsertSet);
    InsertSetParamSetDefault("gol", "", "darah", $stInsertSet);
    InsertSetParamSetDefault("berat", "", "berat", $stInsertSet);
    InsertSetParamSetDefault("tinggi", "", "tinggi", $stInsertSet);
    InsertSetParamSetDefault("kesehatan", "", "kesehatan", $stInsertSet);
    InsertSetParamSetDefault("namaayah", "", "namaayah", $stInsertSet);
    InsertSetParamSetDefault("almayah", "", "almayah", $stInsertSet);
    InsertSetParamSetDefault("namaibu", "", "namaibu", $stInsertSet);
    InsertSetParamSetDefault("almibu", "", "almibu", $stInsertSet);
    InsertSetParamSetDefault("statusayah", "", "statusayah", $stInsertSet);
    InsertSetParamSetDefault("statusibu", "", "statusibu", $stInsertSet);
    InsertSetParamSetDefault("tmplahirayah", "", "tmplahirayah", $stInsertSet);
    InsertSetParamSetDefault("tmplahiribu", "", "tmplahiribu", $stInsertSet);
    InsertSetParamSetNull("pendidikanayah", 0, "pendidikanayah", $stInsertSet);
    InsertSetParamSetNull("pendidikanibu", 0, "pendidikanibu", $stInsertSet);
    InsertSetParamSetNull("pekerjaanayah", 0, "pekerjaanayah", $stInsertSet);
    InsertSetParamSetNull("pekerjaanibu", 0, "pekerjaanibu", $stInsertSet);
    InsertSetParamSetDefault("emailayah", "", "emailayah", $stInsertSet);
    InsertSetParamSetDefault("emailibu", "", "emailibu", $stInsertSet);
    InsertSetParamSetDefault("namawali", "", "wali", $stInsertSet);
    InsertSetParamSetDefault("alamatortu", "", "alamatortu", $stInsertSet);
    InsertSetParamSetDefault("telponortu", "", "telponortu", $stInsertSet);
    InsertSetParamSetDefault("hportu", "", "hportu", $stInsertSet);
    InsertSetParamSetDefault("hportu2", "", "info1", $stInsertSet);
    InsertSetParamSetDefault("hportu3", "", "info2", $stInsertSet);
    InsertSetParamSetDefault("hobi", "", "hobi", $stInsertSet);
    InsertSetParamSetDefault("alamatsurat", "", "alamatsurat", $stInsertSet);
    InsertSetParamSetDefault("keterangan", "", "keterangan", $stInsertSet);
    InsertSetParamSetDefault("sum1", "", "sum1", $stInsertSet);
    InsertSetParamSetDefault("sum2", "", "sum2", $stInsertSet);
    InsertSetParamSetDefault("ujian1", "", "ujian1", $stInsertSet);
    InsertSetParamSetDefault("ujian2", "", "ujian2", $stInsertSet);
    InsertSetParamSetDefault("ujian3", "", "ujian3", $stInsertSet);
    InsertSetParamSetDefault("ujian4", "", "ujian4", $stInsertSet);
    InsertSetParamSetDefault("ujian5", "", "ujian5", $stInsertSet);
    InsertSetParamSetDefault("ujian6", "", "ujian6", $stInsertSet);
    InsertSetParamSetDefault("ujian7", "", "ujian7", $stInsertSet);
    InsertSetParamSetDefault("ujian8", "", "ujian8", $stInsertSet);
    InsertSetParamSetDefault("ujian9", "", "ujian9", $stInsertSet);
    InsertSetParamSetDefault("ujian10", "", "ujian10", $stInsertSet);

    if ($noPendaftaran != "")
    {
        if ($stInsertSet != "")
            $stInsertSet .= ",";

        $stInsertSet .= "nopendaftaran='$noPendaftaran'";
    }

    $penghasilanAyah = RequestData("penghasilanayah", "");
    $penghasilanAyah = UnformatRupiah($penghasilanAyah);
    if ($stInsertSet != "")
        $stInsertSet .= ",";
    $stInsertSet .= "penghasilanayah='$penghasilanAyah'";

    $penghasilanIbu = RequestData("penghasilanibu", "");
    $penghasilanIbu = UnformatRupiah($penghasilanIbu);
    if ($stInsertSet != "")
        $stInsertSet .= ",";
    $stInsertSet .= "penghasilanibu='$penghasilanIbu'";

    $tglLahir = RequestData("tgllahir", "");
    $blnLahir = RequestData("blnlahir", "");
    $thnLahir = RequestData("thnlahir", "");
    if ($tglLahir != "" && $blnLahir != "" && $thnLahir != "")
    {
        $tglLahirString = "$thnLahir-$blnLahir-$tglLahir";
        
        if ($stInsertSet != "")
            $stInsertSet .= ",";
        $stInsertSet .= "tgllahir='$tglLahirString'";
    }

    $tglLahir = RequestData("tgllahirayah", "");
    $blnLahir = RequestData("blnlahirayah", "");
    $thnLahir = RequestData("thnlahirayah", "");
    if ($tglLahir != "" && $blnLahir != "" && $thnLahir != "")
    {
        $tglLahirString = "$thnLahir-$blnLahir-$tglLahir";
        
        if ($stInsertSet != "")
            $stInsertSet .= ",";
        $stInsertSet .= "tgllahirayah='$tglLahirString'";
    }

    $tglLahir = RequestData("tgllahiribu", "");
    $blnLahir = RequestData("blnlahiribu", "");
    $thnLahir = RequestData("thnlahiribu", "");
    if ($tglLahir != "" && $blnLahir != "" && $thnLahir != "")
    {
        $tglLahirString = "$thnLahir-$blnLahir-$tglLahir";
        
        if ($stInsertSet != "")
            $stInsertSet .= ",";
        $stInsertSet .= "tgllahiribu='$tglLahirString'";
    }

    $pin = RandInt(5);
    if ($stInsertSet != "")
        $stInsertSet .= ",";
    $stInsertSet .= "pinsiswa='$pin'";

    return $stInsertSet;
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

        $db->BeginTrans();

        $idProses = RequestData("idproses", 0);
        $noPendaftaran = GenerateNoPendaftaran($db, $idProses);

        $stInsertSet = BuildInsertSet($noPendaftaran);

        $sql = "INSERT INTO jbsakad.calonsiswa
                   SET $stInsertSet";
        $db->QueryDb($sql);                    

        $sql = "SELECT LAST_INSERT_ID()";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $idCalonSiswa = (int)$row[0];

        $nama = RequestData("nama", "");
        $departemen = RequestData("departemen", "");
        $deskripsi = "Pendataan calon siswa $nama ($noPendaftaran) - lengkap";
        RiwayatInput::Save($db, $departemen, "CSIS", "1", $idCalonSiswa, $deskripsi);


        $idtambahan = RequestData("idtambahan", "");
        if (strlen($idtambahan) > 0)
        {
            if (strpos($idtambahan, ",") === false)
                $arridtambahan = array($idtambahan);
            else
                $arridtambahan = explode(",", $idtambahan);

            // READ WARNING IMAGE
            $warnimg = "../images/warningimg.jpg";
            $fh = fopen($warnimg,"r");
            $warnsize = filesize($warnimg);
            $warnfile = addslashes(fread($fh, $warnsize));
            $warntype = "image/jpeg";
            $warnname = "warning.jpg";
            fclose($fh);

            for($i = 0; $i < count($arridtambahan); $i++)
            {
                $replid = $arridtambahan[$i];

                $param = "jenisdata-$replid";
                if (!isset($_REQUEST[$param]))
                    continue;

                $jenis = RequestData($param, 0);
                if ($jenis == 1 || $jenis == 3)
                {
                    $param = "tambahandata-$replid";
                    if (!isset($_REQUEST[$param]))
                        continue;

                    $teks = RequestData($param, "");

                    $sql = "INSERT INTO jbsakad.tambahandatacalon
                               SET nopendaftaran = '$noPendaftaran', idtambahan = '$replid', 
                                   jenis = '$jenis', teks = '$teks'";
                    $db->QueryDb($sql);
                }
                else if ($jenis == 2)
                {
                    $param = "tambahandata-$replid";
                    if (!isset($_FILES[$param]))
                        continue;

                    $file = $_FILES[$param];
                    $tmpfile = $file['tmp_name'];

                    if (strlen($tmpfile) != 0)
                    {
                        if (filesize($tmpfile) <= 256000)
                        {
                            $fh = fopen($tmpfile, "r");
                            $datafile = addslashes(fread($fh, filesize($tmpfile)));
                            fclose($fh);

                            $namefile = $file['name'];
                            $typefile = $file['type'];
                            $sizefile = $file['size'];
                        }
                        else
                        {
                            $datafile = $warnfile;
                            $namefile = $warnname;
                            $typefile = $warntype;
                            $sizefile = $warnsize;
                        }

                        $sql = "INSERT INTO jbsakad.tambahandatacalon
                                   SET nopendaftaran = '$noPendaftaran', idtambahan = '$replid', jenis = '2', 
                                       filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '$sizefile'";
                        $db->QueryDb($sql);
                    }
                }
            }
        }

        //$db->RollbackTrans();
        $db->CommitTrans();

        echo json_encode([1, "OK"]);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        echo json_encode([-1, $ex->getMessage()]);
    }
}

function SimpanEdit($replid)
{
    $db = new Db();
    try
    {
        $db->Open();

        $db->BeginTrans();

        $stInsertSet = BuildInsertSet("");

        $sql = "UPDATE jbsakad.calonsiswa
                   SET $stInsertSet
                 WHERE replid = $replid";
        $db->QueryDb($sql);                    
        
        $noPendaftaran = RequestData("nopendaftaran", "");
        $idtambahan = RequestData("idtambahan", "");
        if (strlen($idtambahan) > 0)
        {
            if (strpos($idtambahan, ",") === false)
                $arridtambahan = array($idtambahan);
            else
                $arridtambahan = explode(",", $idtambahan);

            // READ WARNING IMAGE
            $warnimg = "../images/warningimg.jpg";
            $fh = fopen($warnimg,"r");
            $warnsize = filesize($warnimg);
            $warnfile = addslashes(fread($fh, $warnsize));
            $warntype = "image/jpeg";
            $warnname = "warning.jpg";
            fclose($fh);

            for($i = 0; $i < count($arridtambahan); $i++)
            {
                $replid = $arridtambahan[$i];

                $param = "jenisdata-$replid";
                if (!isset($_REQUEST[$param]))
                    continue;

                $jenis = RequestData($param, 0);
                if ($jenis == 1 || $jenis == 3)
                {
                    $param = "tambahandata-$replid";
                    if (!isset($_REQUEST[$param]))
                        continue;

                    $teks = RequestData($param, "");

                    $param = "repliddata-$replid";
                    $repliddata = RequestData($param, 0);

                    if ($repliddata == 0)
                    {
                        $sql = "INSERT INTO jbsakad.tambahandatacalon
                                   SET nopendaftaran = '$noPendaftaran', idtambahan = '$replid', jenis = '$jenis', 
                                       teks = '$teks'";
                    }
                    else
                    {
                        $sql = "UPDATE jbsakad.tambahandatacalon
                                   SET teks = '$teks'
                                 WHERE replid = $repliddata";
                    }
                    $db->QueryDb($sql);
                }
                else if ($jenis == 2)
                {
                    $param = "tambahandata-$replid";
                    if (!isset($_FILES[$param]))
                        continue;

                    $file = $_FILES[$param];
                    $tmpfile = $file['tmp_name'];

                    $param = "repliddata-$replid";
                    $repliddata = RequestData($param, 0);

                    if (strlen($tmpfile) != 0)
                    {
                        if (filesize($tmpfile) <= 256000)
                        {
                            $fh = fopen($tmpfile, "r");
                            $datafile = addslashes(fread($fh, filesize($tmpfile)));
                            fclose($fh);

                            $namefile = $file['name'];
                            $typefile = $file['type'];
                            $sizefile = $file['size'];
                        }
                        else
                        {
                            $datafile = $warnfile;
                            $namefile = $warnname;
                            $typefile = $warntype;
                            $sizefile = $warnsize;
                        }

                        if ($repliddata == 0)
                        {
                            $sql = "INSERT INTO jbsakad.tambahandatacalon
                                       SET nopendaftaran = '$noPendaftaran', idtambahan = '$replid', jenis = '2', 
                                           filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '$sizefile'";
                        }
                        else
                        {
                            $sql = "UPDATE jbsakad.tambahandatacalon
                                       SET filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '$sizefile'
                                     WHERE replid = $repliddata";
                        }                  
                        $db->QueryDb($sql);
                    }
                }
            }
        }

        //$db->RollbackTrans();
        $db->CommitTrans();

        echo json_encode([1, "OK"]);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        echo json_encode([-1, $ex->getMessage()]);
    }
}


$log = new Logger();
foreach ($_REQUEST as $key => $value)
{
    $log->LogOnce("$key = $value");
}

foreach ($_FILES as $key => $value)
{
    $f = $_FILES[$key];
    foreach($f as $key2 => $value2)
    {
        $log->LogOnce("$key2 = $value2");
    }
}

//echo json_encode([1, "OK"]);


$replid = RequestData("replid", 0);
if ($replid == 0)
    SimpanBaru();
else
    SimpanEdit($replid);

?>

