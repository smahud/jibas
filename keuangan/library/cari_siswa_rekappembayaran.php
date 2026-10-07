<?php
// Endpoint pencarian juga dilindungi ketika dibuka langsung.
require_once __DIR__ . '/rekappembayaran_bootstrap.php';

function RpRenderStudentSearch($assetPrefix = '')
{
    try {
        $scope = RpRequestScope();
        $department = RpText($_GET, 'departemen', 50);
        RpValidateDepartment($scope, $department);
        $nis = RpText($_GET, 'nis', 20);
        $name = RpText($_GET, 'nama', 100);
        $pageText = RpText($_GET, 'page', 6);
        if ($pageText !== '' && !ctype_digit($pageText)) throw new InvalidArgumentException('Halaman tidak valid.');
        $page = $pageText === '' ? 0 : (int)$pageText;
        $rows = array();
        $message = '';
        $searched = isset($_GET['cari']);
        if ($searched) $rows = RpSearchStudents(RpDb(), $nis, $name, $department, $page, $scope);
    } catch (RpAccessDenied $e) {
        RpFail($e->getMessage(), 403);
    } catch (InvalidArgumentException $e) {
        $message = $e->getMessage();
        $rows = array();
    } catch (Throwable $e) {
        RpFail('Pencarian siswa belum dapat dimuat. Periksa koneksi database.', 500);
    }
    $department = $department ?? '';
    $nis = $nis ?? '';
    $name = $name ?? '';
    $page = $page ?? 0;
    $searched = $searched ?? false;
    $hasNext = count($rows) > 50;
    $rows = array_slice($rows, 0, 50);
    RpPageStart('Cari Siswa', $assetPrefix);
    echo '<h2>Cari Siswa</h2><p>Departemen: <strong>' . RpEscape($department ?: ($scope === null ? 'Semua departemen' : implode(', ', $scope))) . '</strong></p>';
    echo '<form method="get" action="' . RpEscape(RpRoute('pilih')) . '">';
    echo '<input type="hidden" name="departemen" value="' . RpEscape($department) . '">';
    echo '<label>NIS lengkap <input name="nis" maxlength="20" value="' . RpEscape($nis) . '"></label>';
    echo '<label>Nama (minimal 3 karakter jika NIS kosong) <input name="nama" maxlength="100" value="' . RpEscape($name) . '"></label>';
    echo '<button name="cari" value="1">Cari</button></form>';
    echo '<p class="muted">Pencarian mencakup siswa aktif, nonaktif, dan alumni. Jika kedua kolom diisi, keduanya harus cocok.</p>';
    if ($message !== '') echo '<p class="notice">' . RpEscape($message) . '</p>';
    if ($searched && $message === '' && !$rows) echo '<p class="notice">Tidak ada siswa yang cocok.</p>';
    foreach ($rows as $student) {
        $url = RpRoute('content') . '?' . http_build_query(array('nis' => $student['nis']), '', '&', PHP_QUERY_RFC3986);
        echo '<article><a target="content" href="' . RpEscape($url) . '"><strong>' . RpEscape($student['nis']) . ' — ' . RpEscape($student['nama']) . '</strong></a>';
        echo '<p>' . RpEscape($student['departemen'] ?: '-') . ' / ' . RpEscape($student['tingkat'] ?: '-') . ' / ' . RpEscape($student['kelas'] ?: '-') . '</p>';
        echo '<small>' . ($student['alumni'] ? 'Alumni' : ($student['aktif'] ? 'Aktif' : 'Nonaktif')) . '</small></article>';
    }
    if ($searched && $message === '') {
        echo '<div class="toolbar">';
        foreach (array($page - 1 => 'Sebelumnya', $page + 1 => 'Berikutnya') as $targetPage => $label) {
            if ($targetPage < 0 || ($targetPage > $page && !$hasNext)) continue;
            $url = RpRoute('pilih') . '?' . http_build_query(array('nis' => $nis,
                'nama' => $name, 'departemen' => $department, 'cari' => '1', 'page' => $targetPage), '', '&', PHP_QUERY_RFC3986);
            echo '<a class="button" href="' . RpEscape($url) . '">' . $label . '</a>';
        }
        echo '</div>';
    }
    echo '</body></html>';
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) RpRenderStudentSearch('../');
