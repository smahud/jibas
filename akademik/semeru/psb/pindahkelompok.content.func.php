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
function ShowTableCalonSiswaKelompokAsal($db)
{
    global $idKelompok;

    $sql = "SELECT s.replid, s.nopendaftaran, s.nama, s.aktif,
                   IFNULL(s.panggilan, '') AS fpanggilan
              FROM jbsakad.calonsiswa s
             WHERE s.idkelompok = '$idKelompok'
            ORDER BY s.nama";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) <= 0)
    {
        HintInfo::ShowLeft("Belum ada data calon siswa<br>Silahkan tambah calon siswa di bagian Penerimaan Siswa Baru menu Pendataan Calon Siswa");
        return;
    }

    echo "<br>";
    echo "<table id='tableCalonSiswaAsal' class='tab tabShadow' width='98%' align='center'>";
    echo "<tr>";
    echo "<td class='bg-table-header fg-white' width='5%' align='center'>No</td>";
    echo "<td class='bg-table-header fg-white' width='20%' align='center'>No Pendaftaran</td>";
    echo "<td class='bg-table-header fg-white' width='*'>Nama</td>";
    echo "<td class='bg-table-header fg-white' width='10%' align='center'>Pilih</td>";
    echo "</tr>";

    $no = 0;
    while($row = mysqli_fetch_assoc($res))
    {
        $no++;

        echo "<tr style='height: 35px'>";
        echo "<td align='center' class='numberColumn'>$no</td>";
        echo "<td align='center' valign='top'>";
        echo "<span class='fg-blue'>$row[nopendaftaran]</span>";
        if ($row['aktif'] == 0)
            echo "<br><span class='fg-red fst-italic fs-10'>[Tidak Aktif]</span>";
        echo "</td>";
        echo "<td align='left' valign='top' style='position: relative;'>";
        echo "<b>$row[nama]</b><br><span class='fg-secondary fst-italic'>$row[fpanggilan]</span>";

        echo "<div style='position: absolute; right: 5px; top: 5px;'>";
        echo "<img src='../images/ico/lihat.png' title='profil' class='cur-hand' onclick='profilCalonSiswa($row[replid])'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/stat01.png' title='dashboard' class='cur-hand' onclick='dashboardCalonSiswa($row[replid])'>";
        echo "</div>";

        echo "</td>";

        echo "<td align='center' valign='top'>";
        echo "<input type='hidden' id='nopendaftaran$no' value='$row[nopendaftaran]'>";
        echo "<input type='checkbox' class='inputbox' id='ck$no'>";
        echo "</td>";

        echo "</tr>";
    }
    echo "</table>";
    echo "<input type='hidden' id='ndata' value='$no'>";
}

function ShowSelectKelompokTujuan($db)
{
    global $idProses, $idKelompok, $idKelompokTujuan;

    $sql = "SELECT replid, kelompok, kapasitas
              FROM jbsakad.kelompokcalonsiswa
             WHERE idproses = '$idProses'
               AND replid <> $idKelompok
             ORDER BY kelompok";
    $res = $db->QueryDb($sql);

    $lsKelompok = [];
    while($row = mysqli_fetch_row($res))             
    {
        $lsKelompok[] = $row;
    }

    echo "<select id='kelompoktujuan' class='inputbox' style='width: 350px; margin-left: 10px;' onchange='onChangeKelompokTujuan()'>";
    for($i = 0; $i < count($lsKelompok); $i++)
    {
        $replid = $lsKelompok[$i][0];
        $kelompok = $lsKelompok[$i][1];
        $kapasitas = $lsKelompok[$i][2];

        $sql = "SELECT COUNT(s.replid)
                  FROM jbsakad.calonsiswa s
                 WHERE s.idkelompok = '$replid'
                   AND s.aktif = 1"; 
        $terisi = $db->ExecuteScalar($sql, 0);                   

        $key = base64_encode(json_encode([$replid, $kapasitas, $terisi]));

        if ($idKelompokTujuan == 0)
            $idKelompokTujuan = $replid;

        $sel = $replid == $idKelompokTujuan ? "selected" : "";
        echo "<option value='$key' $sel>$kelompok, kapasitas: $kapasitas, terisi: $terisi</option>";
    }
    echo "</select>";

}

function ShowTableCalonSiswaKelompokTujuan($db)
{
    global $idKelompokTujuan;

    $sql = "SELECT s.replid, s.nopendaftaran, s.nama, s.aktif, s.ketpindah,
                   IFNULL(s.panggilan, '') AS fpanggilan
              FROM jbsakad.calonsiswa s
             WHERE s.idkelompok = '$idKelompokTujuan'
               AND s.aktif = 1
             ORDER BY s.nama";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) <= 0)
    {
        HintInfo::ShowLeft("Belum ada data calon siswa");
        return;
    }

    echo "<br>";
    echo "<table id='tableCalonSiswaTujuan' class='tab tabShadow' width='98%' align='center'>";
    echo "<tr>";
    echo "<td class='bg-table-header fg-white' width='5%' align='center'>No</td>";
    echo "<td class='bg-table-header fg-white' width='20%' align='center'>No Pendaftaran</td>";
    echo "<td class='bg-table-header fg-white' width='*'>Nama</td>";
    echo "<td class='bg-table-header fg-white' width='30%' align='center'>Keterangan Pindah</td>";
    echo "</tr>";

    $no = 0;
    while($row = mysqli_fetch_assoc($res))
    {
        $no++;

        echo "<tr style='height: 35px'>";
        echo "<td align='center' class='numberColumn'>$no</td>";
        echo "<td align='center' valign='top'>";
        echo "<span class='fg-blue'>$row[nopendaftaran]</span>";
        if ($row['aktif'] == 0)
            echo "<br><span class='fg-red fst-italic fs-10'>[Tidak Aktif]</span>";
        echo "</td>";
        echo "<td align='left' valign='top' style='position: relative;'>";
        echo "<b>$row[nama]</b><br><span class='fg-secondary fst-italic'>$row[fpanggilan]</span>";
        echo "<div style='position: absolute; right: 5px; top: 5px;'>";
        echo "<img src='../images/ico/lihat.png' title='profil' class='cur-hand' onclick='profilCalonSiswa($row[replid])'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/stat01.png' title='dashboard' class='cur-hand' onclick='dashboardCalonSiswa($row[replid])'>";
        echo "</div>";
        echo "</td>";
        echo "<td align='left' valign='top'>";
        echo $row['ketpindah'];
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function PindahCalonSiswa()
{
    $db = new Db();
    try
    {   
        $db->Open();

        $idKelompokTujuan = RequestData("idkelompoktujuan", 0);
        $keterangan = RequestData("keterangan", "");
        $jsonNic = base64_decode($_REQUEST['jsonnic64']);
        $lsNic = json_decode($jsonNic);

        $db->BeginTrans();

        for($i = 0; $i < count($lsNic); $i++)
        {
            $noPendaftaran = $lsNic[$i];

            $sql = "UPDATE jbsakad.calonsiswa
                       SET idkelompok = '$idKelompokTujuan', ketpindah = '$keterangan'
                     WHERE nopendaftaran = '$noPendaftaran'";
            $db->QueryDb($sql);
        }

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
?>

