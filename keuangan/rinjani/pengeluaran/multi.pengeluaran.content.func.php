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
function ShowSelectPengeluaran()
{
    global $departemen;

    $sql = "SELECT replid, nama
              FROM jbsfina.datapengeluaran
             WHERE departemen = '$departemen'
               AND aktif = 1
             ORDER BY nama";
    $res = QueryDb($sql);

    echo "<span style='display:inline-block; width: 85px'>Pengeluaran:</span>";
    echo "<select name='pengeluaran' id='pengeluaran'  class='inputbox' style='width: 250px' onchange='ChangePengeluaran()'>\r\n";
    echo "<option value='0'>--Pilih Pengeluaran--</option>\r\n";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='$row[0]'>$row[1]</option>\r\n";
    }
    echo "</select>\r\n";
}


function ShowCariPengguna2()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = $_REQUEST["departemen"];
        $cari = $_REQUEST["cari"];

        $sql = "SELECT DISTINCT namapemohon
                  FROM jbsfina.pengeluaran p, jbsfina.datapengeluaran dp
                 WHERE p.idpengeluaran = dp.replid
                   AND dp.departemen = '$departemen'
                   AND namapemohon LIKE '%$cari%'
                   AND namapemohon IS NOT NULL 
                   AND LENGTH(namapemohon) > 0
                 ORDER BY namapemohon";
        $res = $db->QueryDb($sql);

        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><i>data tidak ditemukan</i>";
            return;
        }

        echo "<table id='tabPengguna' class='tab' border='0' cellpadding='2' cellspacing='0'>";
        $no = 0;
        while($row = mysqli_fetch_row($res))
        {
            $no += 1;
            echo "<tr>";
            echo "<td width='25' class='numberColumn' align='center'>$no</td>";
            echo "<td width='325' align='left'>$row[0]</td>";
            echo "<td width='*' align='center'>";
            echo "<input type='button' class='dialogButtonGray' style='min-height: 20px; font-size: 10px;' value='pilih' onclick='pilihPengguna2(\"$row[0]\")'>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kgu7j");
    }
    finally
    {
        $db->Close();
    }
}

function ShowSelectPengguna2()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = $_REQUEST["departemen"];
        $sql = "SELECT DISTINCT namapemohon
                  FROM jbsfina.pengeluaran p, jbsfina.datapengeluaran dp
                 WHERE p.idpengeluaran = dp.replid
                   AND dp.departemen = '$departemen'
                   AND namapemohon IS NOT NULL 
                   AND LENGTH(namapemohon) > 0
                 ORDER BY namapemohon";
        $res = $db->QueryDb($sql);

        echo "<div style='position: relative; width: 450px'>";
        echo "<span style='font-size: 14px; font-weight: bold'>Pengguna Dana</span>";
        echo "<span style='position: absolute; right: 0px'>";
        echo "Cari: <input type='text' class='inputbox' id='caripengguna' style='width: 150px' onkeyup='cariPengguna2(event)'>";
        echo "</span>";
        echo "</div><br><br>";
        
        echo "<div id='dvTabPengguna' style='width: 450px; height: 280px; overflow: auto;'>";
        echo "<table id='tabPengguna' class='tab' border='0' cellpadding='2' cellspacing='0'>";
        $no = 0;
        while($row = mysqli_fetch_row($res))
        {
            $no += 1;
            echo "<tr>";
            echo "<td width='25' class='numberColumn' align='center'>$no</td>";
            echo "<td width='325' align='left'>$row[0]</td>";
            echo "<td width='*' align='center'>";
            echo "<input type='button' class='dialogButtonGray' style='min-height: 20px; font-size: 10px;' value='pilih' onclick='pilihPengguna2(\"$row[0]\")'>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kgu7j");
    }
    finally
    {
        $db->Close();
    }
}

function ShowCariPenerima2()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = $_REQUEST["departemen"];
        $cari = $_REQUEST["cari"];

        $sql = "SELECT DISTINCT penerima
                  FROM jbsfina.pengeluaran p, jbsfina.datapengeluaran dp
                 WHERE p.idpengeluaran = dp.replid
                   AND dp.departemen = '$departemen'
                   AND penerima LIKE '%$cari%'
                   AND penerima IS NOT NULL 
                   AND LENGTH(penerima) > 0
                 ORDER BY penerima";
        $res = $db->QueryDb($sql);

        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><i>data tidak ditemukan</i>";
            return;
        }

        echo "<table id='tabPenerima' class='tab' border='0' cellpadding='2' cellspacing='0'>";
        $no = 0;
        while($row = mysqli_fetch_row($res))
        {
            $no += 1;
            echo "<tr>";
            echo "<td width='25' class='numberColumn' align='center'>$no</td>";
            echo "<td width='325' align='left'>$row[0]</td>";
            echo "<td width='*' align='center'>";
            echo "<input type='button' class='dialogButtonGray' style='min-height: 20px; font-size: 10px;' value='pilih' onclick='pilihPenerima2(\"$row[0]\")'";
            echo ">";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kgu7j");
    }
    finally
    {
        $db->Close();
    }
}

function ShowSelectPenerima2()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = $_REQUEST["departemen"];
        $sql = "SELECT DISTINCT penerima
                  FROM jbsfina.pengeluaran p, jbsfina.datapengeluaran dp
                 WHERE p.idpengeluaran = dp.replid
                   AND dp.departemen = '$departemen'
                   AND penerima IS NOT NULL 
                   AND LENGTH(penerima) > 0
                 ORDER BY penerima";
        $res = $db->QueryDb($sql);

        echo "<div style='position: relative; width: 450px'>";
        echo "<span style='font-size: 14px; font-weight: bold'>Penerima Dana</span>";
        echo "<span style='position: absolute; right: 0px'>";
        echo "Cari: <input type='text' class='inputbox' id='caripenerima' style='width: 150px' onkeyup='cariPenerima2(event)'>";
        echo "</span>";
        echo "</div><br><br>";

        echo "<div id='dvTabPenerima' style='width: 450px; height: 280px; overflow: auto;'>";
        echo "<table id='tabPenerima' class='tab' border='0' cellpadding='2' cellspacing='0'>";
        $no = 0;
        while($row = mysqli_fetch_row($res))
        {
            $no += 1;
            echo "<tr>";
            echo "<td width='25' class='numberColumn' align='center'>$no</td>";
            echo "<td width='325' align='left'>$row[0]</td>";
            echo "<td width='*' align='center'>";
            echo "<input type='button' class='dialogButtonGray' style='min-height: 20px; font-size: 10px;' value='pilih' onclick='pilihPenerima2(\"$row[0]\")'>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kgu7j");
    }
    finally
    {
        $db->Close();
    }
}
?>