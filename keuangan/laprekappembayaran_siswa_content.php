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
    echo '<script>alert("Anda tidak memiliki hak akses ke menu ini."); parent.location.href="penerimaan.php";</script>';
    exit();
}

$nis = $_REQUEST['nis'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rekap Pembayaran Siswa</title>
<script src="script/tooltips.js" language="javascript"></script>
<script language="javascript" src="script/tools.js"></script>
<script language="javascript">
function cetakRekap() {
    var addr = "laprekappembayaran_siswa_cetak.php?nis=<?=$nis?>";
    newWindow(addr, 'CetakRekapBayarSiswa','900','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetakSuratLunas() {
    var addr = "laprekappembayaran_siswa_surat_lunas.php?nis=<?=$nis?>";
    newWindow(addr, 'CetakSuratLunas','900','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function excel() {
    var addr = "laprekappembayaran_siswa_excel.php?nis=<?=$nis?>";
    newWindow(addr, 'CetakRekapBayarSiswaExcel','900','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}
</script>
</head>

<body topmargin="10" leftmargin="10">
<?
OpenDb();

// Get student info - including ALL departments they've been in
$sql = "SELECT s.nama, s.nis, s.alumni
        FROM jbsakad.siswa s
        WHERE s.nis = '$nis'";
$result = QueryDb($sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<center><b>Siswa dengan NIS $nis tidak ditemukan!</b></center>";
    CloseDb();
    exit();
}

$namasiswa = $row['nama'];
$is_alumni = $row['alumni'];

// Get ALL payment records for this student across ALL departments and ALL tahun buku
// We need to trace through the kelas history to get all departments

// 1. Get all unique (departemen, tingkat, kelas, tahunbuku) combinations from payments
// Iuran Wajib (JTT) - get all distinct departemen/tahunbuku combinations
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
        $dept_tahunbuku[$key] = array(
            'departemen' => $row['departemen'],
            'iddepartemen' => $row['iddepartemen'],
            'tahunbuku' => $row['tahunbuku'],
            'idtahunbuku' => $row['idtahunbuku'],
            'tingkat' => $row['tingkat'],
            'idtingkat' => $row['idtingkat'],
            'kelas' => $row['kelas'],
            'idkelas' => $row['idkelas']
        );
    }
}

// If no payment history found, check current student info
if (count($dept_tahunbuku) == 0) {
    $sql = "SELECT d.departemen, d.replid AS iddepartemen, t.tingkat, t.replid AS idtingkat, k.kelas, k.replid AS idkelas
            FROM jbsakad.siswa s
            LEFT JOIN jbsakad.kelas k ON s.idkelas = k.replid
            LEFT JOIN jbsakad.tingkat t ON k.idtingkat = t.replid
            LEFT JOIN jbsakad.departemen d ON t.iddepartemen = d.replid
            WHERE s.nis = '$nis'";
    $result = QueryDb($sql);
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $dept_tahunbuku['current'] = array(
            'departemen' => $row['departemen'],
            'iddepartemen' => $row['iddepartemen'],
            'tahunbuku' => 'Saat Ini',
            'idtahunbuku' => 0,
            'tingkat' => $row['tingkat'],
            'idtingkat' => $row['idtingkat'],
            'kelas' => $row['kelas'],
            'idkelas' => $row['idkelas']
        );
    }
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <table width="100%" border="0" height="100%" cellspacing="0" cellpadding="2">
    <tr>
        <td><font size="4" color="#990000"><strong>Riwayat Pembayaran Lengkap Siswa</strong></font></td>
        <td align="right">
            <a href="#" onClick="document.location.reload()"><img src="images/ico/refresh.png" border="0" onMouseOver="showhint('Refresh!', this, event, '50px')"/>&nbsp;Refresh</a>&nbsp;&nbsp;
            <a href="JavaScript:cetakRekap()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak Rekap Pembayaran!', this, event, '100px')"/>&nbsp;Cetak Rekap</a>&nbsp;&nbsp;
            <a href="JavaScript:cetakSuratLunas()"><img src="images/ico/print.png" border="0" onMouseOver="showhint('Cetak Surat Keterangan Lunas Tanggungan!', this, event, '150px')"/>&nbsp;Surat Lunas</a>&nbsp;&nbsp;
            <a href="JavaScript:excel()"><img src="images/ico/excel.png" border="0" onMouseOver="showhint('Buka di Ms Excel!', this, event, '50px')"/>&nbsp;Excel</a>&nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="2"><font size="3"><strong><?=$nis . " - " . $namasiswa . ($is_alumni ? " (ALUMNI)" : "")?></strong></font></td>
    </tr>
    </table>
    <br />
    
<?
if (count($dept_tahunbuku) == 0) {
?>
    <table width="100%" border="0" align="center">          
    <tr>
        <td align="center" valign="middle" height="250">    
            <font size="2" color="red"><b>Tidak ditemukan adanya data pembayaran untuk siswa ini.<br />Siswa belum pernah melakukan pembayaran apapun.</b></font>
        </td>
    </tr>
    </table>  
<?
} else {
    // Process each department-tahunbuku combination
    foreach ($dept_tahunbuku as $key => $dt) {
        $dept = $dt['departemen'];
        $iddept = $dt['iddepartemen'];
        $tahunbuku = $dt['tahunbuku'];
        $idtahunbuku = $dt['idtahunbuku'];
        $tingkat = $dt['tingkat'];
        $kelas = $dt['kelas'];
        
        // ==================== IURAN WAJIB (JTT) ====================
        $sql_wajib = "SELECT DISTINCT b.replid AS idbesar, b.besar, b.lunas, b.keterangan, b.info2 AS idtahunbuku_b, 
                             dp.nama AS namapenerimaan, tb.tahunbuku,
                             s.nama AS namasiswa, s.nis, k.kelas, t.tingkat, d.departemen
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
        
        // ==================== IURAN SUKARELA ====================
        $sql_sukarela = "SELECT DISTINCT p.idpenerimaan, dp.nama AS namapenerimaan, tb.tahunbuku, j.idtahunbuku,
                                s.nama AS namasiswa, s.nis, k.kelas, t.tingkat, d.departemen,
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
?>
    <!-- DEPARTEMEN HEADER -->
    <table class="tab" id="table" border="1" style="border-collapse:collapse" width="100%" align="center" bordercolor="#000000">
    <tr height="30">
        <td colspan="6" bgcolor="#FF9900"><font size="3"><strong><?=$dept . " - " . $tahunbuku . " (" . $tingkat . " - " . $kelas . ")"?></strong></font></td>
    </tr>
    
<?
        // ---- IURAN WAJIB ----
        $totalbesarwjb = 0;
        $totalbayarwjb = 0;
        $totaldiskonwjb = 0;
        $totalsisawjb = 0;
        
        if ($has_wajib) {
            mysqli_data_seek($result_wajib, 0);
            $prev_namapenerimaan = "";
            $first_wajib = true;
            
            while ($row = mysqli_fetch_array($result_wajib)) {
                $idbesarjtt = $row['idbesar'];
                $namapenerimaan = $row['namapenerimaan']; 
                $besar = $row['besar'];
                $lunas = $row['lunas'];
                $keterangan = $row['keterangan'];
                $kelas_siswa = $row['kelas'];
                $tingkat_siswa = $row['tingkat'];
                $dept_siswa = $row['departemen'];
                
                // Calculate payment totals
                $sql = "SELECT SUM(p.jumlah), SUM(p.info1) 
                        FROM penerimaanjtt p 
                        WHERE p.idbesarjtt = '$idbesarjtt'";
                $row2 = FetchSingleRow($sql);
                $pembayaran = ($row2[0] ? $row2[0] : 0) + ($row2[1] ? $row2[1] : 0);
                $diskon = $row2[1] ? $row2[1] : 0;
                $sisa = $besar - $pembayaran;

                $totalbesarwjb += $besar;
                $totalbayarwjb += $pembayaran;
                $totaldiskonwjb += $diskon;
                $totalsisawjb += $sisa;

                // Get last payment info
                $sql = "SELECT p.jumlah, DATE_FORMAT(p.tanggal, '%d-%b-%Y') AS ftanggal, p.info1, j.nokas
                          FROM penerimaanjtt p, jurnal j
                         WHERE p.idjurnal = j.replid
                           AND p.idbesarjtt='$idbesarjtt'
                         ORDER BY p.tanggal DESC
                         LIMIT 1";
                $result2 = QueryDb($sql);
                $byrakhir = 0;
                $dknakhir = 0;
                $tglakhir = "";
                $nojurnal = "";
                if (mysqli_num_rows($result2)) {
                    $row2 = mysqli_fetch_row($result2);
                    $byrakhir = $row2[0];
                    $tglakhir = $row2[1];
                    $dknakhir = $row2[2];
                    $nojurnal = $row2[3];
                }

                // Print Penerimaan header if changed
                if ($namapenerimaan != $prev_namapenerimaan) {
                    if (!$first_wajib) {
                        echo '<tr height="3"><td colspan="6" bgcolor="#E8E8E8">&nbsp;</td></tr>';
                    }
                    echo '<tr height="35" class="section-header">
                        <td colspan="6" bgcolor="#99CC00"><font size="2"><strong>IURAN WAJIB: ' . $namapenerimaan . '</strong></font></td>
                    </tr>    
                    <tr height="25" class="section-header">
                        <td width="12%" bgcolor="#CCFF66"><strong>Kelas/Tingkat</strong></td>
                        <td width="18%" bgcolor="#CCFF66"><strong>Total Bayaran</strong></td>
                        <td width="15%" bgcolor="#CCFF66" align="center"><strong>Pembayaran Terakhir</strong></td>
                        <td width="20%" bgcolor="#CCFF66" align="center"><strong>Keterangan</strong></td>
                        <td width="15%" bgcolor="#CCFF66" align="center"><strong>Status</strong></td>
                        <td width="20%" bgcolor="#CCFF66" align="center"><strong>Detail Angsuran</strong></td>
                    </tr>';
                    $prev_namapenerimaan = $namapenerimaan;
                }
                
                $first_wajib = false;
                
                // Get detail payments
                $sql_detail = "SELECT p.jumlah, p.info1, DATE_FORMAT(p.tanggal, '%d-%b-%Y') AS ftanggal, j.nokas
                                  FROM penerimaanjtt p, jurnal j
                                 WHERE p.idjurnal = j.replid
                                   AND p.idbesarjtt='$idbesarjtt'
                                 ORDER BY p.tanggal ASC";
                $result_detail = QueryDb($sql_detail);
                $detail_count = 0;
                $details = array();
                while ($row_d = mysqli_fetch_row($result_detail)) {
                    $detail_count++;
                    $details[] = "Angsuran ke-$detail_count: " . FormatRupiah($row_d[0]) . ($row_d[1] ? " (Diskon: " . FormatRupiah($row_d[1]) . ")" : "") . " - " . $row_d[2] . " (JK: " . $row_d[3] . ")";
                }
                
                $detail_str = implode("<br>", $details);
                if ($detail_count == 0) {
                    $detail_str = "<i>Belum ada pembayaran</i>";
                }
                
                $status = ($sisa <= 0) ? "<font color='green'><b>LUNAS</b></font>" : "<font color='red'><b>BELUM LUNAS (Sisa: " . FormatRupiah($sisa) . ")</b></font>";
?>
    <tr height="25">
        <td bgcolor="#CCFF66" align="center" valign="top" rowspan="<?php echo max(2, $detail_count + 1); ?>"><?=$kelas_siswa . " / " . $tingkat_siswa?></td>
        <td bgcolor="#CCFF66"><strong>Besar Bayaran</strong></td>
        <td bgcolor="#FFFFFF" align="center" rowspan="<?php echo max(2, $detail_count + 1); ?>">
            <?=FormatRupiah($byrakhir) . ($dknakhir > 0 ? "<br><i>Diskon: " . FormatRupiah($dknakhir) . "</i>" : "") . "<br><i>" . $tglakhir . "</i><br>JK: $nojurnal" ?>
        </td>
        <td bgcolor="#FFFFFF" align="left" valign="top" rowspan="<?php echo max(2, $detail_count + 1); ?>"><?=$keterangan ?></td>
        <td bgcolor="#FFFFFF" align="center" rowspan="<?php echo max(2, $detail_count + 1); ?>"><?=$status?></td>
        <td bgcolor="#FFFFFF" align="right"><?=FormatRupiah($besar) ?></td>
    </tr>
    <tr height="25">
        <td bgcolor="#CCFF66"><strong>Total Dibayar</strong></td>
        <td bgcolor="#FFFFFF" align="right"><?=FormatRupiah($pembayaran) . ($diskon > 0 ? " (Diskon: " . FormatRupiah($diskon) . ")" : "") ?></td>
    </tr>
<?php
                if ($detail_count > 0) {
                    foreach ($details as $idx => $det) {
                        echo '<tr height="20"><td colspan="6" bgcolor="#FFFFFF"><table width="100%"><tr><td width="12%">&nbsp;</td><td>' . $det . '</td></tr></table></td></tr>';
                    }
                }
?>
    <tr height="3"><td colspan="6" bgcolor="#E8E8E8">&nbsp;</td></tr>
<?
            } // while wajib
        }
        
        // ---- IURAN SUKARELA ----
        $totalbayarskr = 0;
        
        if ($has_sukarela) {
            $first_skr = true;
            
            while ($row = mysqli_fetch_array($result_sukarela)) {
                $idpenerimaan = $row['idpenerimaan'];
                $namapenerimaan = $row['namapenerimaan'];
                $pembayaran = $row['totalbayar'];
                $kelas_siswa = $row['kelas'];
                $tingkat_siswa = $row['tingkat'];
                $dept_siswa = $row['departemen'];
                
                $totalbayarskr += $pembayaran;

                // Get last payment info
                $sql = "SELECT p.jumlah, DATE_FORMAT(p.tanggal, '%d-%b-%Y') AS ftanggal, j.nokas
                          FROM penerimaaniuran p, jurnal j
                         WHERE p.idjurnal = j.replid
                           AND p.idpenerimaan='$idpenerimaan'
                           AND p.nis='$nis'
                         ORDER BY p.replid DESC
                         LIMIT 1";
                $result3 = QueryDb($sql);
                $byrakhir = 0;
                $tglakhir = "";
                $nojurnal = "";
                if (mysqli_num_rows($result3)) {
                    $row3 = mysqli_fetch_row($result3);
                    $byrakhir = $row3[0];
                    $tglakhir = $row3[1];
                    $nojurnal = $row3[2];
                }

                if ($first_skr) {
                    echo '<tr height="35" class="section-header">
                        <td colspan="6" bgcolor="#99CC00"><font size="2"><strong>IURAN SUKARELA</strong></font></td>
                    </tr>  
                    <tr height="25" class="section-header">
                        <td width="12%" bgcolor="#CCFF66"><strong>Kelas/Tingkat</strong></td>
                        <td width="18%" bgcolor="#CCFF66" align="center"><strong>Total Pembayaran</strong></td>
                        <td width="15%" bgcolor="#CCFF66" align="center"><strong>Pembayaran Terakhir</strong></td>
                        <td width="20%" colspan="2" bgcolor="#CCFF66" align="center"><strong>Keterangan</strong></td>
                        <td width="15%" bgcolor="#CCFF66" align="center"><strong>Detail</strong></td>
                    </tr>';
                    $first_skr = false;
                }
                
                // Get detail payments
                $sql_detail = "SELECT p.jumlah, DATE_FORMAT(p.tanggal, '%d-%b-%Y') AS ftanggal, j.nokas
                                  FROM penerimaaniuran p, jurnal j
                                 WHERE p.idjurnal = j.replid
                                   AND p.idpenerimaan='$idpenerimaan'
                                   AND p.nis='$nis'
                                 ORDER BY p.tanggal ASC";
                $result_detail = QueryDb($sql_detail);
                $detail_count = 0;
                $details = array();
                while ($row_d = mysqli_fetch_row($result_detail)) {
                    $detail_count++;
                    $details[] = "Angsuran ke-$detail_count: " . FormatRupiah($row_d[0]) . " - " . $row_d[1] . " (JK: " . $row_d[2] . ")";
                }
                
                $detail_str = implode("<br>", $details);
                if ($detail_count == 0) {
                    $detail_str = "<i>Belum ada pembayaran</i>";
                }
?>
    <tr height="25">
        <td bgcolor="#CCFF66" align="center" valign="top" rowspan="<?php echo max(2, $detail_count + 1); ?>"><?=$kelas_siswa . " / " . $tingkat_siswa?></td>
        <td bgcolor="#CCFF66" align="center"><?=FormatRupiah($pembayaran) ?></td>
        <td bgcolor="#FFFFFF" align="center" rowspan="<?php echo max(2, $detail_count + 1); ?>"><?=FormatRupiah($byrakhir) . "<br><i>" . $tglakhir . "</i><br>JK: $nojurnal" ?></td>
        <td colspan="2" bgcolor="#FFFFFF" align="left">&nbsp;</td>
        <td bgcolor="#FFFFFF" valign="top"><i><?=$detail_str?></i></td>
    </tr>
<?php
                if ($detail_count > 0) {
                    foreach ($details as $det) {
                        echo '<tr height="20"><td colspan="6" bgcolor="#FFFFFF"><table width="100%"><tr><td width="12%">&nbsp;</td><td colspan="5">' . $det . '</td></tr></table></td></tr>';
                    }
                }
?>
    <tr height="3"><td colspan="6" bgcolor="#E8E8E8">&nbsp;</td></tr>
<?
            } // while sukarela
        }
?>
    </table>
    <br>
    
    <!-- REKAPITULASI PER DEPARTEMEN -->
    <font style="font-size: 14px;"><strong>REKAPITULASI <?=$dept . " - " . $tahunbuku?></strong></font>
    <table border="0" width="100%">
    <tr>
        <td width="50%" valign="top">
            <table border="1" style="border-width: 1px; border-collapse: collapse;" cellpadding="5" width="100%">
                <tr style="background-color: #87c7f4;">
                    <td colspan="2" align="center"><strong>Iuran Wajib Siswa</strong></td>
                </tr>
                <tr style="background-color: #e6f5ff">
                    <td align="left">Total Semua Besar Bayaran</td>
                    <td align="right"><?=FormatRupiah($totalbesarwjb)?></td>
                </tr>
                <tr style="background-color: #e6f5ff">
                    <td align="left">Total Semua Pembayaran</td>
                    <td align="right"><?=FormatRupiah($totalbayarwjb)?></td>
                </tr>
                <tr style="background-color: #e6f5ff">
                    <td align="left">Total Semua Diskon</td>
                    <td align="right"><?=FormatRupiah($totaldiskonwjb)?></td>
                </tr>
                <tr style="background-color: #e6f5ff">
                    <td align="left">Total Semua Sisa Tagihan</td>
                    <td align="right"><?=FormatRupiah($totalsisawjb)?></td>
                </tr>
                <tr style="background-color: #e6f5ff">
                    <td align="left"><strong>Status Keseluruhan</strong></td>
                    <td align="right"><strong><?=($totalsisawjb <= 0) ? "<font color='green'>LUNAS</font>" : "<font color='red'>BELUM LUNAS</font>"?></strong></td>
                </tr>
            </table>
        </td>
        <td width="50%" valign="top">
            <table border="1" style="border-width: 1px; border-collapse: collapse;" cellpadding="5" width="100%">
                <tr style="background-color: #87c7f4;">
                    <td colspan="2" align="center"><strong>Iuran Sukarela Siswa</strong></td>
                </tr>
                <tr style="background-color: #e6f5ff">
                    <td align="left">Total Semua Pembayaran</td>
                    <td align="right"><?=FormatRupiah($totalbayarskr)?></td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    <br><br>
<?
    } // foreach dept_tahunbuku
}
?>
    </td>
</tr>
</table>
<?
CloseDb();
?>
</body>
</html>