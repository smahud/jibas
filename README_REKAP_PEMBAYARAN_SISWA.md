# Rekap Pembayaran Siswa — pembangunan ulang

Fitur berada di **Keuangan → Penerimaan → Laporan → Rekap Pembayaran Siswa** dengan URL `http://localhost/jibas/keuangan/laprekappembayaran_siswa_main.php`. Implementasi ini menggantikan upgrade terdahulu menggunakan skema JIBAS yang diperiksa pada 6 Oktober 2026.

**Dokumen ini adalah catatan utama patch dan serah-terima pekerjaan.** Seluruh perubahan fitur, keputusan teknis, hasil verifikasi, serta batas yang perlu diperhatikan agent/user berikutnya dicatat di sini. `README.md` root dan `keuangan/README.md` merupakan pengantar/pelengkap; membaca keduanya tidak menggantikan aturan fitur dalam dokumen ini. Jika implementasi berubah, perbarui dokumen ini pada commit perubahan yang sama.

## Penggunaan

1. Login sebagai Manajer Keuangan dengan departemen yang ditetapkan landlord, atau sebagai landlord.
2. Pilih departemen pencarian atau biarkan **Semua departemen** (gabungan departemen yang diizinkan untuk manajer).
3. Isi NIS lengkap, atau nama minimal tiga karakter jika NIS kosong. Jika keduanya diisi, keduanya harus cocok.
4. Klik nama siswa untuk melihat seluruh tagihan dan pembayaran dengan NIS itu.
5. Gunakan **Refresh**, **Cetak Rekap**, **Excel**, atau **Surat Lunas** bila tersedia.

Siswa aktif, nonaktif, dan alumni dapat dicari. NIS dicocokkan tepat, nama dicocokkan sebagian, dan hasil dibatasi 50 siswa per halaman dengan navigasi berikutnya/sebelumnya. Karakter `%`/`_` pada nama diperlakukan sebagai teks literal. Kelayakan akses hanya berdasarkan departemen kelas terakhir atau riwayat kelas akademik. Departemen tagihan/pembayaran saja tidak memberikan hak akses. Setelah siswa memenuhi syarat, laporan memuat seluruh departemen tanpa memotong riwayat keuangannya.

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
php -d disable_functions= tests/rekappembayaran_privacy_test.php
php tests/rekappembayaran_http_test.php
```

Jika PHP tidak berada di PATH, gunakan `C:\YIM\JIBAS\xampp\php\php.exe`. Override `disable_functions` hanya berlaku pada proses CLI pengujian endpoint yang membutuhkan `proc_open`; tidak perlu mengubah php.ini atau konfigurasi Apache.

| Pengujian yang dijalankan | Hasil |
|---|---|
| Model, hak akses, nominal, XSS, struktur XLSX dan teks formula | 39 pemeriksaan lolos |
| Query dengan fixture lintas departemen/tahun dan alumni | 41 pemeriksaan lolos |
| Endpoint asli dengan fixture terisolasi: layar, cetak, surat, Excel, pencarian | 18 pemeriksaan lolos |
| HTTP Apache: sesi, staf, manajer, parameter dan keadaan kosong | 109 pemeriksaan lolos |
| Privasi dua antarmuka, pencabutan izin, alumni, ekspor dan CSRF penetapan | 101 pemeriksaan lolos |
| PHP lint file fitur | Lolos pada PHP 8.2.30 |

Fixture database menggunakan **CREATE TEMPORARY TABLE** yang hanya menutupi tabel asli pada koneksi pengujian itu. Tidak ada penulisan fixture ke tabel permanen. Akun pengujian database memerlukan izin membuat temporary table. Uji HTTP memakai sesi acak sementara yang dihapus setelah pemeriksaan, dan mengakses Apache lokal pada `http://localhost/jibas/keuangan/`.

