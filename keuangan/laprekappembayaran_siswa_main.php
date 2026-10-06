<?php
require_once __DIR__ . '/library/rekappembayaran_bootstrap.php';
RpPageStart('Rekap Pembayaran Siswa', '', 'shell');
?>
<iframe class="header-frame" id="header" name="header" title="Filter departemen" src="laprekappembayaran_siswa_header.php"></iframe>
<div class="panels">
    <iframe class="search-frame" id="pilih" name="pilih" title="Cari siswa" src="laprekappembayaran_siswa_pilih.php"></iframe>
    <iframe class="report-frame" id="content" name="content" title="Riwayat pembayaran" src="laprekappembayaran_siswa_blank.php"></iframe>
</div>
</body></html>
