<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 33.0 (Jan 05, 2026)
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
function ShowSelectLaporanPresensi()
{
    echo "<select id='laporanpresensi' onchange='onChangeLaporanPresensi()' class='inputbox' style='width:250px'>";
    echo "<option value='0' selected>Presensi Harian (Face & Fingerprint)</option>";
    echo "<option value='1'>Presensi Kegiatan (Face & Fingerprint)</option>";
    echo "<option value='2'>Rekap Presensi Harian</option>";
    echo "</select>";
}

function ShowSelectBulan()
{
    $curBln = date('n');
    $lsBulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    echo "<select id='bulan' onchange='onChangeLaporanPresensi()' class='inputbox' style='width:120px'>";
    for($i = 1; $i <= 12; $i++)
    {
        $selected = $i == $curBln ? "selected" : "";
        echo "<option value='$i' $selected>" . $lsBulan[$i] . "</option>";
    }
    echo "</select>";
}

function ShowSelectTahun()
{
    global $G_START_YEAR;

    $curYear = date('Y');
    
    echo "<select id='tahun' onchange='onChangeLaporanPresensi()' class='inputbox' style='width:100px'>";
    for($i = $curYear; $i >= $G_START_YEAR; $i--)
    {
        $selected = $i == $curYear ? "selected" : "";
        echo "<option value='$i' $selected>$i</option>";
    }
    echo "</select>";
}

