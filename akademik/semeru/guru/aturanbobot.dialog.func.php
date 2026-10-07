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
                          FROM jbsakad.aturannhb g, jbsakad.tingkat t 
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

function ShowAturanBobotTable($db)
{
    global $idpelajaran, $idtingkat, $nip, $aspek;
    
    
    echo "<table class='tab tabShadow' id='table' border='1' align='left'>";
    echo "<tr height='25' class='bg-table-header fg-white'>";            
    echo "<td width='25' align='center'>No</td>";
    echo "<td width='40' align='center'>&nbsp;</td>";
    echo "<td width='250' align='center'>Jenis Ujian</td>";
    echo "<td width='100' align='center'>Bobot</td>";
    echo "</tr>";

    $sql = "SELECT replid, jenisujian 
              FROM jbsakad.jenisujian 
             WHERE idpelajaran = '$idpelajaran'
             ORDER BY urutan, jenisujian";
    $res = $db->QueryDb($sql);
    $no = 0;
    while($row = mysqli_fetch_row($res))
    {
        $no++;

        $idJenisUjian = $row[0];
        $jenisUjian = $row[1];
        $idBobot = "0";
        $bobot = "";
        $checked = "";
        $disabled = "disabled";
        $bobotclass = "class='inputbox bg-input-disabled'";

        $sql = "SELECT replid, bobot
                  FROM jbsakad.aturannhb
                 WHERE idtingkat = '$idtingkat'
                   AND idpelajaran = '$idpelajaran'
                   AND dasarpenilaian = '$aspek'
                   AND nipguru = '$nip'
                   AND idjenisujian = '$idJenisUjian'";
        $res2 = $db->QueryDb($sql);
        if ($row2 = mysqli_fetch_row($res2))
        {
            $idBobot = $row2[0];
            $bobot = $row2[1];
            $checked = "checked";
            $disabled = "";
            $bobotclass = "class='inputbox'";
        }

        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td align='center'>";
        echo "<input type='checkbox' id='cek$no' $checked onclick='toggleInputBobot($no)'>";
        echo "<input type='hidden' id='idbobot$no' value='$idBobot'>";
        echo "<input type='hidden' id='isdel$no' value='0'>";
        echo "</td>";
        echo "<td align='left'>";
        echo $row[1];
        echo "<input type='hidden' id='idjenisujian$no' value='$idJenisUjian'>";
        echo "</td>";
        echo "<td align='center'>";
        echo "<input type='text' id='bobot$no' size='5' maxlength='3' value='$bobot' $disabled $bobotclass>";
        echo "</td>";
        echo "</tr>";
    }

    echo "<input type='hidden' id='njenisujian' value='$no'>";
    echo "</table>";
}

function SimpanAturanBobot()
{
    $db = new Db();
    try
    {
        $db->Open();

        $db->BeginTrans();

        $idTingkat = RequestData("idtingkat", 0);
        $aspek = RequestData("aspek", "");
        $idPelajaran = RequestData("idpelajaran", 0);
        $nip = RequestData("nip", "");
        $nBobot = RequestData("nbobot", 0);

        for ($i = 1; $i <= $nBobot; $i++)
        {
            $idBobot = RequestData("idbobot$i", 0);
            $idJenisUjian = RequestData("idjenisujian$i", 0);
            $bobot = RequestData("bobot$i", 0);
            $isDel = RequestData("isdel$i", 0);
            $cek = RequestData("cek$i", 0);

            if ($idBobot > 0)
            {
                if ($isDel == 1)
                {
                    $sql = "DELETE FROM jbsakad.aturannhb WHERE replid = '$idBobot'";
                    $db->QueryDb($sql);
                }
                elseif ($cek == 1 && $bobot > 0)
                {
                    $sql = "UPDATE jbsakad.aturannhb SET bobot = '$bobot' WHERE replid = '$idBobot'";
                    $db->QueryDb($sql);
                }
            }
            elseif ($cek == 1 && $bobot > 0)
            {
                $sql = "INSERT INTO jbsakad.aturannhb 
                           SET idtingkat = '$idTingkat', 
                               idpelajaran = '$idPelajaran', 
                               nipguru = '$nip', 
                               dasarpenilaian = '$aspek', 
                               idjenisujian = '$idJenisUjian', 
                               bobot = '$bobot',
                               aktif = 1";
                $db->QueryDb($sql);
            }
        }

        $db->CommitTrans();

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