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
function ShowTableKalenderAkademik($db)
{
    global $idKalender;

    $sql = "SELECT replid, DATE_FORMAT(tanggalawal, '%d %M %Y') AS ftanggalawal,
                   DATE_FORMAT(tanggalakhir, '%d %M %Y') AS ftanggalakhir,
                   kegiatan, keterangan, kode   
              FROM jbsakad.aktivitaskalender
             WHERE idkalender = $idKalender
          ORDER BY tanggalawal";
    $res = $db->QueryDb($sql);          

    if (mysqli_num_rows($res) == 0)
    {
        HintInfo::ShowCenter("Belum ada kegiatan kalender akademik<br>Silahkan klik ikon tambah untuk membuat kegiatan kalender akademik");
        return;
    }
        
    echo "<table class='tab tabShadow' id='table' width='98%' align='center'>";
    echo "<tr height='30'>";
    echo "<td class='header' width='50' align='center'>No</td>";
    echo "<td class='header' width='150' align='center'>Tanggal Awal</td>";
    echo "<td class='header' width='150' align='center'>Tanggal Akhir</td>";
    echo "<td class='header' align='center'>Kegiatan</td>";
    echo "<td class='header colButton hide-in-report' width='120'>&nbsp;</td>";
    echo "</tr>";

    $cnt = 0;
    while($row = mysqli_fetch_row($res))
    {
        $cnt++;

        echo "<tr height='40'>";
        echo "<td class='numberColumn' align='center' valign='top'>$cnt</td>";
        echo "<td align='center' valign='top'><span class='jamStart'>$row[1]</span></td>";
        echo "<td align='center' valign='top'><span class='jamEnd'>$row[2]</span></td>";
        echo "<td align='left' valign='top'>";
        echo "<b>$row[5] $row[3]</b><br>";
        echo "<div style='width: 95%; height: 100px; overflow: auto;' class='fst-italic fg-secondary'>$row[4]</div>";
        echo "</td>";
        echo "<td align='center' valign='top' class='colButton hide-in-report'>";
        echo "<img src='../images/ico/lihat.png' class='cur-hand hide-in-report' onclick='view($row[0])' title='lihat'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/ubah.png' class='cur-hand hide-in-report' onclick='edit($row[0])' title='edit'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/hapus.png' class='cur-hand hide-in-report' onclick='hapus($row[0])' title='hapus'>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function HapusKegiatanKalender()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);

        $sql = "DELETE FROM jbsakad.aktivitaskalender 
                 WHERE replid = $replid";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil dihapus"]);
    }
    catch(Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451)
            return json_encode([0, "Kegiatan kalender tidak bisa dihapus karena masih digunakan"]);

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>