function ShowLaporanPresensiHarian($db)
{
    global $nis;

    $lsJsonBulan = [];
    $sql = "SELECT DISTINCT CONCAT('[\"', YEAR(date_in), '\",\"', LPAD(MONTH(date_in), 2, '0'), '\"]') AS dt 
              FROM jbssat.frpresence 
             ORDER BY dt DESC 
             LIMIT 3";
    $res = $db->QueryDb($sql);
    while ($row = mysqli_fetch_assoc($res))
    {
        $lsJsonBulan[] = $row['dt'];
    }

    if (count($lsJsonBulan) == 0)
    {
        echo "<br><i>belum ada data presensi JIBAS SPT Face &amp; Fingerprint</i>";
        return;
    }

    $nHadirAll = 0;
    $lsResult = [];
    for($i = 0; $i < count($lsJsonBulan); $i++)
    {
        $jsonBulan = $lsJsonBulan[$i];

        $lsTahunBulan = json_decode($jsonBulan, true);
        $tahun = $lsTahunBulan[0];
        $bulan = $lsTahunBulan[1];

        $lsDateIn = [];
        $sql = "SELECT DISTINCT DATE_FORMAT(date_in, '%Y-%m-%d') 
                  FROM jbssat.frpresence 
                 WHERE YEAR(date_in) = '$tahun' 
                   AND MONTH(date_in) = '$bulan'";
        $res = $db->QueryDb($sql);  
        while($row = mysqli_fetch_row($res))
        {
            $lsDateIn[] = $row[0];
        }

        $nHadir = 0;
        $nNa = 0;
        for($j = 0; $j < count($lsDateIn); $j++)
        {
            $date_in = $lsDateIn[$j];

            $sql = "SELECT COUNT(replid) 
                      FROM jbssat.frpresence 
                     WHERE nis = '$nis' 
                       AND date_in = '$date_in'";             
            $nData = $db->ExecuteScalar($sql, 0);
            if ($nData == 0)                       
                $nNa += 1;
            else
                $nHadir += 1;
        }

        $nHadirAll += $nHadir;

        $namaBulan = NamaBulan($bulan) . " " . $tahun;
        $lsItem = [ $namaBulan, $nHadir, $nNa, $tahun, $bulan ];
        $lsResult[] = $lsItem;
    }

    if ($nHadirAll == 0)
    {
        echo "<br><i>belum ada data presensi JIBAS SPT Face &amp; Fingerprint</i>";
        return;
    }

    echo "<table class='tab' id='tablepresensi' cellpadding='2' cellspacing='0'>";
    echo "<tr height='30' align='center' class='bg-table-header'>";
    echo "<td width='30'>No</td>";
    echo "<td width='180'>Bulan</td>";
    echo "<td width='120'>Hadir</td>";
    echo "<td width='120'>Belum ada data</td>";
    echo "<td width='60'>&nbsp;</td>";
    echo "</tr>";

    for($i = 0; $i < count($lsResult); $i++)
    {
        $lsItem = $lsResult[$i];

        $namaBulan = $lsItem[0];
        $nHadir = $lsItem[1];
        $nNa = $lsItem[2];
        $tahun = $lsItem[3];
        $bulan = $lsItem[4];

        echo "<tr height='30'>";
        echo "<td class='bg-table-number-column' align='center'>" . ($i + 1) . "</td>";
        echo "<td align='left'>" . $namaBulan . "</td>";
        echo "<td align='center'>" . $nHadir . "</td>";
        echo "<td align='center'>" . $nNa . "</td>";
        echo "<td align='center'>";
        echo "<img src='../images/ico/lihat.png' title='rincian' class='cur-hand' onclick='showDetailPresensiHarian(\"$nis\",\"$bulan\",\"$tahun\")'>";
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

function ShowDetailPresensiHarian()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nis = RequestData("nis", "");
        $bulan = RequestData("bulan", 0);
        $tahun = RequestData("tahun", 0);

        $lsDateIn = [];
        $sql = "SELECT DISTINCT DATE_FORMAT(date_in, '%Y-%m-%d'), DATE_FORMAT(date_in, '%d-%b-%Y') 
                  FROM jbssat.frpresence 
                 WHERE YEAR(date_in) = '$tahun' 
                   AND MONTH(date_in) = '$bulan'
                 ORDER BY date_in DESC";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $lsDateIn[] = [$row[0], $row[1]];
        }

        $lsResult = [];                 
        for($i = 0; $i < count($lsDateIn); $i++)
        {
            $dateIn = $lsDateIn[$i][0];
            $namaDateIn = $lsDateIn[$i][1];

            $sql = "SELECT time_in, time_out, IFNULL(info1, 0), IFNULL(description, '') 
                      FROM jbssat.frpresence 
                     WHERE nis = '$nis' 
                       AND date_in = '$dateIn'";
            $res = $db->QueryDb($sql);
            
            $lsItem = null;
            if ($row = mysqli_fetch_row($res))
            {
                $mnTelat = $row[2];
                if ($mnTelat != 0)
                    $telat = "$mnTelat menit";
                else 
                    $telat = "";

                $lsItem = [$namaDateIn, $row[0], $row[1], $telat, $row[3]];                    
            }
            else 
            {
                $lsItem = [$namaDateIn, "(NA)", "(NA)", "", ""];
            }

            $lsResult[] = $lsItem;
        }

        echo "Periode: " . NamaBulan($bulan) . " " . $tahun;
        echo "<br>";
        echo "<table class='tab' id='tablenilai' cellpadding='2' cellspacing='0'>";
        echo "<tr height='30' align='center' class='bg-table-header'>";
        echo "<td width='30'>No</td>";
        echo "<td width='120'>Tanggal</td>";
        echo "<td width='100'>Masuk</td>";
        echo "<td width='180'>Pulang</td>";
        echo "<td width='100'>Telat</td>";
        echo "<td width='120'>Keterangan</td>";
        echo "</tr>";

        for($i = 0; $i < count($lsResult); $i++)
        {
            $lsItem = $lsResult[$i];
            
            echo "<tr>";
            echo "<td class='bg-table-number-column' align='center'>" . ($i + 1) . "</td>";
            echo "<td align='center'>" . $lsItem[0] . "</td>";
            echo "<td align='center'>" . $lsItem[1] . "</td>";
            echo "<td align='center'>" . $lsItem[2] . "</td>";
            echo "<td align='center'>" . $lsItem[3] . "</td>";
            echo "<td align='left'>" . $lsItem[4] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function ShowLaporanPresensiKegiatan()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nis = RequestData("nis", "");
        $tahun = RequestData("tahun", 0);
        $bulan = RequestData("bulan", 0);

        $lsKegiatan = [];
        $sql = "SELECT DISTINCT pk.idkegiatan, k.kegiatan
                  FROM jbssat.frpresensikegiatan pk, jbssat.frkegiatan k
                 WHERE pk.idkegiatan = k.replid
                   AND MONTH(pk.date_in) = '$bulan'
                   AND YEAR(pk.date_in) = '$tahun'
                   AND pk.nis = '$nis'
                 ORDER BY k.kegiatan";
        $res = $db->QueryDb($sql);
        while ($row = mysqli_fetch_row($res))
        {
            $lsKegiatan[] = [$row[0], $row[1]];
        }

        if (count($lsKegiatan) == 0)
        {
            echo "<br><i>belum ada data presensi kegiatan</i>";
            return;
        }

        $lsResult = [];
        for ($i = 0; $i < count($lsKegiatan); $i++)
        {
            $lsItem = $lsKegiatan[$i];
            $idKegiatan = $lsItem[0];
            $kegiatan = $lsItem[1];

            $sql = "SELECT COUNT(DISTINCT pk.date_in)
                      FROM jbssat.frpresensikegiatan pk
                     WHERE MONTH(pk.date_in) = '$bulan'
                       AND YEAR(pk.date_in) = '$tahun'
                       AND pk.idkegiatan = '$idKegiatan'";
            $nHari = $db->ExecuteScalar($sql, 0);

            $sql = "SELECT COUNT(pk.replid)
                      FROM jbssat.frpresensikegiatan pk
                     WHERE MONTH(pk.date_in) = '$bulan'
                       AND YEAR(pk.date_in) = '$tahun'
                       AND pk.nis = '$nis'
                       AND pk.idkegiatan = '$idKegiatan'";
            $nHadir = $db->ExecuteScalar($sql, 0);     

            $nPersen = $nHari == 0 ? 0 : round(100 * $nHadir / $nHari);

            $lsResult[] = [$idKegiatan, $kegiatan, $nHari, $nHadir, $nPersen];
        }

        if (count($lsResult) == 0)
        {
            echo "<br><i>belum ada data presensi kegiatan</i>";
            return;
        }

        echo "<table class='tab' id='tablepresensi' cellpadding='2' cellspacing='0'>";
        echo "<tr height='30' align='center' class='bg-table-header'>";
        echo "<td width='30'>No</td>";
        echo "<td width='250'>Kegiatan</td>";
        echo "<td width='80'>Jumlah Hari</td>";
        echo "<td width='80'>Jumlah Hadir</td>";
        echo "<td width='80'>Persentase</td>";
        echo "<td width='40'>&nbsp;</td>";
        echo "</tr>";

        for ($i = 0; $i < count($lsResult); $i++)
        {
            $lsItem = $lsResult[$i];
            
            echo "<tr>";
            echo "<td class='bg-table-number-column' align='center'>" . ($i + 1) . "</td>";
            echo "<td align='left'>" . $lsItem[1] . "</td>";
            echo "<td align='center'>" . $lsItem[2] . "</td>";
            echo "<td align='center'>" . $lsItem[3] . "</td>";
            echo "<td align='center'>" . $lsItem[4] . "%</td>";
            echo "<td align='center'><img src='../images/ico/lihat.png' class='cur-hand' title='rincian' onclick='showDetailPresensiKegiatan(\"$nis\", \"$bulan\", \"$tahun\", \"$lsItem[0]\", \"$lsItem[1]\")'>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function ShowDetailPresensiKegiatan()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nis = RequestData("nis", "");
        $bulan = RequestData("bulan", 0);
        $tahun = RequestData("tahun", 0);
        $idKegiatan = RequestData("idKegiatan", "");
        $kegiatan = RequestData("kegiatan", "");

        $sql = "SELECT DISTINCT DATE_FORMAT(p.date_in, '%Y-%m-%d') AS tanggal, DATE_FORMAT(p.date_in, '%d-%b-%Y') AS ftanggal
                  FROM jbssat.frpresensikegiatan p
                 WHERE p.idkegiatan = '$idKegiatan'
                   AND MONTH(p.date_in) = '$bulan'
                   AND YEAR(p.date_in) = '$tahun'
                 ORDER BY p.date_in DESC";
        $res = $db->QueryDb($sql);

        $lsTanggal = [];
        while ($row = mysqli_fetch_row($res))
        {
            $lsTanggal[] = [$row[0], $row[1]];
        }

        $lsResult = [];
        for ($i = 0; $i < count($lsTanggal); $i++)
        {
            $lsItem = $lsTanggal[$i];
            $tanggal = $lsItem[0];
            $ftanggal = $lsItem[1];

            $sql = "SELECT p.time_in, p.time_out, IFNULL(p.info1, 0), p.description
                      FROM jbssat.frpresensikegiatan p
                     WHERE p.nis = '$nis'
                       AND p.idkegiatan = '$idKegiatan'
                       AND p.date_in = '$tanggal'";
            $res = $db->QueryDb($sql);
            if ($row = mysqli_fetch_row($res))
            {
                $timeIn = $row[0];
                $timeOut = $row[1];
                $mnTelat = $row[2];
                
                if ($mnTelat != 0)
                    $telat = $mnTelat . " menit";
                else
                    $telat = "";
                
                $keterangan = $row[3];

                $lsResult[] = [$ftanggal, $timeIn, $timeOut, $telat, $keterangan];
            }
            else
            {
                $lsResult[] = [$ftanggal, "(NA)", "(NA)", "", ""];
            }
        }

        if (count($lsResult) == 0)
        {
            echo "<br><i>belum ada data presensi kegiatan</i>";
            return;
        }

        echo "<b>" . $kegiatan . "</b>";   
        echo "<br>"; 
        echo "<table class='tab' id='tablenilai' cellpadding='2' cellspacing='0'>";
        echo "<tr height='30' align='center' class='bg-table-header'>";
        echo "<td width='30'>No</td>";
        echo "<td width='120'>Tanggal</td>";
        echo "<td width='100'>Masuk</td>";
        echo "<td width='180'>Pulang</td>";
        echo "<td width='100'>Telat</td>";
        echo "<td width='120'>Keterangan</td>";
        echo "</tr>";

        for ($i = 0; $i < count($lsResult); $i++)
        {
            $lsItem = $lsResult[$i];
            
            echo "<tr>";
            echo "<td class='bg-table-number-column' align='center'>" . ($i + 1) . "</td>";
            echo "<td align='center'>" . $lsItem[0] . "</td>";
            echo "<td align='center'>" . $lsItem[1] . "</td>";
            echo "<td align='center'>" . $lsItem[2] . "</td>";
            echo "<td align='center'>" . $lsItem[3] . "</td>";
            echo "<td align='left'>" . $lsItem[4] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function ShowRekapPresensiHarian()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nis = RequestData("nis", "");
        $bulan = RequestData("bulan", 0);
        $tahun = RequestData("tahun", 0);

        $sql = "SELECT IFNULL(SUM(hadir), 0), IFNULL(SUM(ijin), 0), IFNULL(SUM(sakit), 0), IFNULL(SUM(cuti), 0), IFNULL(SUM(alpa), 0)
                  FROM jbsakad.phsiswa ps, jbsakad.presensiharian ph
                 WHERE ps.idpresensi = ph.replid
                   AND ps.nis = '$nis'
                   AND ((MONTH(ph.tanggal1) = '$bulan' AND YEAR(ph.tanggal1) = '$tahun')
                   AND (MONTH(ph.tanggal2) = '$bulan' AND YEAR(ph.tanggal2) = '$tahun'))";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><i>belum ada data presensi harian</i>";
            return;
        }

        $lsRekap = null;
        if ($row = mysqli_fetch_row($res))
        {
            $lsRekap = [$row[0], $row[1], $row[2], $row[3], $row[4]];
        }

        $sql = "SELECT CONCAT(DATE_FORMAT(ph.tanggal1, '%d'), '-', DATE_FORMAT(ph.tanggal2, '%d %b %Y')), 
                       hadir, ijin, sakit, cuti, alpa, keterangan
                  FROM jbsakad.phsiswa ps, jbsakad.presensiharian ph
                 WHERE ps.idpresensi = ph.replid
                   AND ps.nis = '$nis'
                   AND ((MONTH(ph.tanggal1) = '$bulan' AND YEAR(ph.tanggal1) = '$tahun')
                   AND (MONTH(ph.tanggal2) = '$bulan' AND YEAR(ph.tanggal2) = '$tahun'))
                 ORDER BY ph.tanggal1 ASC";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><i>belum ada data presensi harian</i>";
            return;
        }

        $lsDetail = [];
        while ($row = mysqli_fetch_row($res))
        {
            $lsDetail[] = [$row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]];
        }


        echo "<br>"; 
        echo "<table class='tab' id='tablenilai' cellpadding='2' cellspacing='0'>";
        echo "<tr height='30' align='center' class='bg-table-header'>";
        echo "<td width='30'>No</td>";
        echo "<td width='120'>Tanggal</td>";
        echo "<td width='100'>Hadir</td>";
        echo "<td width='100'>Izin</td>";
        echo "<td width='100'>Sakit</td>";
        echo "<td width='100'>Cuti</td>";
        echo "<td width='100'>Alpa</td>";
        echo "<td width='160'>Keterangan</td>";
        echo "</tr>";

        for ($i = 0; $i < count($lsDetail); $i++)
        {
            $lsItem = $lsDetail[$i];
            
            echo "<tr>";
            echo "<td class='bg-table-number-column' align='center'>" . ($i + 1) . "</td>";
            echo "<td align='center'>" . $lsItem[0] . "</td>";
            echo "<td align='center'>" . $lsItem[1] . "</td>";
            echo "<td align='center'>" . $lsItem[2] . "</td>";
            echo "<td align='center'>" . $lsItem[3] . "</td>";
            echo "<td align='center'>" . $lsItem[4] . "</td>";
            echo "<td align='center'>" . $lsItem[5] . "</td>";
            echo "<td align='center'>" . $lsItem[6] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>"; 

        echo "<b>Rekapitulasi</b><br>";
        echo "<span style='display: inline-block; width: 80px; margin-top:10px'>Hadir:</span>";
        echo "<span>" . $lsRekap[0] . "</span>";
        echo "<br>";
        echo "<span style='display: inline-block; width: 80px; margin-top:10px'>Izin:</span>";
        echo "<span>" . $lsRekap[1] . "</span>";
        echo "<br>";
        echo "<span style='display: inline-block; width: 80px; margin-top:10px'>Sakit:</span>";
        echo "<span>" . $lsRekap[2] . "</span>";
        echo "<br>";
        echo "<span style='display: inline-block; width: 80px; margin-top:10px'>Cuti:</span>";
        echo "<span>" . $lsRekap[3] . "</span>";
        echo "<br>";
        echo "<span style='display: inline-block; width: 80px; margin-top:10px'>Alpa:</span>";
        echo "<span>" . $lsRekap[4] . "</span>";
        echo "<br>";
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}
?>