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

    echo "<select name='departemen' id='departemen' class='inputbox' style='width:180px' onchange='onDepartemenChange()'>";
    foreach ($deps as $value)
    {
        if ($departemen == "")
            $departemen = $value;

        $sel = ($departemen == $value) ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowActiveProses($db)
{
    global $departemen, $idProsesPsb, $prosesPsb;

    $sql = "SELECT replid, proses 
              FROM jbsakad.prosespenerimaansiswa 
             WHERE departemen = '$departemen' 
               AND aktif = 1 LIMIT 1";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $idProsesPsb = $row[0];
        $prosesPsb = $row[1];

        echo "<input type='hidden' id='idprosespsb' value='$idProsesPsb'>";
        echo "<input id='prosespsb' type='text' class='inputbox_readonly' style='width:180px' value='$prosesPsb' readonly>";
    }
    else 
    {
        echo "<input type='hidden' id='idprosespsb' value='0'>";
        echo "<input id='prosespsb' type='text' class='inputbox_readonly' style='width:180px' value='' readonly>";
    }
}

function CountData($db)
{
    global $idProsesPsb;

    $sql = "SELECT COUNT(k.replid) AS total 
              FROM jbsakad.kelompokcalonsiswa k 
             WHERE k.idproses = $idProsesPsb";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    return $row[0];
}

function ShowTableKelompok($db)
{
    global $SI_USER_STAFF;
    global $idProsesPsb, $page, $nRowPerPage, $nData;

    try
    {
        if ($nData == 0)   
            return;
        
        $startIndex = ($page - 1) * $nRowPerPage;
        $sql = "SELECT k.replid, k.kelompok, k.kapasitas, k.keterangan, COUNT(c.replid) AS jumlah 
                  FROM jbsakad.kelompokcalonsiswa k 
                  LEFT JOIN jbsakad.calonsiswa c ON c.idkelompok = k.replid AND c.aktif = 1 
                 WHERE k.idproses = $idProsesPsb 
                 GROUP BY k.replid 
                 ORDER BY k.kelompok 
                 LIMIT $startIndex, $nRowPerPage";
        $res = $db->QueryDb($sql);

        echo "<input type='hidden' id='ndata' value='$nData'>";
        echo "<table class='tab tabShadow' id='table' border='1' style='border-collapse:collapse' width='95%' align='center' bordercolor='#000000'>";
        echo "<tr height='30'>";
        echo "<td class='header' width='45' align='center'>No</td>";
        echo "<td class='header' width='240' align='center'>Kelompok</td>";
        echo "<td class='header' width='110' align='center'>Kapasitas</td>";
        echo "<td class='header' width='85' align='center'>Terisi</td>";
        echo "<td class='header' align='center'>Keterangan</td>";
        echo "<td class='header colButton hide-in-report' width='85'>&nbsp;</td>";
        echo "</tr>";

        $cnt = $startIndex + 1;
        while ($row = mysqli_fetch_assoc($res))
        {
            $replid = $row['replid'];
            $kelompok = $row['kelompok'];
            $kapasitas = $row['kapasitas'];
            $jumlah = $row['jumlah'];
            $keterangan = $row['keterangan'];

            echo "<tr height='25'>";
            echo "<td align='center' class='numberColumn'>$cnt</td>";
            echo "<td>$kelompok</td>";
            echo "<td align='center'>$kapasitas</td>";
            echo "<td align='center'>$jumlah";
            if ($jumlah > 0)
                echo "&nbsp;<a href=\"JavaScript:lihat($replid)\"><img src='../images/ico/lihat.png' border='0' title='Lihat Calon Siswa'></a>";
            echo "</td>";
            echo "<td>$keterangan</td>";
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
        echo Msg::InfoError($ex->getMessage(), "a7nx1");
    }
}

function ShowPageControl()
{
    global $page, $nRowPerPage, $nData;

    if ($nData == 0)
        return;

    $totalPage = ceil($nData / $nRowPerPage);

    echo "<table border='0' style='border-collapse:collapse' width='94%'>";
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

function HapusKelompok()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];

        $sql = "DELETE FROM jbsakad.kelompokcalonsiswa WHERE replid = $replid";
        $db->QueryDb($sql);

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451)
        {
            $msg = "Data kelompok tidak dapat dihapus karena sudah digunakan di data lain.";
            return "[-1,\"$msg\"]";
        }

        $msg = Msg::InfoError($ex->getMessage(), "a0bqs");
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
