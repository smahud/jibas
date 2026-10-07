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
function ShowTableSiswaKelasAsal($db)
{
    global $idKelas;

    $sql = "SELECT s.replid, s.nis, s.nama, s.aktif,
                   IFNULL(s.panggilan, '') AS fpanggilan
              FROM jbsakad.siswa s
             WHERE s.idkelas = '$idKelas'
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
    echo "<td class='bg-table-header fg-white' width='10%' align='center'>Pilih</td>";
    echo "<td class='bg-table-header fg-white' width='20%' align='center'>NIS</td>";
    echo "<td class='bg-table-header fg-white' width='*'>Nama</td>";
    echo "</tr>";

    $no = 0;
    while($row = mysqli_fetch_assoc($res))
    {
        $no++;

        $replid = $row['replid'];

        echo "<tr style='height: 35px'>";
        echo "<td align='center' class='numberColumn'>$no</td>";
        echo "<td align='center' valign='top'>";
        echo "<input type='hidden' id='nis$no' value='$row[nis]'>";
        echo "<input type='checkbox' class='inputbox' id='ck$no'>";
        echo "</td>";
        echo "<td align='center' valign='top'>";
        echo "<span class='fg-blue'>$row[nis]</span>";
        if ($row['aktif'] == 0)
            echo "<br><span class='fg-red fst-italic fs-10'>[Tidak Aktif]</span>";
        echo "</td>";
        echo "<td align='left' valign='top' style='position: relative;'>";
        echo "<b>$row[nama]</b><br><span class='fg-secondary fst-italic'>$row[fpanggilan]</span>";
        echo "<div style='position: absolute; right: 5px; top: 5px;'>";
        echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand' onclick='profilSiswa($replid)'>&nbsp;&nbsp;";
        echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand' onclick='dashboardSiswa($replid)'>&nbsp;&nbsp;";
        echo "</div>";
        
        echo "</td>";

        

        echo "</tr>";
    }
    echo "</table>";
    echo "<input type='hidden' id='ndata' value='$no'>";
}

function ShowSelectKelasTujuan($db)
{
    global $idTahunAjaran, $idTingkat, $idKelas, $idKelasTujuan;

    $sql = "SELECT replid, kelas, kapasitas
              FROM jbsakad.kelas
             WHERE idtahunajaran = '$idTahunAjaran'
               AND idtingkat = '$idTingkat'
               AND replid <> $idKelas
             ORDER BY kelas";
    $res = $db->QueryDb($sql);

    $lsKelas = [];
    while($row = mysqli_fetch_row($res))             
    {
        $lsKelas[] = $row;
    }

    echo "<select id='kelastujuan' class='inputbox' style='width: 350px; margin-left: 10px;' onchange='onChangeKelasTujuan()'>";
    for($i = 0; $i < count($lsKelas); $i++)
    {
        $replid = $lsKelas[$i][0];
        $kelas = $lsKelas[$i][1];
        $kapasitas = $lsKelas[$i][2];

        $sql = "SELECT COUNT(s.replid)
                  FROM jbsakad.siswa s
                 WHERE s.idkelas = '$replid'
                   AND s.alumni = 0"; 
        $terisi = $db->ExecuteScalar($sql, 0);                   

        $key = base64_encode(json_encode([$replid, $kapasitas, $terisi]));

        if ($idKelasTujuan == 0)
            $idKelasTujuan = $replid;

        $sel = $replid == $idKelasTujuan ? "selected" : "";
        echo "<option value='$key' $sel>$kelas, kapasitas: $kapasitas, terisi: $terisi</option>";
    }
    echo "</select>";

}

