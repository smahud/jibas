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
function ShowRekapKegiatanSiswa($db)
{
    global $nis, $nama, $tglAwal, $tglAkhir;

    $sql = "SELECT DISTINCT pk.idkegiatan
              FROM jbssat.frpresensikegiatan pk, jbssat.frkegiatan k
             WHERE pk.idkegiatan = k.replid
               AND pk.date_in BETWEEN '$tglAwal' AND '$tglAkhir'
               AND pk.nis = '$nis'";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)               
    {
        HintInfo::ShowLeft("Belum ada data presensi kegiatan");
        return;
    }

    echo "<div id='divMenuContent' style='position: relative; width: 100%;' class='hide-in-report'>";
    
    echo "<div style='position: absolute; right: 0; top: 50%; transform: translateY(-50%);'>";
    echo "<span class='cur-hand fg-secondary onclick='refresh()'>";
    echo "<img src='../../images/ico/refresh.png' border='0' title='refresh' />&nbsp;refresh";
    echo "</span>&nbsp;&nbsp;";
    echo "<span class='cur-hand fg-secondary' onclick='cetak()'>";
    echo "<img src='../../images/ico/print.png' border='0' title='cetak'/>&nbsp;cetak";
    echo "</span>";
    echo "</div>";
    echo "</div><br>";

    echo "<table id='table' class='tab tabShadow' style='width: 100%' align='left'>";
    echo "<tr>";
    echo "<td width='5%' class='header' align='center'>No</td>";
    echo "<td width='*' class='header' align='left'>Kegiatan</td>";
    echo "<td width='12%' class='header' align='center'>Jumlah Peserta</td>";
    echo "<td width='12%' class='header' align='center'>Jumlah Hari<br>(A)</td>";
    echo "<td width='12%' class='header' align='center'>Jumlah Hadir<br>(B)</td>";
    echo "<td width='12%' class='header' align='center'>Persentase<br>(B/A)</td>";
    echo "<td width='5%' class='header hide-in-report' align='center'>&nbsp;</td>";
    echo "</tr>";

    $cnt = 0;
    while($row = mysqli_fetch_row($res))
    {
        $idkegiatan = $row[0];
        
        $sql = "SELECT kegiatan, departemen, jenispeserta, idkelompok, iddepartemen,
                       idtingkat, idkelas, kelompokpegawai, jeniswaktu, aktif
                  FROM jbssat.frkegiatan
                 WHERE replid = $idkegiatan";
        $res2 = $db->QueryDb($sql);
        if (mysqli_num_rows($res2) == 0)
            continue;

        $row2 = mysqli_fetch_array($res2);
        $kegiatan = $row2['kegiatan'];
        $jenispeserta = $row2['jenispeserta'];
        $iddepartemen = $row2['iddepartemen'];

        $jenisPesertaName = GetPeserta($db, $idkegiatan, $jenispeserta, $iddepartemen);

        $sql = "SELECT COUNT(DISTINCT pk.date_in)
                  FROM jbssat.frpresensikegiatan pk
                 WHERE pk.date_in BETWEEN '$tglAwal' AND '$tglAkhir'
                   AND pk.idkegiatan = $idkegiatan";
        $res3 = $db->QueryDb($sql);
        $row3 = mysqli_fetch_row($res3);
        $nhari = $row3[0];

        $sql = "SELECT COUNT(DISTINCT pk.date_in)
                  FROM jbssat.frpresensikegiatan pk
                 WHERE pk.date_in BETWEEN '$tglAwal' AND '$tglAkhir'
                   AND pk.nis = '$nis'
                   AND pk.idkegiatan = $idkegiatan";
        $res3 = $db->QueryDb($sql);
        $row3 = mysqli_fetch_row($res3);
        $nhadir = $row3[0];
        
        $persen = $nhari == 0 ? 0 : 100 * round($nhadir / $nhari, 2);

        $cnt += 1;
        echo "<tr height='25'>\r\n";
        echo "<td align='center' class='bg-table-number-column'>$cnt</td>";
        echo "<td align='left'>$kegiatan<br><span class='fg-secondary'>$jenisPesertaName</span></td>";
        echo "<td align='center'>" . GetNPeserta($db, $idkegiatan, $jenispeserta, $iddepartemen) . "</td>";
        echo "<td align='center'>$nhari</td>";
        echo "<td align='center'>$nhadir</td>";
        echo "<td align='center'>$persen %</td>";
        echo "<td align='center' class='hide-in-report'>";
        echo "<img src='../../images/ico/lihat.png' class='cur-hand hide-in-report' title='rincian' onclick='showRincian($idkegiatan, \"$kegiatan\", \"$nis\", \"$nama\")'>";
        echo "</td>";
        echo "</tr>";
    }
}

