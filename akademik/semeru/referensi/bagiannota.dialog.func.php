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
function ShowTableBagianNota()
{
    global $mode;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT id, bagian, urutan
                  FROM jbsumum.bagiannota
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><br><i>belum ada data bagian nota</i>";
            return;
        }

        echo "<table id='table' class='tab tabShadow' width='100%'>";
        echo "<tr>";
        echo "<td class='bg-table-header fg-white' width='8%' align='center'>No</td>";
        if ($mode == "select")
            echo "<td class='bg-table-header fg-white' width='10%' align='center'>Pilih</td>";
        echo "<td class='bg-table-header fg-white' width='*' align='left'>Bagian Nota</td>";
        echo "<td class='bg-table-header fg-white' width='10%' align='center'>Urutan</td>";
        echo "<td class='bg-table-header fg-white' width='12%' align='center'>&nbsp;</td>";
        echo "</tr>";

        $no = 0;
        while($row = mysqli_fetch_row($res))
        {
            $no += 1;

            echo "<tr>";
            echo "<td class='numberColumn' align='center'>$no</td>";
            if ($mode == "select")
            {
                echo "<td align='center'>";
                echo "<img src='../images/ico/select16.png' class='cur-hand' onclick='pilih($row[0], \"$row[1]\")'>";
                echo "</td>";
            }
            echo "<td align='left'>$row[1]</td>";
            echo "<td align='center'>$row[2]</td>";
            echo "<td align='center'>";
            echo "<img src='../images/ico/ubah.png' class='cur-hand' onclick='edit($row[0], \"$row[1]\", $row[2])'>&nbsp;";
            echo "<img src='../images/ico/hapus.png' class='cur-hand' onclick='hapus($row[0])'>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    catch (Exception $ex)
    {
        echo $ex->getMessage();
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

        $bagian = RequestData("bagian", "");
        $urutan = RequestData("urutan", 0);

        $sql = "SELECT COUNT(id)
                  FROM jbsumum.bagiannota
                 WHERE bagian = '$bagian'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData != 0)
            return json_encode([-1, "Bagian Nota $bagian sudah ada"]);
            
        $sql = "INSERT INTO jbsumum.bagiannota
                   SET bagian = '$bagian', urutan = '$urutan'";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil disimpan"]);
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

function SimpanEdit()
{
    $db = new Db();
    try
    {
        $db->Open();

        $bagianid = RequestData("bagianid", 0);
        $bagian = RequestData("bagian", "");
        $urutan = RequestData("urutan", 0);

        $sql = "SELECT COUNT(id)
                  FROM jbsumum.bagiannota
                 WHERE id <> '$bagianid' 
                   AND bagian = '$bagian'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData != 0)
            return json_encode([-1, "Bagian Nota $bagian sudah ada"]);
            
        $sql = "UPDATE jbsumum.bagiannota
                   SET bagian = '$bagian', urutan = '$urutan'
                 WHERE id = '$bagianid'";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil disimpan"]);
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

function HapusBagianNota()
{
     $db = new Db();
    try
    {
        $db->Open();

        $bagianid = RequestData("bagianid", 0);

        $sql = "DELETE FROM jbsumum.bagiannota 
                 WHERE id = '$bagianid'";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil dihapus"]);
    }
    catch(Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451)
            return json_encode([-1, "Bagian nota tidak bisa dihapus karena masih digunakan"]);

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>