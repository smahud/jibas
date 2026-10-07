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

$nRowPerPage = 15;

function ShowSelectBulan()
{
    global $bulan;

    echo "<select name='bulan' id='bulan' style='width:110px' class='inputbox' onchange='onChangeBulanTahun()'>";
    for ($i = 1; $i <= 12; $i++) {
        $sel = ($bulan == $i) ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
    }
    echo "</select>";
}

function ShowSelectTahun()
{
    global $G_START_YEAR, $tahun;

    echo "<select name='tahun' id='tahun' style='width:80px' class='inputbox' onchange='onChangeBulanTahun()'>";
    for ($i = $G_START_YEAR; $i <= date('Y'); $i++) 
    {
        $sel = ($tahun == $i) ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
}

function CountDataAudit($db)
{
    global $bulan, $tahun, $keyword;
    
    $whereKeyword = "";
    if (!empty($keyword))
        $whereKeyword = " AND MATCH(alasan, informasi) AGAINST ('$keyword' IN BOOLEAN MODE) "; //" AND (alasan LIKE '%$keyword%' OR informasi LIKE '%$keyword%')";

    $sql = "SELECT COUNT(*) 
              FROM jbsakad.auditnilai 
             WHERE MONTH(tanggal) = '$bulan' 
               AND YEAR(tanggal) = '$tahun'
               $whereKeyword";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    return $row[0];
}

function ShowTableAudit($db)
{
    global $bulan, $tahun, $keyword, $page, $totalData, $nRowPerPage;

    if ($totalData == 0)
    {
        HintInfo::ShowCenter("Belum tersedia data Perubahan Nilai");
        return;
    }

    $startIndex = ($page - 1) * $nRowPerPage;

    $whereKeyword = "";
    if (!empty($keyword))
        $whereKeyword = " AND MATCH(alasan, informasi) AGAINST ('$keyword' IN BOOLEAN MODE) "; //$whereKeyword = " AND (alasan LIKE '%$keyword%' OR informasi LIKE '%$keyword%')";

    $sql = "SELECT jenisnilai, idnilai, nasli, nubah, DATE_FORMAT(tanggal, '%d %M %Y<br>%H:%i') AS tanggal, 
                   alasan, pengguna, informasi 
	          FROM jbsakad.auditnilai 
             WHERE MONTH(tanggal) = '$bulan' 
               AND YEAR(tanggal) = '$tahun' 
               $whereKeyword
             ORDER BY tanggal DESC 
             LIMIT $startIndex, $nRowPerPage";
    $res = $db->QueryDb($sql);

    echo "<table class='tab tabShadow' id='table' cellpadding='4' border='1' style='border-collapse:collapse' width='95%' align='center' bordercolor='#000000' />";
    echo "<tr height='30' align='center' class='header'>";
    echo "<td width='30' align='center' class='header'>No</td>";
    echo "<td width='120' align='center' class='header'>Tanggal</td>";
    echo "<td width='400' align='center' class='header'>Informasi</td>";
    echo "<td width='80' align='center' class='header'>Sebelum</td>";
    echo "<td width='80' align='center' class='header'>Setelah</td>";
    echo "<td width='*' align='center' class='header'>Alasan</td>";
    echo "<td width='100' align='center' class='header'>Pengguna</td>";
    echo "</tr>";
   
    $no = $startIndex;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;

        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column' valign='top'>$no</td>";
        echo "<td align='center' valign='top'>" . $row['tanggal'] . "</td>";
        echo "<td align='left' valign='top'>" . $row['informasi'] . "</td>";
        echo "<td align='center' valign='top'>" . $row['nasli'] . "</td>";
        echo "<td align='center' valign='top'>" . $row['nubah'] . "</td>";
        echo "<td align='left' valign='top'>" . $row['alasan'] . "</td>";
        echo "<td align='left' valign='top'>" . ($row['pengguna'] == "landlord - landlord" ? "Administrator" : $row['pengguna']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
                 
}

function ShowPageControl()
{
    global $nRowPerPage, $page, $totalData;

    if ($totalData == 0)
        return;

    $totalPage = ceil($totalData / $nRowPerPage);

    echo "Halaman&nbsp;&nbsp;";
    echo "<input type='hidden' id='totalpage' value='$totalPage'>";
    echo "<input type='hidden' id='totaldata' value='$totalData'>";
    echo "<input type='button' class='but' style='height:28px;' value='  <  ' onclick='onPrevPage()'>";
    echo "<select id='page' class='inputbox' style='width: 50px' onchange='onChangePage()'>";
    for ($i = 1; $i <= $totalPage; $i++)
    {
        $sel = ($i == $page) ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
    echo "<input type='button' class='but' style='height:28px;' value='  >  ' onclick='onNextPage()'>";
    echo "&nbsp;dari $totalPage, jumlah $totalData data";
}
?>