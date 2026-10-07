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
function LoadIdentitas($db)
{
    global $replid;
    global $nama, $alamat1, $alamat2, $tlp1, $tlp2, $tlp3, $tlp4, $fax1, $fax2, $situs, $email;

    $sql = "SELECT * FROM jbsumum.identitas WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $nama = $row["nama"];
        $alamat1 = $row["alamat1"];
        $alamat2 = $row["alamat2"];
        $tlp1 = $row["telp1"];
        $tlp2 = $row["telp2"];
        $tlp3 = $row["telp3"];
        $tlp4 = $row["telp4"];
        $fax1 = $row["fax1"];
        $fax2 = $row["fax2"];
        $situs = $row["situs"];
        $email = $row["email"];
    }
}

function SimpanIdentitas()
{
    $db = new Db();
    try
    {
        $db->Open();

        $haslogo = $_REQUEST["haslogo"];
        $sql_logo = "";
        if ($haslogo == 1)
        {
            $logo = $_FILES["logo"];
            $uploadedfile = $logo['tmp_name'];
            if (strlen($uploadedfile) != 0)
            {
                $tmp_path = realpath(".") . "/../temp";
                $tmp_exists = file_exists($tmp_path) && is_dir($tmp_path);
                if (!$tmp_exists)
                    mkdir($tmp_path, 0755);

                $filename = "$tmp_path/id-tmp.jpg";
                ResizeImage($logo, 300, 300, 70, $filename);

                $fh = fopen($filename,"r");
                $logo_data = addslashes(fread($fh, filesize($filename)));
                fclose($fh);

                $sql_logo = ", foto = '$logo_data'";
            }
        }

        $replid = RequestData("replid", 0);
        $departemen = RequestData("departemen", "");
        $nama = RequestData("nama", "");
        $alamat1 = RequestData("alamat1", "");
        $tlp1 = RequestData("tlp1", "");
        $tlp2 = RequestData("tlp2", "");
        $fax1 = RequestData("fax1", "");
        $alamat2 = RequestData("alamat2", "");
        $tlp3 = RequestData("tlp3", "");
        $tlp4 = RequestData("tlp4", "");
        $fax2 = RequestData("fax2", "");
        $situs = RequestData("situs", "");
        $email = RequestData("email", "");

        if ($replid == 0)
        {
            $sql = "INSERT INTO jbsumum.identitas 
                       SET nama='$nama', situs='$situs', email='$email', alamat1='$alamat1', alamat2='$alamat2', 
                           telp1='$tlp1', telp2='$tlp2', telp3='$tlp3', telp4='$tlp4', fax1='$fax1', 
                           fax2='$fax2', departemen='$departemen' $sql_logo";
        }
        else 
        {
            $sql = "UPDATE jbsumum.identitas 
                       SET nama='$nama', situs='$situs', email='$email', alamat1='$alamat1', 
                           alamat2='$alamat2', telp1='$tlp1', telp2='$tlp2', telp3='$tlp3', 
                           telp4='$tlp4', fax1='$fax1', fax2='$fax2' $sql_logo
                     WHERE replid = $replid";
        }

        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        $db->LogLastErrorIfExist();

        return json_encode([-99, Msg::InfoError($ex->getMessage(), "k2qzu")]);
    }
    finally
    {
        $db->Close();
    }
}
?>