<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__ . '/../keuangan/library/rekappembayaran_model.php';
require_once __DIR__ . '/../keuangan/library/rekappembayaran_view.php';
require_once __DIR__ . '/../keuangan/library/rekappembayaran_excel.php';

$checks = 0;
function check($condition, $message)
{
    global $checks;
    $checks++;
    if (!$condition) throw new RuntimeException($message);
}
function charge($id, $amount, $department = 'RA', $yearId = '1', $year = '2024', $lunas = '0')
{
    return array('id' => $id, 'besar' => $amount, 'lunas' => $lunas, 'nama' => 'SPP', 'keterangan' => '',
        'departemen' => $department, 'departemen_jenis' => $department,
        'idtahunbuku' => $yearId, 'tahunbuku' => $year, 'tanggalmulai' => $year . '-07-01');
}
function payment($id, $chargeId, $cash, $discount = '0')
{
    return array('id' => $id, 'idbesarjtt' => $chargeId, 'jumlah' => $cash, 'diskon_raw' => $discount,
        'tanggal' => '2025-02-01', 'journal_id' => '1', 'tahunbuku_transaksi' => '2025',
        'nokas' => 'KAS-01', 'petugas' => 'Petugas', 'keterangan' => '');
}
function voluntary($id, $amount, $yearId, $year)
{
    return array('id' => $id, 'idpenerimaan' => '5', 'jumlah' => $amount,
        'nama' => 'Sumbangan', 'departemen' => 'MI', 'departemen_jenis' => 'MI',
        'idtahunbuku' => $yearId, 'tahunbuku' => $year, 'tanggalmulai' => $year . '-07-01',
        'tanggal' => $year . '-08-01', 'journal_id' => '2', 'nokas' => 'KAS-02', 'petugas' => '', 'keterangan' => '');
}
$student = array('nis' => '0012', 'nama' => '<script>alert(1)</script>', 'aktif' => 1, 'alumni' => 0,
    'departemen' => 'MTs', 'tingkat' => 'VII', 'kelas' => 'A');

check(RpCanAccess(array('namakeuangan' => 'M', 'tingkatkeuangan' => '1')), 'Manajer ditolak.');
check(RpCanAccess(array('namakeuangan' => 'A', 'tingkatkeuangan' => '0', 'login' => 'landlord')), 'Administrator ditolak.');
check(!RpCanAccess(array('namakeuangan' => 'S', 'tingkatkeuangan' => '2', 'departemenkeuangan' => 'ALL')), 'Staf ALL mendapat akses.');
check(!RpCanAccess(array()), 'Sesi kosong mendapat akses.');
check(!RpCanAccess(array('namakeuangan' => 'X', 'tingkatkeuangan' => '99')), 'Level tidak dikenal mendapat akses.');

$report = RpBuildReport($student, array(charge('1', '100000')), array(), array());
check($report['totals']['sisa'] === 100000 && $report['status'] === 'BELUM LUNAS', 'Tagihan tanpa angsuran hilang.');
check(!$report['can_certify'], 'Tagihan belum dibayar mendapat surat.');

$report = RpBuildReport($student, array(charge('1', '100000')), array(payment('1', '1', '80000', '20000')), array());
check($report['can_certify'] && $report['totals']['tunai'] === 80000 && $report['totals']['diskon'] === 20000, 'Tunai/diskon keliru.');
check(count($report['notes']) === 1, 'Flag lunas usang tidak diberi catatan.');
check(array_values($report['groups'])[0]['tahunbuku'] === '2024', 'Angsuran tahun berikut memindahkan tahun tagihan.');
check(array_values($report['groups'])[0]['departemen'] === 'RA', 'Kelas terakhir MTs mengubah departemen RA.');

$report = RpBuildReport($student, array(charge('1', '100'), charge('2', '100', 'MI', '2', '2025')),
    array(payment('1', '1', '150'), payment('2', '2', '50')), array());
check($report['totals']['sisa'] === 50 && $report['totals']['kelebihan'] === 50, 'Kelebihan menutup tagihan lain.');
check(!$report['can_certify'] && count($report['departments']) === 2, 'Status lintas departemen keliru.');

