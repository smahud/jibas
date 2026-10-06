<?php
require_once __DIR__ . '/library/rekappembayaran_bootstrap.php';
$report = RpLoadRequestedReport();
try {
    $identity = RpSchoolIdentity(RpDb(), $report['student']['departemen']);
} catch (Throwable $e) {
    RpFail('Identitas sekolah belum dapat dimuat.', 500);
}
RpPageStart('Cetak Rekap Pembayaran Siswa');
RpPrintToolbar();
RpRenderSchool($identity);
RpRenderReport($report);
echo '</body></html>';
