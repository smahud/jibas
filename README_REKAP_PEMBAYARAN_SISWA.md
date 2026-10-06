# Rekap Pembayaran Siswa — pembangunan ulang

Fitur berada di **Keuangan → Penerimaan → Laporan → Rekap Pembayaran Siswa** dengan URL `http://localhost/jibas/keuangan/laprekappembayaran_siswa_main.php`. Implementasi ini menggantikan upgrade terdahulu menggunakan skema JIBAS yang diperiksa pada 6 Oktober 2026.

## Penggunaan

1. Login sebagai Manajer Keuangan atau administrator.
2. Pilih departemen pencarian atau biarkan **Semua departemen**.
3. Isi NIS lengkap, atau nama minimal tiga karakter jika NIS kosong. Jika keduanya diisi, keduanya harus cocok.
4. Klik nama siswa untuk melihat seluruh tagihan dan pembayaran dengan NIS itu.
5. Gunakan **Refresh**, **Cetak Rekap**, **Excel**, atau **Surat Lunas** bila tersedia.

Siswa aktif, nonaktif, dan alumni dapat dicari. NIS dicocokkan tepat, nama dicocokkan sebagian, dan hasil dibatasi 50 siswa per halaman dengan navigasi berikutnya/sebelumnya. Karakter `%`/`_` pada nama diperlakukan sebagai teks literal. Filter departemen mencakup kelas terakhir, riwayat kelas, serta departemen tagihan/pembayaran; filter ini tidak memotong isi laporan siswa yang sudah dipilih.

## Aturan data dan perhitungan

| Bagian | Aturan |
|---|---|
| Iuran wajib | Semua baris `besarjtt` untuk NIS, termasuk yang belum memiliki angsuran |
| Departemen/tahun tagihan | `besarjtt.info2 → tahunbuku`; departemen diambil dari tahun buku, dengan jenis penerimaan sebagai fallback yang ditandai bila data tidak lengkap |
| Angsuran wajib | Seluruh `penerimaanjtt` per `idbesarjtt`, terurut tanggal lalu ID |
| Tahun angsuran | Ditampilkan dari jurnal; pembayaran tahun berikutnya tetap menyelesaikan tagihan tahun asal |
| Tunai wajib | Jumlah `penerimaanjtt.jumlah` |
| Diskon wajib | Jumlah `penerimaanjtt.info1`; kosong berarti nol, teks tidak valid ditandai |
| Sisa per tagihan | `max(0, besar - tunai - diskon)` |
| Kelebihan per tagihan | `max(0, tunai + diskon - besar)`; tidak menutup tagihan lain |
| Sukarela | Dikelompokkan berdasarkan jenis penerimaan dan tahun buku jurnal; tidak memiliki target tagihan/status lunas |
| Total penerimaan tunai | Tunai wajib + penerimaan sukarela; tidak memasukkan diskon |
| Status | Dihitung dari transaksi, tidak sekadar mengikuti flag `besarjtt.lunas` |

Setiap departemen dan tahun buku mempunyai subtotal dan status. Grand total menjumlahkan kelompok tanpa mengulang transaksi. Nominal dihitung sebagai integer rupiah untuk menghindari pembulatan float. Detail angsuran berisi tanggal, no. kas/jurnal, tahun buku transaksi, tunai, diskon, petugas, dan keterangan.

Departemen riwayat ditentukan oleh data keuangan, bukan kelas siswa saat ini. Kelas terakhir ditampilkan hanya sebagai identitas. Jenis penerimaan dan tahun buku nonaktif tetap tercakup dalam riwayat.

## Surat lunas

Surat hanya dapat dibuat jika ada setidaknya satu tagihan wajib, seluruh sisa per tagihan nol, dan tidak ada masalah integritas yang dideteksi. Tagihan gratis (`besar=0`) diperhitungkan sebagai terselesaikan. Kelebihan bayar ditampilkan terpisah. Siswa tanpa tagihan atau hanya memiliki iuran sukarela tidak otomatis dinyatakan lunas.

Data yang menghalangi surat antara lain tahun buku/jenis penerimaan hilang, jurnal angsuran hilang, nominal tidak valid/negatif, atau departemen jenis penerimaan tidak cocok dengan departemen tahun buku. Selisih flag lunas lama dari hasil hitungan ditampilkan sebagai catatan; laporan tidak mengubah flag tersebut.

Endpoint surat memuat ulang dan memvalidasi laporan. URL yang diakses langsung tetap menolak keadaan belum lunas/tidak terverifikasi dengan HTTP 409. Kop sekolah memakai identitas departemen kelas terakhir, lalu identitas umum bila tersedia, disertai ruang nomor surat dan tanda tangan.

Surat menyatakan kelunasan **tagihan yang sudah tercatat dengan NIS tersebut pada waktu laporan**, bukan kewajiban yang belum didata, NIS lain, atau audit seluruh pembukuan.

