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
function ShowAturanGrading()
{
    global $departemen, $idPelajaran, $nip;

    $db = new Db();
    try
    {
        $db->Open();

        $lsTingkat = [];
        $sql = "SELECT replid, tingkat 
                  FROM jbsakad.tingkat 
                 WHERE departemen = '$departemen' 
                   AND aktif = 1 
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $lsTingkat[] = [$row[0], $row[1]];
        }

        echo "<div style='background-color: red; width: 100%; position: relative;'>";
        echo "<div style='position: absolute; top: 0; right: 0;' class='hide-in-report'>";
        echo "<span class='cur-hand fg-secondary' onclick='cetakAturanGrading()'><img src='../images/ico/print.png' title='cetak'>&nbsp;cetak</span>";
        echo "</div></div><br><br>";

        for ($i = 0; $i < count($lsTingkat); $i++)
        {
            $idTingkat = $lsTingkat[$i][0];
            $tingkat = $lsTingkat[$i][1];

            echo "<fieldset class='tabShadow' style='min-height: 100px; border-radius:4px; border: 1px solid #ccc'>";    
            echo "<legend class='fs-12'>Tingkat $tingkat&nbsp;&nbsp;";
            echo "<span class='fs-12 fg-secondary cur-hand hide-in-report' onclick='tambahAturanGrading($idTingkat, \"$tingkat\")'>";
            echo "<img src='../images/ico/tambah.png'> Input Aturan Grading Nilai Rapor</a>";
            echo "</legend>";
            
            $sql = "SELECT g.dasarpenilaian, dp.keterangan 
				      FROM jbsakad.aturangrading g, jbsakad.tingkat t, jbsakad.dasarpenilaian dp
			   	     WHERE t.replid = g.idtingkat 
                       AND t.departemen = '$departemen' 
					   AND g.dasarpenilaian = dp.dasarpenilaian 
                       AND dp.aktif = 1
					   AND g.idpelajaran = '$idPelajaran' 
                       AND g.idtingkat = '$idTingkat' 
                       AND g.nipguru = '$nip' 
                     GROUP BY g.dasarpenilaian";
            $res = $db->QueryDb($sql);
            $nData = mysqli_num_rows($res);
            if ($nData == 0)
            {
                echo "<div style='display: flex; width: 100%; height: 80px; align-items: center; justify-content: center;'>";
                echo "<span class='fg-secondary fst-italic'>belum ada data aturan grading</span>";
                echo "</div>";
                echo "</fieldset><br><br>";
                continue;
            }

            $lsAturan = [];
            while($row = mysqli_fetch_row($res))
            {
                $lsAturan[] = $row;
            }

            $cnt = 0;
            for($j = 0; $j < count($lsAturan); $j++)
            {
                $dasarPenilaianKode = $lsAturan[$j][0];
                $dasarPenilaian = $lsAturan[$j][1];

                $sql = "SELECT g.replid, grade, nmin, nmax
					      FROM jbsakad.aturangrading g, jbsakad.tingkat t
					     WHERE t.replid = g.idtingkat 
                           AND t.departemen = '$departemen' 
						   AND g.idpelajaran = '$idPelajaran' 
                           AND g.idtingkat = '$idTingkat' 
                           AND g.dasarpenilaian = '$dasarPenilaianKode' 
						   AND g.nipguru = '$nip' 
                         ORDER BY grade";
                $res = $db->QueryDb($sql);
                if (mysqli_num_rows($res) == 0)
                    continue;

                $cnt += 1;
                if ($cnt == 1)
                    echo "<div style='display: flex;'>";

                echo "<div style='min-width: 350px; margin: 15px;'>";
                echo "<table class='tab' width='300'>";
                echo "<tr>";
                echo "<td colspan='2' class='bg-light-gray'>";
                echo "<div style='position: relative;'>";
                echo "<b>$dasarPenilaian</b>";
                echo "<div style='right: 0; top: 0; position: absolute;' class='hide-in-report'>";
                echo "<img src='../images/ico/ubah.png' title='ubah' class='cur-hand' onclick='editAturanGrading($idTingkat, \"$tingkat\", \"$dasarPenilaianKode\")'>&nbsp;";
                echo "<img src='../images/ico/hapus.png' title='hapus' class='cur-hand' onclick='hapusAturanGrading($idTingkat, \"$tingkat\", \"$dasarPenilaianKode\")'>";
                echo "</div>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
                while ($row = mysqli_fetch_row($res))
                {
                    $grade = $row[1];
                    $nmin = $row[2];
                    $nmax = $row[3];
                    echo "<tr>";
                    echo "<td style='width: 40px;' align='center'>$grade</td>";
                    echo "<td>$nmin - $nmax</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";

                if ($cnt == 2)
                {
                    echo "</div>";
                    $cnt = 0;
                }
            }
           
            echo "</fieldset><br><br>";
            
        }

    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo Msg::InfoError($ex->getMessage(), "krufe");
    }
    finally
    {
        $db->Close();
    }
}

function HapusAturanGrading()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idpelajaran = RequestData("idpelajaran", 0);
        $nip = RequestData("nip", "");
        $idtingkat = RequestData("idtingkat", 0);
        $aspek = RequestData("aspek", "");

        $sql = "DELETE FROM jbsakad.aturangrading 
			     WHERE idpelajaran = '$idpelajaran' 
                   AND nipguru = '$nip' 
                   AND idtingkat = '$idtingkat' 
                   AND dasarpenilaian = '$aspek'";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil dihapus"]);                   
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