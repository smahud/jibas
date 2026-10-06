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
function ShowSelectDepartemen()
{
    global $departemen;

    $db = new Db();
    try
    {
        $db->Open();

        echo "<select id='departemen' name='departemen' class='inputbox' style='width: 250px' onchange='changeDep()'>";
        $dep = getDepartemen($db, getAccess());
        foreach($dep as $value)
        {
            if ($departemen == "") $departemen = $value;
            $sel = $departemen == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "k0eas");

    }
    finally
    {
        $db->Close();
    }
}

function ShowSelectTingkat()
{
    global $departemen;
    global $idTingkat, $tingkat;

    $db = new Db();
    try
    {
        $db->Open();

        echo "<select id='tingkat' name='tingkat' class='inputbox' style='width: 250px' onchange='changeTingkat()'>";
        $sql = "SELECT replid, tingkat FROM jbsakad.tingkat WHERE departemen = '$departemen' AND aktif = 1 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            if ($idTingkat == "")
            {
                $idTingkat = $row[0];
                $tingkat = $row[1];
            }
            echo "<option value='$row[0]'>$row[1]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kax96");
    }
    finally
    {
        $db->Close();
    }
}

function ShowSelectKelas()
{
    global $idTingkat, $departemen;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT k.replid, k.kelas
                  FROM jbsakad.kelas k, jbsakad.tahunajaran ta
                 WHERE k.idtahunajaran = ta.replid
                   AND k.idtingkat = $idTingkat
                   AND ta.departemen = '$departemen'
                   AND ta.aktif = 1
                   AND k.aktif = 1
                 ORDER BY k.kelas";
        $res = $db->QueryDb($sql);
        $no = 0;

        echo "<select id='kelas' name='kelas' class='inputbox' style='width: 250px' onchange='changeKelas()'>";
        while($row = mysqli_fetch_row($res))
        {
            echo "<option value='$row[0]'>$row[1]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kjrg1");
    }
    finally
    {
        $db->Close();
    }
}

function ShowDaftarVaSiswa()
{
    global $departemen, $idKelas;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT replid, bank
                  FROM jbsfina.bank2
                 WHERE departemen = '$departemen'
                   AND aktif = 1
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        $lsBank = [];
        while($row = mysqli_fetch_row($res))
        {
            $lsBank[] = array($row[0], $row[1]);
        }                 

        $sql = "SELECT s.nis, s.nama
                  FROM jbsakad.siswa s, jbsakad.kelas k
                 WHERE s.idkelas = k.replid
                   AND k.replid = $idKelas
                 ORDER BY s.nama";
        $res = $db->QueryDb($sql);

        echo "<table class='tab' id='table' border='1' cellspacing='0' cellpadding='0'>";
        echo "<tr style='height: 30px'>";
        echo "<td class='header' style='width: 25px' align='center'>No</td>";
        echo "<td class='header' style='width: 250px' align='center'>Siswa</td>";
        echo "<td class='header' style='width: 500px' align='center'>Virtual Account</td>";
        echo "<td class='header' style='width: 100px' align='center'>&nbsp;</td>";
        echo "</tr>";

        $no = 0;
        while($row = mysqli_fetch_row($res))
        {
            $no++;

            $lsVa = [];
            $nis = $row[0];
            $sql = "SELECT v.id, v.vano, v.idbank
                      FROM jbsfina.vasiswa2 v
                     WHERE v.nis = '$nis'";
            $res2 = $db->QueryDb($sql);
            while($row2 = mysqli_fetch_row($res2))
            {
                $lsVa[] = array($row2[0], $row2[1], $row2[2]);
            }

            $nLsVa = count($lsVa);
            if ($nLsVa < 3)
            {
                for($i = $nLsVa; $i < 3; $i++)
                {
                    $lsVa[] = array("0", "", "");
                }
            }

            echo "<tr>";
            echo "<td class='col_no' align='center'>$no</td>";
            echo "<td align='left' valign='top'><b>$row[1]</b><br><span class='bs_secondary'>$row[0]</span></td>";
            echo "<td>";
            echo "<input type='hidden' id='nis-$no' value='$nis'>";

            $ix = 0;
            foreach($lsVa as $va)
            {
                $ix += 1;
                
                echo "<span>";
                echo "$ix&nbsp;&nbsp;<input type='hidden' id='idva-$no-$ix' value='$va[0]'>";
                echo "<input type='text' id='vano-$no-$ix' class='inputbox' style='width: 200px' value='$va[1]'>";
                echo "<select id='vabank-$no-$ix' class='inputbox' style='width: 200px'>";
                for($i = 0; $i < count($lsBank); $i++)
                {
                    $sel = $va[2] == $lsBank[$i][0] ? "selected" : "";
                    echo "<option value='" . $lsBank[$i][0] . "' $sel>" . $lsBank[$i][1] . "</option>";
                }
                echo "</select>";
                echo "</span><br>";
            }

            echo "</td>";
            echo "<td align='center' valign='top'>";
            echo "<input type='button' id='btnSimpan-$no' class='dialogButtonPositive' onclick='simpanVaSiswa($no)' style='width: 80px' value='Simpan'>";
            echo "<span id='spSimpan-$no' class='bs_secondary fst_italic fsz_10' style='margin-left: 10px'></span>";    
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kjrg1");
    }
    finally
    {
        $db->Close();
    }
}

