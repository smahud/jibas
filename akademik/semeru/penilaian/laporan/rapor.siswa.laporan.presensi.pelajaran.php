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
echo "<fieldset class='tab tabShadow' style='padding: 15px; border-radius: 10px;'>";
echo "<legend><strong>Presensi Pelajaran</strong></legend>";

echo "<table width='100%' class='tab' id='table'>";
echo "<tr>";
echo "<td width='27%' rowspan='2' align='center' class='fst-bold bg-table-header'>Pelajaran</td>";
echo "<td height='25' colspan='2' align='center' class='fst-bold bg-table-header'>Hadir</td>";
echo "<td height='25' colspan='2' align='center' class='fst-bold bg-table-header'>Sakit</td>";
echo "<td height='25' colspan='2' align='center' class='fst-bold bg-table-header'>Ijin</td>";
echo "<td height='25' colspan='2' align='center' class='fst-bold bg-table-header'>Alpa</td>";
echo "<td height='25' colspan='2' align='center' class='fst-bold bg-table-header'>Cuti</td>";
echo "</tr>";
echo "<tr>";
echo "<td width='6' align='center' class='fst-bold bg-table-header'>Jumlah</div></td>";
echo "<td width='6' align='center' class='fst-bold bg-table-header'>%</div></td>";
echo "<td width='6' align='center' class='fst-bold bg-table-header'>Jumlah</div></td>";
echo "<td width='6' align='center' class='fst-bold bg-table-header'>%</div></td>";
echo "<td width='6' align='center' class='fst-bold bg-table-header'>Jumlah</div></td>";
echo "<td width='6' align='center' class='fst-bold bg-table-header'>%</div></td>";
echo "<td width='6' align='center' class='fst-bold bg-table-header'>Jumlah</div></td>";
echo "<td width='6' align='center' class='fst-bold bg-table-header'>%</div></td>";
echo "<td width='6' align='center' class='fst-bold bg-table-header'>Jumlah</div></td>";
echo "<td width='6' align='center' class='fst-bold bg-table-header'>%</div></td>";
echo "</tr>";

$sql = "SELECT pel.replid as replid,pel.nama as nama 
          FROM jbsakad.presensipelajaran ppel, jbsakad.ppsiswa pp, jbsakad.siswa sis, jbsakad.pelajaran pel 
         WHERE pp.nis=sis.nis 
           AND ppel.replid=pp.idpp 
           AND ppel.idpelajaran=pel.replid 
           AND ppel.idsemester='$idSemester' 
           AND ppel.idkelas='$idKelas' 
           AND sis.nis='$nis' 
           AND ppel.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'
         GROUP BY pel.nama";
$res = $db->QueryDb($sql);
$cntpel = 1;

