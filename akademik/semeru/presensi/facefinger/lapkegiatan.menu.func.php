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
     
        echo "<select id='departemen' onchange='onChangeDept()' style='width:250px' class='inputbox'>";
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

function ShowSelectKegiatan($db)
{
    global $departemen, $aktif, $idKegiatan;

    $sql = "SELECT replid, kegiatan
              FROM jbssat.frkegiatan k
             WHERE aktif = $aktif
               AND departemen = '$departemen'
             ORDER BY kegiatan";
    $res = $db->QueryDb($sql);
    echo "<select id='kegiatan' style='width: 280px;' class='inputbox' onchange='clearContent()'>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value=$row[0]>$row[1]</option>";
    }
    echo "</select>";
}

function ShowSelectRentangTanggal()
{
    global $G_START_YEAR;

    try
    {
        $tglAwal = date('d', strtotime('-30 days'));
        $blnAwal = date('m', strtotime('-30 days'));
        $thnAwal = date('Y', strtotime('-30 days'));

        $tglAkhir = date('d');
        $blnAkhir = date('m');
        $thnAkhir = date('Y');

        echo "<select id='tahunawal' onchange='onChangeAwal(); ' class='inputbox' style='width: 80px'>";
        for ($i = $G_START_YEAR; $i <= $thnAwal; $i++)
            echo "<option value='$i' " . StringIsSelected($thnAwal, $i) . ">$i</option>";
        echo "</select>";

        echo "<select id='bulanawal' onchange='onChangeAwal();' class='inputbox' style='width: 100px'>";
        for ($i = 1; $i <= 12; $i++)
        {
            $sel = $i == $blnAwal ? "selected" : "";
            echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
        }
        echo "</select>";

        echo "<span id='spTanggalAwal'>";
        echo "<select id='tanggalawal' class='inputbox' onchange='clearContent()' style='width: 50px'>";
        $lastTglAwal = date('t', strtotime($thnAwal . '-' . $blnAwal . '-01'));
        for ($i = 1; $i <= $lastTglAwal; $i++)
            echo "<option value='$i' " . StringIsSelected($tglAwal, $i) . ">$i</option>";
        echo "</select>";
        echo "</span>&nbsp;s/d&nbsp;";

        echo "<select id='tahunakhir' onchange='onChangeAkhir();' class='inputbox' style='width: 80px'>";
        for ($i = $G_START_YEAR; $i <= $thnAkhir; $i++)
            echo "<option value='$i' " . StringIsSelected($thnAkhir, $i) . ">$i</option>";
        echo "</select>";

        echo "<select id='bulanakhir' onchange='onChangeAkhir();' class='inputbox' style='width: 100px'>";
        for ($i = 1; $i <= 12; $i++)
        {
            $sel = $i == $blnAkhir ? "selected" : "";
            echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
        }
        echo "</select>";

        echo "<span id='spTanggalAkhir'>";
        echo "<select id='tanggalakhir' class='inputbox' onchange='clearContent()' style='width: 50px'>";
        $lastTglAkhir = date('t', strtotime($thnAkhir . '-' . $blnAkhir . '-01'));
        for ($i = 1; $i <= $lastTglAkhir; $i++)
            echo "<option value='$i' " . StringIsSelected($tglAkhir, $i) . ">$i</option>";
        echo "</select>";
        echo "</span>&nbsp;&nbsp;";

    } 
    catch (Exception $e) 
    {
        echo Msg::InfoError($e->getMessage(), "k70qn");
    }
}

function ShowSelectTanggalAwal()
{
    $tahunawal = RequestData("tahunawal", 0);
    $bulanawal = RequestData("bulanawal", 0);
    $tglAwal = RequestData("tanggalawal", 0);

    echo "<select id='tanggalawal' class='inputbox' onchange='clearContent();' style='width: 50px'>";
    $lastTglAwal = date('t', strtotime($tahunawal . '-' . $bulanawal . '-01'));
    for ($i = 1; $i <= $lastTglAwal; $i++)
        echo "<option value='$i' " . StringIsSelected($tglAwal, $i) . ">$i</option>";
    echo "</select>";
}

function ShowSelectTanggalAkhir()
{
    $tahunakhir = RequestData("tahunakhir", 0);
    $bulanakhir = RequestData("bulanakhir", 0);
    $tglAkhir = RequestData("tanggalakhir", 0);

    echo "<select id='tanggalakhir' class='inputbox' onchange='clearContent();' style='width: 50px'>";
    $lastTglAkhir = date('t', strtotime($tahunakhir . '-' . $bulanakhir . '-01'));
    for ($i = 1; $i <= $lastTglAkhir; $i++)
        echo "<option value='$i' " . StringIsSelected($tglAkhir, $i) . ">$i</option>";
    echo "</select>";
}
?>