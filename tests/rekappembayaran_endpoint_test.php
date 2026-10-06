<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$checks = 0;
function runEndpoint($endpoint, $nis, $expectedStatus = 200)
{
    global $checks;
    $process = proc_open(array(PHP_BINARY, __DIR__ . '/rekappembayaran_endpoint_fixture.php', $endpoint, $nis),
        array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
    if (!is_resource($process)) throw new RuntimeException('Subprocess gagal dimulai.');
    fclose($pipes[0]);
    $output = stream_get_contents($pipes[1]); fclose($pipes[1]);
    $errors = stream_get_contents($pipes[2]); fclose($pipes[2]);
    $exit = proc_close($process);
    if ($exit !== 0 || $errors !== 'STATUS=' . $expectedStatus || preg_match('/Fatal error|Warning:|Parse error/', $output))
        throw new RuntimeException('Endpoint ' . $endpoint . ' gagal: ' . $errors);
    $checks++;
    return $output;
}
function endpointCheck($condition, $message)
{
    global $checks;
    $checks++;
    if (!$condition) throw new RuntimeException($message);
}
$html = runEndpoint('content', '001');
endpointCheck(strpos($html, 'Rp 365') !== false && strpos($html, 'disabled') !== false, 'Ringkasan/tombol surat keliru.');
$html = runEndpoint('content', '002');
endpointCheck(strpos($html, 'surat_lunas.php?nis=002') !== false, 'Tautan surat lunas hilang.');
$print = runEndpoint('cetak', '001');
endpointCheck(strpos($print, 'window.print()') !== false && strpos($print, 'Rp 365') !== false, 'Cetak tidak sama dengan laporan.');
$letter = runEndpoint('surat_lunas', '002');
endpointCheck(strpos($letter, 'SURAT KETERANGAN LUNAS TANGGUNGAN') !== false && strpos($letter, 'Sekolah MI') !== false, 'Surat/kop sekolah tidak lengkap.');
runEndpoint('surat_lunas', '001', 409);
runEndpoint('surat_lunas', '003', 409);
runEndpoint('surat_lunas', '004', 409);
runEndpoint('content', 'tidak-ada', 404);
$search = runEndpoint('pilih', '001');
endpointCheck(strpos($search, 'content.php?nis=001') !== false && strpos($search, 'MTs A') !== false, 'Siswa riwayat RA tidak ditemukan.');
$xlsx = runEndpoint('excel', '001');
endpointCheck(substr($xlsx, 0, 4) === "PK\x03\x04", 'Output legacy mencemari header XLSX.');
$filename = tempnam(sys_get_temp_dir(), 'rekap-endpoint-');
try {
    file_put_contents($filename, $xlsx);
    $zip = new ZipArchive();
    endpointCheck($zip->open($filename) === true, 'Endpoint Excel bukan XLSX valid.');
    $sheet = simplexml_load_string($zip->getFromName('xl/worksheets/sheet1.xml'));
    endpointCheck($sheet !== false && strpos($sheet->asXML(), '<v>365</v>') !== false, 'Total Excel berbeda dari laporan.');
    $zip->close();
} finally { unlink($filename); }
echo 'PASS: ' . $checks . " endpoint checks (isolated fixture subprocesses)\n";
