<?
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
<?
require_once('include/errorhandler.php');
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/rupiah.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');
require_once('library/jurnal.php');
require_once('library/repairdatajtt.php');

// ACCESS CONTROL
$access = getAccess();
if ($access != "ALL") {
    echo "Anda tidak memiliki hak akses ke menu ini.";
    exit();
}

$nis = $_REQUEST['nis'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Rekap_Pembayaran_Siswa_$nis.xls");
header("Pragma: no-cache");
header("Expires: 0");

OpenDb();

// Get student info
$sql = "SELECT s.nama, s.nis, s.alumni
        FROM jbsakad.siswa s
        WHERE s.nis = '$nis'";
$result = QueryDb($sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "Siswa dengan NIS $nis tidak ditemukan!";
    CloseDb();
    exit();
}

$namasiswa = $row['nama'];
$is_alumni = $row['alumni'];

// Get ALL payment records across ALL departments
$sql = "SELECT DISTINCT d.departemen, d.replid AS iddepartemen, tb.tahunbuku, tb.replid AS idtahunbuku,
               t.tingkat, t.replid AS idtingkat, k.kelas, k.replid AS idkelas
        FROM besarjtt b
        JOIN penerimaanjtt p ON p.idbesarjtt = b.replid
        JOIN jurnal j ON p.idjurnal = j.replid
        JOIN tahunbuku tb ON j.idtahunbuku = tb.replid
        JOIN jbsakad.siswa s ON b.nis = s.nis
        LEFT JOIN jbsakad.kelas k ON s.idkelas = k.replid
        LEFT JOIN jbsakad.tingkat t ON k.idtingkat = t.replid
        LEFT JOIN jbsakad.departemen d ON t.iddepartemen = d.replid
        WHERE b.nis = '$nis'
        UNION
        SELECT DISTINCT d.departemen, d.replid AS iddepartemen, tb.tahunbuku, tb.replid AS idtahunbuku,
               t.tingkat, t.replid AS idtingkat, k.kelas, k.replid AS idkelas
        FROM penerimaaniuran p
        JOIN jurnal j ON p.idjurnal = j.replid
        JOIN tahunbuku tb ON j.idtahunbuku = tb.replid
        JOIN jbsakad.siswa s ON p.nis = s.nis
        LEFT JOIN jbsakad.kelas k ON s.idkelas = k.replid
        LEFT JOIN jbsakad.tingkat t ON k.idtingkat = t.replid
        LEFT JOIN jbsakad.departemen d ON t.iddepartemen = d.replid
        WHERE p.nis = '$nis'
        ORDER BY departemen, tahunbuku, tingkat, kelas";

$result = QueryDb($sql);
$dept_tahunbuku = array();
while ($row = mysqli_fetch_assoc($result)) {
    $key = $row['iddepartemen'].'|'.$row['idtahunbuku'];
    if (!isset($dept_tahunbuku[$key])) {
        $dept_tahunbuku[$key] = $row;
    }
}

// Grand totals
$grand_total_besar = 0;
$grand_total_bayar_wjb = 0;
$grand_total_diskon = 0;
$grand_total_sisa = 0;
$grand_total_skr = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rekap Pembayaran Siswa Excel</title>
</head>
<body>
<table border="1">
<tr><td colspan="6" align="center"><strong>REKAP PEMBAYARAN SISWA LENGKAP</strong></td></tr>
<tr><td colspan="6"><?=$nis . " - " . $namasiswa . ($is_alumni ? " (ALUMNI)" : "")?></td></tr>
<tr><td colspan="6">Tanggal Cetak: <?=date('d-m-Y H:i:s')?></td></tr>
<tr><td colspan="6">&nbsp;</td></tr>
</table>

<?
foreach ($dept_tahunbuku as $key => $dt) {
    $dept = $dt['departemen'];
    $iddept = $dt['iddepartemen'];
    $tahunbuku = $dt['tahunbuku'];
    $idtahunbuku = $dt['idtahunbuku'];
    $tingkat = $dt['tingkat'];
    $kelas = $dt['kelas'];
    
    // ---- IURAN WAJIB ----
    $sql_wajib = "SELECT DISTINCT b.replid AS idbesar, b.besar, b.lunas, b.keterangan, 
                         dp.nama AS namapenerimaan
                  FROM besarjtt b
                  JOIN penerimaanjtt p ON p.idbesarjtt = b.replid
                  JOIN datapenerimaan dp ON b.idpenerimaan = dp.replid
                  JOIN jurnal j ON p.idjurnal = j.replid
                  JOIN tahunbuku tb ON j.idtahunbuku = tb.replid
                  JOIN jbsakad.siswa s ON b.nis = s.nis
                  LEFT JOIN jbsakad.kelas k ON s.idkelas = k.replid
                  LEFT JOIN jbsakad.tingkat t ON k.idtingkat = t.replid
                  LEFT JOIN jbsakad.departemen d ON t.iddepartemen = d.replid
                  WHERE b.nis = '$nis' 
                    AND d.replid = '$iddept'
                    AND tb.replid = '$idtahunbuku'
                  ORDER BY dp.nama ASC, p.tanggal ASC";
    
    $result_wajib = QueryDb($sql_wajib);
    $has_wajib = mysqli_num_rows($result_wajib) > 0;
    
    // ---- IURAN SUKARELA ----
    $sql_sukarela = "SELECT DISTINCT p.idpenerimaan, dp.nama AS namapenerimaan,
                            SUM(p.jumlah) AS totalbayar
                     FROM penerimaaniuran p
                     JOIN datapenerimaan dp ON p.idpenerimaan = dp.replid
                     JOIN jurnal j ON p.idjurnal = j.replid
                     JOIN tahunbuku tb ON j.idtahunbuku = tb.replid
                     JOIN jbsakad.siswa s ON p.nis = s.nis
                     LEFT JOIN jbsakad.kelas k ON s.idkelas = k.replid
                     LEFT JOIN jbsakad.tingkat t ON k.idtingkat = t.replid
                     LEFT JOIN jbsakad.departemen d ON t.iddepartemen = d.replid
                     WHERE p.nis = '$nis' 
                       AND d.replid = '$iddept'
                       AND tb.replid = '$idtahunbuku'
                     GROUP BY p.idpenerimaan
                     ORDER BY dp.nama ASC";
    
    $result_sukarela = QueryDb($sql_sukarela);
    $has_sukarela = mysqli_num_rows($result_sukarela) > 0;
    
    if (!$has_wajib && !$has_sukarela) continue;
    
    // Dept header
    echo '<table border="1"><tr style="background:#FF9900"><td colspan="6"><strong>'.$dept.' - '.$tahunbuku.' ('.$tingkat.' - '.$kelas.')</strong></td></tr></table>';
    
    // IURAN WAJIB
    $totalbesarwjb = 0;
    $totalbayarwjb = 0;
    $totaldiskonwjb = 0;
    $totalsisawjb = 0;
    
    if ($has_wajib) {
        echo '<table border="1"><tr style="background:#99CC00"><td colspan="6"><strong>IURAN WAJIB</strong></td></tr>';
        echo '<tr style="background:#CCFF66">
            <th>Kelas/Tingkat</th>
            <th>Jenis Pembayaran</th>
            <th>Besar Bayaran</th>
            <th>Total Dibayar</th>
            <th>Diskon</th>
            <th>Sisa / Status</th>
        </tr>';
        
        mysqli_data_seek($result_wajib, 0);
        while ($row = mysqli_fetch_array($result_wajib)) {
            $idbesarjtt = $row['idbesar'];
            $namapenerimaan = $row['namapenerimaan']; 
            $besar = $row['besar'];
            $keterangan = $row['keterangan'];
            $kelas_siswa = $row['kelas'];
            $tingkat_siswa = $row['tingkat'];
            
            $sql = "SELECT SUM(p.jumlah), SUM(p.info1) FROM penerimaanjtt p WHERE p.idbesarjtt = '$idbesarjtt'";
            $row2 = FetchSingleRow($sql);
            $pembayaran = ($row2[0] ? $row2[0] : 0) + ($row2[1] ? $row2[1] : 0);
            $diskon = $row2[1] ? $row2[1] : 0;
            $sisa = $besar - $pembayaran;

            $totalbesarwjb += $besar;
            $totalbayarwjb += $pembayaran;
            $totaldiskonwjb += $diskon;
            $totalsisawjb += $sisa;
            
            $status = ($sisa <= 0) ? "LUNAS" : "BELUM LUNAS (Sisa: ".FormatRupiah($sisa).")";
            
            echo '<tr>
                <td>'.$kelas_siswa.' / '.$tingkat_siswa.'</td>
                <td>'.$namapenerimaan.'</td>
                <td align="right">'.FormatRupiah($besar).'</td>
                <td align="right">'.FormatRupiah($pembayaran).'</td>
                <td align="right">'.FormatRupiah($diskon).'</td>
                <td align="right">'.$status.'</td>
            </tr>';
        }
        
        $grand_total_besar += $totalbesarwjb;
        $grand_total_bayar_wjb += $totalbayarwjb;
        $grand_total_diskon += $totaldiskonwjb;
        $grand_total_sisa += $totalsisawjb;
        
        echo '<tr style="background:#e6f5ff">
            <td colspan="2" align="right"><strong>SUBTOTAL WAJIB</strong></td>
            <td align="right"><strong>'.FormatRupiah($totalbesarwjb).'</strong></td>
            <td align="right"><strong>'.FormatRupiah($totalbayarwjb).'</strong></td>
            <td align="right"><strong>'.FormatRupiah($totaldiskonwjb).'</strong></td>
            <td align="right"><strong>'.FormatRupiah($totalsisawjb).' '.($totalsisawjb <= 0 ? "(LUNAS)" : "(BELUM LUNAS)").'</strong></td>
        </tr></table><br>';
    }
    
    // IURAN SUKARELA
    $totalbayarskr = 0;
    
    if ($has_sukarela) {
        echo '<table border="1"><tr style="background:#99CC00"><td colspan="4"><strong>IURAN SUKARELA</strong></td></tr>';
        echo '<tr style="background:#CCFF66">
            <th>Kelas/Tingkat</th>
            <th>Jenis Pembayaran</th>
            <th>Total Dibayar</th>
            <th>Detail</th>
        </tr>';
        
        while ($row = mysqli_fetch_array($result_sukarela)) {
            $namapenerimaan = $row['namapenerimaan'];
            $pembayaran = $row['totalbayar'];
            $kelas_siswa = $row['kelas'];
            $tingkat_siswa = $row['tingkat'];
            
            $totalbayarskr += $pembayaran;
            
            echo '<tr>
                <td>'.$kelas_siswa.' / '.$tingkat_siswa.'</td>
                <td>'.$namapenerimaan.'</td>
                <td align="right">'.FormatRupiah($pembayaran).'</td>
                <td>&nbsp;</td>
            </tr>';
        }
        
        $grand_total_skr += $totalbayarskr;
        
        echo '<tr style="background:#e6f5ff">
            <td colspan="2" align="right"><strong>SUBTOTAL SUKARELA</strong></td>
            <td align="right"><strong>'.FormatRupiah($totalbayarskr).'</strong></td>
            <td>&nbsp;</td>
        </tr></table><br>';
    }
}

// GRAND TOTAL
echo '<table border="1">
<tr style="background:#87c7f4"><td colspan="5"><strong>GRAND TOTAL KESELURUHAN</strong></td></tr>
<tr style="background:#e6f5ff">
    <td><strong>Total Iuran Wajib - Besar Bayaran</strong></td>
    <td align="right"><strong>'.FormatRupiah($grand_total_besar).'</strong></td>
    <td><strong>Total Iuran Wajib - Dibayar</strong></td>
    <td align="right"><strong>'.FormatRupiah($grand_total_bayar_wjb).'</strong></td>
    <td align="right"><strong>Sisa: '.FormatRupiah($grand_total_sisa).'</strong></td>
</tr>
<tr style="background:#e6f5ff">
    <td><strong>Total Iuran Wajib - Diskon</strong></td>
    <td align="right"><strong>'.FormatRupiah($grand_total_diskon).'</strong></td>
    <td><strong>Total Iuran Sukarela</strong></td>
    <td align="right"><strong>'.FormatRupiah($grand_total_skr).'</strong></td>
    <td><strong>Status: '.($grand_total_sisa <= 0 ? "SELURUHNYA LUNAS" : "ADA TUNGGAKAN").'</strong></td>
</tr>
</table><br><br>';

echo '<table border="0" width="100%">
<tr>
    <td width="33%" align="center">Mengetahui,<br><br><br><br>Kepala Sekolah</td>
    <td width="34%" align="center">'.date('d F Y').'<br>Bendahara,<br><br><br><br>................................</td>
    <td width="33%" align="center">Operator,<br><br><br><br>................................</td>
</tr>
</table>';

CloseDb();
?>
</body>
</html>