function GetPeserta($db, $idkegiatan, $jenispeserta, $iddepartemen)
{
    $peserta = "";
    
    if ($jenispeserta == 0)
    {
        return "Semua Siswa dan Pegawai";
    }
    else if ($jenispeserta == 1)
    {
        return "Semua Siswa";
    }
    else if ($jenispeserta == 2)
    {
        return "Semua Pegawai";
    }
    else if ($jenispeserta == 3)
    {
        return "Siswa " . $iddepartemen;
    }
    else if ($jenispeserta == 4)
    {
        $sql = "SELECT t.tingkat, t.departemen
                  FROM jbssat.frkegiatan k, jbsakad.tingkat t
                 WHERE k.idtingkat = t.replid
                   AND k.replid = $idkegiatan";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            
            $tingkat = $row[0];
            $dept = $row[1];
            
            return "Siswa $dept tingkat $tingkat";
        }
        
        return "";
    }
    else if ($jenispeserta == 5)
    {
        $sql = "SELECT kl.kelas, t.tingkat, t.departemen
                  FROM jbssat.frkegiatan k, jbsakad.kelas kl, jbsakad.tingkat t
                 WHERE k.idkelas = kl.replid
                   AND kl.idtingkat = t.replid
                   AND k.replid = $idkegiatan";
        
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            
            $kelas = $row[0];
            $tingkat = $row[1];
            $dept = $row[2];
            
            return "Siswa $dept tingkat $tingkat kelas $kelas";
        }
        
        return "";           
    }
    else if ($jenispeserta == 6)
    {
        return "Pegawai bagian Akademik";
    }
    else if ($jenispeserta == 7)
    {
        return "Pegawai bagian Non Akademik";
    }
    else if ($jenispeserta == 8)
    {
        $sql = "SELECT kl.kelompok
                  FROM jbssat.frkegiatan k, jbssat.frkelompok kl
                 WHERE k.idkelompok = kl.replid
                   AND k.replid = $idkegiatan";
                   
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            
            $kelompok = $row[0];
            
            return "Kelompok $kelompok";
        }
        
        return "";                      
    }
    
    return "Lainnya";
}

