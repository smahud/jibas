<?php
require_once __DIR__ . '/rekappembayaran_access.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_name('jbskeu'); session_start(); }
if (!isset($_SESSION['namakeuangan'])) { http_response_code(401); exit('Silakan login Keuangan.'); }
if (!RpIsLandlord($_SESSION)) { http_response_code(403); exit('Pengelolaan akun Keuangan hanya tersedia untuk landlord.'); }
