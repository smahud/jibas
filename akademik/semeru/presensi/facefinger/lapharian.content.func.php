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
function ShowTanggalHarian($db)
{
    global $idKegiatan, $tglAwal, $tglAkhir, $selTanggal;

    $sql = "SELECT DISTINCT date_in
              FROM jbssat.frpresence
             WHERE date_in BETWEEN '$tglAwal' AND '$tglAkhir'";
    $res = $db->QueryDb($sql);               
    if (mysqli_num_rows($res) == 0)
    {
        HintInfo::ShowLeft("Belum ada data presensi harian");
        return;
    }

    echo "<span class='fs-14 fg-secondary' style='margin-left: 5px'>Data Presensi Harian</span><br><br>";
    echo "<table id='tabTanggal' class='tab tabShadow' style='width: 95%' align='center'>";
    while($row = mysqli_fetch_row($res))
    {
        if ($selTanggal == "")
            $selTanggal = $row[0];

        echo "<tr style='height: 30px'>";
        echo "<td style='width: 20%' class='cur-hand' onclick='showData(\"$row[0]\")'>";
        echo WeekdayNameFromPhp(date("w", strtotime($row[0])));
        echo "</td>";
        echo "<td style='width: 85%' class='cur-hand' onclick='showData(\"$row[0]\")'>";
        echo LongDateFormat($row[0]);
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function ShowPresennsiHarianHadir($db)
{
    global $selTanggal;

    $sql = "SELECT pk.nis, s.nama AS namasiswa, pk.nip, p.nama AS namapegawai, 
                   IF(pk.nis IS NOT NULL, 'nis', IF(pk.nip IS NOT NULL, 'nip', 'nouser')) as usercol,
                   date_in, time_in, IFNULL(date_out, '') AS fdate_out, IFNULL(time_out, '') AS ftime_out, source,
                   t.departemen, k.kelas, t.tingkat, IF(p.info1 IS NULL, '', p.info1) As telat
              FROM jbssat.frpresence pk
              LEFT JOIN jbsakad.siswa s ON pk.nis = s.nis
              LEFT JOIN jbsakad.kelas k ON s.idkelas = k.replid  
              LEFT JOIN jbsakad.tingkat t ON k.idtingkat = t.replid  
              LEFT JOIN jbssdm.pegawai p ON pk.nip = p.nip
             WHERE date_in = '$selTanggal'
             ORDER BY time_in";
    $res = $db->QueryDb($sql);
    $nData = mysqli_num_rows($res);
    if ($nData == 0)             
    {
        HintInfo::ShowLeft("Belum ada data presensi harian");
        return;
    }

    echo "<div id='divMenuContent' style='position: relative; width: 100%; line-height: 24px' class='hide-in-report'>";
    echo "<span class='fs-14 fg-secondary' style='margin-left: 5px'>Tanggal " . LongDateFormat($selTanggal) . " </span><br>";
    echo "<span class='fs-14 fg-blue' style='margin-left: 5px'>HADIR, Siswa: <span id='jumsiswa'></span>, Pegawai: <span id='jumpegawai'></span>, Total: <span id='jumtotal'></span></span>";
    echo "<div style='position: absolute; right: 0; top: 50%; transform: translateY(-50%);'>";
    echo "<span class='cur-hand fg-secondary onclick='refresh()'>";
    echo "<img src='../../images/ico/refresh.png' border='0' title='refresh' />&nbsp;refresh";
    echo "</span>&nbsp;&nbsp;";
    echo "<span class='cur-hand fg-secondary' onclick='cetak()'>";
    echo "<img src='../../images/ico/print.png' border='0' title='cetak'/>&nbsp;cetak";
    echo "</span>";
    echo "</div>";
    echo "</div><br>";

    echo "<table id='tabContent' class='tab tabShadow' style='width: 100%' align='left'>";
    echo "<tr>";
    echo "<td width='5%' class='header' align='center'>No</td>";
    echo "<td width='*' class='header' align='left'>Peserta</td>";
    echo "<td width='12%' class='header' align='center'>Jam Masuk</td>";
    echo "<td width='12%' class='header' align='center'>Telat</td>";
    echo "<td width='12%' class='header' align='center'>Jam Pulang</td>";
    echo "<td width='15%' class='header' align='center'>Sumber</td>";
    echo "</tr>";

    $nDataSiswa = 0;
    $nDataPegawai = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no++;

        $userCol = $row['usercol'];
        $userId = "";
        $userInfo = "";
        if ($userCol == "nis")
        {
            $nDataSiswa++;
            $userId = $row["nis"];
            $userInfo = "<b>" . $row['namasiswa'] . "</b><br>";
            $userInfo .= $row["nis"] . " | " . $row["departemen"] . " | " . $row["tingkat"] . " | " . $row["kelas"] . "<br>"; 
            $userInfo .= "<span class='fg-secondary fs-11 fst-italic'>Siswa</span>";
        }
        else if ($userCol == "nip")
        {
            $nDataPegawai++;
            $userId = $row["nip"];
            $userInfo = "<b>" . $row['namapegawai'] . "</b><br>";
            $userInfo .= $row["nip"] . "<br>"; 
            $userInfo .= "<span class='fg-secondary fs-11 fst-italic'>Pegawai</span>";
        }

        $source = $row['source'];
        if ($source == "M")
            $source = "Input Manual";
        else if ($source == "FGR" || $source == "F")
            $source = "Fingerprint";
        else if ($source == "FACE" || $source == "W")
            $source = "Wajah";
            
        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td align='left' style='position: relative;'>";
        echo $userInfo;
        echo "<span style='position: absolute; top: 10px; right: 5px'>";
        echo "<img src='../../images/ico/lihat.png' class='cur-hand hide-in-report' onclick='showUserInfo(\"$userCol\", \"$userId\")'>";
        if ($userCol == "nis")
            echo "&nbsp;&nbsp;<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='showDashboardSiswa(\"$userId\")'>";
        echo "</span>";
        echo "</td>";
        echo "<td align='center'>$row[time_in]</td>";
        echo "<td align='center'>$row[telat]</td>";
        echo "<td align='center'>$row[ftime_out]</td>";
        echo "<td align='center'>$source</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<input type='hidden' id='ndatasiswa' value='$nDataSiswa'>";
    echo "<input type='hidden' id='ndatapegawai' value='$nDataPegawai'>";
    echo "<script>";
    echo "document.getElementById('jumsiswa').innerHTML = document.getElementById('ndatasiswa').value;";
    echo "document.getElementById('jumpegawai').innerHTML = document.getElementById('ndatapegawai').value;";
    echo "document.getElementById('jumtotal').innerHTML = parseInt(document.getElementById('ndatasiswa').value) + parseInt(document.getElementById('ndatapegawai').value);";
    echo "</script>";

}

function GetDataMember($db, $departemen)
{
    $nisList = GetNisList($db, $departemen);
    $nipList = GetNipList($db);

    return [$nisList, $nipList];
}

function GetNisList($db, $departemen)
{

    $sql1 = "SELECT DISTINCT nis
               FROM jbssat.frdata
              WHERE ownertype = 0
                AND verify = 1
                AND active = 1
                AND departemen = '$departemen'";

    $sql2 = "SELECT DISTINCT nis
               FROM jbssat.fcface
              WHERE jenis = 0
                AND useraktif = 1
                AND istrained = 1
                AND status = 1
                AND departemen = '$departemen'";
    
    $sql = "SELECT nis FROM ($sql1 UNION $sql2) AS x";
    
    $nislist = "";

    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        if ($nislist != "")
            $nislist .= ",";

        $nislist .= "'" . $row[0] . "'";
    }

    return $nislist;
}

function GetNipList($db)
{
    
    $sql1 = "SELECT DISTINCT nip
                FROM jbssat.frdata
                WHERE ownertype = 1
                AND verify = 1
                AND active = 1";

    $sql2 = "SELECT DISTINCT nip
                FROM jbssat.fcface
                WHERE jenis = 1
                AND useraktif = 1
                AND status = 1
                AND istrained = 1";

    $sql = "SELECT nip FROM ($sql1 UNION $sql2) AS x";

    $niplist = "";

    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_array($res))
    {
        if ($niplist != "")
            $niplist .= ",";

        $niplist .= "'" . $row[0] . "'";
    }

    return $niplist;
}

