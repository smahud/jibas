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

function GetOrderBy($orderBy)
{
    switch ($orderBy)
    {
        case 0:
            return "s.replid DESC";
        case 2:
            return "s.nopendaftaran ASC";
        case 3:
            return "s.sum1 DESC";
        case 4:
            return "s.sum2 DESC";
        case 5:
            return "s.ujian1 DESC";
        case 6:
            return "s.ujian2 DESC";            
        case 7:
            return "s.ujian3 DESC";            
        case 8:
            return "s.ujian4 DESC";            
        case 9:
            return "s.ujian5 DESC";            
        case 10:
            return "s.ujian6 DESC";            
        case 11:
            return "s.ujian7 DESC";            
        case 12:
            return "s.ujian8 DESC";            
        case 13:
            return "s.ujian9 DESC";            
        case 14:
            return "s.ujian10 DESC";            
        default:
            return "s.nama ASC";
    }
}

function ShowSelectOrderBy()
{
    global $orderBy;

    echo "<select id='orderby' class='inputbox' style='width: 120px;' onchange='onChangeOrderBy()'>";
    $sel = ($orderBy == 0) ? "selected" : "";
    echo "<option value='0' $sel>Terbaru</option>";
    $sel = ($orderBy == 1) ? "selected" : "";
    echo "<option value='1' $sel>Nama</option>";
    $sel = ($orderBy == 2) ? "selected" : "";
    echo "<option value='2' $sel>No Pendaftaran</option>";
    $sel = ($orderBy == 3) ? "selected" : "";
    echo "<option value='3' $sel>Sumbangan 1</option>";
    $sel = ($orderBy == 4) ? "selected" : "";
    echo "<option value='4' $sel>Sumbangan 2</option>";
    $sel = ($orderBy == 5) ? "selected" : "";
    echo "<option value='5' $sel>Ujian 1</option>";
    $sel = ($orderBy == 6) ? "selected" : "";
    echo "<option value='6' $sel>Ujian 2</option>";
    $sel = ($orderBy == 7) ? "selected" : "";
    echo "<option value='7' $sel>Ujian 3</option>";
    $sel = ($orderBy == 8) ? "selected" : "";
    echo "<option value='8' $sel>Ujian 4</option>";
    $sel = ($orderBy == 9) ? "selected" : "";
    echo "<option value='9' $sel>Ujian 5</option>";
    $sel = ($orderBy == 10) ? "selected" : "";
    echo "<option value='10' $sel>Ujian 6</option>";
    $sel = ($orderBy == 11) ? "selected" : "";
    echo "<option value='11' $sel>Ujian 7</option>";
    $sel = ($orderBy == 12) ? "selected" : "";
    echo "<option value='12' $sel>Ujian 8</option>";
    $sel = ($orderBy == 13) ? "selected" : "";
    echo "<option value='13' $sel>Ujian 9</option>";
    $sel = ($orderBy == 14) ? "selected" : "";
    echo "<option value='14' $sel>Ujian 10</option>";
    echo "</select>";
}

