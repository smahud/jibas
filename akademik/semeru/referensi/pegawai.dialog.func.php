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
function ShowSelectBagianPegawai($db)
{
    global $bagian;

    $sql = "SELECT bagian FROM jbssdm.bagianpegawai ORDER BY urutan";
    $res = $db->QueryDb($sql);

    echo "<select name='bagian' id='bagian' style='width:180px' class='inputbox' onchange='onChangeBagian();'>";
    while ($row = mysqli_fetch_array($res))
    {
        $sel = ($bagian == $row['bagian']) ? "selected" : "";
        echo "<option value='$row[bagian]' $sel>$row[bagian]</option>";
    }
    echo "</select>";
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $bagian = RequestData("bagian", "Akademik");
        $nama = RequestData("nama", "");
        $panggilan = RequestData("panggilan", "");
        $hp = RequestData("hp", "");
        $keterangan = RequestData("keterangan", "");
        $nip = RequestData("nip", "");
        $email = RequestData("email", "");
        $kelamin = RequestData("kelamin", "L");
        $menikah = RequestData("menikah", "tak_ada");

        $sql = "SELECT COUNT(replid) 
                  FROM jbssdm.pegawai 
                 WHERE nip = '$nip'";
        $nData = $db->ExecuteScalar($sql, 0);                 
        if ($nData > 0)
        {
            $msg = "NIP $nip sudah digunakan oleh pegawai lain";
            return json_encode([-1,$msg]);
        }

        $pin = RandomUtil::RandInt(5);
        $sql = "INSERT INTO jbssdm.pegawai
                   SET bagian='$bagian', nama='$nama', panggilan='$panggilan', 
                       handphone='$hp', keterangan='$keterangan', nip='$nip', email='$email', pinpegawai='$pin', 
                       kelamin='$kelamin', nikah='$menikah'";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "a6mm4");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
    
}

function LoadPegawai($db, $replid) 
{
    global $bagian, $nama, $panggilan, $hp, $keterangan, $nip, $email, $kelamin, $menikah;

    $sql = "SELECT * FROM jbssdm.pegawai WHERE replid=$replid";
    $res = $db->QueryDb($sql);  
    $row = mysqli_fetch_array($res);
    $bagian = $row['bagian'];
    $nama = $row['nama'];       
    $panggilan = $row['panggilan'];
    $hp = $row['handphone'];
    $keterangan = $row['keterangan'];
    $nip = $row['nip'];
    $email = $row['email'];
    $kelamin = $row['kelamin'];
    $menikah = $row['nikah'];
}

function SimpanEdit()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $bagian = RequestData("bagian", "Akademik");
        $nama = RequestData("nama", "");
        $panggilan = RequestData("panggilan", "");
        $hp = RequestData("hp", "");
        $keterangan = RequestData("keterangan", "");
        $nip = RequestData("nip", "");
        $email = RequestData("email", "");
        $kelamin = RequestData("kelamin", "L");
        $menikah = RequestData("menikah", "tak_ada");

        $sql = "SELECT COUNT(replid) 
                  FROM jbssdm.pegawai 
                 WHERE nip = '$nip' 
                   AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);                 
        if ($nData > 0)
        {
            $msg = "NIP $nip sudah digunakan oleh pegawai lain";
            return json_encode([-1,$msg]);
        }

        $sql = "UPDATE jbssdm.pegawai
                   SET bagian='$bagian', nama='$nama', panggilan='$panggilan', handphone='$hp', 
                       keterangan='$keterangan', nip='$nip', email='$email', kelamin='$kelamin', nikah='$menikah'
                 WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "abh3c");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
?>