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

function ShowDepartemen($db)
{
    global $departemen;

    try
    {
        $dep = getDepartemen($db, SI_USER_ACCESS());
        
        echo "<p><strong>Departemen:</strong><br>";
        echo "<select name='departemen' class='inputbox' id='departemen' onchange='onChangeDept(true)' style='width:300px;'>";
        foreach ($dep as $value) 
        {
            if ($departemen == "") 
                $departemen = $value;
            $sel = $departemen == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select></p>";
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo Msg::InfoError($ex->getMessage(), "kg699");
    }
}

function ShowTablePelajaran($db)
{
    global $departemen;

    try
    {
        // Get departemen list for dropdown
        // Query pelajaran table
        $sql = "SELECT replid, nama, departemen, kode 
                  FROM jbsakad.pelajaran 
                 WHERE departemen = '$departemen' 
                 ORDER BY nama";
        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);

        if ($nData > 0) 
        {
            echo "<strong>Pelajaran:</strong>";
            echo "<table class='tab tabShadow' id='table' border='1' width='100%' align='left'>";

            $cnt = 0;
            while ($row = mysqli_fetch_array($res)) 
            {
                $replid = $row['replid'];
                $nama = $row['nama'];
                $dep_name = $row['departemen'];
                $kode = $row['kode'];

                $sql = "SELECT COUNT(replid)
                          FROM jbsakad.guru
                         WHERE idpelajaran = '$replid'
                           AND aktif = 1";
                $nData = $db->ExecuteScalar($sql, 0);                         

                echo "<tr height='25' onclick=\"pilih('$replid', '$dep_name', '$nama')\" style='cursor:pointer;'>";
                echo "<td width='15%' align='left'>$kode</td>";
                echo "<td width='*' align='left'>$nama</td>";
                echo "<td width='12%' align='center' class='bg-light-purple fg-secondary'>$nData</td>";
                echo "</tr>";

                $cnt++;
            }

            $sql = "SELECT COUNT(g.replid)
                      FROM jbsakad.guru g, jbsakad.pelajaran p
                     WHERE g.idpelajaran = p.replid
                       AND p.aktif = 1
                       AND g.aktif = 1
                       AND p.departemen = '$departemen'";
            $nData = $db->ExecuteScalar($sql, 0);                         

            echo "<tr height='25' onclick=\"pilih('0', '$dep_name', 'Semua Guru')\" style='cursor:pointer;'>";
            echo "<td width='15%' align='left' class='bg-light-gray'></td>";
            echo "<td width='*' align='left' class='bg-light-gray'>Semua Guru</a></td>";
            echo "<td width='10%' align='center' class='bg-light-gray fg-secondary'>$nData</td>";
            echo "</tr>";

            echo "</table>";
        } 
        else 
        {
            /*
            echo "<br><br>Belum ada data pelajaran.";
            echo "<table width='100%' border='0' align='center'>";
            echo "<tr>";
            echo "<td align='center' valign='middle' height='200'>";
            echo "<font size='2' color='red'><b>Belum ada data Pelajaran.";
            echo "<br/>Silahkan isi terlebih dahulu di menu Pelajaran pada bagian Guru &amp; Pelajaran.";
            echo "</b></font>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            */
            HintInfo::Show("Belum tersedia data Pelajaran.<br>Silahkan lengkapi data Pelajaran di bagian Guru &amp; Pelajaran menu Pelajaran", 300);
        }
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kdj2d");
    }
}
?>