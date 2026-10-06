<?php
require_once __DIR__ . '/library/rekappembayaran_bootstrap.php';
try {
    $departments = RpDepartments(RpDb());
} catch (Throwable $e) {
    RpFail('Daftar departemen belum dapat dimuat.', 500);
}
RpPageStart('Filter Rekap Pembayaran Siswa');
?>
<h1>Rekap Pembayaran Siswa</h1>
<form action="laprekappembayaran_siswa_pilih.php" method="get" target="pilih" id="filter">
    <label>Departemen pencarian
        <select name="departemen" onchange="document.getElementById('filter').requestSubmit(); if (parent.document.getElementById('content')) parent.document.getElementById('content').src='laprekappembayaran_siswa_blank.php';">
            <option value="">Semua departemen</option>
            <?php foreach ($departments as $department): ?>
            <option value="<?= RpEscape($department['departemen']) ?>"><?= RpEscape($department['departemen']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
</form>
</body></html>