Database lokal saat verifikasi memiliki skema yang sesuai, tetapi tabel siswa, tagihan, dan pembayaran kosong. Karena itu kecocokan dengan transaksi nyata sekolah serta penampilan cetak pada printer/browser pengguna masih perlu diperiksa setelah data tersedia. Pengujian fixture sudah meliputi dua jenjang keuangan walaupun kelas terakhir siswa berada di jenjang ketiga, cicilan tahun berikutnya, tunggakan tanpa angsuran, diskon, gratis, kelebihan bayar, jurnal hilang, dan NIS dengan nol awal.

Lihat [analisis modul Keuangan](keuangan/README.md) untuk alur jurnal, hak akses, serta batas integrasi.

## Riwayat patch dan kondisi repository

| Acuan Git | Keterangan |
|---|---|
| `cfb71d1` | Import source awal, termasuk implementasi rekap terdahulu; berguna untuk analisis historis |
| `42d68b5` | Penggabungan README repo tujuan dengan import proyek |
| `ee63c0e` | Pembangunan ulang rekap, pemisahan model/query/renderer, XLSX asli, dokumentasi, dan pengujian |

Patch `ee63c0e` sudah dikirim ke `main` pada repo `https://github.com/smahud/jibas`; commit penuh `ee63c0ec56131025a0db199782e25e9e12c34d03`. Pembaruan dokumentasi setelah commit tersebut dicatat oleh riwayat Git dokumen ini; nomor commit pembangunan ulang tetap menjadi acuan implementasi awal, bukan klaim bahwa itu selalu HEAD terbaru.

File upgrade terdahulu dan beberapa README pernah dihapus dari working tree karena bermasalah. Kebutuhan fiturnya dibaca dari riwayat Git, kemudian source dibangun ulang. Penghapusan `.gitignore` dan contoh konfigurasi database juga dipulihkan. Tautan menu dikembalikan; isi akhirnya sama dengan tautan pada commit import, sehingga tidak perlu muncul sebagai perubahan tersendiri pada commit pembangunan ulang.

Tidak ada migrasi database, penambahan kolom, pengubahan konfigurasi Apache/PHP permanen, atau perubahan alur penyimpanan pembayaran pada patch ini. Push pembangunan ulang dilakukan normal, tanpa force push. Untuk pekerjaan berikutnya, periksa `git status` dan `git log` aktual sebelum menyimpulkan keadaan repository.

## Masalah versi lama dan keputusan penggantinya

| Temuan yang relevan | Keputusan pada pembangunan ulang |
|---|---|
| Query memakai `tingkat.iddepartemen`, tetapi skema memiliki `tingkat.departemen` | Gunakan relasi akademik yang benar; pengelompokan keuangan mengikuti tahun buku/jenis penerimaan |
| Riwayat pembayaran dihubungkan ke kelas siswa saat ini | Kelas terakhir hanya menjadi identitas; pembayaran lama tidak dipindahkan ke jenjang siswa sekarang |
| Inner join angsuran pada penelusuran riwayat dapat menghilangkan tagihan yang belum dibayar | Ambil seluruh tagihan lebih dahulu, lalu hubungkan detail angsuran di model |
| Pemanggilan `getDepartemen()` sebelum koneksi terbuka pernah menghasilkan error mysqli null | Bootstrap membuka koneksi melalui `RpDb()` sebelum query departemen/pencarian/laporan |
| Nilai pembayaran wajib memakai tunai + diskon dalam satu angka | Tampilkan tunai dan diskon terpisah; total kas tidak memasukkan diskon |
| Query dan hitungan tersebar pada halaman layar/cetak/ekspor/surat | Semua endpoint memakai loader dan model yang sama |
| Rencana ekspor `.xls` dan pustaka legacy | Gunakan XLSX asli dengan ZipArchive; tidak mengubah pustaka ekspor laporan lain |
| Pemeriksaan akses berdasarkan `ALL` saja tidak cukup menjelaskan peran | Izinkan hanya peran administrator/manajer pada setiap endpoint |

Tabel ini menjelaskan source yang diperiksa, bukan daftar semua bug JIBAS. Jangan mengembalikan implementasi lama secara utuh hanya untuk mengambil layout atau satu fungsi.

## Konteks modul yang perlu dipahami sebelum koreksi

