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

function ShowSelectDepartemenTujuan($db)
{
    global $departemenTujuan, $departemen;

    try
    {
        $dep = getDepartemen($db, SI_USER_ACCESS());
     
        echo "<select id='departementujuan' onchange='onChangeDeptTujuan()' style='width:180px' class='inputbox'>";
        foreach ($dep as $value) 
        {
            if ($departemen == $value) 
                continue;

            if ($departemenTujuan == "")
                $departemenTujuan = $value;
            
            $sel = $departemenTujuan == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectAngkatanTujuan($db)
{
    global $departemenTujuan;

    $sql = "SELECT replid, angkatan
              FROM jbsakad.angkatan
             WHERE departemen = '$departemenTujuan' 
               AND aktif = 1
             ORDER BY replid DESC";               
    $res = $db->QueryDb($sql);
    echo "<select id='angkatantujuan' onchange='onChangeAngkatanTujuan()' class='inputbox' style='width:180px'>";
    while ($row = mysqli_fetch_array($res)) 
    {
        echo "<option value='$row[replid]'>$row[angkatan]</option>";
    }
    echo "</select>";
}


function ShowSelectTahunAjaranTujuan($db)
{
    global $departemenTujuan, $idTahunAjaran, $idTahunAjaranTujuan;

    $sql = "SELECT replid, tahunajaran, aktif 
              FROM jbsakad.tahunajaran 
             WHERE departemen = '$departemenTujuan' 
               AND tglmulai > (SELECT tglmulai FROM jbsakad.tahunajaran WHERE replid = '$idTahunAjaran')
             ORDER BY aktif DESC, tglmulai DESC";               
    $res = $db->QueryDb($sql);
    echo "<select id='tahunajarantujuan' onchange='onChangeTahunAjaranTujuan()' class='inputbox' style='width:180px'>";
    while ($row = mysqli_fetch_array($res)) 
    {
        if ($idTahunAjaranTujuan == 0)
            $idTahunAjaranTujuan = $row['replid'];
        
        $sel = $idTahunAjaranTujuan == $row['replid'] ? "selected" : "";
        $aktif = $row['aktif'] == 1 ? "(Aktif)" : "";
        echo "<option value='$row[replid]' $sel>$row[tahunajaran] $aktif</option>";
    }
    echo "</select>";
}

function ShowSelectTingkatTujuan($db)
{
    global $departemenTujuan, $idTingkatTujuan;

    try
    {
        $sql = "SELECT replid, tingkat 
                  FROM jbsakad.tingkat 
                 WHERE departemen = '$departemenTujuan' 
                   AND aktif = 1
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        
        echo "<select id='tingkattujuan' onchange='onChangeTingkatTujuan()' class='inputbox' style='width:180px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idTingkatTujuan == 0)
                $idTingkatTujuan = $row['replid'];
            
            $sel = $idTingkatTujuan == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[tingkat]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectKelasTujuan($db)
{
    global $idTingkatTujuan, $idTahunAjaranTujuan, $idKelasSelected;

    try
    {
        $sql = "SELECT replid, kelas, kapasitas 
                  FROM jbsakad.kelas 
                 WHERE idtahunajaran = '$idTahunAjaranTujuan' 
                   AND idtingkat = '$idTingkatTujuan' 
                   AND aktif = 1
                 ORDER BY kelas";
        $res = $db->QueryDb($sql);
        
        echo "<select id='kelastujuan' onchange='onChangeKelasTujuan()' class='inputbox' style='width:250px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            $sql = "SELECT COUNT(*) 
                      FROM jbsakad.siswa 
                     WHERE idkelas = '$row[replid]' 
                       AND aktif = 1";
            $nTerisi = $db->FetchSingle($sql, 0);

            $idKelas = $row['replid'];
            $kelas = $row['kelas'];
            $kapasitas = $row['kapasitas'];
            $data64 = base64_encode(json_encode([$idKelas, $kelas, $kapasitas, $nTerisi]));

            if ($idKelasSelected == 0)
                $idKelasSelected = $idKelas;

            $sel = $idKelas == $idKelasSelected ? "selected" : "";
            echo "<option value='$data64' $sel>$kelas, kapasitas: $nTerisi / $kapasitas</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowDaftarSiswaKelasTujuan($db)
{
    global $idKelasSelected;

    $sql = "SELECT s.replid, s.nis, s.nama
              FROM jbsakad.siswa s
             WHERE s.idkelas = '$idKelasSelected'
               AND s.aktif = 1
             ORDER BY s.nama";
    $res = $db->QueryDb($sql);             
    if (mysqli_num_rows($res) == 0)
    {
        HintInfo::ShowLeft("Belum ada siswa");
        return;
    }
    
    echo "<table id='tableSiswaTujuan' class='tab' width='100%' align='center'>";
    echo "<tr align='center'>";
    echo "<td class='bg-table-header' width='7%'>No</td>";
    echo "<td class='bg-table-header' width='*''>Siswa</td>";
    echo "<td class='bg-table-header' width='30%'>Keterangan</td>";
    echo "<td class='bg-table-header' width='7%'>&nbsp;</td>";
    echo "</tr>";

    $no = 0;
    while ($row = mysqli_fetch_array($res)) 
    {
        $no += 1;
        $replid = $row["replid"];
        $nis = $row["nis"];
        $nama = $row["nama"];

        $idRiwayat = 0;
        $keterangan = "";
        $status = 0;
        $sql = "SELECT replid, keterangan, status 
                  FROM jbsakad.riwayatkelassiswa 
                 WHERE nis = '$nis' 
                   AND idkelas = '$idKelasSelected'";
        $res2 = $db->QueryDb($sql);
        if ($row2 = mysqli_fetch_array($res2))
        {
            $idRiwayat = $row2["replid"];
            $keterangan = $row2["keterangan"];
            $status = $row2["status"];
        }

        
        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td align='left' style='position: relative;'>";
        echo "<b>$nama</b><br>";
        echo "<span class='fg-secondary'>$nis</span>";
        echo "<span style='position: absolute; right: 5px; top: 5px;'>";
        echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='profilSiswa(\"$nis\")'>&nbsp;&nbsp;";
        echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='dashboardSiswa(\"$nis\")'>&nbsp;&nbsp;";
        echo "</span>";
        echo "</td>";
        echo "<td align='left' class='fg-secondary' style='position: relative;'>";
        echo $keterangan;
        if ($status == 1)
        {
            echo "<div style='position: absolute; top: 10px; right: 5px;'>";
            echo "<img src='../../images/ico/ubah.png' alt='Ubah' title='Ubah' class='cur-hand' onclick='ubahKeterangan(\"$idRiwayat\", \"$keterangan\", \"$nis\", \"$nama\")'>";
            echo "</div>";
        }
        echo "</td>";
        echo "<td align='center'>";
        if ($status == 1)
        {
            echo "<img src='../../images/ico/hapus.png' alt='Batal' title='Batal' class='cur-hand' onclick='batalLulusSiswa(\"$nis\", \"$nama\")'>";
        }
        echo "</td>";
        echo "</tr>";

    }
    
    echo "</table>";
    
}

function BatalLulusSiswa()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nis = RequestData("nis","");
        $departemenTujuan = RequestData("departementujuan","");
        $idKelasTujuan = RequestData("idkelastujuan","");

        $db->BeginTrans();

        $nisLama = "";
        $sql = "SELECT nislama 
                  FROM jbsakad.riwayatdeptsiswa 
                 WHERE nis = '$nis'
                   AND departemen = '$departemenTujuan'";
 
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_array($res))
        {
            $nisLama = $row["nislama"];
        }

        if ($nisLama == "")
            return json_encode([-1, "Tidak ditemukan informasi nis sebelumnya"]);

        $idRiwayatDept = 0;
        $departemenLama = "";
        $sql = "SELECT replid, departemen 
                  FROM jbsakad.riwayatdeptsiswa 
                 WHERE nis = '$nisLama' 
                   AND aktif = 0
                 ORDER BY mulai DESC LIMIT 1";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_array($res))
        {
            $idRiwayatDept = $row['replid'];
            $departemenLama = $row['departemen'];
        }

        if ($idRiwayatDept == 0)
            return json_encode([-1, "Tidak ditemukan informasi riwayat departemen sebelumnya"]);

        $idRiwayatKelas = 0;
        $idKelasLama = 0;
        $idTingkatLama = 0;
        $idTahunAjaranLama = 0;
        
        $sql = "SELECT r.replid, r.idkelas, k.idtingkat, k.idtahunajaran 
                  FROM jbsakad.riwayatkelassiswa r, jbsakad.kelas k 
                 WHERE r.nis='$nisLama' 
                   AND r.idkelas = k.replid 
                 ORDER BY r.mulai DESC 
                 LIMIT 1";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_array($res))
        {
            $idRiwayatKelas = $row['replid'];
            $idKelasLama = $row['idkelas'];
            $idTingkatLama = $row['idtingkat'];
            $idTahunAjaranLama = $row['idtahunajaran'];
        }

        if ($idRiwayatKelas == 0)
            return json_encode([-1, "Tidak ditemukan informasi riwayat kelas sebelumnya"]);

        if ($nisLama == $nis)
        {
            $sql = "DELETE FROM jbsakad.riwayatdeptsiswa 
                     WHERE nis = '$nis' 
                       AND departemen = '$departemenTujuan' 
                       AND aktif = 1";
            $db->QueryDb($sql);

            $sql = "UPDATE jbsakad.riwayatdeptsiswa 
                       SET aktif = 1 
                     WHERE replid = '$idRiwayatDept'";			
			$db->QueryDb($sql);

            $sql = "DELETE FROM jbsakad.riwayatkelassiswa 
                     WHERE nis = '$nis' 
                       AND idkelas = '$idKelasTujuan' 
                       AND aktif = 1";
            $db->QueryDb($sql);                      

            $sql = "UPDATE jbsakad.riwayatkelassiswa 
                       SET aktif = 1 
                     WHERE replid='$idRiwayatKelas'";
            $db->QueryDb($sql);                 
            
            $sql = "DELETE FROM jbsakad.alumni 
                     WHERE nis = '$nisLama'
                       AND departemen = '$departemenLama'";
            $db->QueryDb($sql);       
            
            $sql = "UPDATE jbsakad.siswa 
                       SET aktif = 1, idkelas='$idKelasLama' 
                     WHERE nis = '$nisLama'";
            $db->QueryDb($sql);                      
        }
        else 
        {
            $sql = "DELETE FROM jbsakad.riwayatdeptsiswa 
                     WHERE nis = '$nis' 
                       AND departemen = '$departemenTujuan' 
                       AND aktif = 1";
            $db->QueryDb($sql);
            
            $sql = "UPDATE jbsakad.riwayatdeptsiswa 
                       SET aktif = 1 
                     WHERE replid = '$idRiwayatDept'";
            $db->QueryDb($sql);     
            
            $sql = "DELETE FROM jbsakad.riwayatkelassiswa 
                     WHERE nis = '$nis' 
                       AND idkelas = '$idKelasTujuan' 
                       AND aktif = 1";
            $db->QueryDb($sql);       
            
            $sql = "UPDATE jbsakad.riwayatkelassiswa 
                       SET aktif = 1 
                     WHERE replid = '$idRiwayatKelas'";
            $db->QueryDb($sql);                     

            $sql = "DELETE FROM jbsakad.alumni 
                     WHERE nis = '$nisLama'
                       AND departemen = '$departemenLama'";
            $db->QueryDb($sql);       

            $sql = "DELETE FROM jbsakad.tambahandatasiswa 
                     WHERE nis = '$nis'";
            $db->QueryDb($sql);   

            $sql = "DELETE FROM jbsakad.siswa 
                     WHERE nis = '$nis'";
            $db->QueryDb($sql);   
            
            $sql = "UPDATE jbsakad.siswa 
                       SET aktif = 1, idkelas='$idKelasLama', alumni = 0
                     WHERE nis = '$nisLama'";
            $db->QueryDb($sql);           
        }
        
        $db->CommitTrans();
        
        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}

?>