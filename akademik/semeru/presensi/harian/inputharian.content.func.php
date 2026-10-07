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
function CountHariAktif()
{
    $tahunAwal = RequestData("tahunawal", 0);
    $bulanAwal = RequestData("bulanawal", 0);
    $tglAwal = RequestData("tanggalawal", 0);
    $tahunAkhir = RequestData("tahunakhir", 0);
    $bulanAkhir = RequestData("bulanakhir", 0);
    $tglAkhir = RequestData("tanggalakhir", 0);
    $hariAktif = RequestData("hariaktif", 0);

    $date1 = new DateTime("$tahunAwal-$bulanAwal-$tglAwal");
    $date2 = new DateTime("$tahunAkhir-$bulanAkhir-$tglAkhir");

    // Calculate the difference
    $interval = $date1->diff($date2);

    $jumlah = $interval->days + 1;
    echo "<select id='hariaktif' class='inputbox' style='width: 50px'>";
    for ($i = $jumlah; $i >= 1; $i--) 
    {
        if ($hariAktif == 0)
            $hariAktif = $i;

        $sel = ($hariAktif == $i) ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
}

function ShowSelectTanggalAwal()
{
    $tahunAwal = RequestData("tahunawal", 0);
    $bulanAwal = RequestData("bulanawal", 0);
    $tglAwal = RequestData("tanggalawal", 0);

    echo "<select id='tanggalawal' class='inputbox' onchange='countHariAktif(); dayNameAwal();' style='width: 50px'>";
    $lastTglAwal = date('t', strtotime($tahunAwal . '-' . $bulanAwal . '-01'));
    for ($i = 1; $i <= $lastTglAwal; $i++)
        echo "<option value='$i' " . StringIsSelected($tglAwal, $i) . ">$i</option>";
    echo "</select>";
}

function ShowSelectTanggalAkhir()
{
    $tahunAkhir = RequestData("tahunakhir", 0);
    $bulanAkhir = RequestData("bulanakhir", 0);
    $tglAkhir = RequestData("tanggalakhir", 0);

    echo "<select id='tanggalakhir' class='inputbox' onchange='countHariAktif(); dayNameAkhir();' style='width: 50px'>";
    $lastTglAkhir = date('t', strtotime($tahunAkhir . '-' . $bulanAkhir . '-01'));
    for ($i = 1; $i <= $lastTglAkhir; $i++)
        echo "<option value='$i' " . StringIsSelected($tglAkhir, $i) . ">$i</option>";
    echo "</select>";
}

function ShowRekapInputPresensi($db)
{
    global $idKelas, $idSemester, $bulan, $tahun;

    $sql = "SELECT replid, DAY(tanggal1) AS tgl1, MONTH(tanggal1) AS bln1, YEAR(tanggal1) AS th1, 
                   DAY(tanggal2) AS tgl2, MONTH(tanggal2) AS bln2, YEAR(tanggal2) AS th2 
              FROM jbsakad.presensiharian 
             WHERE idkelas = '$idKelas' 
               AND idsemester = '$idSemester' 
               AND ((MONTH(tanggal1) = '$bulan' AND YEAR(tanggal1) = '$tahun') OR 
                    (MONTH(tanggal2) = '$bulan') AND YEAR(tanggal2) = '$tahun') 
             ORDER BY tanggal1";    
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        HintInfo::ShowLeft("Belum ada pendataan presensi.<br>Silahkan klik tombol Pendataan Baru untuk memulai pendataan presensi", 300);
        return;
    }

    echo "<br>";
    echo "<table id='tableRekapInput' class='tab tabShadow' width='98%' align='center'>";
    echo "<tr>";
    echo "<td class='bg-table-header fg-white' width='5%' align='center'>No</td>";
    echo "<td class='bg-table-header fg-white' width='*'>Tanggal</td>";
    echo "</tr>";

    $no = 0;
    while($row = mysqli_fetch_assoc($res))
    {
        $no++;

        $tanggal = $row['tgl1'] . " " . NamaBulan($row['bln1']) . " " . $row['th1'];
        $tanggal .= " &mdash; " . $row['tgl2'] . " " . NamaBulan($row['bln2']) . " " . $row['th2'];
        echo "<tr style='height: 35px'>";
        echo "<td align='center' class='numberColumn'>$no</td>";
        echo "<td align='left' onclick='showInputForm($row[replid])' class='cur-hand'>";
        echo $tanggal;
        echo "</td>";

        echo "</tr>";
    }
    echo "</table>";
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $departemen = RequestData("departemen", "");
        $idKelas = RequestData("idkelas", 0);
        $kelas = RequestData("kelas", "");
        $idSemester = RequestData("idsemester", 0);
        $idTahunAjaran = RequestData("idtahunajaran", 0);
        $tanggal1 = RequestData("tanggal1", 0);
        $tanggal2 = RequestData("tanggal2", 0);
        $hariAktif = RequestData("hariaktif", 0);

        $sql = "SELECT replid 
                  FROM jbsakad.presensiharian 
                 WHERE (((tanggal1 BETWEEN '$tanggal1' AND '$tanggal2') OR 
                         (tanggal2 BETWEEN '$tanggal1' AND '$tanggal2')) OR 
                        (('$tanggal1' BETWEEN tanggal1 AND tanggal2) OR 
                         ('$tanggal2' BETWEEN tanggal1 AND tanggal2))) 
                   AND idkelas = '$idKelas' 
                   AND idsemester = '$idSemester'";
            
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $ltanggal1 = LongDateFormat($tanggal1);
            $ltanggal2 = LongDateFormat($tanggal2);
            return json_encode([-1, "Tidak dapat menyimpan data karena sudah ada pendataan presensi antara $ltanggal1 sampai dengan $ltanggal2"]);
        }

        $db->BeginTrans();

        $sql = "INSERT INTO jbsakad.presensiharian 
                   SET idkelas='$idKelas', idsemester='$idSemester', 
                       tanggal1='$tanggal1', tanggal2 = '$tanggal2', hariaktif='$hariAktif'";
        $db->QueryDb($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $replid = $row[0];

        $nSiswa = RequestData("nsiswa", 0);
        for ($i = 1; $i <= $nSiswa; $i++)
        {
            $nis = RequestData("nis$i", 0);
            $idPh = RequestData("idph$i", 0);
            $hadir = RequestData("hadir$i", 0);
            $ijin = RequestData("ijin$i", 0);
            $sakit = RequestData("sakit$i", 0);
            $cuti = RequestData("cuti$i", 0);
            $alpa = RequestData("alpa$i", 0);
            $ket = RequestData("ket$i", "");
            $exclude = RequestData("exclude$i", 0);
            
            $sql = "INSERT INTO jbsakad.phsiswa
                       SET idpresensi='$replid', nis='$nis', hadir='$hadir', ijin='$ijin', sakit='$sakit', cuti='$cuti', alpa='$alpa', keterangan='$ket', exclude='$exclude'";
            $db->QueryDb($sql);
        }

        $deskripsi = "Presensi Harian kelas $kelas tanggal " . LongDateFormat($tanggal1) . " s/d " . LongDateFormat($tanggal2);
        RiwayatInput::Save($db, $departemen, "PH", "1", $replid, $deskripsi);

        $db->CommitTrans();

        return json_encode([1, "NEW", $replid]);
    }
    catch(Exception $e)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        return json_encode([-1, $e->getMessage()]);
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

        $replid = RequestData("replid", 0);
        $idKelas = RequestData("idkelas", 0);
        $idSemester = RequestData("idsemester", 0);
        $idTahunAjaran = RequestData("idtahunajaran", 0);
        $tanggal1 = RequestData("tanggal1", 0);
        $tanggal2 = RequestData("tanggal2", 0);
        $hariAktif = RequestData("hariaktif", 0);
        
        $db->BeginTrans();

        $sql = "UPDATE jbsakad.presensiharian 
                   SET idkelas='$idKelas', idsemester='$idSemester', 
                       tanggal1='$tanggal1', tanggal2 = '$tanggal2', hariaktif='$hariAktif'
                 WHERE replid='$replid'";
        $db->QueryDb($sql);
        
        $nSiswa = RequestData("nsiswa", 0);
        for ($i = 1; $i <= $nSiswa; $i++)
        {
            $nis = RequestData("nis$i", 0);
            $idPh = RequestData("idph$i", 0);
            $hadir = RequestData("hadir$i", 0);
            $ijin = RequestData("ijin$i", 0);
            $sakit = RequestData("sakit$i", 0);
            $cuti = RequestData("cuti$i", 0);
            $alpa = RequestData("alpa$i", 0);
            $ket = RequestData("ket$i", "");
            $exclude = RequestData("exclude$i", 0);

            if ($idPh == 0)
            {
                $sql = "INSERT INTO jbsakad.phsiswa
                           SET idpresensi='$replid', nis='$nis', hadir='$hadir', ijin='$ijin', sakit='$sakit', cuti='$cuti', alpa='$alpa', keterangan='$ket', exclude='$exclude'";
                $db->QueryDb($sql);
            }
            else
            {
                $sql = "UPDATE jbsakad.phsiswa 
                           SET hadir='$hadir', ijin='$ijin', sakit='$sakit', cuti='$cuti', alpa='$alpa', keterangan='$ket', exclude='$exclude'
                         WHERE replid='$idPh'";
                $db->QueryDb($sql);
            }
        }

        //$db->RollbackTrans();
        $db->CommitTrans();

        return json_encode([1, "UPDATE", $replid]);
    }
    catch(Exception $e)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        return json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}

function HapusPresensiHarian()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        
        $db->BeginTrans();

        $sql = "DELETE FROM jbsakad.phsiswa
                 WHERE idpresensi = $replid";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.presensiharian
                 WHERE replid = $replid";
        $db->QueryDb($sql);

        RiwayatInput::Delete($db, "PH", "1", $replid);

        $db->CommitTrans();

        return json_encode([1, "DELETE"]);
    }
    catch(Exception $e)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        return json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}