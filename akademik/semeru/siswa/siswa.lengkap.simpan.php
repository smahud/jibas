<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once("../library/logger.php");
require_once("../library/rupiah.php");
require_once("../include/config.php");
require_once("../include/db.onfunc.php");
require_once("../library/common.func.php");
require_once('../library/riwayatinput.php');

function InsertSetParam($request, $defaultValue, $column, &$stInsertSet)
{
    $value = RequestData($request, $defaultValue);
    if ($value == $defaultValue)
        return;

    if ($stInsertSet != "")
        $stInsertSet .= ",";

    $stInsertSet .= "$column='$value'";        
}

function BuildInsertSet()
{
    $stInsertSet = "";
    
    InsertSetParam("idkelas", 0, "idkelas", $stInsertSet);
    InsertSetParam("angkatan", "", "idangkatan", $stInsertSet);
    InsertSetParam("tahunmasuk", "", "tahunmasuk", $stInsertSet);
    InsertSetParam("nis", "", "nis", $stInsertSet);
    InsertSetParam("nama", "", "nama", $stInsertSet);
    InsertSetParam("nisn", "", "nisn", $stInsertSet);
    InsertSetParam("nik", "", "nik", $stInsertSet);
    InsertSetParam("noun", "", "noun", $stInsertSet);
    InsertSetParam("panggilan", "", "panggilan", $stInsertSet);
    InsertSetParam("kelamin", "", "kelamin", $stInsertSet);
    InsertSetParam("tmplahir", "", "tmplahir", $stInsertSet);
    InsertSetParam("agama", "0", "agama", $stInsertSet);
    InsertSetParam("suku", "0", "suku", $stInsertSet);
    InsertSetParam("statussiswa", "0", "`status`", $stInsertSet);
    InsertSetParam("kondisisiswa", "0", "kondisi", $stInsertSet);
    InsertSetParam("warga", "", "warga", $stInsertSet);
    InsertSetParam("urutananak", "", "anakke", $stInsertSet);
    InsertSetParam("jumlahanak", "", "jsaudara", $stInsertSet);
    InsertSetParam("statusanak", "", "statusanak", $stInsertSet);
    InsertSetParam("jkandung", "", "jkandung", $stInsertSet);
    InsertSetParam("jtiri", "", "jtiri", $stInsertSet);
    InsertSetParam("bahasa", "", "bahasa", $stInsertSet);
    InsertSetParam("alamatsiswa", "", "alamatsiswa", $stInsertSet);
    InsertSetParam("kodepos", "", "kodepossiswa", $stInsertSet);
    InsertSetParam("jarak", "", "jarak", $stInsertSet);
    InsertSetParam("telponsiswa", "", "telponsiswa", $stInsertSet);
    InsertSetParam("hpsiswa", "", "hpsiswa", $stInsertSet);
    InsertSetParam("emailsiswa", "", "emailsiswa", $stInsertSet);
    InsertSetParam("asalsekolah", 0, "asalsekolah", $stInsertSet);
    InsertSetParam("noijasah", "", "noijasah", $stInsertSet);
    InsertSetParam("tglijasah", "", "tglijasah", $stInsertSet);
    InsertSetParam("ketsekolah", "", "ketsekolah", $stInsertSet);
    InsertSetParam("gol", "", "darah", $stInsertSet);
    InsertSetParam("berat", "", "berat", $stInsertSet);
    InsertSetParam("tinggi", "", "tinggi", $stInsertSet);
    InsertSetParam("kesehatan", "", "kesehatan", $stInsertSet);
    InsertSetParam("namaayah", "", "namaayah", $stInsertSet);
    InsertSetParam("almayah", "", "almayah", $stInsertSet);
    InsertSetParam("namaibu", "", "namaibu", $stInsertSet);
    InsertSetParam("almibu", "", "almibu", $stInsertSet);
    InsertSetParam("statusayah", "", "statusayah", $stInsertSet);
    InsertSetParam("statusibu", "", "statusibu", $stInsertSet);
    InsertSetParam("tmplahirayah", "", "tmplahirayah", $stInsertSet);
    InsertSetParam("tmplahiribu", "", "tmplahiribu", $stInsertSet);
    InsertSetParam("pendidikanayah", 0, "pendidikanayah", $stInsertSet);
    InsertSetParam("pendidikanibu", 0, "pendidikanibu", $stInsertSet);
    InsertSetParam("pekerjaanayah", 0, "pekerjaanayah", $stInsertSet);
    InsertSetParam("pekerjaanibu", 0, "pekerjaanibu", $stInsertSet);
    InsertSetParam("emailayah", "", "emailayah", $stInsertSet);
    InsertSetParam("emailibu", "", "emailibu", $stInsertSet);
    InsertSetParam("namawali", "", "wali", $stInsertSet);
    InsertSetParam("alamatortu", "", "alamatortu", $stInsertSet);
    InsertSetParam("telponortu", "", "telponortu", $stInsertSet);
    InsertSetParam("hportu", "", "hportu", $stInsertSet);
    InsertSetParam("hportu2", "", "info1", $stInsertSet);
    InsertSetParam("hportu3", "", "info2", $stInsertSet);
    InsertSetParam("hobi", "", "hobi", $stInsertSet);
    InsertSetParam("alamatsurat", "", "alamatsurat", $stInsertSet);
    InsertSetParam("keterangan", "", "keterangan", $stInsertSet);

    $penghasilanAyah = RequestData("penghasilanayah", "");
    $penghasilanAyah = UnformatRupiah($penghasilanAyah);
    if ($penghasilanAyah != 0)
    {
        if ($stInsertSet != "")
            $stInsertSet .= ",";

        $stInsertSet .= "penghasilanayah='$penghasilanAyah'";
    }

    $penghasilanIbu = RequestData("penghasilanibu", "");
    $penghasilanIbu = UnformatRupiah($penghasilanIbu);
    if ($penghasilanIbu != 0)
    {
        if ($stInsertSet != "")
            $stInsertSet .= ",";
            
        $stInsertSet .= "penghasilanibu='$penghasilanIbu'";
    }

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

    $pin = RandInt(5);
    if ($stInsertSet != "")
        $stInsertSet .= ",";
    $stInsertSet .= "pinortu='$pin'";

    $pin = RandInt(5);
    if ($stInsertSet != "")
        $stInsertSet .= ",";
    $stInsertSet .= "pinortuibu='$pin'";

    return $stInsertSet;
}
//$log->Log("stInsertSet = $stInsertSet");

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
        {
            echo json_encode([0, "NIS $nis sudah digunakan"]);
            return;
        }

        $db->BeginTrans();

        $stInsertSet = BuildInsertSet();
        $sql = "INSERT INTO jbsakad.siswa
                SET $stInsertSet";
        $db->QueryDb($sql);                    

        $sql = "SELECT LAST_INSERT_ID()";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $idSiswa = $row[0];
        
        $departemen = RequestData("departemen", "");

        $sql = "INSERT INTO jbsakad.riwayatdeptsiswa 
                SET nis = '$nis', departemen = '$departemen', mulai = CURDATE()";	
        $db->QueryDb($sql);                    
        
        $idKelas = RequestData("idkelas", 0);
        $sql = "INSERT INTO jbsakad.riwayatkelassiswa 
                SET nis = '$nis', idkelas = '$idKelas', mulai = CURDATE()";
        $db->QueryDb($sql);                    

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

                    $sql = "INSERT INTO jbsakad.tambahandatasiswa
                            SET nis = '$nis', idtambahan = '$replid', jenis = '$jenis', teks = '$teks'";
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

                        $sql = "INSERT INTO jbsakad.tambahandatasiswa
                                SET nis = '$nis', idtambahan = '$replid', jenis = '2', 
                                    filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '$sizefile'";
                        $db->QueryDb($sql);
                    }
                }
            }
        }

        $nama = RequestData("nama", "");
        $deskripsi = "Pendataan siswa $nama ($nis) - lengkap";
        RiwayatInput::Save($db, $departemen, "SIS", "1", $idSiswa, $deskripsi);

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

        $nis = RequestData("nis", "");
        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.siswa
                 WHERE nis = '$nis'
                   AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)             
        {
            echo json_encode([0, "NIS $nis sudah digunakan"]);
            return;
        }

        $db->BeginTrans();

        $stInsertSet = BuildInsertSet();
        $sql = "UPDATE jbsakad.siswa
                   SET $stInsertSet
                 WHERE replid = $replid";
        $db->QueryDb($sql);                    
        
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
                        $sql = "INSERT INTO jbsakad.tambahandatasiswa
                                   SET nis = '$nis', idtambahan = '$replid', jenis = '$jenis', teks = '$teks'";
                    }
                    else
                    {
                        $sql = "UPDATE jbsakad.tambahandatasiswa
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
                            $sql = "INSERT INTO jbsakad.tambahandatasiswa
                                       SET nis = '$nis', idtambahan = '$replid', jenis = '2', 
                                           filedata = '$datafile', filename = '$namefile', filemime = '$typefile', filesize = '$sizefile'";
                        }
                        else
                        {
                            $sql = "UPDATE jbsakad.tambahandatasiswa
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

$replid = RequestData("replid", 0);
if ($replid == 0)
    SimpanBaru();
else
    SimpanEdit($replid);



?>

