<?php
require_once __DIR__ . '/library/rekappembayaran_bootstrap.php';
if (!RpIsLandlord($_SESSION)) RpFail('Hanya landlord dapat menetapkan departemen manajer.', 403);
if (empty($_SESSION['rekap_csrf'])) $_SESSION['rekap_csrf'] = bin2hex(random_bytes(32));
try {
    $db = RpDb();
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        $token = RpText($_POST, 'csrf', 64);
        if (!hash_equals($_SESSION['rekap_csrf'], $token)) RpFail('Token formulir tidak valid. Muat ulang halaman.', 403);
        $id = RpText($_POST, 'id', 10);
        if (!ctype_digit($id)) throw new InvalidArgumentException('ID hak akses tidak valid.');
        RpSetManagerDepartment($db, $_SESSION, (int)$id, RpText($_POST, 'departemen', 50));
        header('Location: ' . RpRoute('akses') . '?saved=1', true, 303);
        exit;
    }
    $departments = RpDepartments($db);
    $managers = RpRows($db, "SELECT h.replid,h.login,h.departemen,p.nama FROM jbsuser.hakakses h
        LEFT JOIN jbssdm.pegawai p ON p.nip=h.login WHERE h.modul='KEUANGAN' AND h.tingkat=1 ORDER BY h.login,h.replid");
} catch (InvalidArgumentException $e) { RpFail($e->getMessage()); }
catch (Throwable $e) { RpFail('Penetapan departemen belum dapat diproses.', 500); }
RpPageStart('Departemen Rekap Manajer');
?>
<h1>Departemen Rekap Manajer Keuangan</h1>
<p>Penetapan ini membatasi siswa yang boleh dipilih pada rekap klasik dan Rinjani. Setelah siswa memenuhi syarat, seluruh riwayat departemennya dapat dilihat. Landlord selalu memiliki akses penuh.</p>
<p class="notice">Manajer tanpa departemen valid ditolak. Satu baris hak akses menyimpan satu departemen; jika terdapat beberapa baris manajer untuk login yang sama, izin merupakan gabungannya. Mengosongkan pilihan mencabut izin baris tersebut.</p>
<?php if (isset($_GET['saved'])): ?><p>Penetapan berhasil disimpan.</p><?php endif; ?>
<?php if (!$managers): ?><p>Belum ada akun Manajer Keuangan. Buat akun melalui Daftar Pengguna terlebih dahulu.</p><?php endif; ?>
<?php foreach ($managers as $manager): ?>
<form method="post" action="<?= RpEscape(RpRoute('akses')) ?>">
    <input type="hidden" name="csrf" value="<?= RpEscape($_SESSION['rekap_csrf']) ?>">
    <input type="hidden" name="id" value="<?= RpEscape($manager['replid']) ?>">
    <label><strong><?= RpEscape($manager['login'] . ' - ' . ($manager['nama'] ?: 'Nama belum tersedia')) ?></strong> (hak akses #<?= RpEscape($manager['replid']) ?>)
        <select name="departemen">
            <option value="">Tidak diberi akses rekap</option>
            <?php foreach ($departments as $department): ?>
            <option value="<?= RpEscape($department['departemen']) ?>" <?= $manager['departemen'] === $department['departemen'] ? 'selected' : '' ?>><?= RpEscape($department['departemen']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button>Simpan departemen</button>
</form><hr>
<?php endforeach; ?>
</body></html>