Modul Keuangan adalah PHP prosedural dengan database utama `jbsfina` dan relasi lintas database ke `jbsakad`, `jbsumum`, serta modul pengguna/pegawai. Halaman laporan lama lazim memakai keluarga file `*_main`, `*_header`, `*_content`, `*_cetak`, dan `*_excel`.

| Lokasi source | Relevansi untuk analisis |
|---|---|
| `keuangan/penerimaan.php` | Menu pendataan, pembayaran wajib/sukarela, multi/batch payment, dan laporan penerimaan |
| `keuangan/pembayaran_jtt.php`, `keuangan/penerimaan/inputbayar.func.php` | Pendataan kewajiban pada `besarjtt` beserta jurnalnya |
| `keuangan/pembayaranjtt_add.php`, `keuangan/pembayaranjtt_edit.php` | Angsuran, diskon, pengubahan pembayaran, dan flag lunas |
| `keuangan/library/jurnal.php` | `SimpanJurnal()` dan `SimpanDetailJurnal()`; memahami sumber header dan debit/kredit |
| `keuangan/redirect.php` | Pembentukan sesi/peran setelah login |
| `keuangan/include/sessioninfo.php` | Arti tingkat dan perilaku `getAccess()` legacy |
| `keuangan/include/config.php` | Konfigurasi modul, patch JIBAS, database utama, dan pengubahan `$_REQUEST` |
| `include/mainconfig.php`, `include/database.config.php` | Konfigurasi global dan koneksi lokal |
| `keuangan/include/db_functions.php` | Koneksi global, transaksi legacy, dan penanganan/log error koneksi |
| `akademik/` dan `jbsakad.riwayatkelassiswa` | Riwayat kelas dan identitas siswa; bukan sumber nominal pembayaran |

Bagian lain seperti pengeluaran, buku besar, neraca, rugi laba, tutup buku, tabungan siswa/pegawai, inventori, `schoolpay/`, `onlinepay/`, dan `rinjani/` tetap memakai implementasinya masing-masing. Koreksi laporan rekap tidak otomatis memperbaiki atau menguji seluruh bagian tersebut.

### Field `info*` mempunyai arti berbeda

| Field | Makna pada alur yang diperiksa |
|---|---|
| `besarjtt.info1` | ID jurnal pendataan tagihan; tidak dijumlahkan sebagai diskon |
| `besarjtt.info2` | ID tahun buku tagihan dalam varchar |
| `penerimaanjtt.info1` | Nominal diskon angsuran dalam varchar |
| `datapenerimaan.info1` | Kode rekening diskon pada alur jurnal; bukan nominal diskon |

Jangan menyamakan arti field berdasarkan nama `info1` saja. Query tahun tagihan membandingkan `besarjtt.info2` dengan `CAST(tahunbuku.replid AS CHAR)` agar teks yang tidak sama persis tidak dianggap ID valid melalui konversi numerik longgar. Misalnya `1abc` tidak boleh diam-diam dianggap tahun buku `1`.

## Peta fungsi dan alur eksekusi

```text
Endpoint (main/header/pilih/content/cetak/excel/surat)
  -> rekappembayaran_bootstrap.php
     -> sesi jbskeu + RpCanAccess()
     -> include konfigurasi/database dalam buffer output
     -> RpDb(): OpenDb sekali, charset utf8mb4, CloseDb saat shutdown
  -> RpLoadRequestedReport(): validasi NIS dan status error HTTP
     -> RpLoadReport(): transaksi read-only + consistent snapshot
        -> RpReadReport(): siswa, tagihan, angsuran, sukarela
        -> RpBuildReport(): kelompok, nominal, subtotal, status, can_certify
  -> RpRenderReport() / RpWriteXlsx() / validasi surat
```

