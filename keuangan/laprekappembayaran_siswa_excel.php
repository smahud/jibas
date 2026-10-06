<?php
require_once __DIR__ . '/library/rekappembayaran_bootstrap.php';
require_once __DIR__ . '/library/rekappembayaran_excel.php';
$report = RpLoadRequestedReport();
$file = tempnam(sys_get_temp_dir(), 'jibas-rekap-');
if ($file === false) RpFail('File sementara Excel tidak dapat dibuat.', 500);
try {
    RpWriteXlsx($file, $report);
    $safeNis = preg_replace('/[^a-zA-Z0-9_-]/', '_', $report['student']['nis']);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="rekap-pembayaran-' . $safeNis . '.xlsx"');
    header('Content-Length: ' . filesize($file));
    readfile($file);
} catch (Throwable $e) {
    if (is_file($file)) unlink($file);
    RpFail('Ekspor Excel gagal. Pastikan ekstensi PHP zip aktif dan direktori sementara dapat ditulis.', 500);
} finally {
    if (is_file($file)) unlink($file);
}
