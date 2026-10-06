# JIBAS

Source aplikasi JIBAS Education Community berbasis PHP dan MySQL/MariaDB. Source yang diperiksa memuat penanda versi **35.5**. Repository ini berisi aplikasi di `htdocs/jibas`, bukan seluruh instalasi XAMPP atau dump database.

## Menjalankan

1. Siapkan Apache, PHP dengan `short_open_tag=On`, dan database JIBAS. Lingkungan yang diuji: PHP 8.2.30 pada XAMPP Windows.
2. Salin `include/database.config.example.php` menjadi `include/database.config.php`, lalu isi koneksi lokal. File konfigurasi koneksi tidak masuk Git.
3. Pastikan database JIBAS, termasuk `jbsakad`, `jbsfina`, `jbsumum`, dan database pendukung modul lain, sudah tersedia. Repository ini tidak membuat skema atau mengimpor data sekolah.
4. Periksa pengaturan server/sekolah di `include/`, jalankan Apache dan database, lalu buka `http://localhost/jibas/`.

Ekstensi fitur rekap: `mysqli` dengan mysqlnd, `mbstring`, dan `zip`. Pengujian XML XLSX juga memakai SimpleXML. PHP 64-bit diperlukan untuk perhitungan rupiah bulat.

## Modul

| Direktori | Fungsi utama |
|---|---|
| `akademik/` | Data siswa, kelas, penerimaan siswa, kenaikan, alumni, dan proses akademik |
| `keuangan/` | Penerimaan, pengeluaran, jurnal, laporan, tabungan, dan integrasi pembayaran |
| `kepegawaian/` | Data dan layanan kepegawaian |
| `infoguru/`, `anjungan/` | Layanan informasi guru dan sekolah |
| `simtaka/` | Perpustakaan |
| `cbe/`, `schooltube/`, `smsgateway/`, `buletin/` | Modul pendukung |
| `include/`, `script/`, `style/` | Konfigurasi dan aset bersama |

## Upgrade terakhir

**Rekap Pembayaran Siswa** dibangun ulang untuk menampilkan tagihan dan pembayaran lintas departemen/tahun buku dengan satu NIS. Akses: **Keuangan → Penerimaan → Laporan → Rekap Pembayaran Siswa**.

- [Dokumentasi fitur, aturan perhitungan, dan verifikasi](README_REKAP_PEMBAYARAN_SISWA.md)
- [Analisis struktur modul Keuangan](keuangan/README.md)

Konfigurasi database, file `.env`, log, dan isi runtime direktori `log/` serta `temp/` dikecualikan melalui `.gitignore`. Source JIBAS menyatakan lisensi GNU GPL versi 3 atau setelahnya; perubahan fitur mengikuti lisensi tersebut.