| Jika ingin mengoreksi | Fungsi / file utama |
|---|---|
| Peran yang diizinkan | `RpCanAccess()` pada model dan bootstrap; samakan uji peran dan dokumentasi |
| Validasi panjang/jenis parameter | `RpText()`; input dibaca dari `$_GET` |
| Pencarian NIS/nama/departemen | `RpSearchStudents()` pada data dan `RpRenderStudentSearch()` pada library pencarian |
| Sumber tahun/departemen atau relasi transaksi | `RpReadReport()` pada data |
| Tunai, diskon, sisa, gratis, kelebihan, atau status | `RpMoney()`, `RpBuildReport()`, `RpGroup()`, `RpAddTotals()` pada model |
| Tampilan layar dan cetak | `RpRenderReport()`, `RpTotalsTable()`, `RpPaymentsTable()`, CSS khusus fitur |
| Isi surat dan syarat penerbitan | `can_certify` pada model dan endpoint `..._surat_lunas.php` |
| Isi dan format Excel | `RpExcelRows()`, `RpExcelTotals()`, `RpWriteXlsx()` dan endpoint ekspor |
| Kop sekolah | `RpSchoolIdentity()` dan `RpRenderSchool()` |

Struktur hasil model yang perlu dipertahankan atau diperbarui bersama seluruh konsumennya:

- `student`: NIS, nama, kelas terakhir, aktif/alumni.
- `groups`: kelompok departemen + ID tahun buku; masing-masing berisi `wajib`, `sukarela`, `totals`, `valid`, dan `status`.
- `departments` dan `department_statuses`: subtotal dan status tiap departemen.
- `totals`: `tagihan`, `tunai`, `diskon`, `sisa`, `kelebihan`, `sukarela`.
- `issues`: masalah integritas yang menghalangi surat; nominal yang tidak terbaca tidak dimasukkan ke total.
- `notes`: catatan, termasuk perbedaan flag lunas lama; tidak selalu menghalangi surat.
- `charge_count`, `status`, `can_certify`: jumlah tagihan wajib, status global, dan keputusan surat.

Keputusan surat harus tetap berasal dari model dan diverifikasi lagi di endpoint. Jangan membuat perhitungan kedua yang berbeda hanya di JavaScript, template cetak, atau ekspor.

## Hak akses, parameter, dan respons HTTP

Sesi Keuangan bernama `jbskeu`. `redirect.php` membentuk `namakeuangan`, `tingkatkeuangan`, dan akses departemen. Tingkat `0` adalah administrator/landlord, `1` manajer, `2` staf. Perilaku legacy `getAccess()` mengembalikan `ALL` untuk administrator/manajer; fitur baru memeriksa peran secara eksplisit.

| Parameter GET | Batas / penggunaan |
|---|---|
| `nis` | Maksimal 20 karakter; laporan memerlukan NIS tidak kosong |
| `nama` | Maksimal 100 karakter; pencarian tanpa NIS membutuhkan minimal 3 karakter |
| `departemen` | Maksimal 50 karakter; kosong berarti gabungan departemen yang diizinkan (semua bagi landlord) |
| `page` | Angka nonnegatif, maksimal 6 karakter; indeks halaman dimulai dari 0 |
| `cari` | Kehadirannya memicu pencarian |

Input array, karakter kontrol, dan nilai yang melewati batas ditolak. Nama/NIS tetap teks, termasuk nol awal. Pencarian memakai prepared statements dan escape wildcard `LIKE`; output HTML di-escape, sedangkan teks Excel disimpan sebagai inline string. Respons pengguna sah memakai `Cache-Control: private, no-store` dan `X-Content-Type-Options: nosniff`.

| Status HTTP | Arti |
|---|---|
| `200` | Halaman/hasil valid; validasi pencarian yang ditampilkan dalam formulir juga bisa tetap 200 |
| `400` | Parameter laporan tidak valid atau NIS belum dipilih |
| `401` | Belum memiliki sesi Keuangan |
| `403` | Peran tidak diizinkan |
| `404` | NIS laporan tidak ditemukan |
| `409` | Surat tidak memenuhi syarat kelunasan/integritas |
| `500` | Koneksi, query, identitas, atau ekspor gagal |

## Contoh nominal untuk koreksi manual

