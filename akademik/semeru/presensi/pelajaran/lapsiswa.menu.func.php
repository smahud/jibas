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

function ShowSelectTanggalAwal()
{
    $tahunawal = RequestData("tahunawal", 0);
    $bulanawal = RequestData("bulanawal", 0);
    $tglAwal = RequestData("tanggalawal", 0);

    echo "<select id='tanggalawal' class='inputbox' onchange='clearReport();' style='width: 50px'>";
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

    echo "<select id='tanggalakhir' class='inputbox' onchange='clearReport();' style='width: 50px'>";
    $lastTglAkhir = date('t', strtotime($tahunakhir . '-' . $bulanakhir . '-01'));
    for ($i = 1; $i <= $lastTglAkhir; $i++)
        echo "<option value='$i' " . StringIsSelected($tglAkhir, $i) . ">$i</option>";
    echo "</select>";
}

function ShowSelectRentangTanggal($db)
{
    global $departemen;

    try
    {
        $sql = "SELECT MONTH(ta.tglmulai), YEAR(ta.tglmulai),
                       MONTH(ta.tglakhir), YEAR(ta.tglakhir)
                  FROM jbsakad.tahunajaran ta, jbsakad.departemen d
                 WHERE ta.departemen = d.departemen
                   AND ta.aktif = 1
                   AND d.departemen = '$departemen'";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $blnTa1 = $row[0];
            $thnTa1 = $row[1];
            $blnTa2 = $row[2];
            $thnTa2 = $row[3];

            if ($blnTa2 <= $blnTa1)
            {
                $startBlnTa = 1;
                $endBlnTa = (12 - $blnTa1) + $blnTa2;
            }
            else
            {
                $startBlnTa = $blnTa1;
                $endBlnTa = $blnTa2;
            }
        }                   

        $tglAwal = date('d', strtotime('-30 days'));
        $blnAwal = date('m', strtotime('-30 days'));
        $thnAwal = date('Y', strtotime('-30 days'));

        $tglAkhir = date('d');
        $blnAkhir = date('m');
        $thnAkhir = date('Y');

        echo "<select id='tahunawal' onchange='onChangeAwal(); ' class='inputbox' style='width: 80px'>";
        for ($i = $thnTa1; $i <= $thnTa2; $i++)
            echo "<option value='$i' " . StringIsSelected($thnAwal, $i) . ">$i</option>";
        echo "</select>";

        echo "<select id='bulanawal' onchange='onChangeAwal();' class='inputbox' style='width: 100px'>";
        for ($i = $startBlnTa; $i <= $endBlnTa; $i++)
        {
            $bln = $blnTa1 + $i - 1;
            if ($bln > 12)
                $bln = $bln - 12;
            $sel = $bln == $blnAwal ? "selected" : "";
            echo "<option value='$bln' $sel>" . NamaBulan($bln) . "</option>";
        }
        echo "</select>";

        echo "<span id='spTanggalAwal'>";
        echo "<select id='tanggalawal' class='inputbox' onchange='clearReport()' style='width: 50px'>";
        $lastTglAwal = date('t', strtotime($thnAwal . '-' . $blnAwal . '-01'));
        for ($i = 1; $i <= $lastTglAwal; $i++)
            echo "<option value='$i' " . StringIsSelected($tglAwal, $i) . ">$i</option>";
        echo "</select>";
        echo "</span>&nbsp;s/d&nbsp;";

        echo "<select id='tahunakhir' onchange='onChangeAkhir();' class='inputbox' style='width: 80px'>";
        for ($i = $thnTa1; $i <= $thnTa2; $i++)
            echo "<option value='$i' " . StringIsSelected($thnAkhir, $i) . ">$i</option>";
        echo "</select>";

        echo "<select id='bulanakhir' onchange='onChangeAkhir();' class='inputbox' style='width: 100px'>";
        for ($i = $startBlnTa; $i <= $endBlnTa; $i++)
        {
            $bln = $blnTa1 + $i - 1;
            if ($bln > 12)
                $bln = $bln - 12;
            $sel = $bln == $blnAkhir ? "selected" : "";
            echo "<option value='$bln' $sel>" . NamaBulan($bln) . "</option>";
        }
        echo "</select>";

        echo "<span id='spTanggalAkhir'>";
        echo "<select id='tanggalakhir' class='inputbox' onchange='clearReport()' style='width: 50px'>";
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
?>