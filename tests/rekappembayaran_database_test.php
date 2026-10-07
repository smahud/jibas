<?php
// CLI saja. Semua fixture memakai TEMPORARY TABLE yang hanya hidup pada koneksi ini.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__ . '/../keuangan/library/rekappembayaran_model.php';
require_once __DIR__ . '/../keuangan/library/rekappembayaran_data.php';
require_once __DIR__ . '/../keuangan/library/rekappembayaran_view.php';
require_once __DIR__ . '/../keuangan/library/rekappembayaran_excel.php';
ob_start(); require __DIR__ . '/../include/database.config.php'; ob_end_clean();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli($db_host, $db_user, $db_pass);
$db->set_charset('utf8mb4');
require_once __DIR__ . '/rekappembayaran_fixtures.php';
RpCreateFixtures($db);
$checks = 0;
function dbcheck($value, $message)
{
    global $checks;
    $checks++;
    if (!$value) throw new RuntimeException($message);
}
$report = RpLoadReport($db, '001', null);
dbcheck($report !== null && $report['charge_count'] === 3, 'Tagihan hilang/terduplikasi karena JOIN.');
dbcheck($report['totals']['tagihan'] === 450 && $report['totals']['tunai'] === 290 && $report['totals']['diskon'] === 10, 'Total tagihan/angsuran/diskon salah.');
dbcheck($report['totals']['sisa'] === 150 && !$report['can_certify'], 'Tagihan tanpa angsuran tidak dihitung.');
dbcheck(count($report['departments']) === 2 && !isset($report['departments']['MTs']), 'Kelas siswa dijadikan departemen tagihan.');
dbcheck(count($report['groups']) === 3, 'Pengelompokan departemen/tahun salah.');
dbcheck($report['totals']['sukarela'] === 75, 'Iuran sukarela terduplikasi lintas tahun.');
dbcheck($report['departments']['RA']['tagihan'] === 100 && $report['departments']['MI']['tagihan'] === 350, 'Subtotal departemen salah.');
dbcheck($report['department_statuses']['RA'] === 'LUNAS' && $report['department_statuses']['MI'] === 'BELUM LUNAS', 'Status per departemen salah.');
$miYears = array_values(array_filter($report['groups'], function ($group) { return $group['departemen'] === 'MI'; }));
dbcheck($miYears[0]['totals']['sukarela'] === 25 && $miYears[1]['totals']['sukarela'] === 50, 'Sukarela dipukul rata semua tahun.');
dbcheck($report['issues'] === array(), 'Data valid diperlakukan sebagai anomali.');
$paid = RpLoadReport($db, '002', null);
dbcheck($paid['can_certify'] && $paid['totals']['sisa'] === 0, 'Siswa lunas tidak bisa menerima surat.');
dbcheck(RpLoadReport($db, '003', null)['status'] === 'BELUM ADA TAGIHAN', 'Siswa tanpa data keliru.');
dbcheck(RpLoadReport($db, 'tidak-ada', null) === null, 'NIS tidak ada bukan null.');
$orphan = RpLoadReport($db, '004', null);
dbcheck(!$orphan['can_certify'] && count($orphan['issues']) === 1 && $orphan['totals']['tunai'] === 50, 'Angsuran orphan hilang atau dianggap sah.');
dbcheck(count(RpSearchStudents($db, '001', '', 'RA', 0, null)) === 1, 'Filter departemen riwayat gagal.');
dbcheck(count(RpSearchStudents($db, '001', '', 'MI', 0, null)) === 0, 'Pembayaran saja memberikan bukti akademik.');
dbcheck(count(RpSearchStudents($db, '002', '', '', 0, null)) === 1, 'Alumni hilang dari pencarian.');
dbcheck(count(RpSearchStudents($db, '', 'Ann %_', '', 0, null)) === 1, 'Wildcard nama tidak literal.');
dbcheck(count(RpSearchStudents($db, "001' OR 1=1 --", '', '', 0, null)) === 0, 'Input SQL injection memperluas pencarian.');
dbcheck(RpLoadReport($db, "001' OR 1=1 --", null) === null, 'Input SQL injection memuat siswa.');
dbcheck(count(RpDepartments($db)) === 4, 'Departemen nonaktif tidak terbaca.');
dbcheck(RpSchoolIdentity($db, 'MI')['nama'] === 'Sekolah MI', 'Identitas departemen salah.');
ob_start(); RpRenderReport($report); $html = ob_get_clean();
dbcheck(strpos($html, 'Rp 365') !== false && strpos($html, 'Belum ada pembayaran.') !== false, 'Keluaran laporan tidak lengkap.');
dbcheck(strpos($html, '<script>fixture</script>') === false, 'Keterangan fixture tidak di-escape.');
$landlord = array('login'=>'landlord','namakeuangan'=>'landlord','tingkatkeuangan'=>'0');
$manager = array('login'=>'managerA','namakeuangan'=>'A','tingkatkeuangan'=>'1','departemenkeuangan'=>'ALL');
$scope = RpResolveScope($db,$manager);
dbcheck($scope === array('RA'), 'Sesi ALL membuat manajer bebas akses.');
dbcheck(RpResolveScope($db,$landlord) === null, 'Landlord membutuhkan assignment.');
dbcheck(count(RpDepartments($db,$scope)) === 1, 'Dropdown membocorkan departemen lain.');
dbcheck(RpLoadReport($db,'001',$scope)['totals']['sukarela'] === 75, 'Scope memotong transaksi departemen lain.');
dbcheck(RpLoadReport($db,'002',$scope) === null, 'Siswa di luar riwayat scope dapat dibuka.');
dbcheck(count(RpSearchStudents($db,'001','','',0,$scope)) === 1, 'Siswa bersejarah RA tidak muncul.');
dbcheck(RpLoadReport($db,'001',array('MI')) === null, 'Pembayaran MI saja memberi izin laporan.');
try { RpSearchStudents($db,'001','','MI',0,$scope); dbcheck(false,'Filter di luar izin diterima.'); }
catch (RpAccessDenied $e) { dbcheck(true,'Filter di luar izin ditolak.'); }
try { RpResolveScope($db,array('login'=>'managerNone','namakeuangan'=>'N','tingkatkeuangan'=>'1')); dbcheck(false,'Tanpa assignment diterima.'); }
catch (RpAccessDenied $e) { dbcheck(true,'Tanpa assignment ditolak.'); }
try { RpSetManagerDepartment($db,$manager,1,'MI'); dbcheck(false,'Manajer dapat mengubah assignment.'); }
catch (RpAccessDenied $e) { dbcheck(true,'Manajer tidak dapat mengubah assignment.'); }
try { RpSetManagerDepartment($db,$landlord,5,'MI'); dbcheck(false,'Assignment staf dapat ditulis.'); }
catch (InvalidArgumentException $e) { dbcheck(true,'Assignment staf ditolak.'); }
try { RpSetManagerDepartment($db,$landlord,1,'ALL'); dbcheck(false,'Assignment ALL diterima.'); }
catch (InvalidArgumentException $e) { dbcheck(true,'Assignment ALL ditolak.'); }
RpSetManagerDepartment($db,$landlord,1,'');
try { RpResolveScope($db,$manager); dbcheck(false,'Pencabutan tidak berlaku.'); }
catch (RpAccessDenied $e) { dbcheck(true,'Pencabutan langsung berlaku.'); }
RpSetManagerDepartment($db,$landlord,1,'RA');
dbcheck(RpResolveScope($db,$manager) === array('RA'), 'Assignment ulang gagal.');
$db->query("INSERT INTO jbsuser.hakakses VALUES (6,'managerA','KEUANGAN',1,'MI')");
dbcheck(count(RpResolveScope($db,$manager)) === 2, 'Gabungan beberapa hak akses gagal.');
dbcheck(RpLoadReport($db,'002',RpResolveScope($db,$manager))['can_certify'], 'Gabungan izin tidak membuka alumni MI.');
$db->query("UPDATE jbsuser.login SET aktif=0 WHERE login='managerA'");
try { RpResolveScope($db,$manager); dbcheck(false,'Login nonaktif diterima.'); }
catch (RpAccessDenied $e) { dbcheck(true,'Login nonaktif ditolak.'); }
$db->close(); // Seluruh temporary table otomatis hilang.
echo 'PASS: ' . $checks . " database checks (temporary tables only)\n";
