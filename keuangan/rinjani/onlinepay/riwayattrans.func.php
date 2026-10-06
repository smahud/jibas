<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
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
require_once ("../library/msg.php");

function ShowSelectDepartemen()
{
    global $departemen;

    $db = new Db();
    try
    {
        $db->Open();

        echo "<select id='departemen' name='departemen' class='inputbox' style='width: 250px' onchange='changeDept(); clearContent();'>";
        $dep = getDepartemen($db, getAccess());
        foreach($dep as $value)
        {
            if ($departemen == "") $departemen = $value;
            $sel = $departemen == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kg1ka");
    }
    finally
    {
        $db->Close();
    }
}

function ShowSelectIuranWajib()
{
    $departemen = $_REQUEST["departemen"];

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT replid, nama 
                  FROM jbsfina.datapenerimaan
                 WHERE departemen = '$departemen'
                   AND idkategori = 'JTT'
                   AND aktif = 1
                 ORDER BY nama";
        $res = $db->QueryDb($sql);

        echo "<select id='idpembayaran' class='inputbox' style='width: 250px' onchange='clearContent()'>";
        echo "<option value='0' selected>Semua Iuran Wajib</option>";
        while($row = mysqli_fetch_row($res))
        {
            echo "<option value='$row[0]'>$row[1]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kxdkq");
    }
    finally
    {
        $db->Close();
    }
}

function ShowSelectIuranSukarela()
{
    $departemen = $_REQUEST["departemen"];

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT replid, nama 
                  FROM jbsfina.datapenerimaan
                 WHERE departemen = '$departemen'
                   AND idkategori = 'SKR'
                   AND aktif = 1
                 ORDER BY nama";
        $res = $db->QueryDb($sql);

        echo "<select id='idpembayaran' class='inputbox' style='width: 250px' onchange='clearContent()'>";
        echo "<option value='0' selected>Semua Iuran Sukarela</option>";
        while($row = mysqli_fetch_row($res))
        {
            echo "<option value='$row[0]'>$row[1]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kf07x");
    }
    finally
    {
        $db->Close();
    }

}

function ShowSelectTabunganSiswa()
{
    $departemen = $_REQUEST["departemen"];

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT replid, nama 
                  FROM jbsfina.datatabungan
                 WHERE departemen = '$departemen'
                   AND aktif = 1
                 ORDER BY nama";
        $res = $db->QueryDb($sql);

        echo "<select id='idpembayaran' class='inputbox' style='width: 250px' onchange='clearContent()'>";
        echo "<option value='0' selected>Semua Tabungan Siswa</option>";
        while($row = mysqli_fetch_row($res))
        {
            echo "<option value='$row[0]'>$row[1]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "");
    }
    finally
    {
        $db->Close();
    }
}

function ShowSelectBank()
{
    global $departemen;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT bankno, CONCAT(bank, ' - ', bankname)
                  FROM jbsfina.bank2
                 WHERE aktif = 1
                   AND departemen = '$departemen'
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);

        if ($nData == 0)
        {
            echo "<select id='bank' class='inputbox' style='width: 250px' onchange='clearContent()'>";
            echo "<option value='-1' selected>(belum tersedia data bank)</option>";
            echo "</select>";    
        }
        else 
        {
            echo "<select id='bank' class='inputbox' style='width: 250px' onchange='clearContent()'>";
            echo "<option value='0' selected>Semua Bank</option>";
            while($row = mysqli_fetch_row($res))
            {
                echo "<option value='$row[0]'>$row[1]</option>";
            }
            echo "</select>";    
        }
        
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "k27fm");
    }
    finally
    {
        $db->Close();
    }
}


function ShowSelectPetugas()
{
    $db = new Db();

    try
    {
        $db->Open();

        $sql = "SELECT h.login, p.nama
                  FROM jbsuser.hakakses h, jbssdm.pegawai p
                 WHERE h.login = p.nip
                   AND h.modul = 'KEUANGAN'
                   AND h.aktif = 1
                   AND p.aktif = 1
                 ORDER BY p.nama";
        $res = $db->QueryDb($sql);

        echo "<select id='idpetugas' class='inputbox' style='width: 250px' onchange='clearContent()'>";
        echo "<option value='ALL' selected>Semua Petugas</option>";
        while($row = mysqli_fetch_row($res))
        {
            echo "<option value='$row[0]'>$row[1] $row[0]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "ka319");
    }
    finally
    {
        $db->Close();
    }
}
?>