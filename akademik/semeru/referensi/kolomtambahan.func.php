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
function ShowSelectDepartemen($db)
{
    global $departemen;

    $deps = getDepartemen($db, SI_USER_ACCESS());

    echo "<select id='departemen' class='inputbox' style='width:280px' onchange='onChangeDepartemen()'>";
    foreach ($deps as $value)
    {
        if ($departemen == "")
            $departemen = $value;

        $sel = ($departemen == $value) ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
    
}

function ShowTableKolomTambahan($db)
{
    global $departemen, $SI_USER_STAFF;

    $sql = "SELECT replid, kolom, jenis, keterangan, aktif, urutan 
              FROM jbsakad.tambahandata
             WHERE departemen = '$departemen'
             ORDER BY urutan";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        HintInfo::ShowCenter("Belum ada data kolom tambahan<br>Silahkan klik tombol <b>Tambah / Ubah</b> untuk menambahkannya.");
        return;
    }

    echo "<table class='tab tabShadow' id='table' width='95%' align='center'>";
    echo "<tr height='30'>";
    echo "<td width='4%' class='header' align='center'>No</td>";
    echo "<td width='15%' class='header' align='center'>Kolom</td>";
    echo "<td width='6%' class='header' align='center'>Urutan</td>";
    echo "<td width='8%' class='header' align='center'>Jenis</td>";
    echo "<td width='15%' class='header' align='center'>Data Pilihan</td>";
    echo "<td width='*' class='header' align='center'>Keterangan</td>";
    if (SI_USER_LEVEL() != $SI_USER_STAFF) 
        echo "<td width='5%' class='header hide-in-report' align='center'>Aktif</td>";
    echo "</tr>";

    $cnt = 0;
    while ($row = mysqli_fetch_array($res))
    {
        $replid = $row['replid'];

        $cnt += 1;
        echo "<tr height='25'>";
        echo "<td align='center' class='bg-table-number-column'>$cnt</td>";
        echo "<td align='left'>$row[kolom]</td>";
        echo "<td align='center'>$row[urutan]</td>";
        echo "<td align='center'>";
        if ($row['jenis'] == 1)
            echo "Teks";
        else if ($row['jenis'] == 2)
            echo "File";
        else if ($row['jenis'] == 3)
            echo "Pilihan";
        echo "</td>";
        echo "<td align='center'>";
        if ($row['jenis'] == 3)
        {
            ShowDataPilihan($db, $row['replid']);
            echo "<br>";
            ShowLinkPilihan($row['replid'], $row['kolom']);
        }
        else 
        {
            echo "-";
        }
        echo "</td>";
        echo "<td align='left'>" . $row['keterangan'] . "</td>";
        if (SI_USER_LEVEL() != $SI_USER_STAFF) 
        {
            echo "<td align='center' class='hide-in-report'>";
            if ($row['aktif'] == 1)
                echo "<img id='imAktif$replid' src='../images/ico/aktif.png' class='cur-hand' onclick='setNewAktif($replid, 0)'>";
            else
                echo "<img id='imAktif$replid' src='../images/ico/nonaktif.png' class='cur-hand' onclick='setNewAktif($replid, 1)'>";
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function ShowDataPilihan($db, $idtambahan)
{
    $list = GetDataPilihan($db, $idtambahan);
    echo "<span id='pilihan-$idtambahan'><i>$list</i></span>";
}

function GetDataPilihan($db, $idtambahan)
{
    $list = "";

    $sql = "SELECT pilihan 
              FROM jbsakad.pilihandata
             WHERE idtambahan = '$idtambahan'
               AND aktif = 1
             ORDER BY urutan";
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        if ($list != "") $list .= ", ";
        $list .= $row[0];
    }

    return $list;
}

function ShowLinkPilihan($idTambahan, $namaTambahan)
{
    echo "<a onclick='aturPilihanData($idTambahan, \"$namaTambahan\")' class='ablue hide-in-report'>";
    echo "<img src='../images/ico/ubah.png'>atur pilihan</a>";
}

function SetAktif()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $newAktif = RequestData("newaktif", 1);

        $sql = "UPDATE jbsakad.tambahandata
                   SET aktif = '$newAktif'
                 WHERE replid = '$replid'";
        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?> 