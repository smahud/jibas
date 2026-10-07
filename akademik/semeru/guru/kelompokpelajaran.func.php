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
 * This program is distributed in the hope that it is useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php

function ShowTableKelompokPelajaran($db)
{
    global $SI_USER_STAFF;

    try
    {
        $sql = "SELECT k.replid, k.kode, k.kelompok, k.urutan
                  FROM jbsakad.kelompokpelajaran k
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);
        if ($nData == 0)
        {
            echo HintInfo::ShowCenter("Belum tersedia data kelompok pelajaran.<br>Silahkan klik ikon tambah untuk membuat kelompok pelajaran.");
            return;
        }

        echo "<table class='tab tabShadow' id='table' border='1' style='border-collapse:collapse' width='95%' align='center' bordercolor='#000000'>";
        echo "<tr height='30' align='center'>";
        echo "<td class='header' width='50'>No</td>";
        echo "<td class='header' width='150'>Kode</td>";
        echo "<td class='header' width='*'>Kelompok</td>";
        echo "<td class='header' width='100'>Urutan</td>";
        if (SI_USER_LEVEL() != $SI_USER_STAFF)
        {
            echo "<td class='header colButton hide-in-report' width='80'>&nbsp;</td>";
        }
        echo "</tr>";

        $cnt = 1;
        while ($row = mysqli_fetch_array($res))
        {
            $replid = $row['replid'];
            $kode = $row['kode'];
            $kelompok = $row['kelompok'];
            $urutan = $row['urutan'];

            echo "<tr height='25'>";
            echo "<td align='center' class='numberColumn'>$cnt</td>";
            echo "<td align='center'>$kode</td>";
            echo "<td align='left'>$kelompok</td>";
            echo "<td align='center'>$urutan</td>";
            if (SI_USER_LEVEL() != $SI_USER_STAFF)
            {
                echo "<td align='center' class='colButton hide-in-report'>";
                echo "<a href='JavaScript:edit($replid)'><img src='../images/ico/ubah.png' border='0' title='ubah'></a>&nbsp;";
                echo "<a href='JavaScript:hapus($replid)'><img src='../images/ico/hapus.png' border='0' title='hapus'></a>";
                echo "</td>";
            }
            echo "</tr>";

            $cnt++;
        }

        echo "</table>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kf6t8");
    }
}

function HapusKelompokPelajaran()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];

        $sql = "DELETE FROM jbsakad.kelompokpelajaran WHERE replid = '$replid'";
        $db->QueryDb($sql);

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451) // Cannot delete or update a parent row: a foreign key constraint fails
        {
            $msg = "Data kelompok pelajaran tidak dapat dihapus karena sudah digunakan di data lain.";
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