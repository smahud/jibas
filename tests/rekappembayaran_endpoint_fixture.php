<?php
// Driver subprocess untuk mengetes endpoint asli dengan temporary tables.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$allowed = array('content', 'cetak', 'excel', 'surat_lunas', 'pilih');
$endpoint = $argv[1] ?? '';
if (!in_array($endpoint, $allowed, true)) exit(2);
chdir(__DIR__ . '/../keuangan');
session_name('jbskeu');
session_id('rekapfixture' . bin2hex(random_bytes(16)));
session_start();
$_SESSION = array('namakeuangan' => 'Manajer Fixture', 'tingkatkeuangan' => '1', 'departemenkeuangan' => 'ALL');
register_shutdown_function(function () {
    if (session_status() === PHP_SESSION_ACTIVE) { $_SESSION = array(); session_destroy(); }
    fwrite(STDERR, 'STATUS=' . (http_response_code() ?: 200));
});
$_GET = array('nis' => $argv[2] ?? '001');
if ($endpoint === 'pilih') $_GET = array('nis' => '001', 'departemen' => 'RA', 'cari' => '1');
require_once __DIR__ . '/../keuangan/library/rekappembayaran_bootstrap.php';
require_once __DIR__ . '/rekappembayaran_fixtures.php';
RpCreateFixtures(RpDb());
require __DIR__ . '/../keuangan/laprekappembayaran_siswa_' . $endpoint . '.php';
