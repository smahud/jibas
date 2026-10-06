<?php
require_once __DIR__ . '/library/rekappembayaran_bootstrap.php';
$report = RpLoadRequestedReport();
$query = http_build_query(array('nis' => $report['student']['nis']), '', '&', PHP_QUERY_RFC3986);
RpPageStart('Rekap Pembayaran Siswa');
echo '<nav class="toolbar no-print"><button type="button" onclick="location.reload()">Refresh</button>';
echo '<a class="button" target="_blank" rel="noopener" href="laprekappembayaran_siswa_cetak.php?' . RpEscape($query) . '">Cetak Rekap</a>';
echo '<a class="button" href="laprekappembayaran_siswa_excel.php?' . RpEscape($query) . '">Excel</a>';
if ($report['can_certify'])
    echo '<a class="button" target="_blank" rel="noopener" href="laprekappembayaran_siswa_surat_lunas.php?' . RpEscape($query) . '">Surat Lunas</a>';
else
    echo '<button type="button" disabled title="Memerlukan tagihan wajib yang sudah lunas dan data lengkap">Surat Lunas</button>';
echo '</nav>';
RpRenderReport($report);
echo '</body></html>';
