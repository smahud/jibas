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

    echo "<select name='departemen' id='departemen' class='inputbox' style='width:180px' onchange='onDepartemenChange()'>";
    echo "<option value='yayasan'>Umum</option>";
    foreach ($deps as $value)
    {
        if ($departemen == "")
            $departemen = $value;

        $sel = ($departemen == $value) ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowTableIdentitas($db)
{
    global $departemen;
    global $SI_USER_STAFF;

    $sql = "SELECT *, IF(foto IS NULL, 0, 1) AS fotoexist, 
                    IF(foto IS NULL, '', TO_BASE64(foto)) AS foto64 
              FROM jbsumum.identitas 
             WHERE departemen='$departemen'";
    $res = $db->QueryDb($sql);
    $nData = mysqli_num_rows($res);

    echo "<div style='width: 90%; text-align: right;'>";
    echo "<span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>";
    echo "<img src='../images/ico/refresh.png' border='0'/>&nbsp;refresh";
    echo "</span>&nbsp;&nbsp;";
    if ($nData == 0)
    {
        if (SI_USER_LEVEL() != $SI_USER_STAFF)
        {
            echo "<span class='cur-hand fg-secondary' onclick='tambah()'>";
            echo "<img src='../images/ico/tambah.png' border='0'>&nbsp;tambah";
            echo "</span>";
        }                   
    }
    else 
    {
        if (SI_USER_LEVEL() != $SI_USER_STAFF)
        {
            echo "<span class='cur-hand fg-secondary' onclick='ubah()'>";
            echo "<img src='../images/ico/ubah.png' border='0'>&nbsp;ubah";
            echo "</span>&nbsp;&nbsp;";
            echo "<span class='cur-hand fg-secondary' onclick='hapus()'>";
            echo "<img src='../images/ico/hapus.png' border='0'>&nbsp;hapus";
            echo "</span>";
        }                   
    }
    echo "</div>";

    echo "<table class='tab' id='table' border='0' style='border-collapse:collapse' width='95%' align='center' bordercolor='#000000'>";
    echo "<tr height='30'>";
    echo "<td width='20%' class='header' align='center'>Logo</td>";
    echo "<td width='*' class='header' align='center'>Header</td>";
    echo "</tr>";

    if ($nData == 0)
    {
        echo "<tr>";
        echo "<td style='height: 150px;' colspan='2' align='center' valign='middle'>";
        echo HintInfo::ShowCenter("Belum tersedia data Kop Identitas Sekolah.<br>Silahkan klik ikon tambah untuk membuat identitas sekolah.");
        echo "<input type='hidden' id='replid' value='0'>";
        echo "</td>";
        echo "</tr>";
    }

    if($row = mysqli_fetch_array($res))
    {
        echo "<tr>";
        echo "<td align='center' valign='top'>";
        echo "<input type='hidden' id='replid' value='$row[replid]'>";
        if ($row["fotoexist"] == 1)
            echo "<img src='data:image/jpeg;base64,", $row["foto64"], "' border='0' style='max-width: 250px; max-height: 250px;' alt='foto'/>";
        echo "</td>";
        echo "<td valign='top'>";

        echo "<font size=\"6\"><strong>".$row['nama']."</strong></font><br />";
        echo "<font size=\"2\"><strong>";
        if ($row['alamat2'] <> "" && $row['alamat1'] <> "")
            echo "Lokasi 1: ";

        if ($row['alamat1'] != "")
            echo $row['alamat1'];

        if ($row['telp1'] != "" || $row['telp2'] != "")
            echo "<br>Telp. ";

        if ($row['telp1'] != "")
            echo $row['telp1'];

        if ($row['telp1'] != "" && $row['telp2'] != "")
            echo ", ";

        if ($row['telp2'] != "")
            echo $row['telp2'];

        if ($row['fax1'] != "")
            echo "&nbsp;&nbsp;Fax. ".$row['fax1']."&nbsp;&nbsp;";

        if ($row['alamat2'] <> "" && $row['alamat1'] <> "")
        {
            echo "<br>";
            echo "Lokasi 2: ";
            echo $row['alamat2'];

            if ($row['telp3'] != "" || $row['telp4'] != "")
                echo "<br>Telp. ";

            if ($row['telp3'] != "")
                echo $row['telp3'];

            if ($row['telp3'] != "" && $row['telp4'] != "")
                echo ", ";

            if ($row['telp4'] != "")
                echo $row['telp4'];

            if ($row['fax2'] != "")
                echo "&nbsp;&nbsp;Fax. ".$row['fax2'];
        }

        if ($row['situs'] != "" || $row['email'] != "")
            echo "<br>";
        if ($row['situs'] != "")
            echo "Website: ".$row['situs']."&nbsp;&nbsp;";
        if ($row['email'] != "")
            echo "Email: ".$row['email'];

        echo "</strong></font>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";

}
    

function HapusIdentitas()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);

        $sql = "DELETE FROM jbsumum.identitas WHERE replid = $replid";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "dpt1");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
?>