## Cetak dan Excel

Layar dan cetak memakai renderer/model yang sama. CSS cetak menyembunyikan tombol, mengulang header tabel, dan menggunakan ukuran A4. Cetak dilakukan melalui tombol agar pengguna dapat memilih printer atau menyimpan PDF.

Ekspor sekarang **`.xlsx` asli**, menggantikan rencana `.xls` versi lama. File dibuat dengan ZipArchive dan XML Office Open XML tanpa PHPExcel. Nominal merupakan sel numerik; NIS dan teks merupakan inline strings sehingga nol awal NIS tetap ada dan teks yang diawali `=` tidak berubah menjadi formula. Ekspor mencakup rincian, subtotal tahun/departemen, status, catatan integritas, dan grand total.

File XLSX dibuat di direktori sementara server dan dihapus setelah dikirim. Tidak ada file hasil laporan atau data sekolah yang perlu disimpan dalam repository.

## Struktur file

| File | Tanggung jawab |
|---|---|
| `keuangan/laprekappembayaran_siswa_main.php` | Layout header, pencarian, dan laporan |
| `..._header.php`, `..._pilih.php`, `..._blank.php` | Filter departemen, pencarian, keadaan awal |
| `..._content.php` | Laporan dan tombol aksi |
| `..._cetak.php`, `..._surat_lunas.php`, `..._excel.php` | Cetak rekap, surat dengan validasi ulang, unduhan XLSX |
| `keuangan/library/cari_siswa_rekappembayaran.php` | Pencarian dan navigasi hasil; akses langsung tetap dilindungi |
| `keuangan/library/rekappembayaran_bootstrap.php` | Sesi, peran, konfigurasi, koneksi, penanganan error endpoint |
| `keuangan/library/rekappembayaran_data.php` | Prepared statements dan snapshot read-only; empat query utama per laporan |
| `keuangan/library/rekappembayaran_model.php` | Perhitungan dan keputusan status/surat |
| `keuangan/library/rekappembayaran_view.php` | Escape output dan renderer layar/cetak bersama |
| `keuangan/library/rekappembayaran_excel.php` | Ekspor XLSX bertipe |
| `keuangan/style/rekappembayaran.css` | Layout, tabel, dan aturan cetak khusus fitur |
| `keuangan/penerimaan.php` | Tautan menu laporan |

Nama endpoint lama dipertahankan, tetapi implementasinya dibangun ulang. Query memakai skema `jbsakad`, `jbsfina`, dan `jbsumum` yang sudah ada; tidak memerlukan tabel/kolom baru.

## Verifikasi

Jalankan dari root proyek dengan PHP CLI yang memakai konfigurasi lokal:

```powershell
php tests/rekappembayaran_test.php
php tests/rekappembayaran_database_test.php
php -d disable_functions= tests/rekappembayaran_endpoint_test.php
php tests/rekappembayaran_http_test.php
```

Jika PHP tidak berada di PATH, gunakan `C:\YIM\JIBAS\xampp\php\php.exe`. Override `disable_functions` hanya berlaku pada proses CLI pengujian endpoint yang membutuhkan `proc_open`; tidak perlu mengubah php.ini atau konfigurasi Apache.

| Pengujian yang dijalankan | Hasil |
|---|---|
| Model, hak akses, nominal, XSS, struktur XLSX dan teks formula | 39 pemeriksaan lolos |
| Query dengan fixture lintas departemen/tahun dan alumni | 24 pemeriksaan lolos |
| Endpoint asli dengan fixture terisolasi: layar, cetak, surat, Excel, pencarian | 18 pemeriksaan lolos |
| HTTP Apache: sesi, staf, manajer, parameter dan keadaan kosong | 30 pemeriksaan lolos |
| PHP lint file fitur | Lolos pada PHP 8.2.30 |

Fixture database menggunakan **CREATE TEMPORARY TABLE** yang hanya menutupi tabel asli pada koneksi pengujian itu. Tidak ada penulisan fixture ke tabel permanen. Akun pengujian database memerlukan izin membuat temporary table. Uji HTTP memakai sesi acak sementara yang dihapus setelah pemeriksaan, dan mengakses Apache lokal pada `http://localhost/jibas/keuangan/`.

Database lokal saat verifikasi memiliki skema yang sesuai, tetapi tabel siswa, tagihan, dan pembayaran kosong. Karena itu kecocokan dengan transaksi nyata sekolah serta penampilan cetak pada printer/browser pengguna masih perlu diperiksa setelah data tersedia. Pengujian fixture sudah meliputi dua jenjang keuangan walaupun kelas terakhir siswa berada di jenjang ketiga, cicilan tahun berikutnya, tunggakan tanpa angsuran, diskon, gratis, kelebihan bayar, jurnal hilang, dan NIS dengan nol awal.

Lihat [analisis modul Keuangan](keuangan/README.md) untuk alur jurnal, hak akses, serta batas integrasi.
