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

function ShowTableJam($db)
{
    global $departemen;

    $sql = "SELECT replid, jamke, jam1, jam2, keterangan
              FROM jbsakad.jam 
             WHERE departemen = '$departemen' 
             ORDER BY jamke ASC";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        HintInfo::ShowCenter("Belum ada data jam pembelajaran<br>Silahkan klik ikon <b>tambah</b> untuk membuat jam pembelajaran baru");
        return;
    }

    echo "<table class='tab tabShadow' id='table' width='95%' align='center'>";
    echo "<tr height='30'>";
    echo "<td class='header' width='50' align='center'>No</td>";
    echo "<td class='header' width='100' align='center'>Jam Ke</td>";
    echo "<td class='header' width='200' align='center'>Waktu</td>";
    echo "<td class='header' align='center'>Keterangan</td>";
    echo "<td class='header colButton hide-in-report' width='120'>&nbsp;</td>";
    echo "</tr>";

    $cnt = 0;
    while($row = mysqli_fetch_row($res))
    {
        $jamMulai = FormatJam($row[2]);
        $jamAkhir = FormatJam($row[3]);

        echo "<tr height='40'>";
        echo "<td class='numberColumn' align='center'>" . ($cnt + 1) . "</td>";
        echo "<td align='center'>$row[1]</td>";
        echo "<td align='center'><span class='jamStart'>$jamMulai</span> - <span class='jamEnd'>$jamAkhir</span></td>";
        echo "<td align='left'>$row[4]</td>";
        echo "<td align='center'class='colButton hide-in-report'>";
        echo "<img src='../images/ico/ubah.png' class='cur-hand hide-in-report' onclick='editJam($row[0])' title='edit'> ";
        echo "<img src='../images/ico/hapus.png' class='cur-hand hide-in-report' onclick='hapusJam($row[0])' title='hapus'>";
        echo "</td>";
        echo "</tr>";
        $cnt++;
    }
    echo "</table>";

}

function FormatJam($jam)
{
    $ls = explode(":", $jam);
    $jm = $ls[0];
    $mnt = $ls[1];

    return "$jm:$mnt";
}

function HapusJam()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);

        $sql = "DELETE FROM jbsakad.jam 
                 WHERE replid = $replid";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil dihapus"]);
    }
    catch(Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451)
            return json_encode([0, "Jam tidak bisa dihapus karena masih digunakan"]);

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>