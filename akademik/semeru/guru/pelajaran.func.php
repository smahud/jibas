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

function ShowTablePelajaran($db)
{
    global $departemen, $nRowPerPage, $SI_USER_STAFF, $nData;

    try
    {
        $sql = "SELECT p.replid, p.kode, p.nama, p.sifat, p.keterangan, p.aktif, k.kelompok, p.urutan
                  FROM jbsakad.pelajaran p
                  LEFT JOIN jbsakad.kelompokpelajaran k ON p.idkelompok = k.replid
                 WHERE p.departemen = '$departemen'
                 ORDER BY p.urutan, p.nama";
        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);
        if ($nData == 0)
        {
            HintInfo::ShowCenter("Belum tersedia data Pelajaran.<br>Silahkan klik ikon tambah untuk menambah data Pelajaran");
            return;
        }

        echo "<table class='tab tabShadow' id='table' border='1' style='border-collapse:collapse' width='95%' align='center' bordercolor='#000000'>";
        echo "<tr height='30'>";
        echo "<td class='header' width='4%' align='center'>No</td>";
        echo "<td class='header' width='8%' align='center'>Singkatan</td>";
        echo "<td class='header' width='25%' align='center'>Nama</td>";
        echo "<td class='header' width='10%' align='center'>Sifat</td>";
        echo "<td class='header' width='15%' align='center'>Kelompok Pelajaran</td>";
        echo "<td class='header'>Keterangan</td>";
        echo "<td class='header' width='6%' align='center'>Urutan</td>";
        echo "<td class='header' width='6%' align='center'>Status</td>";
        if (SI_USER_LEVEL() != $SI_USER_STAFF) {
            echo "<td class='header colButton hide-in-report' width='7%'></td>";
        }
        echo "</tr>";

        $cnt = 1;
        while ($row = mysqli_fetch_array($res))
        {
            $replid = $row['replid'];
            $kode = $row['kode'];
            $nama = $row['nama'];
            $sifat = $row['sifat'];
            $kelompok = $row['kelompok'];
            $keterangan = $row['keterangan'];
            $urutan = $row['urutan'];
            $aktif = $row['aktif'];

            echo "<tr height='25'>";
            echo "<td align='center' class='numberColumn'>$cnt</td>";
            echo "<td>$kode</td>";
            echo "<td>$nama</td>";
            echo "<td align='center'>" . ($sifat == 1 ? 'Wajib' : 'Tambahan') . "</td>";
            echo "<td>$kelompok</td>";
            echo "<td>$keterangan</td>";
            echo "<td align='center'>$urutan</td>";
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

            if (SI_USER_LEVEL() != $SI_USER_STAFF) {
                echo "<td align='center' class='colButton hide-in-report'>";
                echo "<span class='cur-hand fg-secondary' onclick='edit($replid)' title='ubah'> <img src='../images/ico/ubah.png' border='0'></span>&nbsp;";
                echo "<span class='cur-hand fg-secondary' onclick='hapus($replid)' title='hapus'> <img src='../images/ico/hapus.png' border='0'></span>";
                echo "</td>";
            }

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

function ShowPageControl($db)
{
    global $departemen, $nRowPerPage, $page, $nData;

    if ($nData == 0)
        return;

    $sql = "SELECT COUNT(*) FROM jbsakad.pelajaran WHERE departemen = '$departemen'";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $nData = $row[0];

    $totalPage = ceil($nData / $nRowPerPage);

    echo "<table border='0' style='border-collapse:collapse' width='95%'>";
    echo "<tr height='30' align='center'>";
    echo "<td align='left'>";

    echo "Halaman&nbsp;&nbsp;";
    echo "<input type='hidden' id='totalpage' value='$totalPage'>";
    echo "<input type='button' class='but' style='height:28px;' value='  <  ' onclick='onPrevPage()'>";
    echo "<select id='page' class='inputbox' style='width: 50px' onchange='onChangePage()'>";
    for ($i = 1; $i <= $totalPage; $i++)
    {
        $sel = ($i == $page) ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
    echo "<input type='button' class='but' style='height:28px;' value='  >  ' onclick='onNextPage()'>";
    echo "&nbsp;dari $totalPage, jumlah $nData data";

    echo "</td>";
    echo "</tr>";
    echo "</table>";
}

function ChangeAktif()
{
    $db = new Db;
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];
        $aktif = $_REQUEST['aktif'];

        $sql = "UPDATE jbsakad.pelajaran SET aktif=$aktif WHERE replid=$replid";
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

function HapusPelajaran()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];

        $sql = "DELETE FROM jbsakad.pelajaran WHERE replid = $replid";
        $db->QueryDb($sql);

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451) // Cannot delete or update a parent row: a foreign key constraint fails
        {
            $msg = "Data pelajaran tidak dapat dihapus karena sudah digunakan di data lain.";
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