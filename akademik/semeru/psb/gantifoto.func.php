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
function ShowCurrentFoto($db)
{
    global $replid;

    $sql = "SELECT nopendaftaran, nama, 
                   IFNULL(panggilan, '') AS fpanggilan,
                   IF(foto IS NULL, '', TO_BASE64(foto)) AS ffoto
              FROM jbsakad.calonsiswa
             WHERE replid = '$replid'";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)           
    {
        echo "<br><i>tidak ditemukan data calon siswa</i>";
        return;
    }  

    $row = mysqli_fetch_assoc($res);

    echo "<div style='position: relative; text-align: center;'>";
    if (!empty($row['ffoto']))
        echo "<img id='img$replid' class='shadow-2 cur-hand' onclick='showImageCalonSiswa($replid)' src='data:image/jpeg;base64,". $row['ffoto'] . "' border='0'>";
    else
        echo "<img id='img$replid' class='shadow-2 cur-hand' onclick='showImageCalonSiswa($replid)' src='" . NoUserImage() . "' border='0'>";
    echo "<br><br>";
    echo "<span id='nama$replid' class='fs-14 fst-bold'>" . $row['nama'] . "</span><br>";
    echo "<span id='nis$replid' class='ff-consolas fs-13'>" . $row['nopendataran'] . "</span><br>";
    echo "<span id='panggilan$replid' class='fst-italic fs-12 fg-secondary'>" . $row['fpanggilan'] . "</span>";
    echo "</div>";            
            
}

function SimpanFoto()
{
    $db = new Db;
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $foto = RequestData("foto", "");

        $sql = "UPDATE jbsakad.calonsiswa 
                   SET foto = FROM_BASE64('$foto') 
                 WHERE replid = $replid";
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
?>