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

    try
    {
        $dep = getDepartemen($db, SI_USER_ACCESS());
     
        echo "<select id='departemen' onchange='onChangeDept()' style='width:200px' class='inputbox'>";
        foreach ($dep as $value) 
        {
            if ($departemen == "") 
                $departemen = $value;
            
            $sel = $departemen == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k70qn");
    }
}

function ShowSelectTahunAjaran($db)
{
    global $departemen, $idTahunAjaran;

    try
    {
        $sql = "SELECT replid, tahunajaran, aktif 
                  FROM jbsakad.tahunajaran 
                 WHERE departemen = '$departemen' 
                 ORDER BY aktif DESC, replid DESC";
        $res = $db->QueryDb($sql);
        
        echo "<select id='tahunajaran' onchange='onChangeTahunAjaran()' class='inputbox' style='width:150px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idTahunAjaran == "") 
                $idTahunAjaran = $row['replid'];
            
            $aktif = $row['aktif'] == 1 ? " (Aktif)" : "";
            $sel = $idTahunAjaran == $row['replid'] ? "selected" : "";

            echo "<option value='$row[replid]' $sel>$row[tahunajaran] $aktif</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kxnau");
    }
}

function SetAktif()
{
    $replid = RequestData("replid", 0);
    $aktif = RequestData("aktif", 1);

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "UPDATE jbsakad.kalenderakademik 
                   SET aktif='$aktif' 
                 WHERE replid='$replid'";
        $db->QueryDb($sql);
        
        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}

function ShowTableKalender($db)
{
    global $departemen, $idTahunAjaran;

    try
    {
        $sql = "SELECT replid, kalender, keterangan, aktif
                  FROM jbsakad.kalenderakademik
                 WHERE departemen = '$departemen'
                   AND idtahunajaran = '$idTahunAjaran'
                 ORDER BY replid DESC";
        $res = $db->QueryDb($sql);

        if (mysqli_num_rows($res) == 0)
        {
            HintInfo::ShowCenter("Belum ada kalender akademik<br>Silahkan klik ikon tambah untuk membuat kalender akademik");
            return;
        }
        
        echo "<table class='tab tabShadow' id='table' width='95%' align='center'>";
        echo "<tr height='30'>";
        echo "<td class='header' width='50' align='center'>No</td>";
        echo "<td class='header' width='200' align='center'>Kalender Akademik</td>";
        echo "<td class='header' align='center'>Keterangan</td>";
        echo "<td class='header colButton hide-in-report' width='120'>&nbsp;</td>";
        echo "</tr>";

        $cnt = 0;
        while($row = mysqli_fetch_row($res))
        {
            $replid = $row[0];

            $status = $row[3] == 1 ? 
                "<img id='imStatus$replid' src='../images/ico/aktif.png' class='cur-hand hide-in-report' title='Aktif' onclick='setNewAktif($replid, 0)'>" : 
                "<img id='imStatus$replid' src='../images/ico/nonaktif.png' class='cur-hand hide-in-report' title='Tidak Aktif' onclick='setNewAktif($replid, 1)'>";

            echo "<tr height='40'>";
            echo "<td class='numberColumn' align='center'>" . ($cnt + 1) . "</td>";
            echo "<td align='left'>$row[1]</td>";
            echo "<td align='left'>$row[2]</td>";
            echo "<td align='center'class='colButton hide-in-report'>";
            echo "<img src='../images/ico/ubah.png' class='cur-hand hide-in-report' onclick='edit($row[0])' title='edit'>&nbsp;&nbsp;";
            echo "$status&nbsp;&nbsp;";
            echo "<img src='../images/ico/hapus.png' class='cur-hand hide-in-report' onclick='hapus($row[0])' title='hapus'>";
            echo "</td>";
            echo "</tr>";
            $cnt++;
        }
        echo "</table>";

    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kxnau");
    }
}

function HapusKalenderAkademik()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData('replid', 0);

        $sql = "DELETE FROM jbsakad.kalenderakademik WHERE replid = '$replid'";
        $db->QueryDb($sql);

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451) // Cannot delete or update a parent row: a foreign key constraint fails
        {
            $msg = "Data kalender akademik tidak dapat dihapus karena sudah digunakan di data lain.";
            return "[-1,\"$msg\"]";
        }

        $msg = Msg::InfoError($ex->getMessage(), "kem31");
        return "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }
}
?>