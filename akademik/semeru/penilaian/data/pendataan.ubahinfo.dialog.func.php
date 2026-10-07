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
function ShowSelectRpp($db)
{
    global $idRpp, $idTingkat, $idSemester, $idPelajaran;

    try
    {
        $sql = "SELECT replid, CONCAT(koderpp, ' - ', rpp) AS rpp 
                  FROM jbsakad.rpp 
                 WHERE idtingkat = '$idTingkat' 
                   AND idpelajaran = '$idPelajaran' 
                   AND aktif = 1 
                 ORDER BY urutan, koderpp";
        $res = $db->QueryDb($sql);
        
        echo "<select id='rpp' class='inputbox' style='width:260px'>";
        $sel = $idRpp == "" ? "selected" : "";
        echo "<option value='' $sel>Tanpa RPP</option>";
        while ($row = mysqli_fetch_array($res)) 
        {
            $sel = $idRpp == $row["replid"] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[rpp]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kzyqk");
    }
}

function SimpanInfoUjian()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idUjian = RequestData("idujian", "");
        $tanggal = RequestData("tanggal", date("Y-m-d"));
        $materi = RequestData("materi", "");
        $kode = RequestData("kode", "");
        $rpp = RequestData("rpp", "");
        $rppValue = $rpp == "" ? "NULL" : "'$rpp'";

        $sql = "UPDATE jbsakad.ujian 
                   SET idrpp = $rppValue, 
                       deskripsi = '$materi', 
                       tanggal = '$tanggal', 
                       kode = '$kode' 
                 WHERE replid = '$idUjian'";
        $db->QueryDb($sql);

        ShowToastAfterLoad("Data berhasil disimpan");
       
        return json_encode([1, "OK"]);
    }
    catch(Exception $e)
    {
        $db->LogLastErrorIfExist();
        return json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>