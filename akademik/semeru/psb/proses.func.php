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

    echo "<select name='departemen' id='departemen' class='inputbox' style='width:180px' onchange='onChangeDepartemen()'>";
    foreach ($deps as $value)
    {
        if ($departemen == "")
            $departemen = $value;

        $sel = ($departemen == $value) ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function CountData($db)
{
    global $departemen;
    
    $sql = "SELECT COUNT(p.replid) AS total 
              FROM jbsakad.prosespenerimaansiswa p 
             WHERE p.departemen = '$departemen'";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_assoc($res);
    
    return $row['total'];
}

function ShowTableProses($db)
{
    global $departemen, $page, $nRowPerPage, $SI_USER_STAFF, $nData;

    try
    {
        if ($nData == 0)
            return;

        $startIndex = ($page - 1) * $nRowPerPage;
        $sql = "SELECT p.replid, p.proses, p.kodeawalan, p.keterangan, p.aktif, COUNT(c.replid) AS jumlah 
                  FROM jbsakad.prosespenerimaansiswa p 
                  LEFT JOIN jbsakad.calonsiswa c ON c.idproses = p.replid AND c.aktif = 1 
                 WHERE p.departemen = '$departemen' 
                 GROUP BY p.replid 
                 ORDER BY p.replid DESC
                 LIMIT $startIndex, $nRowPerPage";
        $res = $db->QueryDb($sql);
        
        echo "<input type='hidden' id='ndata' value='$nData'>";
        echo "<table class='tab tabShadow' id='table' border='1' style='border-collapse:collapse' width='95%' align='center' bordercolor='#000000'>";
        echo "<tr height='30'>";
        echo "<td class='header' width='45' align='center'>No</td>";
        echo "<td class='header' width='180' align='center'>Proses</td>";
        echo "<td class='header' width='120' align='center'>Kode Awalan</td>";
        echo "<td class='header' width='80' align='center'>Jumlah</td>";
        echo "<td class='header' align='center'>Keterangan</td>";
        echo "<td class='header' width='100' align='center'>Status</td>";
        echo "<td class='header colButton hide-in-report' width='90'>&nbsp;</td>";
        echo "</tr>";

        $cnt = $startIndex + 1;
        while ($row = mysqli_fetch_assoc($res))
        {
            $replid = $row['replid'];
            $proses = $row['proses'];
            $kodeawalan = $row['kodeawalan'];
            $jumlah = $row['jumlah'];
            $keterangan = $row['keterangan'];
            $aktif = $row['aktif'];

            echo "<tr height='25'>";
            echo "<td align='center' class='numberColumn'>$cnt</td>";
            echo "<td>$proses</td>";
            echo "<td>$kodeawalan</td>";
            echo "<td align='center'>$jumlah</td>";
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
        echo Msg::InfoError($ex->getMessage(), "arvvb");
    }
}

function ShowPageControl()
{
    global $page, $nRowPerPage, $nData;

    if ($nData == 0)
        return;
  
    $totalPage = ceil($nData / $nRowPerPage);

    echo "<table border='0' style='border-collapse:collapse' width='93%'>";
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

function SetAktifProses()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];
        $aktif = $_REQUEST['aktif'];
        $departemen = $_REQUEST['departemen'];

        $sql = "UPDATE jbsakad.prosespenerimaansiswa SET aktif=$aktif WHERE replid=$replid";
        $db->QueryDb($sql);

        if ($aktif == 1)
        {
            $sql = "UPDATE jbsakad.prosespenerimaansiswa SET aktif=0 WHERE replid <> $replid AND departemen = '$departemen'";
            $db->QueryDb($sql);
        }

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $msg = Msg::InfoError($ex->getMessage(), "ascpz");
        return "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }
}

function HapusProses()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];

        $sql = "DELETE FROM jbsakad.prosespenerimaansiswa WHERE replid = $replid";
        $db->QueryDb($sql);

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451)
        {
            $msg = "Data proses penerimaan tidak dapat dihapus karena sudah digunakan di data lain.";
            return "[-1,\"$msg\"]";
        }

        $msg = Msg::InfoError($ex->getMessage(), "am2j9");
        return "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }
}

function SortIndicator($field, $current, $direction)
{
    if ($field !== $current)
        return "";

    return ($direction === "ASC") ? " &uarr;" : " &darr;";
}
