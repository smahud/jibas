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
function SimpanAturanGrading()
{
    $db = new Db();
    try
    {
        $db->Open();
        $db->BeginTrans();

        $replid = RequestData("replid", 0);
        $departemen = RequestData("departemen", 0);
        $idpelajaran = RequestData("idpelajaran", 0);
        $nip = RequestData("nip", "");
        $idtingkat = RequestData("idtingkat", 0);
        $aspek = RequestData("aspek", "");
        $ngrade = RequestData("ngrade", 0);

        $sql = "DELETE FROM jbsakad.aturangrading 
			     WHERE idpelajaran = $idpelajaran 
                   AND nipguru = '$nip' 
                   AND idtingkat = '$idtingkat' 
                   AND dasarpenilaian = '$aspek'";
        $db->QueryDb($sql);

        for ($i = 1; $i <= $ngrade; $i++)
        {
            $nmin = RequestData("nmin$i", 0);
            $nmax = RequestData("nmax$i", 0);
            $grade = RequestData("grade$i", "");

            $sql = "INSERT INTO jbsakad.aturangrading 
                       SET nipguru = '$nip', idtingkat = '$idtingkat', idpelajaran = '$idpelajaran', 
                           dasarpenilaian = '$aspek',nmin = '$nmin', nmax = '$nmax', grade = '$grade'";
            $db->QueryDb($sql);
        }

        $db->CommitTrans();

        return json_encode([1, "Aturan grading berhasil disimpan."]);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}


function ShowSelectAspek($db)
{
    global $idpelajaran, $idtingkat, $nip, $aspek, $mode;

    if ($mode == "tambah")
    {
        $sql = "SELECT dasarpenilaian, keterangan 
                  FROM jbsakad.dasarpenilaian 
                 WHERE aktif = 1 
                   AND dasarpenilaian NOT IN 
                       (SELECT dasarpenilaian 
                          FROM jbsakad.aturangrading g, jbsakad.tingkat t 
                         WHERE t.replid = g.idtingkat 
                           AND g.idpelajaran = '$idpelajaran' 
                           AND g.idtingkat = '$idtingkat' 
                           AND g.nipguru = '$nip' 
                         GROUP BY g.dasarpenilaian)
                 ORDER BY urutan";            
    }
    else 
    {
        $sql = "SELECT dasarpenilaian, keterangan
                  FROM jbsakad.dasarpenilaian
                 WHERE aktif = 1
                 ORDER BY urutan";
    }
    $res = $db->QueryDb($sql);

    echo "<select id='aspek' class='inputbox' style='width: 200px; font-size: 16px; background-color: #fafcb1ff;'>";
    while ($row = mysqli_fetch_row($res))
    {
        if ($aspek == "")
            $aspek = $row[0];

        $sel = ($aspek == $row[0]) ? "selected" : "";
        echo "<option value='$row[0]' $sel>$row[1]</option>";
    }
    echo "</select>";
}

function ShowAturanGradingTable($db)
{
    global $idpelajaran, $idtingkat, $nip, $aspek;
    
    $sql = "SELECT nmin, nmax, grade 
              FROM jbsakad.aturangrading 
             WHERE idpelajaran = '$idpelajaran' 
               AND nipguru = '$nip' 
               AND idtingkat = '$idtingkat' 
               AND dasarpenilaian = '$aspek' 
             ORDER BY grade";            
    $res = $db->QueryDb($sql);

    $i = 1;
    $nmin = [];
    $nmax = [];
    $grade = [];
    while ($row = @mysqli_fetch_array($res)) 
    {
        $nmin[$i] = $row['nmin'];
        $nmax[$i] = $row['nmax'];
        $grade[$i] = $row['grade'];				
        $i++;
    }
    
    echo "<table class='tab tabShadow' id='table' border='1' width='420px' align='left'>";
    echo "<tr height='25' class='bg-table-header fg-white'>";            
    echo "<td width='25px' align='center'>No</td>";
    echo "<td width='110px' align='center'>Nilai Min</td>";
    echo "<td width='110px' align='center'>Nilai Max</td>";
    echo "<td width='*' align='center'>Huruf</td>";
    echo "</tr>";

    for($i = 1; $i <= 10; $i++)
    {
        $vmin = $nmin[$i];
        $vmax = $nmax[$i];
        $vgrade = $grade[$i];

        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>$i</td>";
        echo "<td align='center'><input type='text' id='nmin$i' value='$vmin' class='inputbox' size='8' maxlength='5'></td>";
        echo "<td align='center'><input type='text' id='nmax$i' value='$vmax' class='inputbox' size='8' maxlength='5'></td>";
        echo "<td align='center'><input type='text' id='grade$i' value='$vgrade' class='inputbox' size='8' maxlength='3'></td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>