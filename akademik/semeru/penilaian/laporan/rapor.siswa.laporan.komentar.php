<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
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
$arrjenis = array('SPI', 'SOS');
$arrnmjenis = array('Spiritual', 'Sosial');
for($i = 0; $i < count($arrjenis); $i++)
{
    $jenis = $arrjenis[$i];
    $nmjenis = $arrnmjenis[$i];

    $sql = "SELECT komentar, k.predikat
              FROM jbsakad.komenrapor k 
             WHERE k.nis = '$nis' 
               AND k.idsemester = '$idSemester' 
               AND k.idkelas = '$idKelas'
               AND k.jenis = '$jenis'";
    $res2 = $db->QueryDb($sql);
    $komentar = "";
    $predikat = "";
    $nilaiExist = false;
    if ($row2 = mysqli_fetch_row($res2))
    {
        $nilaiExist = true;
        $komentar = $row2[0];
        $predikat = PredikatNama($row2[1]);
    }

    echo "<fieldset class='tab tabShadow' style='padding: 15px; border-radius: 10px;'>";
    echo "<legend>Sikap $nmjenis</legend>";
    echo "<table border='0' cellpadding='5' cellspacing='0' width='100%' style='border-collapse: collapse;'>";
    echo "<tr><td style='width: 150px'>Predikat: <b>$predikat</b></td></tr>";
    echo "<tr><td style='padding-top: 10px; padding-bottom: 10px'>$komentar</td></tr>";
    echo "</table>";
    echo "</fieldset><br><br>";

}
?>