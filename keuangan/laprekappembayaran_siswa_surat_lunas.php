<?php
require_once __DIR__ . '/library/rekappembayaran_bootstrap.php';
$report = RpLoadRequestedReport();
// Validasi ulang saat URL dibuka; tombol disabled tidak cukup untuk mencegah surat salah.
if (!$report['can_certify'])
    RpFail('Surat lunas tidak dapat diterbitkan: belum ada tagihan wajib, masih ada tunggakan, atau data perlu diverifikasi.', 409);
try {
    $identity = RpSchoolIdentity(RpDb(), $report['student']['departemen']);
} catch (Throwable $e) {
    RpFail('Identitas sekolah belum dapat dimuat.', 500);
}
RpPageStart('Surat Keterangan Lunas Tanggungan');
RpPrintToolbar();
RpRenderSchool($identity);
?>
<h1 style="text-align:center;margin-top:25px">SURAT KETERANGAN LUNAS TANGGUNGAN</h1>
<p>Nomor: ................................................</p>
<p>Dengan ini menerangkan bahwa:</p>
<table class="summary"><tbody>
    <tr><th>NIS</th><td><?= RpEscape($report['student']['nis']) ?></td></tr>
    <tr><th>Nama siswa</th><td><?= RpEscape($report['student']['nama']) ?></td></tr>
    <tr><th>Departemen / kelas terakhir</th><td><?= RpEscape(($report['student']['departemen'] ?: '-') . ' / ' . ($report['student']['kelas'] ?: '-')) ?></td></tr>
</tbody></table>
<p>Telah menyelesaikan seluruh tagihan iuran wajib yang tercatat dengan NIS tersebut di sistem JIBAS hingga <?= RpEscape(date('d-m-Y H:i:s')) ?> waktu server, dengan rincian:</p>
<?php foreach ($report['departments'] as $department => $totals) RpTotalsTable($totals, 'Departemen ' . $department); ?>
<?php RpTotalsTable($report['totals'], 'Total seluruh departemen', 'LUNAS'); ?>
<p>Pelunasan meliputi pembayaran tunai dan diskon yang tercatat. Surat ini hanya mencakup tagihan yang telah didata dengan NIS di atas; tidak mencakup tagihan yang belum dimasukkan atau tercatat dengan NIS lain.</p>
<p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
<div class="signature">
    <div><p>Mengetahui,<br>Kepala Sekolah</p><p class="line">Nama dan tanda tangan</p></div>
    <div><p>........................, <?= RpEscape(date('d-m-Y')) ?><br>Manajer Keuangan</p><p class="line"><?= RpEscape($_SESSION['namakeuangan']) ?></p></div>
</div>
</body></html>
