<?php
require_once __DIR__ . '/library/rekappembayaran_bootstrap.php';
try {
    $scope = RpRequestScope();
    $departments = RpDepartments(RpDb(), $scope);
} catch (Throwable $e) {
    RpFail('Daftar departemen belum dapat dimuat.', 500);
}
RpPageStart('Filter Rekap Pembayaran Siswa');
?>
<h1>Rekap Pembayaran Siswa</h1>
<?php if (RpRinjani()): ?><p class="breadcrumb"><a class="pageLink" href="<?= RpEscape(RpRootUrl()) ?>rinjani/penerimaan/penerimaan.php" target="_parent">Penerimaan</a> &gt; <span class="pageLinkCurrent">Rekap Pembayaran Siswa</span></p><?php endif; ?>
<form action="<?= RpEscape(RpRoute('pilih')) ?>" method="get" target="pilih" id="filter">
    <label>Departemen pencarian
        <select name="departemen" data-blank="<?= RpEscape(RpRoute('blank')) ?>" onchange="document.getElementById('filter').requestSubmit(); if (parent.document.getElementById('content')) parent.document.getElementById('content').src=this.dataset.blank;">
            <option value=""><?= $scope === null ? 'Semua departemen' : 'Semua departemen yang diizinkan' ?></option>
            <?php foreach ($departments as $department): ?>
            <option value="<?= RpEscape($department['departemen']) ?>"><?= RpEscape($department['departemen']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
</form>
</body></html>