| Kasus | Hasil yang diharapkan |
|---|---|
| Tagihan 100.000, tunai 80.000, diskon 20.000 | Lunas; penerimaan kas wajib tetap 80.000 |
| Tagihan 100.000 tanpa angsuran | Sisa 100.000 dan belum lunas; tetap muncul di laporan |
| Tagihan A 100 dibayar 150; tagihan B 100 dibayar 50 | Sisa keseluruhan 50, kelebihan 50, belum lunas |
| Tagihan tahun 2024 dibayar melalui jurnal 2025 | Kelompok tagihan tetap 2024, detail angsuran menampilkan tahun transaksi 2025 |
| Sukarela 25 tahun 2025 dan 50 tahun 2026 | Subtotal per tahun 25/50, grand total sukarela 75 |
| Hanya iuran sukarela atau tidak ada data keuangan | `BELUM ADA TAGIHAN`, surat tidak tersedia |
| Tagihan nol dengan metadata valid | `GRATIS` pada item; dapat menjadi bagian kelunasan |
| Flag lunas 1 tetapi sisa transaksi positif | Belum lunas dan diberi catatan perbedaan flag |
| Nominal/jurnal/tahun buku tidak dapat diverifikasi | `PERLU VERIFIKASI`; jangan menerbitkan surat |

## Diagnosis masalah

| Gejala | Langkah pemeriksaan |
|---|---|
| Departemen/header gagal dimuat | Periksa koneksi lokal, izin SELECT `jbsakad.departemen`, dan bahwa `RpDb()` dipanggil sebelum query |
| `Unknown column ... iddepartemen` | Pastikan endpoint memakai implementasi baru; periksa `SHOW COLUMNS FROM jbsakad.tingkat`, jangan menambah kolom untuk menyesuaikan query lama |
| Riwayat jenjang lama tidak ditemukan | Cocokkan NIS pada siswa/tagihan; telusuri `besarjtt.info2`, tahun buku, jenis penerimaan, dan apakah NIS pernah berubah |
| Tagihan belum dibayar hilang | Pastikan query tagihan tidak diberi inner join angsuran atau filter tanggal pembayaran |
| Status lunas berbeda dari laporan lama | Hitung tunai dan diskon per tagihan; bandingkan flag tersimpan, sisa, dan kelebihan secara terpisah |
| Hasil pencarian kosong | Periksa NIS tepat, gabungan syarat nama/NIS, filter departemen historis, dan ketersediaan data |
| XLSX tidak dapat dibuka | Pastikan `zip` aktif, direktori sementara bisa ditulis, file dimulai signature ZIP `PK`, dan tidak ada warning/whitespace sebelum arsip |
| Surat 409 | Baca `issues`, jumlah tagihan, dan sisa; jangan menghapus pengecekan endpoint untuk memaksa cetak |
| Error `proc_open` pada test endpoint | Jalankan override CLI `-d disable_functions=` seperti contoh; jangan mengubah konfigurasi web permanen |
| Karakter panah/tanda baca terlihat rusak di terminal Windows | Baca Markdown sebagai UTF-8, misalnya `Get-Content -Encoding UTF8`; jangan mengubah encoding source berdasarkan tampilan terminal ANSI |

Loader laporan mencatat pesan generik beserta kode error melalui `error_log()` PHP; periksa tujuan log yang dikonfigurasi pada PHP/Apache. Penanganan koneksi/query legacy pada `keuangan/include/db_functions.php` juga dapat menulis `log/keuangan-error.log`. Log legacy bisa memuat SQL dan data pengguna, sehingga jangan menyalinnya mentah ke README atau commit.

### Query pembanding yang hanya membaca data

Gunakan pada database lokal/staging melalui klien SQL yang sah. Isi `@nis` dengan NIS yang memang hendak dianalisis; jangan mempublikasikan hasil yang berisi identitas/transaksi sekolah.