while ($row = mysqli_fetch_array($res))
{
    $idPelajaran = $row["replid"];
    $pelajaran = $row["nama"];

    //ambil semua jumlah presensi per pelajaran
    $sql = "SELECT count(*) as jumlah 
              FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
             WHERE pel.idpelajaran = $idPelajaran 
               AND pel.idsemester = $idSemester 
               AND pel.idkelas = $idKelas 
               AND pel.replid = pp.idpp 
               AND pel.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'
               AND pp.nis = '$nis'";
    $res2 = $db->QueryDb($sql);
    $row2 = mysqli_fetch_array($res2);
    $jumlah_presensi = $row2['jumlah'];

    //ambil yang hadir
    $sql = "SELECT count(*) as hadir 
            FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
            WHERE pel.idpelajaran = $idPelajaran 
              AND pel.idsemester = $idSemester 
              AND pel.idkelas = $idKelas 
              AND pel.replid = pp.idpp 
              AND pp.nis = '$nis' 
              AND pel.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'
              AND pp.statushadir = 0";
    $res2 = $db->QueryDb($sql);
    $row2 = mysqli_fetch_array($res2);
    $hadir = $row2['hadir'];
    $hh[$cntpel] = $hadir;

    //ambil yang sakit
    $sql = "SELECT count(*) as sakit 
              FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
             WHERE pel.idpelajaran = $idPelajaran 
               AND pel.idsemester = $idSemester 
               AND pel.idkelas = $idKelas 
               AND pel.replid = pp.idpp 
               AND pp.nis = '$nis' 
               AND pel.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'
               AND pp.statushadir = 1";
    $res2 = $db->QueryDb($sql);
    $row2 = mysqli_fetch_array($res2);
    $sakit = $row2['sakit'];
    $ss[$cntpel] = $sakit;

    //ambil yang ijin
    $sql = "SELECT count(*) as ijin 
              FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
             WHERE pel.idpelajaran = $idPelajaran 
               AND pel.idsemester = $idSemester 
               AND pel.idkelas = $idKelas 
               AND pel.replid = pp.idpp 
               AND pp.nis = '$nis' 
               AND pel.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'
               AND pp.statushadir = 2";
    $res2 = $db->QueryDb($sql);
    $row2 = mysqli_fetch_array($res2);
    $ijin = $row2['ijin'];
    $ii[$cntpel] = $ijin;

    //ambil yang alpa
    $sql = "SELECT count(*) as alpa 
            FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
            WHERE pel.idpelajaran = $idPelajaran 
              AND pel.idsemester = $idSemester 
              AND pel.idkelas = $idKelas 
              AND pel.replid = pp.idpp 
              AND pp.nis = '$nis' 
              AND pel.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'
              AND pp.statushadir = 3";
    $res2 = $db->QueryDb($sql);
    $row2 = mysqli_fetch_array($res2);
    $alpa = $row2['alpa'];
    $aa[$cntpel] = $alpa;

    //ambil yang cuti
    $sql = "SELECT count(*) as cuti 
            FROM jbsakad.presensipelajaran pel, jbsakad.ppsiswa pp 
            WHERE pel.idpelajaran = $idPelajaran 
              AND pel.idsemester = $idSemester 
              AND pel.idkelas = $idKelas 
              AND pel.replid = pp.idpp 
              AND pp.nis = '$nis' 
              AND pel.tanggal BETWEEN '$tglAwal' AND '$tglAkhir'
              AND pp.statushadir = 4";
    $res2 = $db->QueryDb($sql);
    $row2 = mysqli_fetch_array($res2);
    $cuti = $row2['cuti'];
    $cc[$cntpel] = $cuti;

    //hitung prosentase kalo jumlahnya gak 0
    if ($jumlah_presensi != 0) 
    {
        $p_hadir = round(($hadir / $jumlah_presensi) * 100);
        $p_sakit = round(($sakit / $jumlah_presensi) * 100);
        $p_ijin = round(($ijin / $jumlah_presensi) * 100);
        $p_alpa = round(($alpa / $jumlah_presensi) * 100);
        $p_cuti = round(($cuti / $jumlah_presensi) * 100);
    } 
    else 
    {
        $p_hadir = 0;
        $p_sakit = 0;
        $p_ijin = 0;
        $p_alpa = 0;
        $p_cuti = 0;
    }

    echo "<tr>";
    echo "<td height='25'>" . $pelajaran. "</td>";
    echo "<td height='25'><div align='center'>" . $hadir. "</div></td>";
    echo "<td height='25'><div align='center'>" . $p_hadir. "%</div></td>";
    echo "<td height='25'><div align='center'>" . $sakit. "</div></td>";
    echo "<td height='25'><div align='center'>" . $p_sakit. "%</div></td>";
    echo "<td height='25'><div align='center'>" . $ijin. "</div></td>";
    echo "<td height='25'><div align='center'>" . $p_ijin. "%</div></td>";
    echo "<td height='25'><div align='center'>" . $alpa. "</div></td>";
    echo "<td height='25'><div align='center'>" . $p_alpa. "%</div></td>";
    echo "<td height='25'><div align='center'>" . $cuti. "</div></td>";
    echo "<td height='25'><div align='center'>" . $p_cuti. "%</div></td>";
    echo "</tr>";
    
    $cntpel++;
}

$hdr = 0;
if ($hh == null) $hh = [];
for ($i=1;$i<=count($hh);$i++)
    $hdr += $hh[$i];

$skt = 0;
if ($ss == null) $ss = [];
for ($i=1;$i<=count($ss);$i++)
    $skt += $ss[$i];

$ijn = 0;
if ($ii == null) $ii = [];
for ($i=1;$i<=count($ii);$i++)
    $ijn += $ii[$i];

$alp = 0;
if ($aa == null) $aa = [];
for ($i=1;$i<=count($aa);$i++)
    $alp += $aa[$i]; 

$cti = 0;
if ($cc == null) $cc = [];
for ($i=1;$i<=count($cc);$i++)
    $cti += $cc[$i];

//total
$jumlah_presensi = $hdr + $skt + $ijn + $alp + $cti;
if ($jumlah_presensi != 0) 
{
    $p_hadir = round(($hdr / $jumlah_presensi) * 100);
    $p_sakit = round(($skt / $jumlah_presensi) * 100);
    $p_ijin = round(($ijn / $jumlah_presensi) * 100);
    $p_alpa = round(($alp / $jumlah_presensi) * 100);
    $p_cuti = round(($cti / $jumlah_presensi) * 100);
} 
else 
{
    $p_hadir = 0;
    $p_sakit = 0;
    $p_ijin = 0;
    $p_alpa = 0;
    $p_cuti = 0;
}

echo "<tr>";
echo "<td height='25' class='fst-bold bg-gray-100' align='right'>Total</td>";
echo "<td height='25' class='fst-bold bg-gray-100' align='center'>" . $hdr. "</td>";
echo "<td height='25' class='fst-bold bg-gray-100' align='center'>" . $p_hadir. "%</td>";
echo "<td height='25' class='fst-bold bg-gray-100' align='center'>" . $skt. "</td>";
echo "<td height='25' class='fst-bold bg-gray-100' align='center'>" . $p_sakit. "%</td>";
echo "<td height='25' class='fst-bold bg-gray-100' align='center'>" . $ijn. "</td>";
echo "<td height='25' class='fst-bold bg-gray-100' align='center'>" . $p_ijin. "%</td>";
echo "<td height='25' class='fst-bold bg-gray-100' align='center'>" . $alp. "</td>";
echo "<td height='25' class='fst-bold bg-gray-100' align='center'>" . $p_alpa. "%</td>";
echo "<td height='25' class='fst-bold bg-gray-100' align='center'>" . $cti. "</td>";
echo "<td height='25' class='fst-bold bg-gray-100' align='center'>" . $p_cuti. "%</td>";
echo "</tr>";   
echo "</table>";

echo "</fieldset><br><br>";