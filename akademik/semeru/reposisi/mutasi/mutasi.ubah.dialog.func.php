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

function ShowSelectJenisMutasi($db)
{
    global $idJenisMutasi;

    $sql = "SELECT replid, jenismutasi
              FROM jbsakad.jenismutasi 
             ORDER BY jenismutasi";
    $res = $db->QueryDb($sql);
    echo "<select id='jenismutasi' class='inputbox' style='width:200px'>";
    while ($row = mysqli_fetch_array($res)) 
    {
        $sel = $idJenisMutasi == $row['replid'] ? "selected" : "";
        echo "<option value='$row[replid]' $sel>$row[jenismutasi]</option>";
    }
    echo "</select>";             
}

function SimpanUbahMutasi()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idMutasi = RequestData("idmutasi","");
        $tglMutasi = RequestData("tglmutasi","");
        $idJenisMutasi = RequestData("idjenismutasi","");
        $keterangan = RequestData("keterangan", "");
        
        $sql = "UPDATE jbsakad.mutasisiswa 
                   SET jenismutasi='$idJenisMutasi', tglmutasi='$tglMutasi', 
                        keterangan='$keterangan'
                  WHERE replid = $idMutasi";
        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>