```sql
SET @nis = 'NIS_CONTOH';

SELECT b.replid, b.info2 AS tahun_tagihan_id, tb.tahunbuku,
       tb.departemen, d.nama, b.besar, b.lunas,
       COALESCE(p.tunai, 0) AS tunai,
       COALESCE(p.diskon, 0) AS diskon,
       b.besar - COALESCE(p.tunai, 0) - COALESCE(p.diskon, 0) AS saldo
FROM jbsfina.besarjtt b
LEFT JOIN jbsfina.tahunbuku tb ON b.info2 = CAST(tb.replid AS CHAR)
LEFT JOIN jbsfina.datapenerimaan d ON d.replid = b.idpenerimaan
LEFT JOIN (
    SELECT idbesarjtt, SUM(jumlah) AS tunai,
           SUM(CAST(NULLIF(info1, '') AS DECIMAL(15, 0))) AS diskon
    FROM jbsfina.penerimaanjtt GROUP BY idbesarjtt
) p ON p.idbesarjtt = b.replid
WHERE b.nis = @nis ORDER BY b.replid;

SELECT p.replid, p.idbesarjtt, p.jumlah, p.info1 AS diskon_raw,
       p.tanggal, j.nokas, j.idtahunbuku, tb.tahunbuku
FROM jbsfina.penerimaanjtt p
JOIN jbsfina.besarjtt b ON b.replid = p.idbesarjtt
LEFT JOIN jbsfina.jurnal j ON j.replid = p.idjurnal
LEFT JOIN jbsfina.tahunbuku tb ON tb.replid = j.idtahunbuku
WHERE b.nis = @nis ORDER BY p.tanggal, p.replid;
```

Query agregat ini hanya pembanding untuk diskon yang sudah valid. SQL `CAST` bisa mengubah teks rusak menjadi nol/angka parsial; model PHP justru menandainya sebagai masalah. Periksa `diskon_raw` sebelum menyamakan hasil SQL dengan laporan. `saldo` negatif berarti kelebihan pada item tersebut, bukan izin menutup saldo positif item lain.

## Batas yang belum boleh dianggap selesai

- Basis data lokal pada verifikasi awal kosong untuk siswa/tagihan/pembayaran. Hasil awal 111 pemeriksaan pada 6 Oktober, dan 308 pemeriksaan pada 7 Oktober, adalah bukti model, fixture, endpoint, dan HTTP yang dijalankan, bukan bukti rekonsiliasi transaksi nyata.
- Snapshot read-only menjaga konsistensi pembacaan pada tabel dengan engine transaksional yang mendukungnya. Tabel MyISAM/nontransaksional tidak mendapat jaminan snapshot InnoDB.
- Jurnal angsuran/tahun transaksi dan metadata jenis/tagihan diperiksa, tetapi keseimbangan debit/kredit `jurnaldetail` dan jurnal pendataan `besarjtt.info1` tidak diaudit lengkap oleh fitur ini.
- Pembayaran angsuran yang tidak lagi mempunyai baris `besarjtt` tidak dapat dipetakan ke NIS oleh loader saat ini. Audit transaksi orphan global adalah pekerjaan terpisah.
- Relasi satu NIS tidak menyatukan NIS lama/baru atau siswa bernama sama. Nama bukan kunci untuk penggabungan transaksi.
- Tidak mencakup tabungan, penerimaan calon siswa, seluruh gateway, tagihan yang belum didata, atau audit pembukuan keseluruhan.
- Kop mengutamakan identitas departemen kelas terakhir, lalu identitas umum. Jika keduanya tidak ada, pemilihan dapat jatuh ke baris identitas pertama yang tersedia; bila tabel identitas kosong, renderer memakai nama `Sekolah`. Verifikasi kop sebelum memakai surat secara operasional.
- Layout cetak A4, iframe responsif, dan workbook sudah diuji secara programatis; pratinjau visual pada browser/printer serta membuka file pada versi Excel/LibreOffice yang dipakai sekolah masih perlu dilakukan.
- Laporan panjang memuat seluruh transaksi satu NIS. Pencarian dibatasi per halaman, tetapi laporan/Excel belum memakai streaming atau pagination transaksi; ukur penggunaan memori pada siswa dengan riwayat sangat besar sebelum memperluas skala.
- PHP 64-bit, rupiah bulat, mysqlnd, dan ekstensi terkait adalah asumsi implementasi. Skema bernominal pecahan atau lingkungan berbeda memerlukan evaluasi ulang model/test.

## Urutan kerja untuk agent/user berikutnya

