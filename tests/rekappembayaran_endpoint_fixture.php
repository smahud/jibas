<?php
// Driver subprocess untuk mengetes endpoint asli dengan temporary tables.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$allowed = array('main','header','blank','content', 'cetak', 'excel', 'surat_lunas', 'pilih','akses');
$endpoint = $argv[1] ?? '';
if (!in_array($endpoint, $allowed, true)) exit(2);
chdir(__DIR__ . '/../keuangan');
session_name('jbskeu');
session_id('rekapfixture' . bin2hex(random_bytes(16)));
session_start();
$actor = $argv[4] ?? 'landlord';
$_SESSION = array('login' => $actor,'namakeuangan' => 'Pengguna Fixture', 'tingkatkeuangan' => $actor === 'landlord' ? '0' : ($actor === 'staff' ? '2' : '1'), 'departemenkeuangan' => 'ALL');
register_shutdown_function(function () {
    if (session_status() === PHP_SESSION_ACTIVE) { $_SESSION = array(); session_destroy(); }
    fwrite(STDERR, 'STATUS=' . (http_response_code() ?: 200));
});
$_GET = array('nis' => $argv[2] ?? '001');
if ($endpoint === 'pilih') $_GET = array('nis' => $argv[2] ?? '001', 'departemen' => $argv[5] ?? '', 'cari' => '1');
require_once __DIR__ . '/../keuangan/library/rekappembayaran_bootstrap.php';
require_once __DIR__ . '/rekappembayaran_fixtures.php';
RpCreateFixtures(RpDb());
if ($endpoint === 'akses' && isset($argv[5])) {
    $_SERVER['REQUEST_METHOD']='POST';
    $_SESSION['rekap_csrf']='fixture-token';
    $_POST=array('id'=>'1','departemen'=>$argv[6] ?? 'MI','csrf'=>$argv[5] === 'validcsrf' ? 'fixture-token' : 'invalid');
}
if (($argv[3] ?? 'classic') === 'rinjani') {
    $path = $endpoint === 'akses' ? 'rinjani/pengaturan/rekapsiswa.akses.php'
        : 'rinjani/penerimaan/laporan/rekapsiswa' . ($endpoint === 'main' ? '' : '.' . $endpoint) . '.php';
} else $path = $endpoint === 'akses' ? 'rekappembayaran_akses.php' : 'laprekappembayaran_siswa_' . $endpoint . '.php';
chdir(dirname(__DIR__ . '/../keuangan/' . $path));
require __DIR__ . '/../keuangan/' . $path;
