<?php
require_once __DIR__ . '/rekappembayaran_model.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('jbskeu');
    session_start();
}
if (!isset($_SESSION['namakeuangan'])) {
    http_response_code(401);
    echo '<!doctype html><meta charset="utf-8"><p>Silakan <a href="/jibas/keuangan/index.php" target="_top">login Keuangan</a> terlebih dahulu.</p>';
    exit;
}
if (!RpCanAccess($_SESSION)) {
    http_response_code(403);
    echo '<!doctype html><meta charset="utf-8"><p>Rekap lintas departemen hanya tersedia untuk Manajer Keuangan dan administrator.</p>';
    exit;
}
header('Cache-Control: private, no-store');
header('X-Content-Type-Options: nosniff');
// Pakai konfigurasi JIBAS, tetapi input fitur ini dibaca dari $_GET (bukan $_REQUEST yang diubah config.php).
// File legacy memakai beberapa blok short tag dan dapat mengeluarkan whitespace.
// Buang hanya output include agar header HTTP dan arsip XLSX tidak tercemar.
ob_start();
try {
    require_once __DIR__ . '/../include/config.php';
    require_once __DIR__ . '/../include/db_functions.php';
} finally {
    ob_end_clean();
}
require_once __DIR__ . '/rekappembayaran_data.php';
require_once __DIR__ . '/rekappembayaran_view.php';

function RpDb()
{
    global $mysqlconnection;
    if ($mysqlconnection === null) {
        OpenDb();
        if (!$mysqlconnection || !mysqli_set_charset($mysqlconnection, 'utf8mb4'))
            throw new RuntimeException('Koneksi database gagal.');
        register_shutdown_function('CloseDb');
    }
    return $mysqlconnection;
}

function RpFail($message, $status = 400)
{
    http_response_code($status);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><meta charset="utf-8"><p>' . RpEscape($message) . '</p>';
    exit;
}

function RpLoadRequestedReport()
{
    try {
        $nis = RpText($_GET, 'nis', 20);
        if ($nis === '') RpFail('Pilih siswa terlebih dahulu.');
        $report = RpLoadReport(RpDb(), $nis);
        if ($report === null) RpFail('Siswa tidak ditemukan.', 404);
        return $report;
    } catch (InvalidArgumentException $e) {
        RpFail($e->getMessage());
    } catch (Throwable $e) {
        // Jangan kirim SQL, kredensial, atau data siswa melalui pesan error browser.
        error_log('Rekap pembayaran siswa: gagal memuat laporan, kode ' . $e->getCode());
        RpFail('Laporan belum dapat dimuat. Periksa koneksi dan struktur database.', 500);
    }
}