1. Baca dokumen ini, periksa diff/commit aktual, lalu identifikasi apakah masalah berada pada data, relasi, perhitungan, akses, atau tampilan.
2. Reproduksi dengan NIS/data yang sah. Jika membagikan contoh, gunakan data anonim atau fixture, bukan kredensial/data siswa nyata.
3. Cocokkan field dengan skema aktual dan source penyimpanan pembayaran. Hindari asumsi relasi atau arti `info*` berdasarkan namanya.
4. Koreksi pada lapisan yang menjadi sumber masalah. Untuk aturan keuangan, perbarui model bersama pengujian kasusnya, bukan hanya angka/label di template.
5. Jalankan pengujian yang relevan dari bagian Verifikasi dan PHP lint untuk file berubah. Jika aturan/status berubah, cek konsistensi layar, cetak, Excel, dan keputusan surat.
6. Catat di dokumen ini: pemicu masalah, akar masalah, file/fungsi yang berubah, perilaku sesudah koreksi, pengujian yang benar-benar dijalankan, dan batas yang masih belum terverifikasi.
7. Review `git diff --check` dan staged diff. Jangan ikutkan `include/database.config.php`, token, log, dump, atau hasil laporan berisi data sekolah. Commit/push sesuai instruksi pengguna dan riwayat repo aktual.

Pemeriksaan hanya dokumentasi tidak memerlukan pengulangan seluruh test aplikasi. Namun angka hasil verifikasi tidak boleh dinaikkan atau diberi tanggal baru tanpa menjalankan pemeriksaan yang mendasarinya. Riwayat patch berikutnya sebaiknya menambahkan catatan bertanggal dan acuan commit tanpa menghapus batas pengujian sebelumnya.

## Patch 7 Oktober 2026: JIBAS 36.0, Rinjani dan privasi

PRD dibuat sebelum implementasi: [PRD_REKAP_PEMBAYARAN_SISWA.md](PRD_REKAP_PEMBAYARAN_SISWA.md). Baseline update provider dicatat pada commit `d056351`, termasuk Akademik Semeru dan Keuangan Rinjani KEU-36.0.1403. File provider di luar integrasi patch dipertahankan. Catatan bertanggal ini menggantikan kebijakan akses pembangunan awal yang lebih longgar.

### Aturan yang wajib dipertahankan

- Landlord harus mempunyai login `landlord` dan level 0; mempunyai akses penuh tanpa penetapan departemen. Level 0 dengan login lain tidak otomatis memperoleh hak rekap.
- Manajer level 1 harus mempunyai akun login dan pegawai aktif serta baris hakakses KEUANGAN tingkat 1 dengan departemen valid. NULL, kosong dan ALL ditolak sebagai penetapan. Beberapa baris valid membentuk gabungan izin. Hak dibaca ulang dari database setiap permintaan, sehingga sesi ALL lama tidak melewati pencabutan izin.
- Staf tidak memperoleh menu; akses langsung ke halaman, pencarian, cetak, Excel dan surat ditolak. Manajer tanpa penetapan ditolak 403. Siswa tidak ditemukan dan siswa di luar izin sama-sama menghasilkan 404 pada laporan/ekspor.
- Siswa aktif/nonaktif/lulus boleh ditampilkan jika kelas terakhir atau riwayat kelas pernah berada di departemen manajer. Pembayaran pada departemen tersebut tidak menjadi bukti keanggotaan akademik. Jika lolos, seluruh transaksi NIS tersebut di semua departemen dan tahun buku tampil dan dapat diidentifikasi.
- Contoh: manajer A boleh melihat siswa dengan riwayat A serta transaksi C/D; manajer B tidak boleh melihat siswa yang tidak pernah berada di B. Tidak ada penggabungan siswa hanya karena nama sama.

### Pemasangan dan penetapan izin

