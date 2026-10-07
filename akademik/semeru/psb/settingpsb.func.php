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

$nRowPerPage = 10;

function ShowSelectDepartemen($db)
{
    global $departemen;

    $deps = getDepartemen($db, SI_USER_ACCESS());

    echo "<select id='departemen' class='inputbox' style='width:180px' onchange='onDepartemenChange()'>";
    foreach ($deps as $value)
    {
        if ($departemen == "")
            $departemen = $value;

        $sel = ($departemen == $value) ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowSelectProsesPenerimaan($db)
{
    global $departemen, $idProsesPsb;

    $sql = "SELECT replid, proses 
              FROM jbsakad.prosespenerimaansiswa 
             WHERE departemen = '$departemen' 
               AND aktif = 1
             ORDER BY replid";
    $res = $db->QueryDb($sql);

    echo "<select id='proses' class='inputbox' style='width:180px' onchange='onProsesPenerimaanChange()'>";
    while ($row = mysqli_fetch_assoc($res))
    {
        if ($idProsesPsb == 0)
            $idProsesPsb = $row['replid'];

        $sel = ($idProsesPsb == $row['replid']) ? "selected" : "";
        echo "<option value='$row[replid]' $sel>$row[proses]</option>";
    }
    echo "</select>";
}

function ShowTableSettingPsb($db)
{
    global $idProsesPsb, $SI_USER_STAFF;

    $sql = "SELECT COUNT(replid) 
              FROM jbsakad.settingpsb 
              WHERE idproses = '$idProsesPsb'";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $ndata = $row[0];
    if ($ndata > 0)
    {
        $sql = "SELECT * 
                  FROM jbsakad.settingpsb 
                 WHERE idproses = '$idProsesPsb'";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_array($res);
	
        $kdsum1 = $row['kdsum1']; $nmsum1 = $row['nmsum1'];
	    $kdsum2 = $row['kdsum2']; $nmsum2 = $row['nmsum2'];
	    $kdujian1 = $row['kdujian1']; $nmujian1 = $row['nmujian1'];
	    $kdujian2 = $row['kdujian2']; $nmujian2 = $row['nmujian2'];
	    $kdujian3 = $row['kdujian3']; $nmujian3 = $row['nmujian3'];
	    $kdujian4 = $row['kdujian4']; $nmujian4 = $row['nmujian4'];
	    $kdujian5 = $row['kdujian5']; $nmujian5 = $row['nmujian5'];
	    $kdujian6 = $row['kdujian6']; $nmujian6 = $row['nmujian6'];
	    $kdujian7 = $row['kdujian7']; $nmujian7 = $row['nmujian7'];
	    $kdujian8 = $row['kdujian8']; $nmujian8 = $row['nmujian8'];
	    $kdujian9 = $row['kdujian9']; $nmujian9 = $row['nmujian9'];
	    $kdujian10 = $row['kdujian10']; $nmujian10 = $row['nmujian10'];
    }

    echo "<table class='tab tabShadow' id='table' cellpadding='2' cellspacing='0' align='left' style='width: 700px; margin-left: 60px'>";
    echo "<tr height='30'>";
    echo "<td align='left' width='30%' class='header'>Jenis</td>";
    echo "<td align='center' width='20%' class='header'>Kode</td>";
    echo "<td align='left' width='*' class='header'>Nama</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Sumbangan #1</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdsum1' value='" .  $kdsum1 . "' name='kdsum1' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmsum1' value='" .  $nmsum1 . "' name='nmsum1' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Sumbangan #2</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdsum2' value='" .  $kdsum2 . "' name='kdsum2' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmsum2' value='" .  $nmsum2 . "' name='nmsum2' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Ujian #1</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdujian1' value='" .  $kdujian1 . "' name='kdujian1' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmujian1' value='" .  $nmujian1 . "' name='nmujian1' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Ujian #2</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdujian2' value='" .  $kdujian2 . "' name='kdujian2' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmujian2' value='" .  $nmujian2 . "' name='nmujian2' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Ujian #3</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdujian3' value='" .  $kdujian3 . "' name='kdujian3' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmujian3' value='" .  $nmujian3 . "' name='nmujian3' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Ujian #4</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdujian4' value='" .  $kdujian4 . "' name='kdujian4' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmujian4' value='" .  $nmujian4 . "' name='nmujian4' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Ujian #5</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdujian5' value='" .  $kdujian5 . "' name='kdujian5' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmujian5' value='" .  $nmujian5 . "' name='nmujian5' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Ujian #6</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdujian6' value='" .  $kdujian6 . "' name='kdujian6' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmujian6' value='" .  $nmujian6 . "' name='nmujian6' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Ujian #7</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdujian7' value='" .  $kdujian7 . "' name='kdujian7' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmujian7' value='" .  $nmujian7 . "' name='nmujian7' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Ujian #8</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdujian8' value='" .  $kdujian8 . "' name='kdujian8' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmujian8' value='" .  $nmujian8 . "' name='nmujian8' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Ujian #9</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdujian9' value='" .  $kdujian9 . "' name='kdujian9' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmujian9' value='" .  $nmujian9 . "' name='nmujian9' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='left'>Ujian #10</td>";
    echo "<td align='center'><input type='text' class='inputbox' id='kdujian10' value='" .  $kdujian10 . "' name='kdujian10' size='5' maxlength='5' /></td>";
    echo "<td align='left'><input type='text' class='inputbox' id='nmujian10' value='" .  $nmujian10 . "' name='nmujian10' size='30' maxlength='50' /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan='3' align='center' style='background-color:#CCC'>";
    if (SI_USER_LEVEL() != $SI_USER_STAFF)
    {
        echo "<input type='button' class='dialogButtonPositive w80 h30' id='btSimpan' value='Simpan' onclick='simpanSettingPsb()'/>";
    }
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}

function SimpanSettingPsb()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idProsesPsb = $_POST['idprosespsb'];
        
        $sql = "SELECT COUNT(replid) 
                  FROM jbsakad.settingpsb 
                 WHERE idproses = '$idProsesPsb'";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $nData = $row[0];

        $set = "";
        for($i = 1; $i <= 2; $i++)
        {
            if ($set != "")
                $set .= ", ";
            $fkd = "kdsum$i";
            $fnm = "nmsum$i";
            $kd = RequestData($fkd, "");
            $nm = RequestData($fnm, "");
            $set .= "$fkd = '$kd', $fnm = '$nm'";
        }

        for($i = 1; $i <= 10; $i++)
	    {
		    if ($set != "")
		        $set .= ", ";
		    $fkd = "kdujian$i";
		    $fnm = "nmujian$i";
		    $kd = RequestData($fkd, "");
	    	$nm = RequestData($fnm, "");
		    $set .= "$fkd = '$kd', $fnm = '$nm'";
    	}

        if ($nData == 0)
		    $sql = "INSERT INTO jbsakad.settingpsb SET idproses = '$idProsesPsb', $set";
	    else
		    $sql = "UPDATE jbsakad.settingpsb SET $set WHERE idproses = '$idProsesPsb'";
        $db->QueryDb($sql);
        
        return json_encode([1, "OK"]);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>