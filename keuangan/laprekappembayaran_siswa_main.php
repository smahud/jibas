<?php
require_once __DIR__ . '/library/rekappembayaran_bootstrap.php';
RpRequestScope();
RpPageStart('Rekap Pembayaran Siswa', '', 'shell');
?>
<iframe class="header-frame" id="header" name="header" title="Filter departemen" src="<?= RpEscape(RpRoute('header')) ?>"></iframe>
<div class="panels">
    <iframe class="search-frame" id="pilih" name="pilih" title="Cari siswa" src="<?= RpEscape(RpRoute('pilih')) ?>"></iframe>
    <iframe class="report-frame" id="content" name="content" title="Riwayat pembayaran" src="<?= RpEscape(RpRoute('blank')) ?>"></iframe>
</div>
</body></html>