function GetNPeserta($db, $idkegiatan, $jenispeserta, $iddepartemen)
{
    $npeserta = 0;
    
    if ($jenispeserta == 0)
    {
        // peserta = "Semua Siswa dan Pegawai";
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran ta
                 WHERE f.nis = s.nis
                   AND s.idkelas = k.replid
                   AND k.idtahunajaran = ta.replid 
                   AND ta.aktif = 1 
                   AND f.active = 1 
                   AND f.verify = 1
                   AND s.aktif = 1";
        $nsiswa = $db->FetchSingle($sql, 0);
        
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbssdm.pegawai p
                 WHERE f.nip = p.nip
                   AND f.active = 1 
                   AND f.verify = 1
                   AND p.aktif = 1";
        $npegawai = $db->FetchSingle($sql, 0);
        
        $npeserta = $npegawai + $nsiswa;
    }
    else if ($jenispeserta == 1)
    {
        // peserta = "Semua Siswa";
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran ta
                 WHERE f.nis = s.nis
                   AND s.idkelas = k.replid
                   AND k.idtahunajaran = ta.replid 
                   AND ta.aktif = 1 
                   AND f.active = 1 
                   AND f.verify = 1
                   AND s.aktif = 1";
        $npeserta = $db->FetchSingle($sql);           
    }
    else if ($jenispeserta == 2)
    {
        // peserta = "Semua Pegawai";
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbssdm.pegawai p
                 WHERE f.nip = p.nip
                   AND f.active = 1 
                   AND f.verify = 1
                   AND p.aktif = 1";
        $npeserta = FetchSingle($sql);
    }
    else if ($jenispeserta == 3)
    {
        // peserta = "Siswa " + iddepartemen;
        $sql =  "SELECT COUNT(f.replid)
                   FROM jbssat.frdata f, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran ta
                  WHERE f.nis = s.nis
                    AND s.idkelas = k.replid
                    AND k.idtahunajaran = ta.replid 
                    AND ta.aktif = 1 
                    AND f.active = 1 
                    AND f.verify = 1
                    AND s.aktif = 1
                    AND ta.departemen = '$iddepartemen'";
        $npeserta = $db->FetchSingle($sql);        
    }
    else if ($jenispeserta == 4)
    {
        $sql = "SELECT k.idtingkat
                  FROM jbssat.frkegiatan k
                 WHERE k.replid = $idkegiatan";
        $idtingkat = $db->FetchSingle($sql);

        $sql =  "SELECT COUNT(f.replid)
                   FROM jbssat.frdata f, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran ta
                  WHERE f.nis = s.nis
                    AND s.idkelas = k.replid
                    AND k.idtahunajaran = ta.replid 
                    AND ta.aktif = 1 
                    AND f.active = 1 
                    AND f.verify = 1
                    AND s.aktif = 1
                    AND k.idtingkat = '$idtingkat'";
        $npeserta = FetchSingle($sql);
    }
    else if ($jenispeserta == 5)
    {
        $sql = "SELECT k.idkelas
                  FROM jbssat.frkegiatan k
                 WHERE k.replid = $idkegiatan";
        $idkelas = $db->FetchSingle($sql);

        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran ta
                 WHERE f.nis = s.nis
                   AND s.idkelas = k.replid
                   AND k.idtahunajaran = ta.replid 
                   AND ta.aktif = 1 
                   AND f.active = 1 
                   AND f.verify = 1
                   AND s.aktif = 1
                   AND k.replid = $idkelas";
        $npeserta = $db->FetchSingle($sql);
    }
    else if ($jenispeserta == 6)
    {
        // peserta = "Pegawai bagian Akademik";
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbssdm.pegawai p
                 WHERE f.nip = p.nip
                   AND f.active = 1 
                   AND f.verify = 1
                   AND p.aktif = 1
                   AND p.bagian = 'Akademik'";
        $npeserta = $db->FetchSingle($sql);
    }
    else if ($jenispeserta == 7)
    {
        // peserta = "Pegawai bagian Non Akademik";
        $sql = "SELECT COUNT(f.replid)
                  FROM jbssat.frdata f, jbssdm.pegawai p
                 WHERE f.nip = p.nip
                   AND f.active = 1 
                   AND f.verify = 1
                   AND p.aktif = 1
                   AND p.bagian = 'Non Akademik'";
        $npeserta = $db->FetchSingle($sql);
    }
    else if ($jenispeserta == 8)
    {
        $sql = "SELECT k.idkelompok
                  FROM jbssat.frkegiatan k
                 WHERE k.replid = $idkegiatan";
        $idkelompok = $db->FetchSingle($sql);

        $sql = "SELECT COUNT(a.replid)
                  FROM jbssat.franggota a
                 WHERE a.idkelompok = $idkelompok";
        $npeserta = $db->FetchSingle($sql);        
    }
    else if ($jenispeserta == 9)
    {
        $sql = "SELECT COUNT(ps.replid)
                  FROM jbssat.frpeserta ps
                 WHERE ps.idkegiatan = $idkegiatan";
        $npeserta = $db->FetchSingle($sql);        
    }
    
    return $npeserta;
}
?>
