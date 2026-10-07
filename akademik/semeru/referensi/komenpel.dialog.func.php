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
function ShowTableKomenPel()
{
    global $mode, $idPelajaran, $kodeAspek, $idTingkat;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT replid, komentar, urutan, penulis, DATE_FORMAT(waktu, '%d-%b-%Y %H:%i') AS fwaktu
                  FROM jbsakad.pilihkomenpel
                 WHERE idpelajaran = '$idPelajaran'
                   AND dasarpenilaian = '$kodeAspek'
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><br><i>belum ada data komentar pelajaran</i>";
            return;
        }

        echo "<table id='table' class='tab tabShadow' width='100%'>";
        echo "<tr>";
        echo "<td class='bg-table-header fg-white' width='7%' align='center'>No</td>";
        if ($mode == "select")
            echo "<td class='bg-table-header fg-white' width='8%' align='center'>Pilih</td>";
        echo "<td class='bg-table-header fg-white' width='*' align='left'>Komentar</td>";
        echo "<td class='bg-table-header fg-white' width='8%' align='center'>Urutan</td>";
        echo "<td class='bg-table-header fg-white' width='12%' align='center'>&nbsp;</td>";
        echo "</tr>";

        $no = 0;
        while($row = mysqli_fetch_row($res))
        {
            $no += 1;

            $data64 = base64_encode(json_encode([$row[0], $row[1], $row[2]]));

            echo "<tr>";
            echo "<td class='numberColumn' align='center' valign='top' style='padding-top: 10px;'>$no</td>";
            if ($mode == "select")
            {
                echo "<td align='center' valign='top' style='padding-top: 10px;'>";
                echo "<img src='../images/ico/select16.png' class='cur-hand' onclick='pilih($row[0])'>";
                echo "</td>";
            }
            echo "<td align='left' valign='top'>";
            echo $row[1];
            echo "<span class='fg-secondary fst-italic'>$row[3], $row[4]</span>";
            echo "</td>";
            echo "<td align='center' valign='top' style='padding-top: 10px;'>$row[2]</td>";
            echo "<td align='center' valign='top' style='padding-top: 10px;'>";
            echo "<input type='hidden' id='data$row[0]' value='$data64'>";
            echo "<img src='../images/ico/ubah.png' class='cur-hand' onclick='edit($row[0])'>&nbsp;";
            echo "<img src='../images/ico/hapus.png' class='cur-hand' onclick='hapus($row[0])'>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    catch (Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $komenpel64 = RequestData("komenpel64", "");
        $komenpel = base64_decode($komenpel64);
        $urutan = RequestData("urutan", 0);
        $idPelajaran = RequestData("idpelajaran", 0);
        $idTingkat = RequestData("idtingkat", 0);
        $kodeAspek = RequestData("kodeaspek", "");

        $sql = "INSERT INTO jbsakad.pilihkomenpel
                   SET komentar = ?, urutan = ?, idpelajaran = ?, idtingkat = ?, dasarpenilaian = ?";

        $stmt = $db->PrepareStatement($sql);
        $stmt->bind_param("siiis", $komenpel, $urutan, $idPelajaran, $idTingkat, $kodeAspek);
        $stmt->execute();

        return json_encode([1, "Data berhasil disimpan"]);
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

function SimpanEdit()
{
    $db = new Db();
    try
    {
        $db->Open();

        $komenpelid = RequestData("komenpelid", 0);
        $komenpel64 = RequestData("komenpel64", "IA==");
        $komenpel = base64_decode($komenpel64);
        $urutan = RequestData("urutan", 0);

        $sql = "UPDATE jbsakad.pilihkomenpel
                   SET komentar = ?, urutan = ?
                 WHERE replid = ?";
        $stmt = $db->PrepareStatement($sql);
        $stmt->bind_param("sii", $komenpel, $urutan, $komenpelid);
        $stmt->execute();

        return json_encode([1, "Data berhasil disimpan"]);
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

function HapusKomenPel()
{
    $db = new Db();
    try
    {
        $db->Open();

        $komenpelid = RequestData("komenpelid", 0);

        $sql = "DELETE FROM jbsakad.pilihkomenpel 
                 WHERE replid = '$komenpelid'";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil dihapus"]);
    }
    catch(Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451)
            return json_encode([-1, "Komentar Pelajaran tidak bisa dihapus karena masih digunakan"]);

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>