Tidak ada migrasi tabel. Field existing `jbsuser.hakakses.departemen` dipakai untuk penetapan. Login landlord, buka Pengaturan Pengguna dan pilih **Akses Departemen Rekap**. Pada klasik URL `keuangan/rekappembayaran_akses.php`; pada Rinjani `keuangan/rinjani/pengaturan/rekapsiswa.akses.php`. Pilih departemen pada baris manajer dan simpan. Pilihan kosong mencabut izin; ALL tidak diterima. POST penetapan menggunakan token CSRF sesi dan transaksi database. Akun manajer lama wajib ditetapkan sebelum memakai rekap.

Pengelolaan akun Keuangan klasik dan Rinjani sekarang hanya landlord, termasuk endpoint AJAX/cetak/dialog; ini mencegah manajer mengubah peran atau departemen sendiri. Ganti password sendiri tetap mengikuti alur bawaan. Rinjani mempertahankan penetapan saat edit akun manajer tetap sebagai manajer. Setelah perubahan peran, periksa kembali penetapan. Perilaku legacy getAccess() untuk fitur keuangan lain tetap berlaku; pembatasan siswa baru khusus fitur ini.

### Dua antarmuka, satu implementasi

Klasik: `keuangan/laprekappembayaran_siswa_main.php`. Rinjani: `keuangan/rinjani/penerimaan/laporan/rekapsiswa.php`, ditautkan dari Penerimaan/Pelaporan. Wrapper Rinjani memakai model, query, renderer, XLSX dan kebijakan yang sama; jangan menyalin mesin hitung ke wrapper. Bootstrap memuat mainconfig dengan path absolut agar endpoint dalam direktori dalam tetap bekerja.

File penting: `library/rekappembayaran_access.php` menyediakan RpResolveScope, RpEligibility dan RpSetManagerDepartment; `rekappembayaran_admin_guard.php` melindungi administrasi akun. RpSearchStudents dan RpLoadReport wajib menerima scope secara eksplisit; NULL hanya diberikan setelah validasi landlord. RpRoute membentuk URL sesuai varian, sedangkan stylesheet Rinjani menerapkan font/judul/tombol sesuai tampilan provider. Mengubah pencarian saja tidak cukup: pemeriksaan sebelum load siswa juga harus dipertahankan di semua keluaran.

### Verifikasi dan batas

Tanggal 7 Oktober 2026: 308 pemeriksaan lolos (39 model, 41 database, 18 endpoint, 101 privasi kedua antarmuka, 109 HTTP Apache). Fixture menggunakan tabel temporer per koneksi; tidak mengubah hak akses atau transaksi sekolah. Pratinjau Edge headless atas frame utama dan laporan Rinjani diperiksa; dropdown dibatasi dan data lintas departemen tampil. Excel diuji sebagai paket XML XLSX, belum dibuka manual dalam aplikasi Excel; hasil cetak kertas belum diperiksa.

Database lokal masih kosong pada tabel siswa/tagihan/pembayaran. Tabel transaksi utama yang diperiksa menggunakan InnoDB, mendukung snapshot konsisten. Data produksi, variasi skema sekolah, daftar riwayat kelas yang tidak lengkap, kop dan format tanda tangan harus diverifikasi sebelum penggunaan operasional. Jika riwayat akademik hilang, sistem menolak akses yang tidak dapat dibuktikan; jangan memperluasnya memakai riwayat pembayaran.

Lint baseline provider menemukan 13 file pustaka Semeru yang gagal pada lingkungan PHP 8.2: pustaka PHPExcel lama dengan akses offset kurung kurawal dan jpgraph_utils terkait eval yang dinonaktifkan. Komponen tersebut tidak dipanggil oleh rekap; XLSX rekap memakai ZipArchive langsung. Ini adalah keterbatasan provider di luar patch, bukan klaim bahwa seluruh modul JIBAS kompatibel PHP 8.2. Jangan mengatasi ekspor rekap dengan mengalihkannya ke PHPExcel lama.

Untuk rollback, pulihkan seluruh integrasi akses dan endpoint kedua varian secara konsisten. Mengembalikan hanya guard atau query dapat membuka privasi. Penetapan departemen tersimpan di database dan tidak otomatis dihapus oleh rollback Git. Commit/push dilakukan setelah perubahan diverifikasi; token dan konfigurasi database tidak disimpan dalam dokumentasi/source.