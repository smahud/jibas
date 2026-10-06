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
require_once('include/getheader.php');
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Surat Keterangan Lunas Tanggungan</title>
<style>
@media print { .no-print { display: none; } }
body { font-family: 'Times New Roman', Times, serif; font-size: 12px; line-height: 1.6; }
.table-border { border-collapse: collapse; width: 100%; }
.table-border th, .table-border td { border: 1px solid #000; padding: 4px; }
.header-title { font-size: 16px; font-weight: bold; text-align: center; text-transform: uppercase; margin: 10px 0; }
.sub-header { font-size: 13px; text-align: center; margin: 5px 0; }
.nomor-surat { font-size: 13px; text-align: center; margin: 15px 0; }
.isi-surat { text-align: justify; margin: 20px 0; text-indent: 50px; }
.ttd { margin-top: 60px; }
</style>
<script language="javascript">window.print();</script>
</head>

<body topmargin="20" leftmargin="30" rightmargin="30">
<?
OpenDb();

// Get student info
$sql = "SELECT s.nama, s.nis, s.nisn, s.alumni, s.tanggallahir, s.tmptlahir,
               k.kelas, t.tingkat, d.departemen, d.replid AS iddepartemen
        FROM jbsakad.siswa s
        LEFT JOIN jbsakad.kelas k ON s.idkelas = k.replid
        LEFT JOIN jbsakad.tingkat t ON k.idtingkat = t.replid
        LEFT JOIN jbsakad.departemen d ON t.iddepartemen = d.replid
        WHERE s.nis = '$nis'";
$result = QueryDb($sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<center><b>Siswa dengan NIS $nis tidak ditemukan!</b></center>";
    CloseDb();
    exit();
}

$namasiswa = $row['nama'];
$nisn = $row['nisn'];
$is_alumni = $row['alumni'];
$tanggallahir = $row['tanggallahir'];
$tmptlahir = $row['tmptlahir'];
$kelas = $row['kelas'];
$tingkat = $row['tingkat'];
$departemen = $row['departemen'];
$iddepartemen = $row['iddepartemen'];

// Check ALL payment status across ALL departments
$total_sisa = 0;
$detail_per_dept = array();

// Get all departments the student has been in (from payment history)
$sql = "SELECT DISTINCT d.departemen, d.replid AS iddepartemen, tb.tahunbuku, tb.replid AS idtahunbuku
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
        SELECT DISTINCT d.departemen, d.replid AS iddepartemen, tb.tahunbuku, tb.replid AS idtahunbuku
        FROM penerimaaniuran p
        JOIN jurnal j ON p.idjurnal = j.replid
        JOIN tahunbuku tb ON j.idtahunbuku = tb.replid
        JOIN jbsakad.siswa s ON p.nis = s.nis
        LEFT JOIN jbsakad.kelas k ON s.idkelas = k.replid
        LEFT JOIN jbsakad.tingkat t ON k.idtingkat = t.replid
        LEFT JOIN jbsakad.departemen d ON t.iddepartemen = d.replid
        WHERE p.nis = '$nis'
        ORDER BY departemen, tahunbuku";

$result = QueryDb($sql);
$dept_list = array();
while ($row = mysqli_fetch_assoc($result)) {
    $key = $row['iddepartemen'].'|'.$row['idtahunbuku'];
    if (!isset($dept_list[$key])) {
        $dept_list[$key] = $row;
    }
}

// If no payment history, use current department
if (count($dept_list) == 0) {
    $dept_list['current'] = array(
        'departemen' => $departemen,
        'iddepartemen' => $iddepartemen,
        'tahunbuku' => 'Saat Ini',
        'idtahunbuku' => 0
    );
}

// Calculate totals per department
foreach ($dept_list as $key => $dt) {
    $d = $dt['departemen'];
    $iddept = $dt['iddepartemen'];
    $tb = $dt['tahunbuku'];
    $idtb = $dt['idtahunbuku'];
    
    $dept_sisa = 0;
    $dept_total_besar = 0;
    $dept_total_bayar = 0;
    $dept_items = array();
    
    // Wajib
    $sql = "SELECT b.besar, b.keterangan, dp.nama AS namapenerimaan,
                   SUM(p.jumlah) AS total_bayar, SUM(p.info1) AS total_diskon
            FROM besarjtt b
            JOIN penerimaanjtt p ON p.idbesarjtt = b.replid
            JOIN datapenerimaan dp ON b.idpenerimaan = dp.replid
            JOIN jurnal j ON p.idjurnal = j.replid
            JOIN tahunbuku tb ON j.idtahunbuku = tb.replid
            JOIN jbsakad.siswa s ON b.nis = s.nis
            LEFT JOIN jbsakad.kelas k ON s.idkelas = k.replid
            LEFT JOIN jbsakad.tingkat t ON k.idtingkat = t.replid
            LEFT JOIN jbsakad.departemen d ON t.iddepartemen = d.replid
            WHERE b.nis = '$nis' AND d.replid = '$iddept' AND tb.replid = '$idtb'
            GROUP BY b.replid
            ORDER BY dp.nama";
    $res = QueryDb($sql);
    while ($r = mysqli_fetch_assoc($res)) {
        $besar = $r['besar'];
        $bayar = ($r['total_bayar'] ? $r['total_bayar'] : 0) + ($r['total_diskon'] ? $r['total_diskon'] : 0);
        $sisa = $besar - $bayar;
        $dept_sisa += $sisa;
        $dept_total_besar += $besar;
        $dept_total_bayar += $bayar;
        $dept_items[] = array(
            'jenis' => 'Wajib',
            'nama' => $r['namapenerimaan'],
            'besar' => $besar,
            'bayar' => $bayar,
            'sisa' => $sisa,
            'keterangan' => $r['keterangan']
        );
    }
    
    // Sukarela
    $sql = "SELECT dp.nama AS namapenerimaan, SUM(p.jumlah) AS total_bayar
            FROM penerimaaniuran p
            JOIN datapenerimaan dp ON p.idpenerimaan = dp.replid
            JOIN jurnal j ON p.idjurnal = j.replid
            JOIN tahunbuku tb ON j.idtahunbuku = tb.replid
            JOIN jbsakad.siswa s ON p.nis = s.nis
            LEFT JOIN jbsakad.kelas k ON s.idkelas = k.replid
            LEFT JOIN jbsakad.tingkat t ON k.idtingkat = t.replid
            LEFT JOIN jbsakad.departemen d ON t.iddepartemen = d.replid
            WHERE p.nis = '$nis' AND d.replid = '$iddept' AND tb.replid = '$idtb'
            GROUP BY p.idpenerimaan
            ORDER BY dp.nama";
    $res = QueryDb($sql);
    while ($r = mysqli_fetch_assoc($res)) {
        $bayar = $r['total_bayar'];
        $dept_total_bayar += $bayar;
        $dept_items[] = array(
            'jenis' => 'Sukarela',
            'nama' => $r['namapenerimaan'],
            'besar' => 0,
            'bayar' => $bayar,
            'sisa' => 0,
            'keterangan' => ''
        );
    }
    
    $total_sisa += $dept_sisa;
    $detail_per_dept[] = array(
        'departemen' => $d,
        'tahunbuku' => $tb,
        'sisa' => $dept_sisa,
        'total_besar' => $dept_total_besar,
        'total_bayar' => $dept_total_bayar,
        'items' => $dept_items
    );
}

// Get school info for header
$sql = "SELECT * FROM jbsumum.identitas WHERE departemen='$departemen'";
$res = QueryDb($sql);
$identitas = mysqli_fetch_assoc($res);
$nama_sekolah = $identitas['nama'];
$alamat_sekolah = $identitas['alamat1'].($identitas['alamat2'] ? ", ".$identitas['alamat2'] : "");
$telp_sekolah = $identitas['telp1'].($identitas['telp2'] ? ", ".$identitas['telp2'] : "");
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td width="15%" align="center" valign="top" rowspan="3">
        <?php if ($identitas['replid']) { ?>
        <img src="library/gambar.php?replid=<?=$identitas['replid']?>&table=jbsumum.identitas" width="80" />
        <?php } ?>
    </td>
    <td width="85%" valign="bottom">
        <div class="header-title"><?=strtoupper($nama_sekolah)?></div>
        <div class="sub-header"><?=$alamat_sekolah?><br>Telp: <?=$telp_sekolah?></div>
        <hr style="border: 2px solid #000; margin: 5px 0;">
    </td>
</tr>
</table>

<div class="nomor-surat">
    <u>SURAT KETERANGAN LUNAS TANGGUNGAN</u><br>
    Nomor: ......................../KEU/<?=date('m/Y')?>
</div>

<div class="isi-surat">
    Yang bertanda tangan di bawah ini, Kepala Sekolah <strong><?=strtoupper($nama_sekolah)?></strong>, menerangkan bahwa:
</div>

<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
    <td width="150"><strong>Nama Siswa</strong></td>
    <td width="10"><strong>:</strong></td>
    <td><strong><?=$namasiswa?></strong></td>
</tr>
<tr>
    <td><strong>NIS / NISN</strong></td>
    <td><strong>:</strong></td>
    <td><?=$nis . ($nisn ? " / " . $nisn : "")?></td>
</tr>
<tr>
    <td><strong>Tempat, Tanggal Lahir</strong></td>
    <td><strong>:</strong></td>
    <td><?=$tmptlahir . ", " . date('d F Y', strtotime($tanggallahir))?></td>
</tr>
<tr>
    <td><strong>Kelas / Tingkat</strong></td>
    <td><strong>:</strong></td>
    <td><?=$tingkat . " - " . $kelas?></td>
</tr>
<tr>
    <td><strong>Departemen</strong></td>
    <td><strong>:</strong></td>
    <td><?=$departemen?></td>
</tr>
</table>

<div class="isi-surat">
    Siswa tersebut di atas telah <strong>MELUNASI SELURUH TANGGUNGAN PEMBAYARAN</strong> (Iuran Wajib dan Iuran Sukarela) 
    selama belajar di sekolah ini dari tingkat awal hingga saat ini, rinciannya sebagai berikut:
</div>

<table class="table-border" cellpadding="4">
<tr style="background:#CCCCCC;">
    <th width="5%">No</th>
    <th width="20%">Departemen / Tahun Buku</th>
    <th width="15%">Jenis</th>
    <th width="30%">Nama Pembayaran</th>
    <th width="15%" align="right">Besar Bayaran</th>
    <th width="15%" align="right">Total Dibayar</th>
</tr>
<?
$no = 1;
foreach ($detail_per_dept as $dept) {
    $first_row = true;
    foreach ($dept['items'] as $item) {
        $rowspan = count($dept['items']);
        if ($first_row) {
            echo '<tr>
                <td align="center" rowspan="'.$rowspan.'">'.$no++.'</td>
                <td align="center" rowspan="'.$rowspan.'">'.$dept['departemen'].' / '.$dept['tahunbuku'].'</td>
                <td>'.$item['jenis'].'</td>
                <td>'.$item['nama'].'</td>
                <td align="right">'.($item['besar'] > 0 ? FormatRupiah($item['besar']) : '-').'</td>
                <td align="right">'.FormatRupiah($item['bayar']).'</td>
            </tr>';
            $first_row = false;
        } else {
            echo '<tr>
                <td>'.$item['jenis'].'</td>
                <td>'.$item['nama'].'</td>
                <td align="right">'.($item['besar'] > 0 ? FormatRupiah($item['besar']) : '-').'</td>
                <td align="right">'.FormatRupiah($item['bayar']).'</td>
            </tr>';
        }
    }
}
?>
</table>

<div class="isi-surat" style="margin-top: 20px; font-weight: bold;">
    Status Keseluruhan: 
    <?php if ($total_sisa <= 0) { ?>
        <span style="color:green; font-size:14px;"><strong>LUNAS (Tidak ada tunggakan apapun)</strong></span>
    <?php } else { ?>
        <span style="color:red; font-size:14px;"><strong>BELUM LUNAS (Masih ada tunggakan: <?=FormatRupiah($total_sisa)?>)</strong></span>
    <?php } ?>
</div>

<div class="isi-surat">
    Demikian Surat Keterangan Lunas Tanggungan ini dibuat untuk dipergunakan sebagaimana mestinya.
</div>

<div class="ttd">
    <table width="100%" border="0">
    <tr>
        <td width="50%"></td>
        <td width="50%" align="center">
            <?=$departemen?>, <?=date('d F Y')?><br>
            Kepala Sekolah<br><br><br><br>
            <strong><u>..........................................</u></strong><br>
            NIP. ..................................
        </td>
    </tr>
    </table>
</div>

<div style="margin-top: 30px; padding: 10px; border: 1px dashed #000; font-size: 11px;">
    <strong>Catatan:</strong> Surat ini hanya berlaku sebagai bukti kelunasan tanggangan pembayaran sekolah. 
    Tidak berlaku untuk keperluan lain di luar kewenangan sekolah.
    <br><br>
    <strong>Rincian Per Departemen:</strong><br>
<?
foreach ($detail_per_dept as $dept) {
    echo $dept['departemen'].' ('.$dept['tahunbuku'].'): Total Tagihan '.FormatRupiah($dept['total_besar']).', Total Bayar '.FormatRupiah($dept['total_bayar']).', Sisa '.FormatRupiah($dept['sisa']).' - '.($dept['sisa'] <= 0 ? "<font color='green'><b>LUNAS</b></font>" : "<font color='red'><b>BELUM LUNAS</b></font>").'<br>';
}
?>
    <br>
    <strong>GRAND TOTAL SEMUA DEPARTEMEN:</strong> Sisa Tunggakan = <?=FormatRupiah($total_sisa).' - '.($total_sisa <= 0 ? "<font color='green'><b>SELURUHNYA LUNAS</b></font>" : "<font color='red'><b>ADA TUNGGAKAN</b></font>")?>
</div>

<?
CloseDb();
?>
</body>
</html>