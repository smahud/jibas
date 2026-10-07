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

function ShowSelectTahunAjaran($db)
{
    global $departemen, $tahunajaran;

    echo "<select name='tahunajaran' id='tahunajaran' class='inputbox' style='width:220px' onchange='onChangeFilter()'>";

    $sql = "SELECT replid, tahunajaran, aktif
              FROM jbsakad.tahunajaran
             WHERE departemen='$departemen'
             ORDER BY aktif DESC, tglmulai DESC";
    $res = $db->QueryDb($sql);
    while ($row = mysqli_fetch_assoc($res))
    {
        if ($tahunajaran == 0)
            $tahunajaran = $row['replid'];
            
        $sel = ($row['replid'] == $tahunajaran) ? "selected" : "";
        $label = $row['tahunajaran'];
        if ($row['aktif'])
            $label .= " (Aktif)";
        echo "<option value='$row[replid]' $sel>$label</option>";
    }

    echo "</select>";
}

function ShowSelectTingkat($db)
{
    global $departemen, $tingkat;

    echo "<select name='tingkat' id='tingkat' class='inputbox' style='width:180px' onchange='onChangeFilter()'>";

    $sql = "SELECT replid, tingkat 
              FROM jbsakad.tingkat 
             WHERE departemen='$departemen' 
               AND aktif=1 
             ORDER BY urutan";
    $res = $db->QueryDb($sql);
    while ($row = mysqli_fetch_assoc($res))
    {
        if ($tingkat == 0)
            $tingkat = $row['replid'];
            
        $sel = ($row['replid'] == $tingkat) ? "selected" : "";
        echo "<option value='$row[replid]' $sel>$row[tingkat]</option>";
    }

    echo "</select>";
}

function ShowTableKelas($db)
{
    global $departemen, $tahunajaran, $tingkat, $urut, $urutan, $page, $nRowPerPage, $SI_USER_STAFF, $nData;

    $startIndex = ($page - 1) * $nRowPerPage;
    try
    {
        $sql = "SELECT k.replid, k.kelas, k.kapasitas, k.nipwali, k.aktif, k.keterangan,
                       p.nama AS wali, COUNT(s.replid) AS terisi
                  FROM jbsakad.kelas k
                  JOIN jbsakad.tahunajaran t ON k.idtahunajaran = t.replid
                  JOIN jbssdm.pegawai p ON k.nipwali = p.nip
                  LEFT JOIN jbsakad.siswa s ON s.idkelas = k.replid AND s.aktif = 1
                 WHERE t.departemen = '$departemen'
                   AND k.idtingkat = '$tingkat'
                   AND k.idtahunajaran = '$tahunajaran'
                 GROUP BY k.replid
                 ORDER BY $urut $urutan
                 LIMIT $startIndex, $nRowPerPage";

        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);
        if ($nData == 0)
        {
            HintInfo::ShowCenter("Belum tersedia data kelas<br>Silahkan klik ikon tambah untuk menambahkan data kelas.");
            return;
        }

        echo "<input type='hidden' id='urut' value='$urut'>";
        echo "<input type='hidden' id='urutan' value='$urutan'>";
        echo "<input type='hidden' id='varbaris' value='$nRowPerPage'>";

        echo "<table class='tab tabShadow' id='table' border='1' style='border-collapse:collapse' width='100%' align='center' bordercolor='#000000'>";
        echo "<tr height='30'>";
        echo "<td class='header' width='45' align='center'>No</td>";
        echo "<td class='header' width='160' align='center' style='cursor:pointer;' onclick='changeSort(\"kelas\")'>Kelas" . SortIndicator('kelas', $urut, $urutan) . "</td>";
        echo "<td class='header' width='200' align='center' style='cursor:pointer;' onclick='changeSort(\"p.nama\")'>Wali Kelas" . SortIndicator('p.nama', $urut, $urutan) . "</td>";
        echo "<td class='header' width='90' align='center' style='cursor:pointer;' onclick='changeSort(\"kapasitas\")'>Kapasitas" . SortIndicator('kapasitas', $urut, $urutan) . "</td>";
        echo "<td class='header' width='75' align='center'>Terisi</td>";
        echo "<td class='header' align='center'>Keterangan</td>";
        echo "<td class='header' width='100' align='center' style='cursor:pointer;' onclick='changeSort(\"k.aktif\")'>Status" . SortIndicator('k.aktif', $urut, $urutan) . "</td>";
        echo "<td class='header colButton hide-in-report' width='120'>&nbsp;</td>";
        echo "</tr>";

        $cnt = $startIndex + 1;
        while ($row = mysqli_fetch_assoc($res))
        {
            $replid = $row['replid'];
            $kelas = $row['kelas'];
            $kapasitas = $row['kapasitas'];
            $wali = $row['wali'];
            $nipwali = $row['nipwali'];
            $terisi = $row['terisi'];
            $keterangan = $row['keterangan'];
            $aktif = $row['aktif'];

            echo "<tr height='25'>";
            echo "<td align='center' class='numberColumn'>$cnt</td>";
            echo "<td><b>$kelas</b></td>";
            echo "<td><b>$wali</b><br><span class='fg-secondary'>$nipwali</span></td>";
            echo "<td align='center'>$kapasitas</td>";
            echo "<td align='center'>$terisi";
            if ($terisi > 0)
                echo "&nbsp;<a href=\"javascript:lihatSiswa($replid, '$kelas')\"><img src='../images/ico/lihat.png' border='0' title='Lihat Siswa'></a>";
            echo "</td>";
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
        echo "<input type='hidden' id='page' value='$page'>";
        echo "<input type='hidden' id='totalpage' value='" . ceil($nData / $nRowPerPage) . "'>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowPageControl($db)
{
    global $departemen, $tahunajaran, $tingkat, $nRowPerPage, $page, $nData;

    if ($nData == 0)
        return;

    $sql = "SELECT COUNT(*) FROM jbsakad.kelas k JOIN jbsakad.tahunajaran t ON k.idtahunajaran = t.replid WHERE t.departemen = '$departemen' AND k.idtingkat = '$tingkat' AND k.idtahunajaran = '$tahunajaran'";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $nData = $row[0];

    $totalPage = ceil($nData / $nRowPerPage);

    echo "<table border='0' style='border-collapse:collapse' width='100%'>";
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

function ChangeAktifKelas()
{
    $db = new Db;
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];
        $aktif = $_REQUEST['aktif'];

        $sql = "UPDATE jbsakad.kelas SET aktif=$aktif WHERE replid=$replid";
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

function HapusKelas()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];

        $sql = "DELETE FROM jbsakad.kelas WHERE replid = $replid";
        $db->QueryDb($sql);

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451)
        {
            $msg = "Data kelas tidak dapat dihapus karena sudah digunakan di data lain.";
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

function SortIndicator($field, $current, $direction)
{
    if ($field !== $current)
        return "";

    return ($direction === "ASC") ? " &uarr;" : " &darr;";
}
