<?php

function RpRootUrl()
{
    $path = $_SERVER['SCRIPT_NAME'] ?? '';
    $offset = strpos($path, '/keuangan/');
    return ($offset === false ? '/jibas' : substr($path, 0, $offset)) . '/keuangan/';
}

function RpRinjani()
{
    return defined('RP_VARIANT') && RP_VARIANT === 'rinjani';
}

function RpRoute($part)
{
    $allowed = array('main','header','pilih','blank','content','cetak','excel','surat_lunas','akses');
    if (!in_array($part,$allowed,true)) throw new InvalidArgumentException('Halaman tidak valid.');
    if (!RpRinjani()) return RpRootUrl() . ($part === 'akses' ? 'rekappembayaran_akses.php' : 'laprekappembayaran_siswa_' . $part . '.php');
    if ($part === 'akses') return RpRootUrl() . 'rinjani/pengaturan/rekapsiswa.akses.php';
    return RpRootUrl() . 'rinjani/penerimaan/laporan/rekapsiswa' . ($part === 'main' ? '' : '.' . $part) . '.php';
}

function RpEscape($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function RpCurrency($value)
{
    return $value === null ? 'Tidak valid' : 'Rp ' . number_format($value, 0, ',', '.');
}

function RpDate($value)
{
    if (!preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/D', (string)$value, $parts)
        || !checkdate((int)$parts[2], (int)$parts[3], (int)$parts[1])) return '-';
    return $parts[3] . '-' . $parts[2] . '-' . $parts[1];
}

function RpPageStart($title, $assetPrefix = '', $bodyClass = '')
{
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . RpEscape($title) . '</title>';
    if (RpRinjani()) echo '<link rel="stylesheet" href="' . RpEscape(RpRootUrl()) . 'rinjani/style/style.css">';
    echo '<link rel="stylesheet" href="' . RpEscape(RpRootUrl()) . 'style/rekappembayaran.css">';
    if (RpRinjani()) echo '<link rel="stylesheet" href="' . RpEscape(RpRootUrl()) . 'rinjani/style/rekappembayaran.css">';
    echo '</head><body class="' . RpEscape(trim($bodyClass . (RpRinjani() ? ' rp-rinjani' : ''))) . '">';
}

function RpStudentInfo($student)
{
    echo '<h1>Rekap Pembayaran Siswa</h1><p><strong>' . RpEscape($student['nis']) . ' — ' . RpEscape($student['nama']) . '</strong></p>';
    echo '<p class="muted">Kelas terakhir: ' . RpEscape($student['departemen'] ?: '-') . ' / '
        . RpEscape($student['tingkat'] ?: '-') . ' / ' . RpEscape($student['kelas'] ?: '-')
        . ' · ' . ($student['alumni'] ? 'Alumni' : ($student['aktif'] ? 'Aktif' : 'Nonaktif')) . '</p>';
    echo '<p class="muted">Seluruh tahun buku dan departemen untuk NIS ini. Kelas terakhir hanya sebagai identitas; tidak menentukan kelompok pembayaran.</p>';
}

function RpTotalsTable($totals, $caption, $status = '')
{
    echo '<table class="summary"><caption>' . RpEscape($caption) . '</caption><tbody>';
    $labels = array('tagihan' => 'Tagihan wajib', 'tunai' => 'Pembayaran tunai wajib', 'diskon' => 'Diskon wajib',
        'sisa' => 'Sisa tagihan wajib', 'kelebihan' => 'Kelebihan bayar wajib', 'sukarela' => 'Penerimaan sukarela');
    foreach ($labels as $key => $label)
        echo '<tr><th scope="row">' . $label . '</th><td class="money">' . RpCurrency($totals[$key]) . '</td></tr>';
    echo '<tr><th scope="row">Total penerimaan tunai (wajib + sukarela)</th><td class="money">'
        . RpCurrency($totals['tunai'] + $totals['sukarela']) . '</td></tr>';
    if ($status !== '') echo '<tr><th scope="row">Status tagihan wajib</th><td>' . RpEscape($status) . '</td></tr>';
    echo '</tbody></table>';
}

function RpPaymentsTable($payments, $wajib)
{
    echo '<div class="table-scroll"><table><caption>Rincian ' . ($wajib ? 'angsuran wajib' : 'penerimaan sukarela') . '</caption>';
    echo '<thead><tr><th>Tanggal</th><th>No. kas / jurnal</th>';
    if ($wajib) echo '<th>Tahun buku transaksi</th>';
    echo '<th>Tunai</th>';
    if ($wajib) echo '<th>Diskon</th>';
    echo '<th>Petugas</th><th>Keterangan</th></tr></thead><tbody>';
    if (!$payments) echo '<tr><td colspan="' . ($wajib ? 7 : 5) . '">Belum ada pembayaran.</td></tr>';
    foreach ($payments as $payment) {
        echo '<tr><td>' . RpDate($payment['tanggal']) . '</td><td>' . RpEscape($payment['nokas'] ?: '-') . '</td>';
        if ($wajib) echo '<td>' . RpEscape($payment['tahunbuku_transaksi'] ?: '-') . '</td>';
        echo '<td class="money">' . RpCurrency($payment['tunai']) . '</td>';
        if ($wajib) echo '<td class="money">' . RpCurrency($payment['diskon']) . '</td>';
        echo '<td>' . RpEscape($payment['petugas']) . '</td><td>' . nl2br(RpEscape($payment['keterangan'])) . '</td></tr>';
    }
    echo '</tbody></table></div>';
}

function RpRenderReport($report)
{
    RpStudentInfo($report['student']);
    echo '<p><strong>Status: ' . RpEscape($report['status']) . '</strong></p>';
    if ($report['issues']) {
        echo '<div class="notice error"><strong>Data perlu diverifikasi; surat lunas tidak tersedia.</strong><ul>';
        foreach ($report['issues'] as $issue) echo '<li>' . RpEscape($issue) . '</li>';
        echo '</ul><p>Total di bawah hanya mencakup nominal yang dapat dibaca.</p></div>';
    }
    if ($report['notes']) {
        echo '<div class="notice"><strong>Catatan perhitungan</strong><ul>';
        foreach ($report['notes'] as $note) echo '<li>' . RpEscape($note) . '</li>';
        echo '</ul><p>Status laporan dihitung dari tagihan dikurangi tunai dan diskon, tanpa mengubah database.</p></div>';
    }
    if (!$report['groups']) echo '<p class="notice">Belum ada tagihan atau pembayaran yang tercatat untuk NIS ini.</p>';
    foreach ($report['departments'] as $department => $totals) {
        echo '<section><h2>Departemen: ' . RpEscape($department) . '</h2>';
        foreach ($report['groups'] as $group) {
            if ($group['departemen'] !== $department) continue;
            echo '<h3>Tahun buku: ' . RpEscape($group['tahunbuku']) . '</h3>';
            foreach ($group['wajib'] as $item) {
                echo '<article><h4>Iuran wajib — ' . RpEscape($item['nama'] ?: 'Jenis tidak diketahui')
                    . ' <small>(tagihan #' . RpEscape($item['id']) . ')</small></h4>';
                RpTotalsTable($item['totals'], 'Ringkasan tagihan', $item['status']);
                if ($item['keterangan'] !== '') echo '<p>' . nl2br(RpEscape($item['keterangan'])) . '</p>';
                RpPaymentsTable($item['payments'], true);
                echo '</article>';
            }
            foreach ($group['sukarela'] as $item) {
                echo '<article><h4>Iuran sukarela — ' . RpEscape($item['nama'] ?: 'Jenis tidak diketahui') . '</h4>';
                echo '<p>Total penerimaan tahun buku ini: <strong>' . RpCurrency($item['jumlah']) . '</strong> · Status: TERCATAT (tanpa target tagihan)</p>';
                RpPaymentsTable($item['payments'], false);
                echo '</article>';
            }
            RpTotalsTable($group['totals'], 'Subtotal tahun buku ' . $group['tahunbuku'], $group['status']);
        }
        RpTotalsTable($totals, 'Subtotal departemen ' . $department, $report['department_statuses'][$department]);
        echo '</section>';
    }
    RpTotalsTable($report['totals'], 'Grand total seluruh departemen', $report['status']);
    echo '<p class="muted">Sisa dihitung per tagihan; kelebihan pada satu tagihan tidak menutup tunggakan tagihan lain. Diskon bukan penerimaan tunai. Iuran sukarela tidak menentukan kelunasan.</p>';
    echo '<p class="muted">Dicetak/dimuat: ' . RpEscape(date('d-m-Y H:i:s')) . ' waktu server. Cakupan hanya tagihan yang sudah didata dengan NIS ini.</p>';
}

function RpRenderSchool($identity)
{
    echo '<header class="school"><h2>' . RpEscape($identity['nama']) . '</h2>';
    foreach (array('alamat1', 'alamat2') as $key)
        if (!empty($identity[$key])) echo '<p>' . RpEscape($identity[$key]) . '</p>';
    echo '<p>' . RpEscape($identity['telp1']) . ' ' . RpEscape($identity['email']) . '</p></header>';
}

function RpPrintToolbar()
{
    echo '<div class="toolbar no-print"><button type="button" onclick="window.print()">Cetak</button></div>';
}
