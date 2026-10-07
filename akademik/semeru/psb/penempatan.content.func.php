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
function ShowSelectAngkatan($db)
{
    global $departemen, $idAngkatan;

    $sql = "SELECT replid, angkatan
              FROM jbsakad.angkatan
             WHERE aktif = 1
               AND departemen = '$departemen'
             ORDER BY replid DESC";
    $res = $db->QueryDb($sql);
    echo "<select id='angkatan' class='inputbox' style='width:300px' onchange='onChangeAngkatan()'>";
    while ($row = mysqli_fetch_assoc($res))
    {
        if ($idAngkatan == 0)
            $idAngkatan = $row['replid'];
            
        $sel = ($row['replid'] == $idAngkatan) ? "selected" : "";
        echo "<option value='$row[replid]' $sel>$row[angkatan]</option>";
    }
    echo "</select>";    
}

function ShowSelectTahunAjaran($db)
{
    global $departemen, $idTahunAjaran;

    $sql = "SELECT replid, tahunajaran, aktif
              FROM jbsakad.tahunajaran
             WHERE departemen = '$departemen'
             ORDER BY aktif DESC, replid DESC";
    $res = $db->QueryDb($sql);
    echo "<select id='tahunajaran' class='inputbox' style='width:300px' onchange='onChangeTahunAjaran()'>";
    while ($row = mysqli_fetch_assoc($res))
    {
        if ($idTahunAjaran == 0)
            $idTahunAjaran = $row['replid'];
            
        $aktif = ($row['aktif'] == 1) ? " (Aktif)" : "";
        $sel = ($row['replid'] == $idTahunAjaran) ? "selected" : "";
        echo "<option value='$row[replid]' $sel>$row[tahunajaran] $aktif</option>";
    }
    echo "</select>";    
}

function ShowSelectTingkat($db)
{
    global $departemen, $idTingkat;

    $sql = "SELECT replid, tingkat
              FROM jbsakad.tingkat
             WHERE aktif = 1
               AND departemen = '$departemen'
             ORDER BY urutan";
    $res = $db->QueryDb($sql);
    echo "<select id='tingkat' class='inputbox' style='width:150px' onchange='onChangeTingkat()'>";
    while ($row = mysqli_fetch_assoc($res))
    {
        if ($idTingkat == 0)
            $idTingkat = $row['replid'];
        
        $sel = ($row['replid'] == $idTingkat) ? "selected" : "";
        echo "<option value='$row[replid]' $sel>$row[tingkat]</option>";
    }
    echo "</select>";    
}

function ShowSelectKelas($db)
{
    global $idKelas, $idTingkat, $idTahunAjaran;

    $sql = "SELECT replid, kelas, kapasitas
              FROM jbsakad.kelas
             WHERE aktif = 1
               AND idtingkat = '$idTingkat'
               AND idtahunajaran = '$idTahunAjaran'
             ORDER BY kelas";
    $res = $db->QueryDb($sql);
    echo "<select id='kelas' class='inputbox' style='width:250px' onchange='onChangeKelas()'>";
    while ($row = mysqli_fetch_assoc($res))
    {
        $replid = $row['replid'];
        $kelas = $row['kelas'];
        $kapasitas = $row['kapasitas'];

        $sql = "SELECT COUNT(s.replid)
                  FROM jbsakad.siswa s
                 WHERE s.idkelas = '$replid'
                   AND s.alumni = 0"; 
        $terisi = $db->ExecuteScalar($sql, 0);         
        
        if ($idKelas == 0)
            $idKelas = $replid;

        $sel = ($replid == $idKelas) ? "selected" : "";
        $key = base64_encode(json_encode([$replid, $kelas, $kapasitas, $terisi]));
        echo "<option value='$key' $sel>$kelas, kapasitas: $kapasitas, terisi: $terisi</option>";
    }
    echo "</select>";    
}

function ShowTableSiswaKelasTujuan($db)
{
    global $idKelas, $idAngkatan;

    $sql = "SELECT s.replid, s.nis, s.nama, s.aktif, s.frompsb,
                   IFNULL(s.panggilan, '') AS fpanggilan
              FROM jbsakad.siswa s
             WHERE s.idkelas = '$idKelas'
               AND s.idangkatan = '$idAngkatan'
             ORDER BY s.nama";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) <= 0)
    {
        echo "<br><br><i>belum ada data siswa</i>";
        return;
    }

    echo "<br>";
    echo "<table id='tableSiswaAsal' class='tab tabShadow' width='98%' align='center'>";
    echo "<tr>";
    echo "<td class='bg-table-header fg-white' width='5%' align='center'>No</td>";
    echo "<td class='bg-table-header fg-white' width='20%' align='center'>NIS</td>";
    echo "<td class='bg-table-header fg-white' width='*'>Nama</td>";
    echo "<td class='bg-table-header fg-white' width='10%' align='center'>Batal</td>";
    echo "</tr>";

    $no = 0;
    while($row = mysqli_fetch_assoc($res))
    {
        $no++;

        $replid = $row['replid'];
        $nis = $row['nis'];

        echo "<tr id='trSiswa$replid' style='height: 35px'>";
        echo "<td align='center' class='numberColumn'>$no</td>";
        echo "<td align='center' valign='top'>";
        echo "<span class='fg-blue'>$row[nis]</span>";
        if ($row['aktif'] == 0)
            echo "<br><span class='fg-red fst-italic fs-10'>[Tidak Aktif]</span>";
        echo "</td>";
        echo "<td align='left' valign='top' style='position: relative;'>";
        echo "<b>$row[nama]</b><br><span class='fg-secondary fst-italic'>$row[fpanggilan]</span>";

        echo "<span style='position: absolute; right: 5px; top: 3px;'>";
        echo "<img src='../images/ico/lihat.png' title='profil' class='cur-hand' onclick='profilSiswa($replid)'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/stat01.png' title='dashboard' class='cur-hand' onclick='dashboardSiswa($replid)'>";
        echo "</span>";
        echo "</td>";

        echo "<td align='center' valign='top'>";
        echo "<input type='hidden' id='nis$no' value='$row[nis]'>";
        if ($row['frompsb'] == 1)
        {
            echo "<span id='spBatal$replid'>";
            echo "<img src='../images/ico/hapus.png' class='cur-hand' onclick='batalTerima(\"$replid\", \"$nis\")' title='batalkan penerimaan'>";
            echo "</span>";
        }
        echo "</td>";

        echo "</tr>";
    }
    echo "</table>";
    echo "<input type='hidden' id='ndata' value='$no'>";
}

function PembatalanTerima()
{
    $db = new Db();
    try
    {
        $db->Open();
        
        $replid = RequestData("replid", 0);
        $nis = RequestData("nis", "");

        $db->BeginTrans();

        $sql = "DELETE FROM jbsakad.siswa 
                 WHERE replid = '$replid'";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.tambahandatasiswa 
                 WHERE nis = '$nis'";
        $db->QueryDb($sql);

        $sql = "UPDATE jbsakad.calonsiswa 
                   SET replidsiswa = NULL 
                 WHERE replidsiswa = '$replid'";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.riwayatdeptsiswa 
                 WHERE nis = '$nis'";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.riwayatkelassiswa 
                 WHERE nis = '$nis'";
        $db->QueryDb($sql);

        $db->CommitTrans();

        return json_encode([1, "OK"]);
    }
    catch(Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        $db->RollbackTrans();

        if ($errNo == 1451)
        {
            $msg = "Data siswa tidak dapat dihapus karena sudah digunakan di data lain.";
            return "[-1,\"$msg\"]";
        }

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }

    
}
?>
