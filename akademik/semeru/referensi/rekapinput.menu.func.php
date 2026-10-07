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
     
        echo "<select id='departemen' onchange='clearContent()' style='width:250px' class='inputbox'>";
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
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectJenisLaporan()
{
    echo "<select id='jenis' onchange='clearContent()' style='width:120px' class='inputbox'>";
    echo "<option value='riwayat'>Riwayat</option>";
    echo "<option value='rekap'>Rekapitulasi</option>";
    echo "</select>";
}

function ShowSelectRentangLaporan()
{
    echo "<select id='rentang' onchange='clearContent()' style='width:150px' class='inputbox'>";
    echo "<option value='0'>Hari ini</option>";
    echo "<option value='7'>Seminggu terakhir</option>";
    echo "<option value='14'>Dua minggu terakhir</option>";
    echo "<option value='30'>Satu bulan terakhir</option>";
    echo "<option value='90'>Tiga bulan terakhir</option>";
    echo "<option value='182'>Enam bulan terakhir</option>";
    echo "<option value='365'>Satu tahun terakhir</option>";
    echo "</select>";
}