$report = RpBuildReport($student, array(charge('1', '100', 'RA', '1', '2024', '1')), array(payment('1', '1', '20')), array());
check($report['totals']['sisa'] === 80 && !$report['can_certify'], 'Flag lunas menutupi tunggakan nyata.');
$report = RpBuildReport($student, array(charge('1', '0', 'RA', '1', '2024', '2')), array(), array());
check($report['can_certify'] && array_values($report['groups'])[0]['wajib'][0]['status'] === 'GRATIS', 'Tagihan gratis keliru.');
$report = RpBuildReport($student, array(), array(), array());
check(!$report['can_certify'] && $report['status'] === 'BELUM ADA TAGIHAN', 'Tanpa data dianggap lunas.');

$report = RpBuildReport($student, array(), array(), array(voluntary('1', '300', '2', '2025'), voluntary('2', '500', '3', '2026')));
check(count($report['groups']) === 2 && $report['totals']['sukarela'] === 800, 'Iuran sukarela tidak dipisah tahun.');
check(array_values($report['groups'])[0]['totals']['sukarela'] === 300, 'Subtotal sukarela mengulang seluruh tahun.');
check(!$report['can_certify'] && $report['totals']['sisa'] === 0, 'Sukarela dijadikan tagihan wajib.');

$bad = payment('1', '1', '100', 'bukan angka');
$report = RpBuildReport($student, array(charge('1', '100')), array($bad), array());
check(!$report['can_certify'] && $report['issues'], 'Diskon teks tidak diblokir.');
$bad = payment('1', '1', '100'); $bad['journal_id'] = null;
$report = RpBuildReport($student, array(charge('1', '100')), array($bad), array());
check(!$report['can_certify'] && $report['issues'], 'Pembayaran tanpa jurnal diberi surat.');
$bad = charge('1', '0'); $bad['idtahunbuku'] = null;
$report = RpBuildReport($student, array($bad), array(), array());
check(!$report['can_certify'] && $report['issues'], 'Tagihan tanpa tahun buku diberi surat.');
$bad = charge('1', '0'); $bad['departemen_jenis'] = 'MI';
$report = RpBuildReport($student, array($bad), array(), array());
check(!$report['can_certify'] && $report['issues'], 'Departemen tidak cocok diberi surat.');
$bad = payment('1', '1', '-100');
$report = RpBuildReport($student, array(charge('1', '100')), array($bad), array());
check(!$report['can_certify'] && $report['issues'], 'Nominal negatif tidak diblokir.');

check(RpMoney('999999999999999') === 999999999999999, 'Nominal besar kehilangan presisi.');
try { RpText(array('nis' => array('x')), 'nis', 20); check(false, 'Input array diterima.'); }
catch (InvalidArgumentException $e) { check(true, 'Input array ditolak.'); }
try { RpText(array('nis' => str_repeat('a', 21)), 'nis', 20); check(false, 'Input terlalu panjang diterima.'); }
catch (InvalidArgumentException $e) { check(true, 'Input terlalu panjang ditolak.'); }

$report = RpBuildReport($student, array(charge('1', '100')), array(payment('1', '1', '100')), array(voluntary('2', '50', '2', '2025')));
ob_start(); RpRenderReport($report); $html = ob_get_clean();
check(strpos($html, '<script>alert(1)</script>') === false && strpos($html, '&lt;script&gt;') !== false, 'HTML tidak di-escape.');
check(strpos($html, 'Grand total') !== false && strpos($html, 'Rp 150') !== false, 'Grand total tunai tidak terlihat.');

$report['student']['nama'] = '=HYPERLINK("https://example.invalid")';
$file = tempnam(sys_get_temp_dir(), 'rekap-test-');
try {
    RpWriteXlsx($file, $report);
    $zip = new ZipArchive(); check($zip->open($file) === true, 'XLSX bukan ZIP valid.');
    check($zip->numFiles === 5, 'Komponen XLSX kurang.');
    for ($i = 0; $i < $zip->numFiles; $i++) check(simplexml_load_string($zip->getFromIndex($i)) !== false, 'XML XLSX tidak valid.');
    $xml = $zip->getFromName('xl/worksheets/sheet1.xml');
    check(strpos($xml, '<f>') === false && strpos($xml, '=HYPERLINK') !== false, 'Teks berubah menjadi formula.');
    check(strpos($xml, '<t xml:space="preserve">0012</t>') !== false, 'Nol awal NIS hilang.');
    check(strpos($xml, '<v>150</v>') !== false, 'Nominal Excel bukan angka.');
    $zip->close();
} finally { unlink($file); }
echo 'PASS: ' . $checks . " checks\n";