function ShowTableNilaiInfo($db)
{
    global $page, $nRowPerPage, $idProses, $idKelompok, $orderBy;

    $orderBy = GetOrderBy($orderBy);
    $startIndex = ($page - 1) * $nRowPerPage;

    $sql = "SELECT s.replid, nopendaftaran, nama, s.aktif,
                   IFNULL(panggilan, '') AS fpanggilan,
                   IFNULL(sum1, '') AS fsum1,
                   IFNULL(sum2, '') AS fsum2,
                   IFNULL(ujian1, '') AS fujian1,
                   IFNULL(ujian2, '') AS fujian2,
                   IFNULL(ujian3, '') AS fujian3,
                   IFNULL(ujian4, '') AS fujian4,
                   IFNULL(ujian5, '') AS fujian5,
                   IFNULL(ujian6, '') AS fujian6,
                   IFNULL(ujian7, '') AS fujian7,
                   IFNULL(ujian8, '') AS fujian8,
                   IFNULL(ujian9, '') AS fujian9,
                   IFNULL(ujian10, '') AS fujian10
              FROM jbsakad.calonsiswa s
             WHERE s.idkelompok = '$idKelompok' 
            ORDER BY $orderBy
            LIMIT $startIndex, $nRowPerPage";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) <= 0)
    {
        echo "<br><br><i>belum ada data siswa";
        return;
    }

    $sql = "SELECT COUNT(replid) 
              FROM jbsakad.settingpsb 
             WHERE idproses = $idProses";
    $ndata = $db->ExecuteScalar($sql, 0);
    if ($ndata > 0)
    {
        $sql = "SELECT * 
                  FROM jbsakad.settingpsb 
                 WHERE idproses = $idProses";
        $res2 = $db->QueryDb($sql);
        $row2 = mysqli_fetch_array($res2);
        
        $kdsum1 = $row2['kdsum1'];
        $kdsum2 = $row2['kdsum2'];
        $kdujian1 = $row2['kdujian1'];
        $kdujian2 = $row2['kdujian2'];
        $kdujian3 = $row2['kdujian3'];
        $kdujian4 = $row2['kdujian4'];
        $kdujian5 = $row2['kdujian5'];
        $kdujian6 = $row2['kdujian6'];
        $kdujian7 = $row2['kdujian7'];
        $kdujian8 = $row2['kdujian8'];
        $kdujian9 = $row2['kdujian9'];
        $kdujian10 = $row2['kdujian10'];
    }

    echo "<table id='table' class='tab tabShadow' width='100%'>";
    echo "<tr>";
    echo "<td class='header' width='3%' align='center'>No</td>";
    echo "<td class='header' width='10%'>No. Pendaftaran</td>";
    echo "<td class='header' width='*'>Nama</td>";
    echo "<td class='header' align='center' width='10%'>Sumbangan 1<br>$kdsum1</td>";
    echo "<td class='header' align='center' width='10%'>Sumbangan 2<br>$kdsum2</td>";
    echo "<td class='header' align='center' width='5%'>Ujian 1<br>$kdujian1</td>";
    echo "<td class='header' align='center' width='5%'>Ujian 2<br>$kdujian2</td>";
    echo "<td class='header' align='center' width='5%'>Ujian 3<br>$kdujian3</td>";
    echo "<td class='header' align='center' width='5%'>Ujian 4<br>$kdujian4</td>";
    echo "<td class='header' align='center' width='5%'>Ujian 5<br>$kdujian5</td>";
    echo "<td class='header' align='center' width='5%'>Ujian 6<br>$kdujian6</td>";
    echo "<td class='header' align='center' width='5%'>Ujian 7<br>$kdujian7</td>";
    echo "<td class='header' align='center' width='5%'>Ujian 8<br>$kdujian8</td>";
    echo "<td class='header' align='center' width='5%'>Ujian 9<br>$kdujian9</td>";
    echo "<td class='header' align='center' width='5%'>Ujian 10<br>$kdujian10</td>";
    echo "</tr>";

    $no = $startIndex;
    while($row = mysqli_fetch_assoc($res))
    {
        $no++;

        $replid = $row['replid'];

        echo "<tr style='height: 40px;'>";
        echo "<td align='center' class='numberColumn'>$no</td>";
        echo "<td align='left'><span class='ablue' onclick='showInfoCalonSiswa(\"$row[nopendaftaran]\")'>$row[nopendaftaran]</span></td>";
        echo "<td align='left'>$row[nama]</td>";
        echo "<td align='right' class='ff-courier fs-13'>" . FormatEmptyRupiah($row['fsum1']) . "</td>";
        echo "<td align='right' class='ff-courier fs-13'>" . FormatEmptyRupiah($row['fsum2']) . "</td>";
        echo "<td align='center' class='ff-courier fs-13'>" . FormatEmptyNilai($row['fujian1']) . "</td>";
        echo "<td align='center' class='ff-courier fs-13'>" . FormatEmptyNilai($row['fujian2']) . "</td>";
        echo "<td align='center' class='ff-courier fs-13'>" . FormatEmptyNilai($row['fujian3']) . "</td>";
        echo "<td align='center' class='ff-courier fs-13'>" . FormatEmptyNilai($row['fujian4']) . "</td>";
        echo "<td align='center' class='ff-courier fs-13'>" . FormatEmptyNilai($row['fujian5']) . "</td>";
        echo "<td align='center' class='ff-courier fs-13'>" . FormatEmptyNilai($row['fujian6']) . "</td>";
        echo "<td align='center' class='ff-courier fs-13'>" . FormatEmptyNilai($row['fujian7']) . "</td>";
        echo "<td align='center' class='ff-courier fs-13'>" . FormatEmptyNilai($row['fujian8']) . "</td>";
        echo "<td align='center' class='ff-courier fs-13'>" . FormatEmptyNilai($row['fujian9']) . "</td>";
        echo "<td align='center' class='ff-courier fs-13'>" . FormatEmptyNilai($row['fujian10']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function FormatEmptyRupiah($value)
{
    if ((int) $value == 0)
        return "";

    return FormatRupiah($value);
}

function FormatEmptyNilai($value)
{
    if ((int) $value == 0)
        return "";

    return $value;
}

function ShowPageControl($db)
{
    global $page, $nRowPerPage, $idKelompok;

    $sql = "SELECT COUNT(replid)
              FROM jbsakad.calonsiswa s
             WHERE s.idkelompok = '$idKelompok'";
    $nData = $db->ExecuteScalar($sql, 0);

    if ($nData == 0)
        echo "";

    $nPage = (int) ceil($nData / $nRowPerPage);
    echo "Halaman ";
    echo "<input type='button' class='but' style='height: 25px; width: 25px;' value='<' onclick='onPrevPage()'>";
    echo "<select id='page' class='inputbox' style='width: 50px' onchange='onChangePage()'>";
    for ($i = 1; $i <= $nPage; $i++)
    {
        $sel = $i == $page ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
    echo "<input type='button' class='but' style='height: 25px; width: 25px;' value='>' onclick='onNextPage()'>";
    echo " dari $nPage, jumlah $nData data";
    echo "<input type='hidden' id='npage' value='$nPage'>";
}
?>