function SimpanVaSiswa()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nis = $_REQUEST["nis"];

        $lsDupVa = [];
        for($i = 1; $i <= 3; $i++)
        {
            $idVa = $_REQUEST["idva-$i"];
            $vano = trim($_REQUEST["vano-$i"]);
            $idBank = $_REQUEST["vabank-$i"];

            if (strlen($vano) == 0)
                continue;

            if ($idVa == "0")
            {
                $sql = "SELECT COUNT(id)
                          FROM jbsfina.vasiswa2
                         WHERE vano = '$vano'";    
            }
            else 
            {
                $sql = "SELECT COUNT(id)
                          FROM jbsfina.vasiswa2
                         WHERE vano = '$vano'
                           AND id <> $idVa";
            }
            Logger::LogOnce($sql);

            $res = $db->QueryDb($sql);
            $row = mysqli_fetch_row($res);
            $nData = (int) $row[0];
            if ($nData > 0)
            {
                $lsDupVa[] = $vano;
            }
        }

        if (count($lsDupVa) > 0)
            return json_encode([0, "Virtual Account " . implode(", ", $lsDupVa) . " sudah digunakan", ""]);

        $db->BeginTrans();

        $lsIdVaSiswa = [];
        for($i = 1; $i <= 3; $i++)
        {
            $idVa = $_REQUEST["idva-$i"];
            $vano = trim($_REQUEST["vano-$i"]);
            $idBank = $_REQUEST["vabank-$i"];

            if ($idVa == "0")
            {
                if (strlen($vano) > 0)
                {
                    $sql = "INSERT INTO jbsfina.vasiswa2
                               SET vano = '$vano', idbank = '$idBank', nis = '$nis'";
                    Logger::LogOnce($sql);
                    $db->QueryDb($sql);

                    $sql = "SELECT LAST_INSERT_ID()";
                    $res = $db->QueryDb($sql);
                    $row = mysqli_fetch_row($res);
                    $lsIdVaSiswa[] = $row[0];
                }
                else 
                {
                    $lsIdVaSiswa[] = "0";
                }
            }
            else 
            {
                if (strlen($vano) == 0)
                {
                    $sql = "DELETE FROM jbsfina.vasiswa2 
                             WHERE id = $idVa";
                    Logger::LogOnce($sql);
                    $db->QueryDb($sql);

                    $lsIdVaSiswa[] = "0";
                }
                else
                {
                    $sql = "UPDATE jbsfina.vasiswa2 
                               SET vano = '$vano', idbank = '$idBank' 
                             WHERE id = $idVa";
                    Logger::LogOnce($sql);
                    $db->QueryDb($sql);

                    $lsIdVaSiswa[] = $idVa;
                }
            }
        }

        $db->CommitTrans();
        //$db->RollbackTrans();

        return json_encode([1, "OK", $lsIdVaSiswa]);
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();

        return json_encode([-1, $ex->getMessage(), ""]);
    }
    finally
    {
        $db->Close();
    }
}
?>