function ShowPresennsiHarianBelum($db)
{
    global $departemen, $selTanggal;

    $arr = GetDataMember($db, $departemen);
    $nisList = $arr[0];
    $nipList = $arr[1];

    echo "<div id='divMenuContent' style='position: relative; width: 95%; line-height: 24px' class='hide-in-report'>";
    echo "<span class='fs-14 fg-secondary' style='margin-left: 5px'>Tanggal " . LongDateFormat($selTanggal) . " </span><br>";
    echo "<span class='fs-14 fg-maroon' style='margin-left: 5px'>BELUM TERDATA, Siswa: <span id='jumsiswa'></span>, Pegawai: <span id='jumpegawai'></span>, Total: <span id='jumtotal'></span></span>";
    echo "<div style='position: absolute; right: 0; top: 50%; transform: translateY(-50%);'>";
    echo "<span class='cur-hand fg-secondary' onclick='refresh()'>";
    echo "<img src='../../images/ico/refresh.png' border='0' title='refresh' />&nbsp;refresh";
    echo "</span>&nbsp;&nbsp;";
    echo "<span class='cur-hand fg-secondary' onclick='cetak()'>";
    echo "<img src='../../images/ico/print.png' border='0' title='cetak'/>&nbsp;cetak";
    echo "</span>";
    echo "</div>";
    echo "</div><br>";

    echo "<table id='tabContent' class='tab tabShadow' style='width: 550' align='left'>";
    echo "<tr>";
    echo "<td width='50' class='header' align='center'>No</td>";
    echo "<td width='500' class='header' align='left'>Peserta</td>";
    echo "</tr>";

    $no = 0;
    if ($nisList != "")
    {
        $sql1 = "SELECT DISTINCT d.nis, s.nama, t.departemen, t.tingkat, k.kelas, IF(fk.replid IS NULL, 0, fk.replid) AS fkreplid
               FROM jbssat.frdata d
              INNER JOIN jbsakad.siswa s
                 ON d.nis = s.nis
                AND d.nis IN ($nisList)  
                AND d.active = 1 
                AND d.verify = 1
                AND s.aktif = 1
                AND s.alumni = 0  
              INNER JOIN jbsakad.kelas k
                 ON s.idkelas = k.replid
              INNER JOIN jbsakad.tingkat t
                 ON k.idtingkat = t.replid
               LEFT JOIN jbssat.frpresence fk
                 ON fk.nis = d.nis 
                AND fk.departemen = '$departemen'   
                AND fk.date_in = '$selTanggal' 
             HAVING fkreplid = 0";

        $sql2 = "SELECT DISTINCT f.nis, s.nama, t.departemen, t.tingkat, k.kelas, IF(fk.replid IS NULL, 0, fk.replid) AS fkreplid
                FROM jbssat.fcface f
                INNER JOIN jbsakad.siswa s
                    ON f.nis = s.nis
                    AND f.nis IN ($nisList)  
                    AND f.useraktif = 1
                    AND f.status = 1
                    AND f.istrained = 1
                    AND s.aktif = 1
                    AND s.alumni = 0  
                INNER JOIN jbsakad.kelas k
                    ON s.idkelas = k.replid
                INNER JOIN jbsakad.tingkat t
                    ON k.idtingkat = t.replid
                LEFT JOIN jbssat.frpresence fk
                    ON fk.nis = f.nis 
                    AND fk.departemen = '$departemen'   
                    AND fk.date_in = '$selTanggal' 
                HAVING fkreplid = 0";

        $sql = "SELECT nis, nama, departemen, tingkat, kelas, fkreplid 
                FROM ({$sql1} UNION {$sql2}) AS x
                ORDER BY nama";             
        $res = $db->QueryDb($sql);           
        $nDataSiswa = mysqli_num_rows($res);   
        while($row = mysqli_fetch_array($res))
        {
            $no++;

            $userId = $row["nis"];
            $userInfo = "<b>" . $row['nama'] . "</b><br>";
            $userInfo .= $row["nis"] . " | " . $row["departemen"] . " | " . $row["tingkat"] . " | " . $row["kelas"] . "<br>"; 
            $userInfo .= "<span class='fg-secondary fs-11 fst-italic'>Siswa</span>";
            
            echo "<tr>";
            echo "<td align='center' class='bg-table-number-column'>$no</td>";
            echo "<td align='left' style='position: relative;'>";
            echo $userInfo;
            echo "<span style='position: absolute; top: 10px; right: 5px'>";
            echo "<img src='../../images/ico/lihat.png' class='cur-hand hide-in-report' onclick='showUserInfo(\"nis\", \"$userId\")'>";
            echo "&nbsp;&nbsp;<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='showDashboardSiswa(\"$userId\")'>";
            echo "</span>";
            echo "</td>"; 
            echo "</tr>";
        }
    }

    if ($nipList != "")
    {
        $sql1 = "SELECT DISTINCT d.nip, pg.nama, pg.bagian, '-' AS kelas, IF(fk.replid IS NULL, 0, fk.replid) AS fkreplid
                   FROM jbssat.frdata d
                  INNER JOIN jbssdm.pegawai pg
                     ON d.nip = pg.nip
                    AND d.nip IN ($nipList) 
                    AND d.active = 1 
                    AND d.verify = 1
                    AND pg.aktif = 1 
                   LEFT JOIN jbssat.frpresence fk
                     ON fk.nip = d.nip 
                    AND fk.date_in = '$selTanggal'          
                 HAVING fkreplid = 0";

        $sql2 = "SELECT DISTINCT f.nip, pg.nama, pg.bagian, '-' AS kelas, IF(fk.replid IS NULL, 0, fk.replid) AS fkreplid
                   FROM jbssat.fcface f
                  INNER JOIN jbssdm.pegawai pg
                     ON f.nip = pg.nip
                    AND f.nip IN ($nipList) 
                    AND f.status = 1
                    AND f.istrained = 1
                    AND f.useraktif = 1
                    AND pg.aktif = 1 
                    LEFT JOIN jbssat.frpresence fk
                    ON fk.nip = f.nip 
                    AND fk.date_in = '$selTanggal'          
                    HAVING fkreplid = 0";                 

        $sql = "SELECT nip, nama, bagian, kelas, fkreplid 
                  FROM ($sql1 UNION $sql2) AS x
                 ORDER BY nama";               
        $res = $db->QueryDb($sql);  
        $nDataPegawai = mysqli_num_rows($res);           
        while($row = mysqli_fetch_array($res))
        {
            $no++;

            $userId = $row["nip"];
            $userInfo = "<b>" . $row['nama'] . "</b><br>";
            $userInfo .= $row["nip"] . " | " . $row["bagian"] . "<br>"; 
            $userInfo .= "<span class='fg-secondary fs-11 fst-italic'>Pegawai</span>";
            
            echo "<tr>";
            echo "<td align='center' class='bg-table-number-column'>$no</td>";
            echo "<td align='left' style='position: relative;'>";
            echo $userInfo;
            echo "<img src='../../images/ico/lihat.png' class='cur-hand hide-in-report' style='position: absolute; top: 5px; right: 5px' onclick='showUserInfo(\"nip\", \"$userId\")'>";
            echo "</td>"; 
            echo "</tr>";
        }                      
    }
    echo "</table>";
    echo "<input type='hidden' id='ndatasiswa' value='$nDataSiswa'>";
    echo "<input type='hidden' id='ndatapegawai' value='$nDataPegawai'>";
    echo "<script>";
    echo "document.getElementById('jumsiswa').innerHTML = document.getElementById('ndatasiswa').value;";
    echo "document.getElementById('jumpegawai').innerHTML = document.getElementById('ndatapegawai').value;";
    echo "document.getElementById('jumtotal').innerHTML = parseInt(document.getElementById('ndatasiswa').value) + parseInt(document.getElementById('ndatapegawai').value);";
    echo "</script>";
}
?>