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
function ShowAturanBobot()
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
        echo "<span class='cur-hand fg-secondary' onclick='cetakAturanBobot()'><img src='../images/ico/print.png' title='cetak'>&nbsp;cetak</span>";
        echo "</div></div><br><br>";

        for ($i = 0; $i < count($lsTingkat); $i++)
        {
            $idTingkat = $lsTingkat[$i][0];
            $tingkat = $lsTingkat[$i][1];

            echo "<fieldset class='tabShadow' style='min-height: 100px; border-radius:4px; border: 1px solid #ccc'>";    
            echo "<legend class='fs-12'>Tingkat $tingkat&nbsp;&nbsp;";
            echo "<span class='fs-12 fg-secondary cur-hand hide-in-report' onclick='tambahAturanBobot($idTingkat, \"$tingkat\")'>";
            echo "<img src='../images/ico/tambah.png'> Input Aturan Perhitungan Nilai Rapor</a>";
            echo "</legend>";

            $sql = "SELECT a.dasarpenilaian, dp.keterangan
		              FROM jbsakad.aturannhb a, jbsakad.tingkat t, jbsakad.dasarpenilaian dp 
			 		 WHERE a.idtingkat = '$idTingkat' 
                       AND a.idpelajaran = '$idPelajaran' 
                       AND t.departemen = '$departemen' 
					   AND a.dasarpenilaian = dp.dasarpenilaian 
                       AND dp.aktif = 1
  					   AND t.replid = a.idtingkat 
                       AND a.nipguru = '$nip' 
                     GROUP BY a.dasarpenilaian";
            
            $res = $db->QueryDb($sql);
            $nData = mysqli_num_rows($res);
            if ($nData == 0)
            {
                echo "<div style='display: flex; width: 100%; height: 80px; align-items: center; justify-content: center;'>";
                echo "<span class='fg-secondary fst-italic'>belum ada data aturan perhitungan nilai rapor</span>";
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

                $sql = "SELECT j.jenisujian, a.bobot, a.aktif, a.replid 
                          FROM jbsakad.aturannhb a, jbsakad.tingkat t, jbsakad.jenisujian j 
					 	 WHERE a.idtingkat = '$idTingkat' 
                           AND a.idpelajaran = '$idPelajaran' 
                           AND j.replid = a.idjenisujian 
						   AND t.departemen = '$departemen' 
                           AND a.dasarpenilaian = '$dasarPenilaianKode' 
                           AND a.nipguru = '$nip' 
                           AND t.replid = a.idtingkat";
                
                $res = $db->QueryDb($sql);
                if (mysqli_num_rows($res) == 0)
                    continue;

                $cnt += 1;
                if ($cnt == 1)
                    echo "<div style='display: flex;'>";

                echo "<div style='min-width: 350px; margin: 15px;'>";
                echo "<table class='tab' width='300'>";
                echo "<tr>";
                echo "<td colspan='3' class='bg-light-gray'>";
                echo "<div style='position: relative;'>";
                echo "<b>$dasarPenilaian</b>";
                echo "<div style='right: 0; top: 0; position: absolute;' class='hide-in-report'>";
                echo "<img src='../images/ico/ubah.png' title='ubah' class='cur-hand' onclick='editAturanBobot($idTingkat, \"$tingkat\", \"$dasarPenilaianKode\")'>&nbsp;";
                echo "<img src='../images/ico/hapus.png' title='hapus' class='cur-hand' onclick='hapusAturanBobot($idTingkat, \"$tingkat\", \"$dasarPenilaianKode\")'>";
                echo "</div>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
                while ($row = mysqli_fetch_row($res))
                {
                    $jenisUjian = $row[0];
                    $bobot = $row[1];
                    $aktif = $row[2];
                    $replid = $row[3];

                    $ahref = "";
                    if ($aktif == 0)
                        $ahref = "<img src='../images/ico/nonaktif.png' title='tidak aktif' class='cur-hand' onclick='setAktifAturanBobot($replid, 1)'>";
                    else
                        $ahref = "<img src='../images/ico/aktif.png' title='aktif' class='cur-hand' onclick='setAktifAturanBobot($replid, 0)'>";

                    echo "<tr>";
                    echo "<td>$jenisUjian</td>";
                    echo "<td style='width: 40px;' align='center'>$bobot</td>";
                    echo "<td style='width: 50px;' align='center'>";
                    echo "<span id='spAktif$replid'>";
                    echo $ahref;
                    echo "</span>";
                    echo "</td>";
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

function HapusAturanBobot()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idpelajaran = RequestData("idpelajaran", 0);
        $nip = RequestData("nip", "");
        $idtingkat = RequestData("idtingkat", 0);
        $aspek = RequestData("aspek", "");

        $sql = "DELETE FROM jbsakad.aturannhb 
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
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451) // Cannot delete or update a parent row: a foreign key constraint fails
        {
            $msg = "Data aturan bobot tidak dapat dihapus karena sudah digunakan di data lain.";
            return "[-1,\"$msg\"]";
        }
        
        return json_encode([-1, "Gagal menghapus data: " . $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}

function SetAktifAturanBobot()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $newAktif = RequestData("newaktif", 0);

        $sql = "UPDATE jbsakad.aturannhb 
                SET aktif = '$newAktif' 
                WHERE replid = '$replid'";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil diubah"]);                   
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
