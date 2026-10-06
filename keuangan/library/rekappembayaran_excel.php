<?php
/** Ekspor XLSX asli tanpa ketergantungan PHPExcel lama yang tidak kompatibel PHP 8. */
require_once __DIR__ . '/rekappembayaran_model.php';

function RpExcelTotals(&$rows, $title, $totals)
{
    $rows[] = array($title);
    foreach (array('tagihan' => 'Tagihan wajib', 'tunai' => 'Tunai wajib', 'diskon' => 'Diskon wajib',
        'sisa' => 'Sisa wajib', 'kelebihan' => 'Kelebihan bayar wajib', 'sukarela' => 'Tunai sukarela') as $key => $label)
        $rows[] = array($label, '', '', '', $totals[$key]);
    $rows[] = array('Total penerimaan tunai', '', '', '', $totals['tunai'] + $totals['sukarela']);
}

function RpExcelRows($report)
{
    $rows = array(array('REKAP PEMBAYARAN SISWA'), array('NIS', $report['student']['nis']),
        array('Nama', $report['student']['nama']), array('Status', $report['status']),
        array('Dimuat', date('d-m-Y H:i:s') . ' waktu server'),
        array('Cakupan', 'Seluruh tagihan dan pembayaran yang tercatat dengan NIS ini.'),
        array('Catatan', 'Tunai dipisahkan dari diskon; kelebihan satu tagihan tidak menutup tunggakan lain.'));
    foreach ($report['issues'] as $issue) $rows[] = array('PERLU VERIFIKASI', $issue);
    foreach ($report['notes'] as $note) $rows[] = array('Catatan', $note);
    foreach ($report['departments'] as $department => $totals) {
        foreach ($report['groups'] as $group) {
            if ($group['departemen'] !== $department) continue;
            $rows[] = array();
            $rows[] = array('Departemen', $department, 'Tahun buku tagihan / penerimaan', $group['tahunbuku']);
            foreach ($group['wajib'] as $item) {
                $rows[] = array('Iuran wajib', $item['nama'] ?: 'Jenis tidak diketahui', 'Tagihan #' . $item['id'], $item['status']);
                $rows[] = array('Keterangan tagihan', $item['keterangan']);
                RpExcelTotals($rows, 'Ringkasan tagihan #' . $item['id'], $item['totals']);
                $rows[] = array('Tanggal', 'Jenis', 'No. kas / jurnal', 'Tahun buku transaksi', 'Tunai', 'Diskon', 'Petugas', 'Keterangan');
                if (!$item['payments']) $rows[] = array('Belum ada pembayaran');
                foreach ($item['payments'] as $payment)
                    $rows[] = array($payment['tanggal'], 'Wajib', $payment['nokas'] ?: '-',
                        $payment['tahunbuku_transaksi'] ?: '-', $payment['tunai'] ?? 'Tidak valid',
                        $payment['diskon'] ?? 'Tidak valid', $payment['petugas'], $payment['keterangan']);
            }
            foreach ($group['sukarela'] as $item) {
                $rows[] = array('Iuran sukarela', $item['nama'] ?: 'Jenis tidak diketahui', 'TERCATAT (tanpa target tagihan)', '', $item['jumlah']);
                $rows[] = array('Tanggal', 'Jenis', 'No. kas / jurnal', 'Tahun buku transaksi', 'Tunai', 'Diskon', 'Petugas', 'Keterangan');
                foreach ($item['payments'] as $payment)
                    $rows[] = array($payment['tanggal'], 'Sukarela', $payment['nokas'] ?: '-',
                        $group['tahunbuku'], $payment['tunai'] ?? 'Tidak valid', '', $payment['petugas'], $payment['keterangan']);
            }
            RpExcelTotals($rows, 'Subtotal tahun buku ' . $group['tahunbuku'], $group['totals']);
            $rows[] = array('Status tahun buku', $group['status']);
        }
        RpExcelTotals($rows, 'Subtotal departemen ' . $department, $totals);
        $rows[] = array('Status departemen', $report['department_statuses'][$department]);
    }
    RpExcelTotals($rows, 'Grand total seluruh departemen', $report['totals']);
    return $rows;
}

function RpXmlText($value)
{
    $value = preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', (string)$value);
    return htmlspecialchars($value ?? '', ENT_XML1 | ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function RpWriteXlsx($filename, $report)
{
    if (!class_exists('ZipArchive')) throw new RuntimeException('Ekstensi PHP zip belum aktif.');
    $rows = RpExcelRows($report);
    $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
        . '<cols><col min="1" max="1" width="32" customWidth="1"/><col min="2" max="4" width="35" customWidth="1"/>'
        . '<col min="5" max="6" width="23" customWidth="1"/><col min="7" max="8" width="40" customWidth="1"/></cols><sheetData>';
    foreach ($rows as $index => $row) {
        $rowNumber = $index + 1;
        $sheet .= '<row r="' . $rowNumber . '">';
        foreach ($row as $column => $value) {
            $cell = chr(65 + $column) . $rowNumber;
            // Inline strings mempertahankan nol awal NIS dan mencegah formula dari teks pengguna.
            if (is_int($value)) $sheet .= '<c r="' . $cell . '"><v>' . $value . '</v></c>';
            else $sheet .= '<c r="' . $cell . '" t="inlineStr"><is><t xml:space="preserve">' . RpXmlText($value) . '</t></is></c>';
        }
        $sheet .= '</row>';
    }
    $sheet .= '</sheetData></worksheet>';
    $zip = new ZipArchive();
    if ($zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true)
        throw new RuntimeException('File Excel tidak dapat dibuat.');
    try {
        $entries = array(
            '[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
                . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
                . '<Default Extension="xml" ContentType="application/xml"/>'
                . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
                . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>',
            '_rels/.rels' => '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
            'xl/workbook.xml' => '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
                . '<sheets><sheet name="Rekap Pembayaran" sheetId="1" r:id="rId1"/></sheets></workbook>',
            'xl/_rels/workbook.xml.rels' => '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
                . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>',
            'xl/worksheets/sheet1.xml' => $sheet
        );
        foreach ($entries as $path => $content)
            if (!$zip->addFromString($path, $content)) throw new RuntimeException('Isi Excel tidak dapat dibuat.');
    } finally {
        if (!$zip->close()) throw new RuntimeException('File Excel gagal diselesaikan.');
    }
}
