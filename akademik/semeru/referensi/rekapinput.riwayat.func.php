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

function NamaKategori($kategori)
{
    if ($kategori == "NU")
        return "Nilai Ujian";

    if ($kategori == "PP")
        return "Presensi Pelajaran";

    if ($kategori == "PH")
        return "Presensi Harian";

    if ($kategori == "RPP")
        return "Rencana Program Pembelajaran";

    if ($kategori == "CSIS")
        return "Calon Siswa";

    if ($kategori == "SIS")
        return "Siswa";

    if ($kategori == "KRS")
        return "Komentar Rapor - Sikap";

    if ($kategori == "KRN")
        return "Komentar Rapor - Nilai";

    return $kategori;
}

function NamaAplikasi($app)
{
    if ($app == "AKAD")
        return "JIBAS Akademik";

    if ($app == "JS")
        return "JIBAS Jendela Sekolah";

    return $app;
}

function ShowTableRiwayat($db)
{
    global $departemen, $rentang, $page, $nRowPerPage, $keyword;

    $startIndex = ($page - 1) * $nRowPerPage;

    $whereKeyword = "";
    if (!empty($keyword))
        $whereKeyword = " AND MATCH(deskripsi) AGAINST ('$keyword' IN BOOLEAN MODE) "; //$whereKeyword = " AND (alasan LIKE '%$keyword%' OR informasi LIKE '%$keyword%')";

    $sql = "SELECT DATE_FORMAT(ri.waktu, '%d %M %Y<br>%H:%i') AS fwaktu, ri.kategori, ri.deskripsi, ri.app,
                   IFNULL(ri.userid, 'jibas') AS fuserid, IFNULL(pg.nama, 'Adminisrator JIBAS') AS fusername
              FROM jbsjs.riwayatinput ri
              LEFT JOIN jbssdm.pegawai pg ON ri.userid = pg.nip
             WHERE tanggal BETWEEN DATE_SUB(CURDATE(), INTERVAL $rentang DAY) AND CURDATE() 
               AND departemen = '$departemen' $whereKeyword
             ORDER BY ri.waktu DESC
             LIMIT $startIndex, $nRowPerPage";
    $res = $db->QueryDb($sql);

    echo "<table class='tab tabShadow' id='table' width='98%' align='center'>";
    echo "<tr>";
    echo "<td class='header' style='width:30px' align='center'>No</td>";
    echo "<td class='header' style='width:150px' align='center'>Waktu</td>";
    echo "<td class='header' style='width:180px' align='left'>Petugas</td>";
    echo "<td class='header' style='width:200px' align='left'>Pendataan</td>";
    echo "<td class='header' align='left'>Deskripsi</td>";
    echo "<td class='header' style='width:150px' align='center'>Aplikasi</td>";
    echo "</tr>";

    $no = $startIndex;
    while ($row = mysqli_fetch_array($res))
    {
        $no++;
        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td align='center'>$row[fwaktu]</td>";
        echo "<td align='left'><b>$row[fusername]</b><br><span class='fg-secondary'>$row[fuserid]</span></td>";
        echo "<td align='left'>" . NamaKategori($row['kategori']) . "</td>";
        echo "<td align='left'>$row[deskripsi]</td>";
        echo "<td align='center'>" . NamaAplikasi($row['app']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function ShowPageControl()
{
    global $nRowPerPage, $page, $totalData;

    $totalPage = ceil($totalData / $nRowPerPage);

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
    echo "&nbsp;dari $totalPage, jumlah $totalData data";
}
?>