function ShowTableSiswaKelasTujuan($db)
{
    global $idKelasTujuan;

    $sql = "SELECT s.replid, s.nis, s.nama, s.aktif,
                   IFNULL(s.panggilan, '') AS fpanggilan,
                   IFNULL(rks.replid, 0) AS idrks
              FROM jbsakad.siswa s
              LEFT JOIN jbsakad.riwayatkelassiswa rks ON s.nis = rks.nis AND rks.aktif = 1 AND rks.status = 3
             WHERE s.idkelas = '$idKelasTujuan'
               
             ORDER BY s.nama";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) <= 0)
    {
        echo "<br><br><i>belum ada data siswa</i>";
        return;
    }

    echo "<br>";
    echo "<table id='tableSiswaTujuan' class='tab tabShadow' width='98%' align='center'>";
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

        echo "<tr style='height: 35px'>";
        echo "<td align='center' class='numberColumn'>$no</td>";
        echo "<td align='center' valign='top'>";
        echo "<span class='fg-blue'>$row[nis]</span>";
        if ($row['aktif'] == 0)
            echo "<br><span class='fg-red fst-italic fs-10'>[Tidak Aktif]</span>";
        echo "</td>";
        echo "<td align='left' valign='top' style='position: relative;'>";
        echo "<b>$row[nama]</b><br><span class='fg-secondary fst-italic'>$row[fpanggilan]</span>";
        echo "<div style='position: absolute; right: 5px; top: 5px;'>";
        echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand' onclick='profilSiswa($replid)'>&nbsp;&nbsp;";
        echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand' onclick='dashboardSiswa($replid)'>&nbsp;&nbsp;";
        echo "</div>";
        
        echo "</td>";

        echo "<td align='center' valign='top'>";
        if ($row['idrks'] != 0)
            echo "<img src='../../images/ico/hapus.png' class='cur-hand' onclick='batalPindah(\"$row[nis]\",\"$idKelasTujuan\")' title='batal pindah'>";
        echo "</td>";

        echo "</tr>";
    }
    echo "</table>";
}

function PindahSiswa()
{
    $db = new Db();
    try
    {   
        $db->Open();

        $idKelasTujuan = RequestData("idkelastujuan", 0);
        $keterangan = RequestData("keterangan", "");
        $jsonNis = base64_decode($_REQUEST['jsonnis64']);
        $lsNis = json_decode($jsonNis);

        $db->BeginTrans();

        for($i = 0; $i < count($lsNis); $i++)
        {
            $nis = $lsNis[$i];

            $sql = "UPDATE jbsakad.siswa
                       SET idkelas = '$idKelasTujuan'
                     WHERE nis = '$nis'";
            $db->QueryDb($sql);

            $sql = "UPDATE jbsakad.riwayatkelassiswa 
                       SET aktif = 0 
                     WHERE nis = '$nis'";	
            $db->QueryDb($sql);

            $sql = "INSERT INTO jbsakad.riwayatkelassiswa 
                       SET nis = '$nis', idkelas = '$idKelasTujuan', mulai = CURDATE(), 
                           aktif = 1, status = 3, keterangan = '$keterangan'";	
            $db->QueryDb($sql);                           
        }

        //$db->RollbackTrans();
        $db->CommitTrans();

        return json_encode([1, "OK"]);
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

function BatalPindahSiswa()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nis = RequestData("nis", "");
        $idKelas = RequestData("idkelas", 0);
                   
        $sql = "SELECT rks.idkelas, k.kapasitas
                  FROM jbsakad.riwayatkelassiswa rks, jbsakad.kelas k
                 WHERE rks.idkelas = k.replid
                   AND rks.nis = '$nis'
                   AND rks.aktif = 0
                 ORDER BY rks.mulai DESC
                 LIMIT 1";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)                 
            return json_encode([0, "Data riwayat kelas tidak ditemukan"]);

        $row = mysqli_fetch_assoc($res);
        $idKelasAsal = $row['idkelas'];
        $kapasitasAsal = $row['kapasitas'];

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.siswa
                 WHERE idkelas = '$idKelasAsal'
                   AND alumni = 0";
        $nSiswa = $db->ExecuteScalar($sql, 0);
        if ($nSiswa + 1 > $kapasitasAsal)
            return json_encode([0, "Kapasitas kelas asal tidak mencukupi"]);

        $db->BeginTrans();

        $sql = "DELETE FROM jbsakad.riwayatkelassiswa 
                 WHERE nis = '$nis' 
                   AND idkelas = '$idKelas' 
                   AND status = 3";
        $db->QueryDb($sql);

        $sql = "UPDATE jbsakad.riwayatkelassiswa
                   SET aktif = 1
                 WHERE nis = '$nis' 
                   AND idkelas = '$idKelasAsal'";
        $db->QueryDb($sql);        

        $sql = "UPDATE jbsakad.siswa
                   SET idkelas = '$idKelasAsal'
                 WHERE nis = '$nis'";
        $db->QueryDb($sql);     

        $db->CommitTrans();
        //$db->RollbackTrans();

        return json_encode([1, "OK"]);
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

?>

