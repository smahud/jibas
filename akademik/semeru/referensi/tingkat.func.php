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

$nRowPerPage = 10;

function ShowSelectDepartemen($db)
{
    global $departemen;

    $deps = getDepartemen($db, SI_USER_ACCESS());

    echo "<select name='departemen' id='departemen' style='width:250px' class='inputbox' onchange='onChangeDepartemen();'>";
    foreach ($deps as $value)
    {
        $sel = ($departemen == $value) ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowTableTingkat($db)
{
    global $departemen, $urut, $urutan, $page, $nRowPerPage, $SI_USER_STAFF, $nData;

    $startIndex = ($page - 1) * $nRowPerPage;
    try
    {
        $sql = "SELECT *
                  FROM jbsakad.tingkat
                 WHERE departemen = '$departemen'
                 ORDER BY $urut $urutan
                 LIMIT $startIndex, $nRowPerPage";
        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);
        if ($nData == 0)
        {
            HintInfo::ShowCenter("Belum tersedia data tingkat<br>Silahkan klik ikon tambah untuk menambahkan data tingkat.");
            return;
        }

        echo "<input type='hidden' id='urut' value='$urut'>";
        echo "<table class='tab tabShadow' id='table' border='1' style='border-collapse:collapse' width='95%' align='center' bordercolor='#000000'>";
        echo "<tr height='30'>";
        echo "<td class='header' width='50' align='center'>No</td>";
        echo "<td class='header' width='200' align='center'>Tingkat</td>";
        echo "<td class='header' align='center'>Keterangan</td>";
        echo "<td class='header' width='80' align='center'>Status</td>";
        echo "<td class='header colButton hide-in-report' width='120'>&nbsp;</td>";
        echo "</tr>";

        $cnt = $startIndex + 1;
        while ($row = mysqli_fetch_array($res))
        {
            $replid = $row['replid'];
            $tingkat = $row['tingkat'];
            $keterangan = $row['keterangan'];
            $aktif = $row['aktif'];

            echo "<tr height='25'>";
            echo "<td align='center' class='numberColumn'>$cnt</td>";
            echo "<td>$tingkat</td>";
            echo "<td>$keterangan</td>";
            echo "<td align='center'>";

            if (SI_USER_LEVEL() == $SI_USER_STAFF)
            {
                if ($aktif == 1)
                    echo "<img src='../images/ico/aktif.png' border='0' title='aktif'>";
                else
                    echo "<img src='../images/ico/nonaktif.png' border='0' title='tidak aktif'>";
            }
            else
            {
                if ($aktif == 1)
                    echo "<img id='status-$replid' src='../images/ico/aktif.png' onclick='setAktif($replid, 0)' style='cursor:pointer' border='0' title='aktif'>";
                else
                    echo "<img id='status-$replid' src='../images/ico/nonaktif.png' onclick='setAktif($replid, 1)' style='cursor:pointer' border='0' title='tidak aktif'>";
            }

            echo "</td>";
            echo "<td align='center' class='colButton hide-in-report'>";
            if (SI_USER_LEVEL() != $SI_USER_STAFF)
            {
                echo "<a href='JavaScript:edit($replid)'><img src='../images/ico/ubah.png' border='0' title='ubah'></a>&nbsp;";
                echo "<a href='JavaScript:hapus($replid)'><img src='../images/ico/hapus.png' border='0' title='hapus'></a>";
            }
            echo "</td>";
            echo "</tr>";

            $cnt++;
        }

        echo "</table>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ChangeAktif()
{
    $db = new Db;
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];
        $aktif = $_REQUEST['aktif'];

        $sql = "UPDATE jbsakad.tingkat SET aktif=$aktif WHERE replid=$replid";
        $db->QueryDb($sql);

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $msg = Msg::InfoError($ex->getMessage(), "k3edk");
        return "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }
}

function HapusTingkat()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];

        $sql = "DELETE FROM jbsakad.tingkat WHERE replid = $replid";
        $db->QueryDb($sql);

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451) // Cannot delete or update a parent row: a foreign key constraint fails
        {
            $msg = "Data tingkat tidak dapat dihapus karena sudah digunakan di data lain.";
            return "[-1,\"$msg\"]";
        }

        $msg = Msg::InfoError($ex->getMessage(), "k3edk");
        return "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }
}
?>